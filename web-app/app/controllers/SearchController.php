<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Item, ItemCategory};

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) : string {
            $searchTerm = $request->get['search-bar'];
            $auction_data = $this->getRelativeAuctionDataSearch(explode(" ", $searchTerm), $request);

            $date = (isset($request->get["date"])) ? $request->get["date"] : "0";
            $price = (isset($request->get["price"])) ? $request->get["price"] : "0";

            if(!empty($auction_data)){
                $categories = ItemCategory::all();
                return (new View('search', ["selectedCategories" => $request->get, "categories" => $categories, "auctionsFound" => true, "searchTerm" => $searchTerm, "auctionData" => $auction_data, "date" => $date, "price" => $price]))->render();
            }

            else{
                return (new View('search', ["auctionsFound" => false, "searchTerm" => $searchTerm]))->render();
            }
        }

        private function getExactAuctionDataSearch($searchTerms, $request) {
            $columns_array = ['a.name', 'a.description', 'i.name', 'i.description'];
            $query = "SELECT DISTINCT a.name, a.description, a.end_date, a.id, greatest(a.starting_price, IFNULL(m.max_bid + 1, 0)) as max_value, i.image_url FROM `Auction` a LEFT JOIN `Item` i ON a.id = i.auction_id LEFT JOIN `AuctionsMaxBid` m ON a.id = m.auction_id WHERE (";

            $firstOr = true;
            foreach ($columns_array as $column) {
                $query = !$firstOr ? $query." OR " : $query;
                $firstOr = false;

                $firstAnd = true;
                foreach ($searchTerms as $searchTerm) {
                    $query = $firstAnd? $query.$column." LIKE '%".$searchTerm."%'" : $query." AND ".$column." LIKE '%".$searchTerm."%'";
                    $firstAnd = false;
                }
            }

            $query = $query.")";
            $selected_categories = $request->get;
            $firstOr = true;

            $fake_categories = 0;
            if(array_key_exists("search-bar", $selected_categories)) $fake_categories++;
            if(array_key_exists("date", $selected_categories)) $fake_categories++;
            if(array_key_exists("price", $selected_categories)) $fake_categories++;


            if(count($selected_categories) - $fake_categories > 0){
                $query = $query." AND i.id IN ( SELECT i.item_id FROM `ItemCategory` i LEFT JOIN `Category` c ON i.category_id = c.id WHERE ";

                foreach($selected_categories as $key => $category){
                    if($key=="search-bar" || $key=="date" || $key=="price")
                        continue;

                    $query = !$firstOr ? $query." OR " : $query;
                    $firstOr = false;
                    $query = $query."c.name="."\"".$category."\"";
                }

                $query = $query." GROUP BY i.item_id HAVING COUNT(i.item_id) = ".(count($selected_categories) - $fake_categories).")";
            }

            $query = $query." GROUP BY a.name, a.id";

            if(array_key_exists("date", $selected_categories)) $query = ($selected_categories["date"] == "1") ? $query." ORDER BY a.end_date" : $query." ORDER BY a.end_date DESC";

            if(array_key_exists("price", $selected_categories)) $query = ($selected_categories["price"] == "1") ? $query." ORDER BY max_value" : $query." ORDER BY max_value DESC";

            $result = Database::query($query);
            return $result;

        }

        private function getRelativeAuctionDataSearch($searchTerm, $request){
            $searchTerm = array_slice($searchTerm, 0, 4);
            $searchTerm = $this->power_set($searchTerm);
            usort($searchTerm, array($this, 'sortString'));

            $i = 0;
            do{

                $auction_data= $this->getExactAuctionDataSearch($searchTerm[$i], $request);
                $i++;
            }while($i < count($searchTerm) - 1 && empty($auction_data));
            
            

            return $auction_data;
        }

        function power_set($array) {
            $results = array(array( ));

            foreach ($array as $element)
                foreach ($results as $combination)
                    array_push($results, array_merge(array($element), $combination));

            return $results;   
        }

        static function sortString($a,$b){
            return count($b)-count($a);
        }
    }