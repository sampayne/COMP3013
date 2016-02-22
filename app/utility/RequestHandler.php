<?php declare(strict_types=1);

    namespace App\Utility;

    use App\Utility\{Request, View};
    use App\Utility\Session as Session;

    use App\Controller\{ TestController, LoginController, DashboardController, HomeController, SignupController, SearchController };

    class RequestHandler {

        public function __construct(){

            //At the moment, do nothing.

        }

        public function handleRequest(Request $request) : string {

            $session = new Session($request);

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

                
                return "You are at /auction/create"; //just a dumb placeholder for sanity check

            }else if($request->matches('GET', '/auction/??/edit')){

                return "You are at /auction/id/edit";  //just a dumb placeholder for sanity check

            }else if($request->matches('POST', '/auction')){







            }else if($request->matches('GET','/auction/??')){



            }else if($request->matches('POST','/auction/??/bid')){

                return "You are at /auction/id/bid"; //just a dumb placeholder for sanity check

            }else if($request->matches('POST','/auction/??/watch')){






            }else if($request->matches('GET', '/search')){

                $controller = new SearchController();

                return $controller->getSearch($request, $session);

            }else if($request->matches('GET', '/')){

                $controller = new HomeController();

                return $controller->getHomepage($request, $session);

            }
        }
    }