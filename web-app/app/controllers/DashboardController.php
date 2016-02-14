<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};

    class DashboardController extends Controller {

        
    	private function getAuctions(User $user) {

    		//$results = Database::query('SELECT id FROM User WHERE email = ? AND password = ?', [$user->id]);

    	}


        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/');
            }

            $view = new View('dashboard', ['user' => $session->activeUser()]);

            return $view->render();
        }
    }