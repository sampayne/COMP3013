<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class SearchController extends Controller {

        public function getSearch(Request $request, Session $session) : string {
            $searchTerm = $request->get['search-bar'];
            return (new View('search', ['searchTerm' => $searchTerm]))->render();
        }

    }