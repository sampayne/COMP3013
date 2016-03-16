<?php declare(strict_types=1);

    namespace App\Model;

    use App\Utility\Database;
    use App\Model\Role;

    class User {

        public $username;
        public $id;

        private $buyer_role_id = NULL;
        private $seller_role_id = NULL;

        public function __construct(int $id){

            $this->id = $id;

        }

        public static function fromID($id) : User {

            $results = Database::selectOne('SELECT email FROM User WHERE id = ?', [$id]);

            if(!count($results)){
                fatalError('User Not Found');
            }

            $user = new User((int) $id);
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

        public function sellerID(){

            if(is_null($this->seller_role_id)){
                $this->loadUserRoles();
            }

            return $this->seller_role_id;
        }

        public function buyerID(){

            if(is_null($this->buyer_role_id)){
                $this->loadUserRoles();
            }

            return $this->buyer_role_id;

        }

        public function isSeller() : bool {
            return $this->sellerID() != NULL;
        }

        public function isBuyer() : bool {
            return $this->buyerID() != NULL;
        }

        public function getAuctions() : array {

            return Auction::getAuctionsForUser($this->sellerID());
        }

        public function getLiveAuctions() : array {

            return Auction::getLiveAuctionsForUser($this->sellerID());
        }

        public function getCompletedAuctions() : array {

            return Auction::getCompletedAuctionsForUser($this->sellerID());
        }

        public function getLiveWatchedAuctions() : array {

            return Auction::getLiveWatchedAuctionsForUser($this->buyerID());
        }

        public function getLiveBidAuctions() : array {

            return Auction::getLiveBidAuctionsForUser($this->buyerID());
        }

        public function getCompletedBidAuctions() : array {
            return Auction::getCompletedBidAuctionsForUser($this->buyerID());
        }

        public function getWonAuctions() : array {
            return Auction::getPercentageAuctionsWonForUser($this->buyerID());
        }

        public function getSellerFeedback() : array {
            return $this->isSeller() ? SellerFeedback::getFeedbackForUser($this->sellerID()) : [];
        }

        public function getSellerMeanRating() : array {

            $results =  SellerFeedback::getMeanRatingForUser($this->sellerID());

            $results['overall'] = array_sum($results)/count($results);

            $results = array_map(function($i){ return round($i,1);  }, $results);

            return $results;
        }

        public function getBuyerFeedback() : array {

            return $this->isBuyer() ? BuyerFeedback::getFeedbackForUser($this->buyerID()) : [];
        }

        public function getBuyerMeanRating() : array {
            return BuyerFeedback::getMeanRatingForUser($this->buyerID());
        }

        public function getBuyerBidCount() : int {

            $result = Database::query("SELECT COUNT(*) AS count FROM Bid WHERE userrole_id = ?", [$this->buyerID()]);
            return (int) $result[0]['count'];

        }

        public function getBuyerWatchCount() : int {

            $result = Database::query("SELECT COUNT(*) AS count FROM Watch WHERE userrole_id = ?", [$this->buyerID()]);
            return (int) $result[0]['count'];

        }

        public function getPercentageAuctionsWon() : int {

            return Auction::getPercentageAuctionsWonForUser($this->buyerID());

        }

        public function getRecommendations() : array {

            return Auction::getRecommendationsForUser($this->buyerID());
        
        }

    }