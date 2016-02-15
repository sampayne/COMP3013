<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class DashboardController extends Controller {

        
    	private function getAuctions(User $user) : array {

    		$results = Database::query('SELECT * FROM Auction WHERE userrole_id = ?', [$user->seller_role_id]);
    		return $results;

    	}


        public function getDashboard(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/');
            }
            $auctions = $this->getAuctions($session->activeUser());
            $view = new View('dashboard', ['user' => $session->activeUser(), 'auctions' => $auctions]);

            return $view->render();
        }
    }