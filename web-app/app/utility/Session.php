<?php declare(strict_types=1);

    namespace App\Utility;

    use App\Utility\Request;
    use App\Utility\Database;
    use App\Model\User;

    class Session {

        private $user = null;
        private $session_array = [];

        public function __construct(Request $request){

            $this->session_array = $request->session;

            if(isset($this->session_array['auth_token'])){

                $this->user = $this->loadActiveUser($this->session_array['auth_token']);

            }else if(isset($request->post['auth_token'])){

                $this->user = $this->loadActiveUser($request->post['auth_token']);

            }else if(isset($request->get['auth_token'])){

                $this->user = $this->loadActiveUser($request->get['auth_token']);
            }
        }

        private function loadActiveUser(string $token) : User {

            $query_result = Database::query("SELECT id, email FROM User WHERE id IN(SELECT user_id FROM Session WHERE token = ?)", [$token]);

            if (count($query_result) > 0) {

                $query_result = $query_result[0];

                $user = new User((int) $query_result['id']);
                $user->email = $query_result['email'];

                return $user;
            }

            return null;
        }

        public function activeUser() : User {

            return $this->user;
        }

        public function userIsLoggedIn() : bool {

            return !is_null($this->user);
        }

        public function endSession() {

            $_SESSION['auth_token'] = '';

            session_unset();
            session_destroy();
        }

        public function generateSession(int $user_id) {

            $auth_key = hash('sha512', (string) rand());

            while(Database::checkExists($auth_key, 'token', 'Session')){
                $auth_key = hash('sha512', (string) rand());
            }

            Database::insert('INSERT INTO Session (user_id,token) VALUES(?,?)',[$user_id, $auth_key]);

            $_SESSION['auth_token'] = $auth_key;
        }
    }
