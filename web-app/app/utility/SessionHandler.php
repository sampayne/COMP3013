<?php declare(strict_types=1);

    namespace App\Utility;

    use App\Utility\Request;
    use App\Utility\Database;
    use App\Model\User;

    class Session {

        private $user = null;
        private $session_array = [];

        public function __construct(Request $request){

            start_session();

            $this->session_array = $request->session;

            if(isset($this->session_array['auth_token'])){

                $this->user = $this->loadActiveUser();

            }

        }

        private function loadActiveUser() : User {

            $userQuery = Database::query("SELECT id, user FROM User WHERE id IN(SELECT user_id FROM Session WHERE token = '?'", [$this->session_array['auth_token']]);

            if (count($userQuery) > 0) {

                $userQuery = $userQuery[0];

                $user = new User($userQuery['id']);
                $user->username = $userQuery['username'];
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

        public function endSession(){

            session_unset();
            session_destroy();

        }

        public function createSession(string $auth_token) {

            $_SESSION['auth_token'] = $auth_token;

        }
    }


