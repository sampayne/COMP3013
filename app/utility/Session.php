<?php declare(strict_types=1);

    namespace App\Utility;

    use App\Utility\Request;
    use App\Utility\Database;
    use App\Model\User;
    use App\Model\Role;

    class Session {

        private $user = null;
        private $session_array = [];

        public function __construct(Request $request){

            $this->session_array = $request->session;

            if(isset($this->session_array['auth_token'])){

                $this->user = $this->loadActiveUserFromSession();

            }else if(isset($this->post['auth_token'])){

                $this->user = $this->loadActiveUserFromPost();

            }else if(isset($this->get['auth_token'])){

                $this->user = $this->loadActiveUserFromGet();
            }
        }

        private function loadActiveUserFromPost() : User {

            $user_query = Database::query("SELECT id, email FROM User WHERE id IN(SELECT user_id FROM Session WHERE token = ?)", [$this->post['auth_token']]);

            return $this->loadActiveUser($user_query);
        }

        private function loadActiveUserFromGet() : User {

            $user_query = Database::query("SELECT id, email FROM User WHERE id IN(SELECT user_id FROM Session WHERE token = ?)", [$this->get['auth_token']]);

            return $this->loadActiveUser($user_query);
        }

        private function loadActiveUserFromSession() : User {

            $user_query = Database::query("SELECT id, email FROM User WHERE id IN(SELECT user_id FROM Session WHERE token = ?)", [$this->session_array['auth_token']]);

            return $this->loadActiveUser($user_query);
        }

        private function loadActiveUser(array $query_result) : User {

            if (count($query_result) > 0) {

                $query_result = $query_result[0];

                $user = new User((int) $query_result['id']);
                $user->email = $query_result['email'];

                $this->loadUserRole($user);

                return $user;
            }
 
            return null;
        }

        private function loadUserRole(User $user) {

            $userrole_query = Database::query("SELECT id, role_id FROM UserRole WHERE user_id = ? ORDER BY role_id", [$user->id]);
            if(!empty($userrole_query) && !empty($userrole_query[0])) {

                if($userrole_query[0]['role_id'] == Role::seller()) {
                    $user->seller_role_id = $userrole_query[0]['id'];
                    if(!empty($userrole_query[1]) && $userrole_query[1]['role_id'] == Role::buyer())
                        $user->buyer_role_id = $userrole_query[1]['id'];
                }
                else
                     if($userrole_query[0]['role_id'] == Role::buyer())
                        $user->buyer_role_id = $userrole_query[0]['id'];

            }
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
