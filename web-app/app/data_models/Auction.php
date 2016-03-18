<?php namespace App\Model;

    use App\Utility\Database;
    use App\Model\Item;
    use App\Model\User;

    class Auction {

        public $id;
        public $name;
        public $description;
        public $starting_price;
        public $end_date;
        public $userrole_id;
        public $created_at;
        public $updated_at;

        public $items = [];

        public $highest_bid = -1;
        public $bid_count = -1;
        public $view_count = -1;
        public $watch_count = -1;


        public function __construct(array $sqlResultRow) {

            $this->id = $sqlResultRow['id'];
            $this->name = $sqlResultRow['name'];
            $this->description = $sqlResultRow['description'];
            $this->starting_price = (int) $sqlResultRow['starting_price'];
            $this->end_date = $sqlResultRow['end_date'];
            $this->userrole_id = $sqlResultRow['userrole_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];
        }

        public static function arrayFromSQLRows(array $SQLRows) {

            $auctions = [];

            foreach($SQLRows as $row){


                $auctions[] = new Auction($row);


            }

            return $auctions;
        }

        public static function getAuctionWithId($id) {

            $results = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);

            return new Auction($results[0]);
        }

        public static function getAuctionsForUser($userrole_id) {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ?', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveAuctionsForUser($userrole_id) {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getCompletedAuctionsForUser($userrole_id) {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date <= now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveWatchedAuctionsForUser($userrole_id)  {

            $results = Database::query('SELECT * FROM Auction WHERE id IN
                (SELECT auction_id FROM Watch WHERE userrole_id = ?) AND end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveBidAuctionsForUser( $userrole_id)  {

            $results = Database::query('SELECT DISTINCT(a.id), a.name, a.description, a.starting_price, a.end_date, a.userrole_id, a.created_at, a.updated_at
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getCompletedBidAuctionsForUser($userrole_id)  {

            $results = Database::query('SELECT DISTINCT(a.id), a.name, a.description, a.starting_price, a.end_date, a.userrole_id, a.created_at, a.updated_at
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date <= now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getWonAuctionsForUser( $userrole_id)  {
            $results = Database::query('SELECT * FROM AuctionsWinners WHERE userrole_id_winner = ?',
                [$userrole_id]);
            return self::processAuctionsResultSetSql($results);

        }

        public static function getPercentageAuctionsWonForUser( $userrole_id) {

            $completedAuctions = self::getCompletedBidAuctionsForUser($userrole_id);
            $countAuctions = count($completedAuctions);
            if($countAuctions == 0)
                return 0;
            $countWon = count(self::getWonAuctionsForUser($userrole_id));
            return (int) ($countWon / $countAuctions * 100);
        }

        private static function processAuctionsResultSetSql(array $sql_results)  {

            $auctions = array();

            foreach($sql_results as $row) {
                $auctions[] = new Auction($row);
            }

            return $auctions;
        }

        public function isFinished()  {

            $results = Database::query('SELECT 1 FROM Auction WHERE id = ? AND end_date < NOW() LIMIT 1', [$this->id]);

            return isset($results[0]) && count($results[0]) !== 0;

        }

        public function buyer()  {

            $results = Database::query('SELECT ur.user_id, b.userrole_id, MAX(b.value)
                                        FROM Auction as au
                                        JOIN Bid as b
                                        ON b.auction_id = au.id
                                        JOIN UserRole AS ur
                                        ON b.userrole_id = ur.id
                                        WHERE au.id = ? AND b.created_at < au.end_date AND au.end_date < NOW()', [$this->id]);
            if(!isset($results[0])){
                return NULL;
            }

            return new User($results[0][0]);

        }

        public function seller()  {

            return User::fromUserRoleID($this->userrole_id);

        }

        public function highestBidder()  {

            $result = Database::query('SELECT ur.user_id, b.userrole_id, MAX(b.value)
                                        FROM Auction as au
                                        JOIN Bid as b
                                        ON b.auction_id = au.id
                                        JOIN UserRole AS ur
                                        ON b.userrole_id = ur.id
                                        WHERE au.id = ? AND b.created_at < au.end_date AND au.end_date > NOW()', [$this->id]);
            if(!isset($results[0])){
                return NULL;
            }

            return new User($results[0][0]);

        }

        public function getHighestBid() {

            if($this->highest_bid == -1) {

                $result = Database::query('SELECT MAX(value) as max_bid FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->highest_bid = (int) $result[0]['max_bid'];
            }

            return (int) $this->highest_bid;
        }

        public function getHighestBidForUser(User $user)  {

            $result = Database::query('SELECT max(value) as max_bid_user FROM Bid WHERE userrole_id = ? AND auction_id = ? ', [$user->buyerID(), $this->id]);

            return (int) $result[0]['max_bid_user'];
        }

        public function getBidCount()  {

            if($this->bid_count == -1) {

                $result = Database::query('SELECT count(*) as bid_count FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->bid_count = $result[0]['bid_count'];
            }

            return (int) $this->bid_count;
        }

        public function getViewCount()  {

            if($this->view_count == -1) {

                $result = Database::query('SELECT count(*) as view_count FROM View WHERE auction_id = ?', [$this->id]);
                $this->view_count = $result[0]['view_count'];
            }

            return (int) $this->view_count;
        }

        public function getWatchCount()  {

            if($this->watch_count == -1) {

                $result = Database::query('SELECT count(*) as watch_count FROM Watch WHERE auction_id = ?', [$this->id]);
                $this->watch_count = (int) $result[0]['watch_count'];
            }

            return (int) $this->watch_count;
        }

        public function hasBuyerFeedback() {

            return BuyerFeedback::existsForAuctionID($this->id);
        }

        public function hasSellerFeedback() {

            return SellerFeedback::existsForAuctionID($this->id);
        }

        public function getItems()  {

            if(count($this->items) === 0 ){

                $this->items = Item::getItemsForAuction($this->id);

            }

            return $this->items;

        }

        public function getFirstItem()  {

            return $this->getItems()[0];

        }


        public static function getRecommendationsForUser( $userrole_id)  {

            /*
                recommend auctions that users who bid on the same auctions as me bid on
            */

            $results = Database::query('SELECT Auction.* FROM Auction WHERE Auction.id IN
                (SELECT Bid.userrole_id FROM Bid WHERE Bid.auction_id IN
                (SELECT AuctionsWinners.id FROM AuctionsWinners WHERE AuctionsWinners.userrole_id_winner = ?))
                AND Auction.end_date > now() AND NOT EXISTS
                (SELECT * FROM Bid WHERE Bid.auction_id = Auction.id AND userrole_id = ?)', [$userrole_id, $userrole_id]);


            if(count($results) == 0) {
            /*
                if on the auctions the user bid he was the only bidder
                give suggestions from top categories he bought from

            */
                $results = Database::query('SELECT DISTINCT Auction.* FROM Auction JOIN Item ON Auction.id = Item.auction_id
                    WHERE Item.id IN (SELECT DISTINCT(ItemCategory.item_id) FROM ItemCategory JOIN
                    (SELECT ItemCategory.category_id, COUNT(ItemCategory.item_id) as no_items FROM ItemCategory JOIN
                    (SELECT Item.id FROM AuctionsWinners JOIN Item ON AuctionsWinners.id = Item.auction_id
                    WHERE AuctionsWinners.userrole_id_winner = ?) AS WonItems
                    ON ItemCategory.item_id = WonItems.id
                    GROUP BY ItemCategory.category_id
                    ORDER BY no_items
                    LIMIT 1) as TopCategories
                    ON ItemCategory.category_id = TopCategories.category_id)
                    AND Auction.end_date > now()
                    AND NOT EXISTS (SELECT * FROM Bid WHERE Bid.auction_id = Auction.id AND userrole_id = ?)
                    ORDER BY Auction.id ASC', [$userrole_id]);

            }

            if(count($results) == 0) {

            /*
                    if the user did not bid on any auction, recommend from popular categories

            */

                    $results = Database::query('SELECT DISTINCT Auction.* FROM Auction JOIN Item ON Auction.id = Item.auction_id
                    WHERE Item.id IN (SELECT DISTINCT(ItemCategory.item_id) FROM ItemCategory JOIN
                    (SELECT ItemCategory.category_id, COUNT(ItemCategory.item_id) as no_items FROM ItemCategory
                    GROUP BY ItemCategory.category_id
                    ORDER BY no_items
                    LIMIT 1) as TopCategories
                    ON ItemCategory.category_id = TopCategories.category_id)
                    AND Auction.end_date > now()
                     AND NOT EXISTS (SELECT * FROM Bid WHERE Bid.auction_id = Auction.id AND userrole_id = ?)
                    ORDER BY Auction.id ASC', [$userrole_id]);


            }

            return self::processAuctionsResultSetSql($results);

        }


        public function startWatchingAuction($user){

            $userrole_id = $user->buyerID();
            $auction_id = $this->id;
            $query = "INSERT INTO Watch (userrole_id, auction_id) VALUES (?,?);";

            Database::insert($query, [$userrole_id, $auction_id]);
        }

        public function stopWatchingAuction($user){

            $userrole_id = ($user->buyerID());
            $auction_id = $this->id;
            $query = "DELETE From Watch WHERE userrole_id=? AND auction_id=?";

            Database::delete($query, [$userrole_id, $auction_id]);
        }

        public function placeBid($user, $bid){

            $userrole_id = ($user->buyerID());
            $auction_id = $this->id;
            $query = "INSERT INTO Bid (userrole_id, auction_id, value) VALUES (?,?,?);";

            Database::insert($query, [$userrole_id, $auction_id, $bid]);
        }

        public function incrementViewsNumber($user){
            $auction_id = $this->id;

            if(!is_null($user)){

                if(!is_null($user->buyerID())){
                    $userrole_id = $user->buyerID();
                }

                else{
                    $userrole_id = $user->sellerID();
                }


            }
            else{
                $userrole_id = -1;
            }

            $query = "INSERT INTO View (userrole_id, auction_id) VALUES (?,?);";
            Database::insert($query, [$userrole_id, $auction_id]);
        }


    }