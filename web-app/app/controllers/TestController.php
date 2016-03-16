<?php

    namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\Database;

    class TestController extends Controller{

        public function runTest(Request $request, Session $session) {

            $connect_test = Database::testConnection();

            return $connect_test ? "Database connection test successful" : "Database connection test failed";
        }
    }
