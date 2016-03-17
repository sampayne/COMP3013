<?php namespace App\Utility;

    use App\Utility\Request;
    use App\Utility\View;
    use App\Utility\Session as Session;

    use App\Controller\AuctionController;
    use App\Controller\TestController;
    use App\Controller\LoginController;
    use App\Controller\DashboardController;
    use App\Controller\HomeController;
    use App\Controller\SignupController;
    use App\Controller\SearchController;
    use App\Controller\FeedbackController;

    use App\Utility\NotificationSender;

    class RequestHandler {

        public function __construct(){

            //At the moment, do nothing.

        }

        public function handleRequest(Request $request)  {

            $session = new Session($request);

            View::$current_user = $session->activeUser();

            NotificationSender::scanForItemWonNotifications();
            NotificationSender::scanForItemEndedNotifications();




            if($request->matches('GET', '/test')){

                $controller = new TestController();

                return $controller->runTest($request, $session);








            }else if($request->matches('GET','/dashboard')){







                $controller = new DashboardController();

                return $controller->getDashboard($request, $session);










            }else if($request->matches('GET','/login')){

                $controller = new LoginController();

                return $controller->getLoginPage($request, $session);

            }else if($request->matches('POST','/login')){

                $controller = new LoginController();

                return $controller->processLoginAttempt($request, $session);

            }else if($request->matches('POST','/signup')){

                    $controller = new SignupController();

                    return $controller->processSignup($request, $session);

            }else if($request->matches('GET','/logout')){


                    $controller = new LoginController();

                    return $controller->logout($request,$session);




            }else if($request->matches('GET', '/auction/create')){

                $controller = new AuctionController();

                return $controller->getCreateAuctionPage($request, $session);

            }else if($request->matches('GET', '/auction/??/edit')){

                return "You are at /auction/id/edit"; //just a dumb placeholder for sanity check

            }else if($request->matches('POST', '/auction')){

                $controller = new AuctionController();

                return $controller->createNewAuction($request, $session);





            }else if($request->matches('GET','/auction/??')){

                $controller = new AuctionController();

                return $controller->getAuction($request, $session);

            }else if($request->matches('POST','/auction/??/bid')){

                $controller = new AuctionController();

                return $controller->getBidConfirmationPage($request, $session);

            }else if($request->matches('POST','/auction/??/watch')){

                $controller = new AuctionController();

                return $controller->getWatchConfirmationPage($request, $session);




            }else if($request->matches('GET','/auction/??/feedback/create')){

                $controller = new FeedbackController();

                return $controller->getFeedbackForm($request, $session, (int) $request->url_array[1]);

            }else if($request->matches('POST','/auction/??/feedback/buyer')){

                $controller = new FeedbackController();

                return $controller->postBuyerFeedback($request, $session, (int) $request->url_array[1]);

            }else if($request->matches('POST','/auction/??/feedback/seller')){

                $controller = new FeedbackController();

                return $controller->postSellerFeedback($request, $session, (int) $request->url_array[1]);




            }else if($request->matches('GET','/user/??/feedback')){

                    $controller = new FeedbackController();

                    return $controller->getFeedbackList($request, $session, (int) $request->url_array[1]);



            }else if($request->matches('GET', '/search')){

                $controller = new SearchController();

                return $controller->getSearch($request, $session);

            }else if($request->matches('GET', '/')){

                $controller = new HomeController();

                return $controller->getHomepage($request, $session);

            }



            return View::renderView('general_error',['user'=>$session->activeUser(),'message'=>'URL Not Found']);

        }
    }