<?php

    namespace App\Model;

    use App\Utility\Database;
    use App\Model\Role;

    class User {

        public $username;
        public $id;

        private $buyer_role_id = NULL;
        private $seller_role_id = NULL;

        public function __construct($id){

            $this->id = $id;

        }

        public static function fromUserRoleID($userrole_id) {

            $results = Database::selectOne('SELECT id, email FROM User WHERE id IN (SELECT user_id FROM UserRole WHERE id = ?)', [$userrole_id]);

            if(!count($results)){
                fatalError('User Not Found');
            }

            $user = new User((int) $results['id']);
            $user->email = $results['email'];

            return $user;


        }

        public function notifications(){

            if(!isset($this->notifications)){

                $this->notifications = Notification::forUser($this->id);

            }

            return $this->notifications;

        }

        public function email(){

            if(!isset($this->email)){

                $results = Database::selectOne('SELECT email FROM User WHERE id = ?', [$id]);
                $this->email = $results[0][0];

            }

            return $this->email;

        }

        public static function fromID($id) {

            $results = Database::selectOne('SELECT email FROM User WHERE id = ?', [$id]);

            if(!count($results)){
                fatalError('User Not Found');
            }

            $user = new User($id);
            $user->email = $results['email'];

            return $user;

        }

        private function loadUserRoles(){

            $userrole_query = Database::query("SELECT id, role_id FROM UserRole WHERE user_id = ? ORDER BY role_id", [$this->id]);

            if(!empty($userrole_query) && !empty($userrole_query[0])) {

                if($userrole_query[0]['role_id'] == Role::seller()) {
                    $this->seller_role_id = (int) $userrole_query[0]['id'];
                    if(!empty($userrole_query[1]) && $userrole_query[1]['role_id'] == Role::buyer())
                        $this->buyer_role_id = (int) $userrole_query[1]['id'];
                }
                else
                     if($userrole_query[0]['role_id'] == Role::buyer())
                        $this->buyer_role_id = (int) $userrole_query[0]['id'];

            }
        }

        public function sellerID() {

            if(is_null($this->seller_role_id)){
                $this->loadUserRoles();
            }

            return (int) $this->seller_role_id;
        }

        public function buyerID() {

            if(is_null($this->buyer_role_id)){
                $this->loadUserRoles();
            }

            return (int) $this->buyer_role_id;

        }

        public function isSeller() {
            return $this->sellerID() != NULL;
        }

        public function isBuyer() {
            return $this->buyerID() != NULL;
        }

        public function getAuctions() {

            return Auction::getAuctionsForUser($this->sellerID());
        }

        public function getLiveAuctions() {

            return Auction::getLiveAuctionsForUser($this->sellerID());
        }

        public function getCompletedAuctions() {

            return Auction::getCompletedAuctionsForUser($this->sellerID());
        }

        public function getLiveWatchedAuctions() {

            return Auction::getLiveWatchedAuctionsForUser($this->buyerID());
        }

        public function getLiveBidAuctions() {

            return Auction::getLiveBidAuctionsForUser($this->buyerID());
        }

        public function getCompletedBidAuctions() {
            return Auction::getCompletedBidAuctionsForUser($this->buyerID());
        }

        public function getWonAuctions() {
            return Auction::getPercentageAuctionsWonForUser($this->buyerID());
        }

        public function getSellerFeedback() {
            return $this->isSeller() ? SellerFeedback::getFeedbackForUser($this->sellerID()) : [];
        }

        public function getSellerMeanRating() {

            $results =  SellerFeedback::getMeanRatingForUser($this->sellerID());

            $results['overall'] = array_sum($results)/count($results);

            $results = array_map(function($i){ return round($i,1);  }, $results);

            return $results;
        }

        public function getBuyerFeedback() {

            return $this->isBuyer() ? BuyerFeedback::getFeedbackForUser($this->buyerID()) : [];
        }

        public function getBuyerMeanRating() {
            return BuyerFeedback::getMeanRatingForUser($this->buyerID());
        }

        public function getBuyerBidCount() {

            $result = Database::query("SELECT COUNT(*) AS count FROM Bid WHERE userrole_id = ?", [$this->buyerID()]);
            return (int) $result[0]['count'];

        }

        public function getBuyerWatchCount() {

            $result = Database::query("SELECT COUNT(*) AS count FROM Watch WHERE userrole_id = ?", [$this->buyerID()]);
            return (int) $result[0]['count'];

        }

       public function getPercentageAuctionsWon() {

            return Auction::getPercentageAuctionsWonForUser($this->buyerID());

        }

        public function getRecommendations() {

            return Auction::getRecommendationsForUser($this->buyerID());

        }

    }