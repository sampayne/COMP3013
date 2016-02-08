<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, SessionHandler, View};

    class DashboardController extends Controller {

        public function getDashboard(Request $request, SessionHandler $session) : string {

            $view = new View('dashboard', ['user' => $session->user]);

            return $view->render();

        }

    }