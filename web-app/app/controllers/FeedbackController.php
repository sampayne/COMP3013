<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class FeedbackController extends Controller {

        public function getFeedbackList(Request $request, Session $session, int $user_id) : string {

            $user = User::fromID($user_id);

            return View::renderView('feedback_list', ['user'=> $session->activeUser(), 'related_user' => $user]);
        }

        public function getFeedbackForm(Request $request, Session $session, int $auction_id) : string {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            $auction = Auction::getAuctionWithId($auction_id);
            print_r($auction);
            return '';

            return View::renderView('feedback_form', ['user'=>$session->activeUser(), 'auction'=>$auction]);

        }

    }