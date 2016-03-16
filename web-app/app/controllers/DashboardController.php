<?php
    namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\User;
    use App\Model\Auction;

    class DashboardController extends Controller {

        public function getDashboard(Request $request, Session $session) {

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
                                           'liveSellerAuctions' => isset($liveSellerAuctions) ? $liveSellerAuctions : NULL,
                                           'completedSellerAuctions' => isset($completedSellerAuctions) ? $completedSellerAuctions : NULL,
                                           'sellerFeedback' => isset($sellerFeedback) ? $sellerFeedback : NULL,
                                           'sellerRating' => isset($sellerRating) ? $sellerRating : NULL,
                                           'liveBidBuyerAuctions' => isset($liveBidBuyerAuctions) ? $liveBidBuyerAuctions : NULL ,
                                           'completedBidBuyerAuctions' => isset($completedBidBuyerAuctions) ? $completedBidBuyerAuctions : NULL,
                                           'liveWatchedBuyerAuctions' => isset($liveWatchedBuyerAuctions) ? $liveWatchedBuyerAuctions : NULL,
                                           'buyerFeedback' => isset($buyerFeedback) ? $buyerFeedback : NULL,
                                           'buyerRating' => isset($buyerRating) ? $buyerRating : NULL,
                                           'recommendations' => isset($recommendations) ? $recommendations : NULL,
                                           'message' => isset($request->get['message']) ? $request->get['message'] : NULL,
                                           'error' => isset($request->get['error']) ? $request->get['error'] : NULL
                                           ]);

            return $view->render();
        }
    }