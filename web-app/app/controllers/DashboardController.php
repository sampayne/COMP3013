<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Auction};

    class DashboardController extends Controller {

        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/');
            }
            
            //Seller
            $liveSellerAuctions = Auction::getLiveAuctionsForUser($session->activeUser()->seller_role_id);
            $completedSellerAuctions = Auction::getCompletedAuctionsForUser($session->activeUser()->seller_role_id);
            $sellerFeedback = $session->activeUser()->getSellerFeedback();
            $sellerRating = $session->activeUser()->getSellerMeanRating();

            //Buyer
            $liveBidBuyerAuctions = Auction::getLiveBidAuctionsForUser($session->activeUser()->buyer_role_id);
            $completedBidBuyerAuctions = Auction::getCompletedBidAuctionsForUser($session->activeUser()->buyer_role_id);
            $liveWatchedBuyerAuctions = Auction::getLiveWatchedAuctionsForUser($session->activeUser()->buyer_role_id);
            $buyerFeedback = $session->activeUser()->getBuyerFeedback();
            $buyerRating = $session->activeUser()->getBuyerMeanRating();

            $view = new View('dashboard', ['user' => $session->activeUser(), 'liveSellerAuctions' => $liveSellerAuctions,
            	'completedSellerAuctions' => $completedSellerAuctions,'sellerFeedback' => $sellerFeedback, 
                'sellerRating' => $sellerRating, 'liveBidBuyerAuctions' => $liveBidBuyerAuctions,
                'completedBidBuyerAuctions' => $completedBidBuyerAuctions, 'liveWatchedBuyerAuctions' => $liveWatchedBuyerAuctions,
                'buyerFeedback' => $buyerFeedback, 'buyerRating' => $buyerRating]);

            return $view->render();
        }
    }