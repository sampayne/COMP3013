<?php declare(strict_types=1);

    namespace App\Controller;

    use App\Utility\{Request, Session, View, Database};
    use App\Model\User;
    use App\Model\ItemCategory;

    use App\Utility\Creator\AuctionCreator;
    use App\Utility\Creator\ItemCreator;

    class AuctionController extends Controller {

        public function getAuction(Request $request, Session $session) : string {

        	$auction_id = end($request->url_array);
        	$auction_data = $this->getAuctionData($auction_id);

        	if(!empty($auction_data)){

        		$auction_data[0]["auction_exists"] = true;
            	return (new View('auction', $auction_data[0]))->render();

            }else{

            	return (new View('auction', ["auction_exists" => false]))->render();
            }

        }

        private function getAuctionData($id) : string {

            $result = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);
            return $result;

        }

        public function getCreateAuctionPage(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/login');
            }

            if(!$session->activeUser()->isSeller()){

                return $this->redirectTo('/dashboard?error='.urlencode('You must register as a seller to create an auction'));
            }

            return  (new View('create_auction', ['user'=>$session->activeUser(), 'item_categories' => ItemCategory::all()]))->render();

        }

        public function createNewAuction(Request $request, Session $session) : string {

            if(!$session->userIsLoggedIn()){

                return $this->redirectTo('/login');
            }

            if(!$session->activeUser()->isSeller()){

                return $this->redirectTo('/dashboard?error='.urlencode('You must register as a seller to create an auction'));
            }

            echo json_encode($request->post);


            $auction_input = $request->post;

            $auction_input['userrole_id'] = $session->activeUser()->seller_role_id;

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

        private function processInput(array $items, array $images) : array {

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

    }