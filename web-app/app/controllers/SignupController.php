<?php

    namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\Role;

    class SignupController extends Controller {

        public function processSignup(Request $request, Session $session) {

            if($session->userIsLoggedIn()){

                return $this->redirectTo('/dashboard');
            }

            if(isset($request->post['email']) &&
               isset($request->post['password']) &&
              (isset($request->post['buyer_account']) ||
               isset($request->post['seller_account'])) &&
                    ($request->post['buyer_account'] == 1 ||
                     $request->post['seller_account'] == 1)){

                if(Database::checkExists($request->post['email'], 'email', 'User')){
                    return View::renderView('login', ['signup_errors' => 'Email already exists']);
                }

                Database::insert('INSERT INTO User (email,password) VALUES (?,?)', [$request->post['email'],
                    password_hash($request->post['password'], PASSWORD_DEFAULT)]);

                $user_id = Database::lastID();

                if($request->post['buyer_account'] == 1){
                    Database::insert('INSERT INTO UserRole (user_id, role_id) VALUES (?,?)', [$user_id, Role::buyer()]);
                }

                if($request->post['seller_account'] == 1){
                    Database::insert('INSERT INTO UserRole (user_id, role_id) VALUES (?,?)', [$user_id, Role::seller()]);
                }

                $session->generateSession($user_id);

                return $this->redirectTo('/dashboard');
            }

            return View::renderView('login', ['signup_errors' => 'You must complete the signup form']);
        }
    }