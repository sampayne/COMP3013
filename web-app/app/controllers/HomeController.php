<?php

    namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\ItemCategory;

    class HomeController extends Controller {

        public function getHomepage(Request $request, Session $session) {

        	$categories = ItemCategory::all();
            return (new View('home', ["categories" => $categories]))->render();
        }
    }