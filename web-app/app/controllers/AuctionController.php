<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class AuctionController extends Controller {

        public function getAuction(Request $request, Session $session) : string {
        	$auction_id = end($request->url_array); 
        	$auction_data = $this->getAuctionData($auction_id);

        	if(!empty($auction_data)){
        		$auction_data[0]["auction_exists"] = true;
            	return (new View('auction', $auction_data[0]))->render();
            }

            else{
            	return (new View('auction', ["auction_exists" => false]))->render();
            }
        }

        private function getAuctionData($id) {
        
            $result = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);
            return $result; 

        }

    }