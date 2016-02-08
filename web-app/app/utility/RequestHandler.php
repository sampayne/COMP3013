<?php declare(strict_types=1);

    namespace App\Utility;

    use App\Utility\Request;
    use App\Utility\SessionHandler;

    use App\Controller\{ TestController, LoginController, DashboardController, HomeController };


    class RequestHandler {

        public function __construct(){

            //At the moment, do nothing.

        }

        public function handleRequest(Request $request) : string {

            $session = new SessionHandler($request);





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








            }else if($request->matches('GET','/auction/??')){


            }else if($request->matches('POST','/auction/??/bid')){


            }else if($request->matches('POST','/auction/??/watch')){









            }else if($request->matches('GET', '/auction/create')){


            }else if($request->matches('GET', '/auction/??/edit')){


            }else if($request->matches('POST', '/auction')){










            }else if($request->matches('GET', '/search')){

                $search_term = $request->get['s'];
                $category = $request->get['c'];

            }else if($request->matches('GET', '/')){

                $controller = new HomeController();

                return $controller->getHomepage($request, $session);

            }
        }
    }