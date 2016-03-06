<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\{User, Auction};

    class DashboardController extends Controller {

        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/login');
            }

            if($session->activeUser()->isSeller()) {
              
              $liveSellerAuctions = Auction::getLiveAuctionsForUser($session->activeUser()->sellerID());
              $completedSellerAuctions = Auction::getCompletedAuctionsForUser($session->activeUser()->sellerID());
              $sellerFeedback = $session->activeUser()->getSellerFeedback();
              $sellerRating = $session->activeUser()->getSellerMeanRating();

            }

            if($session->activeUser()->isBuyer()) {
            	
            	$liveBidBuyerAuctions = Auction::getLiveBidAuctionsForUser($session->activeUser()->buyerID());
            	$completedBidBuyerAuctions = Auction::getCompletedBidAuctionsForUser($session->activeUser()->buyerID());
            	$liveWatchedBuyerAuctions = Auction::getLiveWatchedAuctionsForUser($session->activeUser()->buyerID());
            	$buyerFeedback = $session->activeUser()->getBuyerFeedback();
            	$buyerRating = $session->activeUser()->getBuyerMeanRating();
            	$recommendations = $session->activeUser()->getRecommendations();

          	}

            $view = new View('dashboard', ['user' => $session->activeUser(),
                                           'liveSellerAuctions' => $liveSellerAuctions ?? NULL,
                                           'completedSellerAuctions' => $completedSellerAuctions ?? NULL,
                                           'sellerFeedback' => $sellerFeedback ?? NULL,
                                           'sellerRating' => $sellerRating ?? NULL,
                                           'liveBidBuyerAuctions' => $liveBidBuyerAuctions ?? NULL ,
                                           'completedBidBuyerAuctions' => $completedBidBuyerAuctions ?? NULL,
                                           'liveWatchedBuyerAuctions' => $liveWatchedBuyerAuctions ?? NULL,
                                           'buyerFeedback' => $buyerFeedback ?? NULL,
                                           'buyerRating' => $buyerRating ?? NULL,
                                           'recommendations' => $recommendations ?? NULL,
                                           'message' => isset($request->get['message']) ? $request->get['message'] : NULL,
                                           'error' => isset($request->get['error']) ? $request->get['error'] : NULL

                                           ]);

            return $view->render();
        }
    }