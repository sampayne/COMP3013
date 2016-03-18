<?php
    namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;

    use App\Model\User;
    use App\Model\Notification;

    class NotificationController extends Controller {

        public function clearNotifications(Request $request, Session $session) {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/login');
            }

            Notification::clearForUser($session->activeUser()->id);

            return $this->redirectTo('/dashboard?message='.urlencode('Notifications cleared'));

        }
    }