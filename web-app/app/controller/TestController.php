<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, SessionHandler, Database};

    class TestController extends Controller{

        public function runTest(Request $request, SessionHandler $session) : string {

            $connect_test = Database::testConnection();

            return $connect_test ? "Database connection test successful" : "Database connection test failed";

        }

    }
