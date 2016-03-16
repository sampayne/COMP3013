<?php namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\User;
    use App\Model\Auction;

    class FeedbackController extends Controller {

        public function getFeedbackList(Request $request, Session $session, $user_id) {

            $user = User::fromID($user_id);

            return View::renderView('feedback_list', ['user'=> $session->activeUser(), 'related_user' => $user]);
        }

        public function getFeedbackForm(Request $request, Session $session, $auction_id) {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            $auction = Auction::getAuctionWithId($auction_id);


            return View::renderView('feedback_form', ['user'=>$session->activeUser(), 'auction'=>$auction]);

        }

    }