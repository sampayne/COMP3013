<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, Database};

    class TestController extends Controller{

        public function runTest(Request $request, Session $session) : string {

            $connect_test = Database::testConnection();

            return $connect_test ? "Database connection test successful" : "Database connection test failed";
        }
    }
