<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class DashboardController extends Controller {


        private function getHighestBid(array $auctions) : array {
            
            foreach($auctions as $index=>$auction) {
                $result = Database::query('SELECT max(value) as max_bid FROM Bid WHERE auction_id = ?', [$auction['id']]);
                $auctions[$index]['max_bid'] = $result[0]['max_bid'];
            }
            return $auctions;       

        }


        private function getBidCount(array $auctions) : array {
            
            foreach($auctions as $index=>$auction) {
                $result = Database::query('SELECT count(*) as bid_count FROM Bid WHERE auction_id = ?', [$auction['id']]);
                $auctions[$index]['bid_count'] = $result[0]['bid_count'];
            }
            return $auctions;

        }

        private function getViewCount(array $auctions) : array {
            
            foreach($auctions as $index=>$auction) {
                $result = Database::query('SELECT count(*) as view_count FROM View WHERE auction_id = ?', [$auction['id']]);
                $auctions[$index]['view_count'] = $result[0]['view_count'];
            }
            return $auctions;

        }

        private function getWatchCount(array $auctions) : array {
            
            foreach($auctions as $index=>$auction) {
                $result = Database::query('SELECT count(*) as watch_count FROM Watch WHERE auction_id = ?', [$auction['id']]);
                $auctions[$index]['watch_count'] = $result[0]['watch_count'];
            }
            return $auctions;

        }

    	
        private function getLiveSellerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date > now()', [$user->seller_role_id]);
            return $results;  

        }

         private function getLiveSellerAuctionsWithAggregateInfo(User $user) : array {
            
            $liveAuctions = $this->getLiveSellerAuctions($user);
            $liveAuctions = $this->getHighestBid($liveAuctions);
            $liveAuctions = $this->getBidCount($liveAuctions);
            $liveAuctions = $this->getViewCount($liveAuctions);
            $liveAuctions = $this->getWatchCount($liveAuctions);
            return $liveAuctions;

        }

        private function getCompletedSellerAuctions(User $user) : array {

    		$results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date <= now()', [$user->seller_role_id]);
    		return $results;

    	}

        private function getCompletedSellerAuctionsWithAggregateInfo(User $user) : array {

            $completedAuctions = $this->getCompletedSellerAuctions($user);
            $completedAuctions = $this->getHighestBid($completedAuctions);
            $completedAuctions = $this->getBidCount($completedAuctions);
            $completedAuctions = $this->getViewCount($completedAuctions);
            $completedAuctions = $this->getWatchCount($completedAuctions);
            return $completedAuctions;

        }


    	private function getSellerFeedback(User $user) : array {

    		$results = Database::query('SELECT * FROM SellerFeedback WHERE auction_id IN 
    			(SELECT id FROM Auction WHERE userrole_id = ?)', [$user->seller_role_id]);
    		return $results;
    	}

        private function getAggregateSellerFeedback(User $user) : array {

            $results = Database::query('SELECT avg(item_as_described) as mean_item_as_described,
                                                avg(communication) as mean_communication,
                                                avg(dispatch_time) as mean_dispatch_time,
                                                avg(posting) as mean_posting,
                                                count(*) as no_feedback 
                                                FROM SellerFeedback WHERE auction_id IN 
                        (SELECT id FROM Auction WHERE userrole_id = ?)', [$user->seller_role_id]);
            return $results[0];

        }

        private function getLiveWatchedBuyerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE id IN 
                (SELECT auction_id FROM Watch WHERE userrole_id = 73) AND end_date > now()', [$user->buyer_role_id]);
            return $results;

        }

        private function getCompletedWatchedBuyerAuctions(User $user) : array {
             $results = Database::query('SELECT * FROM Auction WHERE id IN 
                (SELECT auction_id FROM Watch WHERE userrole_id = 73) AND end_date <= now()', [$user->buyer_role_id]);
            return $results;

        }

        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/');
            }
            //Seller
            $liveSellerAuctions = $this->getLiveSellerAuctionsWithAggregateInfo($session->activeUser());
            $completedSellerAuctions = $this->getCompletedSellerAuctionsWithAggregateInfo($session->activeUser());
            $feedback = $this->getSellerFeedback($session->activeUser());
            $aggregateFeedback = $this->getAggregateSellerFeedback($session->activeUser());

            //Buyer
            $liveBuyerAuctions = $this->getLiveWatchedBuyerAuctions($session->activeUser());
            $completedBuyerAuctions = $this->getCompletedWatchedBuyerAuctions($session->activeUser());

            $view = new View('dashboard', ['user' => $session->activeUser(), 'liveSellerAuctions' => $liveSellerAuctions,
            	'completedSellerAuctions' => $completedSellerAuctions,'feedback' => $feedback, 
                'aggregateFeedback' => $aggregateFeedback, 'liveBuyerAuctions' => $liveBuyerAuctions,
                'completedBuyerAuctions' => $completedBuyerAuctions]);

            return $view->render();
        }
    }