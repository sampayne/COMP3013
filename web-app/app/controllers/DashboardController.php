<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class DashboardController extends Controller {

        
    	private function getSellerAuctions(User $user) : array {

    		$results = Database::query('SELECT * FROM Auction WHERE userrole_id = ?', [$user->seller_role_id]);
    		return $results;

    	}

    	private function getSellerFeedback(User $user) : array {

    		$results = Database::query('SELECT * FROM SellerFeedback WHERE auction_id IN 
    			(SELECT id FROM Auction WHERE userrole_id = ?)', [$user->seller_role_id]);
    		return $results;
    	}

        private function getAggregateFeedback(User $user) : array {

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
            $auctions = $this->getSellerAuctions($session->activeUser());
            $feedback = $this->getSellerFeedback($session->activeUser());
            $aggregateFeedback = $this->getAggregateFeedback($session->activeUser());
            $view = new View('dashboard', ['user' => $session->activeUser(), 'auctions' => $auctions,
            	'feedback' => $feedback, 'aggregateFeedback' => $aggregateFeedback]);

            return $view->render();
        }
    }