<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) : string {
        	$searchTerm = $request->get['search-bar']; 
        	$auction_data = $this->getSearchAuctionData($searchTerm);

        	if(!empty($auction_data)){
            	return (new View('search', ["auctionsFound" => true, "searchTerm" => $searchTerm, "auctionData" => $auction_data]))->render();
            }

            else{
            	return (new View('search', ["auctionsFound" => false, "searchTerm" => $searchTerm]))->render();
            }
        }

        private function getSearchAuctionData($searchTerm) {
        
            $result = Database::query("SELECT * FROM Auction WHERE description LIKE ? OR name LIKE ?", ['%'.$searchTerm.'%', '%'.$searchTerm.'%']);
            #also implement select from item
            return $result; 

        }

    }