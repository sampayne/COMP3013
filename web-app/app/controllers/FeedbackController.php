<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;

    class FeedbackController extends Controller {

        public function getFeedbackList(Request $request, Session $session, $user_id) : string {

            $user = User::fromID($user_id);

            return View::renderView('feedback_list', ['related_user' => $user]);
        }
    }