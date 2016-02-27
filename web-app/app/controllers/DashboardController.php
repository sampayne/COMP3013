<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Auction};

    class DashboardController extends Controller {

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
            
            //Seller
            $liveSellerAuctions = Auction::getLiveAuctionsForUser($session->activeUser()->seller_role_id);
            $completedSellerAuctions = Auction::getCompletedAuctionsForUser($session->activeUser()->seller_role_id);
            $feedback = $this->getSellerFeedback($session->activeUser());
            $aggregateFeedback = $this->getAggregateSellerFeedback($session->activeUser());

            //Buyer
            $liveBidBuyerAuctions = Auction::getLiveBidAuctionsForUser($session->activeUser()->buyer_role_id);
            $completedBidBuyerAuctions = Auction::getCompletedBidAuctionsForUser($session->activeUser()->buyer_role_id);
            $liveWatchedBuyerAuctions = Auction::getLiveWatchedAuctionsForUser($session->activeUser()->buyer_role_id);

            $view = new View('dashboard', ['user' => $session->activeUser(), 'liveSellerAuctions' => $liveSellerAuctions,
            	'completedSellerAuctions' => $completedSellerAuctions,'feedback' => $feedback, 
                'aggregateFeedback' => $aggregateFeedback, 'liveBidBuyerAuctions' => $liveBidBuyerAuctions,
                'completedBidBuyerAuctions' => $completedBidBuyerAuctions, 'liveWatchedBuyerAuctions' => $liveWatchedBuyerAuctions]);

            return $view->render();
        }
    }