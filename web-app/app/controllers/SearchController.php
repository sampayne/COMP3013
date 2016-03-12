<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Item, ItemCategory};

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) : string {
        	$searchTerm = $request->get['search-bar'];
        	$auction_data = $this->getRelativeAuctionDataSearch(explode(" ", $searchTerm), $request);

        	if(!empty($auction_data)){
                $categories = ItemCategory::all();
            	return (new View('search', ["selectedCategories" => $request->get, "categories" => $categories, "auctionsFound" => true, "searchTerm" => $searchTerm, "auctionData" => $auction_data]))->render();
            }

            else{
            	return (new View('search', ["auctionsFound" => false, "searchTerm" => $searchTerm]))->render();
            }
        }

        private function getExactAuctionDataSearch($searchTerms, $request) {
            $columns_array = ['a.name', 'a.description', 'i.name', 'i.description'];
            $query = "SELECT DISTINCT a.name, a.description, a.end_date, a.id FROM `Auction` a LEFT JOIN `Item` i ON a.id = i.auction_id WHERE (";

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

            if(count($selected_categories) > 1){
                $query = $query." AND i.id IN ( SELECT i.item_id FROM `ItemCategory` i LEFT JOIN `Category` c ON i.category_id = c.id WHERE ";

                foreach($selected_categories as $key => $category){
                    if($key=="search-bar")
                        continue;

                    $query = !$firstOr ? $query." OR " : $query;
                    $firstOr = false;
                    $query = $query."c.name="."\"".$category."\"";
                }

                $query = $query.")";
            }

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