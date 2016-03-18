<?php namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\User;
    use App\Model\Item;
    use App\Model\ItemCategory;
    use App\Model\Auction;

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) {
            $searchTerm = $request->get['search-bar'];
            $auction_data = $this->getExactAuctionDataSearch(explode(" ", $searchTerm), $request);
            
            $auction_array = array();
            foreach ($auction_data as $value) {
                $auction = new Auction($value);
                
                if(new \DateTime() <= new \DateTime($auction->end_date))
                    array_push($auction_array, $auction);
            }

            $date = (isset($request->get["date"])) ? $request->get["date"] : "0";
            $price = (isset($request->get["price"])) ? $request->get["price"] : "0";

            if(!empty($auction_data)){
                $categories = ItemCategory::all();
                return (new View('search', ["selectedCategories" => $request->get, "categories" => $categories, "auctionsFound" => true, "searchTerm" => $searchTerm, "auctionData" => $auction_data, "date" => $date, "price" => $price, "auction_array" => $auction_array]))->render();
            }

            else{
                return (new View('search', ["auctionsFound" => false, "searchTerm" => $searchTerm]))->render();
            }
        }

        private function getExactAuctionDataSearch($searchTerms, $request) {

            $query = "SELECT DISTINCT a.name, a.description, a.end_date, a.id, greatest(a.starting_price, IFNULL(m.max_bid + 1, 0)) as max_value, i.image_url, a.starting_price, a.userrole_id, a.created_at, a.updated_at FROM `Auction` a LEFT JOIN `Item` i ON a.id = i.auction_id LEFT JOIN `AuctionsMaxBid` m ON a.id = m.auction_id WHERE a.id IN (";

            $relevanceAlias = $this->createRelevanceAlias($searchTerms);

            $searchQuery = " SELECT DISTINCT tb.id FROM `Auction` a JOIN (";
            $searchQuery = $searchQuery."SELECT a.id, ".$relevanceAlias." FROM `Auction` a";

            $this->createLeftJoins($searchQuery, $searchTerms);
            $searchQuery = $searchQuery." HAVING relevance = (";
            $searchQuery = $searchQuery."SELECT ".$relevanceAlias." FROM `Auction` a";
            $this->createLeftJoins($searchQuery, $searchTerms);
            $searchQuery = $searchQuery." ORDER BY relevance DESC LIMIT 1";

            $searchQuery = $searchQuery.") AND relevance > 0";
            $searchQuery = $searchQuery.") tb";

            $query = $query.$searchQuery.")";
            $selected_categories = $request->get;
            $firstOr = true;

            $fake_categories = 0;
            if(array_key_exists("search-bar", $selected_categories)) $fake_categories++;
            if(array_key_exists("date", $selected_categories)) $fake_categories++;
            if(array_key_exists("price", $selected_categories)) $fake_categories++;

            foreach($selected_categories as $key => $category){
                if($key=="search-bar" || $key=="date" || $key=="price")
                    continue;

                $query = $query." AND a.id IN(SELECT a.id FROM `Item` it LEFT JOIN `ItemCategory` i ON it.id = i.item_id LEFT JOIN `Category` c ON i.category_id = c.id LEFT JOIN `Auction` a ON a.id
= it.auction_id WHERE c.name=\"".$category."\" GROUP BY a.id HAVING COUNT(a.id) >= 1) ";
            }

            $query = $query." GROUP BY a.name, a.id";

            if(array_key_exists("date", $selected_categories)) $query = ($selected_categories["date"] == "1") ? $query." ORDER BY a.end_date" : $query." ORDER BY a.end_date DESC";

            if(array_key_exists("price", $selected_categories)) $query = ($selected_categories["price"] == "1") ? $query." ORDER BY max_value" : $query." ORDER BY max_value DESC";

            $result = Database::query($query);
            return $result;

        }

        private function searchAllColumns($searchTerm){
            //$columns_array = ['a.name', 'a.description', 'i.name', 'i.description'];
            $columns_array = ['a.name', 'a.description'];
            $query = "";

            $firstAnd = true;
            foreach ($columns_array as $column) {
                $query = $firstAnd? $query.$column." LIKE '%".$searchTerm."%'" : $query." OR ".$column." LIKE '%".$searchTerm."%'";
                $firstAnd = false;
            }

            return $query;

        }

        private function createRelevanceAlias($searchTerms){
            $query = "(";
            $query = $query."IFNULL(tb1.e1, 0)";

            for($i = 2; $i <= count($searchTerms); ++$i){
                $query = $query."+ IFNULL(tb".$i.".e".$i.", 0)";
            }

            $query = $query.") AS relevance";

            return $query;
        }

        private function createLeftJoins(&$query, $searchTerms){

            for($i = 1; $i <= count($searchTerms); $i++){
               /* $query = $query." LEFT JOIN (SELECT a.id, 1 as e".$i." FROM `Item` i LEFT JOIN `Auction` a ON i.auction_id = a.id WHERE ".$this->searchAllColumns($searchTerms[$i-1]).") as tb".$i." ON a.id=tb".$i.".id";
               */
                $query = $query." LEFT JOIN (SELECT a.id, 1 as e".$i." FROM `Auction` a WHERE ".$this->searchAllColumns($searchTerms[$i-1]).") as tb".$i." ON a.id=tb".$i.".id";
            }

        }
    }