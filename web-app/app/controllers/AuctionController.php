<?php namespace App\Controller;

    use App\Utility\Request;
    use App\Utility\Session;
    use App\Utility\View;
    use App\Utility\Database;


    use App\Model\User;
    use App\Model\Auction;
    use App\Model\ItemCategory;

    use App\Utility\Creator\AuctionCreator;
    use App\Utility\Creator\ItemCreator;

    use App\Utility\NotificationSender;

    class AuctionController extends Controller {

        public function getAuction(Request $request, Session $session) {

            $auction_id = end($request->url_array);
            $auction_data = $this->getAuctionData($auction_id);
            $current_auction = Auction :: getAuctionWithId(intval($auction_id));

            if($session->userIsLoggedIn())
                $current_auction->incrementViewsNumber($session->activeUser());

            else
                $current_auction->incrementViewsNumber(NULL);


            if(!empty($auction_data)){

                $this->setWatchPreferences($auction_data, $auction_id, $session);
                $this->setMinimumPriceToBid($auction_data, $auction_id);
                $auction_data[0]["auction_exists"] = true;
                $auction_data[0]["auction"] = Auction::getAuctionWithId(intval($auction_id));
                $auction_data[0]["expired"] = (new \DateTime() > new \DateTime($auction_data[0]["auction"]->end_date)) ? false : true;
                $auction_data[0]["items"] = $auction_data[0]["auction"]->getItems();
                $auction_data[0]['message'] = (isset($request->get['message'])) ? $request->get['message'] : NULL;
                $auction_data[0]['error'] = (isset($request->get['error'])) ? $request->get['error'] : NULL;
                return (new View('auction', $auction_data[0]))->render();

            }else{

                return (new View('auction', ["auction_exists" => false]))->render();
            }

        }

        public function getWatchConfirmationPage(Request $request, Session $session) {

            $data = array();
            $auction_id = intval($request->url_array[1]);
            $this->setWatchConfirmation($data, $session, $request);

            if($data["watch"] == 1)
                return $this->redirectTo('/auction/'.$auction_id.'?message='.urlencode('You are now watching this auction!'));

            else{
                return $this->redirectTo('/auction/'.$auction_id.'?message='.urlencode('You have succesfully stopped watching this auction!'));
            }
        }

        public function getBidConfirmationPage(Request $request, Session $session) {

            $data = array();
            $bid = floatval($request->post["bid-bar"]) * 100;
            $auction_id = intval($request->url_array[1]);

            if($bid >= $this->getHighestBid($auction_id)){
                $data["isHighest"] = "true";
                $current_user = $session->activeUser();
                $current_auction = Auction::getAuctionWithId($auction_id);

                $previous_highest_bidder = $current_auction->highestBidder();



                if(!is_null($previous_highest_bidder) && !($previous_highest_bidder->id == $current_user->id) ){

                    NotificationSender::sendOutbidNotification($previous_highest_bidder->id, $current_auction);

                }

                $current_auction->placeBid($current_user, $bid);

                NotificationSender::sendBidRecievedNotification($current_auction);
            }

            else{
                $data["isHighest"] = "false";
            }

            if($data["isHighest"] == "true"){
                return $this->redirectTo('/auction/'.$auction_id.'?message='.urlencode('Congratulations, you have now placed the highest bid from this auction!'));
            }

            else{
                return $this->redirectTo('/auction/'.$auction_id.'?error='.urlencode('Sorry, your bid was not placed as it was lower than the minimum required bid'));
            }
        }

        private function getAuctionData($id) {

            $result = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);
            return $result;

        }

        public function getCreateAuctionPage(Request $request, Session $session)  {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/login');
            }

            if(!$session->activeUser()->isSeller()){

                return $this->redirectTo('/dashboard?error='.urlencode('You must register as a seller to create an auction'));
            }

            return  (new View('create_auction', ['user'=>$session->activeUser(), 'item_categories' => ItemCategory::all()]))->render();

        }

        public function getHighestBid($auction_id){

            $current_auction = Auction::getAuctionWithId(intval($auction_id));
            return $current_auction->getNextBidValue();

        }

        public function createNewAuction(Request $request, Session $session) {

            if(!$session->userIsLoggedIn()){
                return $this->redirectTo('/login');
            }

            if(!$session->activeUser()->isSeller()){
                return $this->redirectTo('/dashboard?error='.urlencode('You must register as a seller to create an auction'));
            }

            $auction_input = $request->post;

            $auction_input['userrole_id'] = $session->activeUser()->sellerID();

            $auction_creator = new AuctionCreator($request);
            $auction_errors = $auction_creator->validateInput($auction_input);

            if(count($auction_errors) > 0){
                fatalError($auction_errors);
            }

            if(isset($auction_input['items']) === false){
                fatalError('Items Missing');
            }

            if(isset($request->files['item_image']['name']) === false || count($request->files['item_image']['name']) !== count($auction_input['items'])){
                fatalError('Items Images Missing');
            }


            $items = $this->processInput($auction_input['items'], $request->files['item_image']);

            $item_creator = new ItemCreator($request);

            foreach($items as $key => $item){

                $item_errors = $item_creator->validateInput($item);

                if(count($item_errors) > 0){
                    fatalError($item_errors);
                }
            }

            $auction_id = $auction_creator->saveInput($auction_input);

            foreach($items as $key => $item){

                $item['auction_id'] = $auction_id;
                $item_id = $item_creator->saveInput($item);

            }

            return $this->redirectTo('/dashboard?message='.urlencode('Auction Created'));
        }

        private function processInput(array $items, array $images) {

            $merged_items = [];

            foreach($items as $item_index => $item){

                $new_image = [];

                foreach($images as $image_key => $image_value){

                    $new_image[$image_key] = $image_value[$item_index];
                }

                $item['image'] = $new_image;

                $merged_items[] = $item;

            }

            return $merged_items;
        }

        private function setWatchPreferences(&$auction_data, $auction_id, $session) {
            $auction_data[0]["isUserBuyer"] = false;
            $auction_data[0]["isWatched"] = false;

            if($session->userIsLoggedIn()){
                $current_user = $session->activeUser();
                $auction_data[0]["isUserBuyer"] = $current_user->isBuyer();
                $test = $current_user->getLiveWatchedAuctions();

                foreach ($test as $watched_auction){

                    if($watched_auction->id == $auction_id){
                        $auction_data[0]["isWatched"] = true;
                        break;
                    }
                }
            }

            return $auction_data[0]["isWatched"];
        }

        private function setMinimumPriceToBid(&$auction_data, $auction_id){

            $auction_data[0]["min_bid"] = ($this->getHighestBid($auction_id) + 1) / 100;
            $auction_data[0]["starting_price"] = $auction_data[0]["starting_price"] / 100;

        }

        private function setWatchConfirmation(&$data, $session, $request){
            $current_user = $session->activeUser();
            $current_auction = Auction::getAuctionWithId(intval($request->url_array[1]));

            $data["watch"] = $request->post["watch"];

            if($data["watch"] == "1"){

                $current_auction->startWatchingAuction($current_user);

                NotificationSender::sendWatchRecievedNotification($current_auction);


            }

            elseif($data["watch"] == "0"){
                $current_auction->stopWatchingAuction($current_user);
            }

        }

    }