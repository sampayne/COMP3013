<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, SessionHandler, View};

    class HomeController extends Controller {

        public function getHomepage(Request $request, SessionHandler $session) : string {

            return (new View('home'))->render();

        }

    }