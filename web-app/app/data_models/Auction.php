<?php declare(strict_types=1);

    namespace App\Model;

    use App\Utility\Database;
    use App\Model\Item;

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
            $this->starting_price = $sqlResultRow['starting_price'];
            $this->end_date = $sqlResultRow['end_date'];
            $this->userrole_id = $sqlResultRow['userrole_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];
        }

        public static function getAuctionWithId(int $id) : Auction {

            $results = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);

            return new Auction($results[0]);
        }

        public static function getAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ?', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getCompletedAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ? AND end_date <= now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveWatchedAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM Auction WHERE id IN
                (SELECT auction_id FROM Watch WHERE userrole_id = ?) AND end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getLiveBidAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT DISTINCT(a.id), a.name, a.description, a.starting_price, a.end_date, a.userrole_id, a.created_at, a.updated_at
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date > now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getCompletedBidAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT DISTINCT(a.id), a.name, a.description, a.starting_price, a.end_date, a.userrole_id, a.created_at, a.updated_at
                FROM Bid b JOIN Auction a ON b.auction_id = a.id
                WHERE b.userrole_id = ? AND a.end_date <= now()', [$userrole_id]);

            return self::processAuctionsResultSetSql($results);
        }

        public static function getWonAuctionsForUser(int $userrole_id) : array {
            $results = Database::query('SELECT * FROM AuctionsWinners WHERE userrole_id_winner = ?', 
                [$userrole_id]);
            return self::processAuctionsResultSetSql($results);

        }
      
        public static function getPercentageAuctionsWonForUser(int $userrole_id) : int {

            $completedAuctions = self::getCompletedBidAuctionsForUser($userrole_id);
            $countAuctions = count($completedAuctions);
            if($countAuctions == 0)
                return 0;
            $countWon = count(self::getWonAuctionsForUser($userrole_id));
            return (int) ($countWon / $countAuctions * 100);
        }

        private static function processAuctionsResultSetSql(array $sql_results) : array {

            $auctions = Array();

            foreach($sql_results as $row) {
                $auctions[] = new Auction($row);
            }

            return $auctions;
        }

        public function getHighestBid() : int {

            if($this->highest_bid == -1) {

                $result = Database::query('SELECT max(value) as max_bid FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->highest_bid = $result[0]['max_bid'];
            }

            return (int) $this->highest_bid;
        }

        public function getHighestBidForUser(User $user) : int {

            $result = Database::query('SELECT max(value) as max_bid_user FROM Bid WHERE userrole_id = ? AND auction_id = ? ', [$user->buyerID(), $this->id]);

            return (int) $result[0]['max_bid_user'];
        }


        public function getBidCount() : int {

            if($this->bid_count == -1) {

                $result = Database::query('SELECT count(*) as bid_count FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->bid_count = $result[0]['bid_count'];
            }

            return (int) $this->bid_count;
        }

        public function getViewCount() : int {

            if($this->view_count == -1) {

                $result = Database::query('SELECT count(*) as view_count FROM View WHERE auction_id = ?', [$this->id]);
                $this->view_count = $result[0]['view_count'];
            }

            return (int) $this->view_count;
        }

        public function getWatchCount() : int {

            if($this->watch_count == -1) {

                $result = Database::query('SELECT count(*) as watch_count FROM Watch WHERE auction_id = ?', [$this->id]);
                $this->watch_count = $result[0]['watch_count'];
            }

            return (int) $this->watch_count;
        }

        public function getItems() : array {

            if(count($this->items) === 0 ){

                $this->items = Item::getItemsForAuction($this->id);

            }

            return $this->items;

        }

        public function getFirstItem() : Item {

            return $this->getItems()[0];

        }

        public static function getRecommendationsForUser(int $userrole_id) : array {

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
                give suggestions from top categories

            */
                $results = Database::query('SELECT DISTINCT Auction.* FROM Auction JOIN Item ON Auction.id = Item.auction_id 
                    WHERE Item.id IN (SELECT DISTINCT(ItemCategory.item_id) FROM ItemCategory JOIN 
                    (SELECT ItemCategory.category_id, COUNT(ItemCategory.item_id) as no_items FROM ItemCategory JOIN 
                    (SELECT Item.id FROM AuctionsWinners JOIN Item ON AuctionsWinners.id = Item.auction_id 
                    WHERE AuctionsWinners.userrole_id_winner = ?) AS WonItems 
                    ON ItemCategory.item_id = WonItems.id 
                    GROUP BY ItemCategory.category_id
                    ORDER BY no_items
                    LIMIT 2) as TopCategories
                    ON ItemCategory.category_id = TopCategories.category_id)
                    AND Auction.end_date > now()  
                    ORDER BY Auction.id ASC', [$userrole_id]);

            }

            return self::processAuctionsResultSetSql($results);

        }


        public function startWatchingAuction($user){

            $userrole_id = ($user->buyerID());
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