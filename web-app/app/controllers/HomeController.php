<?php declare(strict_types=1);
    namespace App\Controller;
    use App\Utility\{Request, Session, View};
    use App\Model\ItemCategory;
    class HomeController extends Controller {
        public function getHomepage(Request $request, Session $session) : string {
        	$categories = ItemCategory::all();
            return (new View('home', ["categories" => $categories]))->render();
        }
    }