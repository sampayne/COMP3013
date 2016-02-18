<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class DashboardController extends Controller {

    	
        private function getLiveSellerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date > now()', [$user->seller_role_id]);
            return $results;            
        }

        private function getCompletedSellerAuctions(User $user) : array {

    		$results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date <= now()', [$user->seller_role_id]);
    		return $results;

    	}

        private function getHighestBid(array $auctions) {
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
                $result = Database::query('SELECT count(*) as views_count FROM View WHERE auction_id = ?', [$auction['id']]);
                $auctions[$index]['views_count'] = $result[0]['views_count'];
            }
            return $auctions;

        }

        private function getWatchCount(array $auctions) : array {
            foreach($auctions as $index=>$auction) {
                $result = Database::query('SELECT count(*) as views_count FROM Watch WHERE auction_id = 36 ', [$auction['id']]);
                $auctions[$index]['watches_count'] = $result[0]['watches_count'];
            }
            return $auctions;

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

        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/');
            }
            $liveAuctions = $this->getLiveSellerAuctions($session->activeUser());
            $liveAuctions = $this->getBidCount($liveAuctions);
            $completedAuctions = $this->getCompletedSellerAuctions($session->activeUser());
            $feedback = $this->getSellerFeedback($session->activeUser());
            $aggregateFeedback = $this->getAggregateSellerFeedback($session->activeUser());
            $view = new View('dashboard', ['user' => $session->activeUser(), 'liveAuctions' => $liveAuctions,
            	'completedAuctions' => $completedAuctions,'feedback' => $feedback, 
                'aggregateFeedback' => $aggregateFeedback]);

            return $view->render();
        }
    }