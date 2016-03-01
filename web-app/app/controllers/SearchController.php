<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

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

        private function getExactAuctionDataSearch($searchTerm) {
        	$searchTerm = explode(" ", $searchTerm);
        	$searchTerm = implode("%", $searchTerm);
        	$searchTerm = '%'.$searchTerm.'%';
        	$query = "SELECT DISTINCT a.name, a.id FROM `Auction` a LEFT JOIN `Item` i ON a.id = i.auction_id WHERE a.name LIKE ? OR a.description LIKE ? OR i.name LIKE ? OR i.description LIKE ?";

            $result = Database::query($query, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            return $result;

        }

        private function getRelativeAuctionDataSearch($searchTerm){
        	$searchTerm = array_slice($searchTerm, 0, 4);
        	$collect = array();
        	$this->depth_picker($searchTerm, "", $collect);
        	usort($collect, array($this, 'sortString'));

        	$i = 0;
        	do{

        		$auction_data= $this->getExactAuctionDataSearch($collect[$i]);
        		$i++;
        	}while($i < count($collect) && empty($auction_data));
        	return $auction_data;
        }

        function depth_picker($arr, $temp_string, &$collect) {
    		if ($temp_string != "") 
        		$collect []= $temp_string;

    		for ($i=0; $i<sizeof($arr);$i++) {
        		$arrcopy = $arr;
        		$elem = array_splice($arrcopy, $i, 1); // removes and returns the i'th element
        		if (sizeof($arrcopy) > 0) {
            		$this->depth_picker($arrcopy, $temp_string ." " . $elem[0], $collect);
        		}
        		else {
            		$collect []= $temp_string. " " . $elem[0];
        		}   
    		}   
		}

	    static function sortString($a,$b){
    		return strlen($b)-strlen($a);
		}
    }