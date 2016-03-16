<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};

    class LoginController extends Controller {

        public function getLoginPage(Request $request, Session $session) : string {

            if($session->userIsLoggedIn()){

                return $this->redirectTo('/dashboard');
            }

            $view = new View('login');

            return $view->render();
        }

        public function processLoginAttempt(Request $request, Session $session) : string {

            if($session->userIsLoggedIn()){

                return $this->redirectTo('/dashboard');
            }

            if(!isset($request->post['email']) || !isset($request->post['password'])){

                return (new View('login', ['errors'=> 'Form incomplete']))->render();
            }
            
            $results = Database::query('SELECT id, password FROM User WHERE email = ?', [$request->post['email']]);

            if(password_verify($request->post['password'], $results[0]['password'])){

                $id = (int) $results[0]['id'];

                $session->generateSession($id);

                return $this->redirectTo('/dashboard');
            }

            return (new View('login', ['errors'=> 'Login Incorrect']))->render();
        }

        public function logout(Request $request, Session $session){

            $session->endSession();

            return $this->redirectTo('/');
        }
    }