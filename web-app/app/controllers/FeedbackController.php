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

            return View::renderView('feedback_list', ['related_user' => $user]);
        }

        public function getFeedbackForm(Request $request, Session $session, $auction_id) {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            $auction = Auction::getAuctionWithId($auction_id);


            return View::renderView('feedback_form', ['auction'=>$auction]);

        }

        public function postSellerFeedback(Request $request, Session $session, $auction_id) {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            $auction = Auction::getAuctionWithId($auction_id);

            Database::insert('INSERT INTO SellerFeedback (content, item_as_described, dispatch_time, communication, posting, auction_id) VALUES (?,?,?,?,?,?)',
                                                                        [$request->post['feedback_comment'],
                                                                         $request->post['item_as_described'],
                                                                         $request->post['communication'],
                                                                         $request->post['dispatch_time'],
                                                                         $request->post['packaging'],
                                                                         $auction->id
                                                                         ]);




            return $this->redirectTo('/dashboard/?message='.urlencode('Feedback saved!'));

        }


        public function postBuyerFeedback(Request $request, Session $session, $auction_id) {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            $auction = Auction::getAuctionWithId($auction_id);

            Database::insert('INSERT INTO BuyerFeedback (content, speed_of_payment, communication, auction_id) VALUES (?,?,?,?)',[$request->post['feedback_comment'], $request->post['speed_of_payment'], $request->post['communication'], $auction->id]);

            return $this->redirectTo('/dashboard/?message='.urlencode('Feedback saved!'));


        }


    }