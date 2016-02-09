<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View};

    class DashboardController extends Controller {

        public function getDashboard(Request $request, Session $session) : string {

            $view = new View('dashboard', ['user' => $session->activeUser()]);

            return $view->render();
        }
    }