<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Auction};

    class DashboardController extends Controller {


        private function getHighestBid(array $auction) {
        
            $result = Database::query('SELECT max(value) as max_bid FROM Bid WHERE auction_id = ?', [$auction['id']]);
            return $result[0]['max_bid']; 

        }


        private function getBidCount(array $auction) {
            
            $result = Database::query('SELECT count(*) as bid_count FROM Bid WHERE auction_id = ?', [$auction['id']]);
            return $result[0]['bid_count'];

        }

        private function getViewCount(array $auction) {
            
            $result = Database::query('SELECT count(*) as view_count FROM View WHERE auction_id = ?', [$auction['id']]);  
            return $result[0]['view_count'];

        }

        private function getWatchCount(array $auction) {
            
            $result = Database::query('SELECT count(*) as watch_count FROM Watch WHERE auction_id = ?', [$auction['id']]);   
            return $result[0]['watch_count'];

        }

        private function getLiveSellerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date > now()', [$user->seller_role_id]);
            return $results;  

        }

         private function getLiveSellerAuctionsWithAggregateInfo(User $user) : array {
            
            $auctions = $this->getLiveSellerAuctions($user);
            foreach($auctions as $index=>$auction) {
                $auctions[$index]['max_bid'] = $this->getHighestBid($auction);
                $auctions[$index]['bid_count'] = $this->getBidCount($auction); 
                $auctions[$index]['view_count'] = $this->getViewCount($auction);
                $auctions[$index]['watch_count'] =  $this->getWatchCount($auction);

            }

            return $auctions;

        }

        private function getCompletedSellerAuctions(User $user) : array {

    		$results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date <= now()', [$user->seller_role_id]);
    		return $results;

    	}

        private function getCompletedSellerAuctionsWithAggregateInfo(User $user) : array {

            $auctions = $this->getCompletedSellerAuctions($user);
            foreach($auctions as $index=>$auction) {
                $auctions[$index]['max_bid'] = $this->getHighestBid($auction);
                $auctions[$index]['bid_count'] = $this->getBidCount($auction); 
                $auctions[$index]['view_count'] = $this->getViewCount($auction);
                $auctions[$index]['watch_count'] =  $this->getWatchCount($auction);

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

        private function getLiveWatchedBuyerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE id IN 
                (SELECT auction_id FROM Watch WHERE userrole_id = ?) AND end_date > now()', [$user->buyer_role_id]);
            return $results;

        }


        private function getLiveWatchedBuyerAuctionsWithAggregateInfo(User $user) : array {

            $auctions = $this->getLiveWatchedBuyerAuctions($user);
            foreach($auctions as $index=>$auction) {
                $auctions[$index]['max_bid'] = $this->getHighestBid($auction);
                $auctions[$index]['bid_count'] = $this->getBidCount($auction); 
               

            }
            return $auctions;
            
        }
/*
        private function getCompletedWatchedBuyerAuctions(User $user) : array {

            $results = Database::query('SELECT * FROM Auction WHERE id IN 
                (SELECT auction_id FROM Watch WHERE userrole_id = ?) AND end_date <= now()', [$user->buyer_role_id]);
            return $results;

        }
*/

        private function getLiveBidBuyerAuctions(User $user) : array {

            $results = Database::query('SELECT a.id, a.name, a.description, a.end_date, max(b.value) as user_bid
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date > now()
                GROUP BY b.auction_id', [$user->buyer_role_id]);
            return $results;

        }

        private function getLiveBidBuyerAuctionsWithAggregateInfo(User $user) : array {
            $auctions = $this->getLiveBidBuyerAuctions($user);
            foreach($auctions as $index=>$auction) {
                $auctions[$index]['max_bid'] = $this->getHighestBid($auction);
            }
            return $auctions;

        }

        private function getCompletedBidBuyerAuctions(User $user) : array {

            $results = Database::query('SELECT a.id, a.name, a.description, a.end_date, max(b.value) as user_bid
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date <= now()
                GROUP BY b.auction_id', [$user->buyer_role_id]);
            return $results;

        }

        private function getCompletedBidBuyerAuctionsWithAggregateInfo(User $user) : array {
            $auctions = $this->getCompletedBidBuyerAuctions($user);
            foreach($auctions as $index=>$auction) {
                $auctions[$index]['max_bid'] = $this->getHighestBid($auction);
            }
            return $auctions;

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
            $completedBidBuyerAuctions = $this->getCompletedBidBuyerAuctionsWithAggregateInfo($session->activeUser());
            $liveWatchedBuyerAuctions = $this->getLiveWatchedBuyerAuctionsWithAggregateInfo($session->activeUser());

            $view = new View('dashboard', ['user' => $session->activeUser(), 'liveSellerAuctions' => $liveSellerAuctions,
            	'completedSellerAuctions' => $completedSellerAuctions,'feedback' => $feedback, 
                'aggregateFeedback' => $aggregateFeedback, 'liveBidBuyerAuctions' => $liveBidBuyerAuctions,
                'completedBidBuyerAuctions' => $completedBidBuyerAuctions, 'liveWatchedBuyerAuctions' => $liveWatchedBuyerAuctions]);

            return $view->render();
        }
    }