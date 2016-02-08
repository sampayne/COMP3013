<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, SessionHandler, View};

    class LoginController extends Controller {

        public function getLoginPage(Request $request, SessionHandler $session) : string {

            if($session->userIsLoggedIn()){

                return $this->redirectTo('/dashboard');
            }

            $view = new View('login');

            return $view->render();

        }

        public function processLoginAttempt(Request $request, SessionHandler $session) : string {

            if($session->userIsLoggedIn()){

                return $this->redirectTo('/dashboard');

            }

            if(isset($request->post['email']) && isset($request->post['password'])){

                $results = Database::query('SELECT id FROM User WHERE email = ? AND password = ?', [$request->post['email'],$request->post['password']]);

            }

            return (new View('login', ['errors'=> 'Form incomplete']))->render();

        }

        public function logout(Request $request, SessionHandler $session){

            $session->endSession();

            return $this->redirectTo('/');

        }

    }