<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;
    use App\Model\Item;

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) : string {
        	$searchTerm = $request->get['search-bar']; 
        	$auction_data = $this->getRelativeAuctionDataSearch(explode(" ", $searchTerm));

        	if(!empty($auction_data)){
            	return (new View('search', ["auctionsFound" => true, "searchTerm" => $searchTerm, "auctionData" => $auction_data]))->render();
            }

            else{
            	return (new View('search', ["auctionsFound" => false, "searchTerm" => $searchTerm]))->render();
            }
        }

        private function getExactAuctionDataSearch($searchTerms) {
            $columns_array = ['a.name', 'a.description', 'i.name', 'i.description'];
            $query = "SELECT DISTINCT a.name, a.description, a.end_date, a.id, i.id FROM `Auction` a LEFT JOIN `Item` i ON a.id = i.auction_id WHERE ";

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

            $result = Database::query($query);
            return $result;

        }

        private function getRelativeAuctionDataSearch($searchTerm){
        	$searchTerm = array_slice($searchTerm, 0, 4);
        	$searchTerm = $this->power_set($searchTerm);
        	usort($searchTerm, array($this, 'sortString'));

        	$i = 0;
        	do{

        		$auction_data= $this->getExactAuctionDataSearch($searchTerm[$i]);
        		$i++;
        	}while($i < count($searchTerm) - 1 && empty($auction_data));
            
            $array_initial_size = count($auction_data);
            for($i = 0; $i < $array_initial_size; $i++){

                if($i > 0 && $auction_data[$i][3] == $auction_data[$i-1][3]){
                    unset($auction_data[$i-1]);
                    continue;
                }

                $auction_data[$i][4] = (Item::getItemWithId($auction_data[$i][4])) -> image_url;
            }

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