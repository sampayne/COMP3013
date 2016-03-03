<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View};

    class HomeController extends Controller {

        public function getHomepage(Request $request, Session $session) : string {

            return (new View('home', ['user' => $session->activeUser()]))->render();
        }
    }