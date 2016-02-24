<?php declare(strict_types=1);

    namespace App\Model;
    use App\Utility\Database;

    class Auction {

        public $id;
        public $name;
        public $description;
        public $starting_price;
        public $end_date;
        public $userrole_id;
        public $created_at;
        public $updated_at;

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

        public static getAuctionWithId(int $id) : Auction {

            $results = Database::query('SELECT * FROM Auction WHERE id = ?', [$id]);
            return new Auction($results[0]);
        }

        public static getAuctionsForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM Auction WHERE userrole_id = ?', [$userrole_id]);
            $users = Array();
            foreach($results as $row) {
                $users[] = new User($row);
            }
            return $users;   

        }

        public function getHighestBid() {
        
            if($this->highest_bid == -1) {
                $result = Database::query('SELECT max(value) as max_bid FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->highest_bid = $result[0]['max_bid'];

            }

            return $this->highest_bid;
        }


        public function getBidCount() {
            
            if($this->bid_count == -1) {
                $result = Database::query('SELECT count(*) as bid_count FROM Bid WHERE auction_id = ?', [$this->id]);
                $this->bid_count = $result[0]['bid_count'];
            }
            
            return $this->bid_count;
        }

        public function getViewCount() {
            
            if($this->view_count == -1) {
                $result = Database::query('SELECT count(*) as view_count FROM View WHERE auction_id = ?', [$this->id]);  
                $this->view_count = $result[0]['view_count'];
            }
            return $this->view_count;

        }

        public function getWatchCount() {
            
            if($this->watch_count == -1) {
                $result = Database::query('SELECT count(*) as watch_count FROM Watch WHERE auction_id = ?', [$this->id]);
                $this->watch_count = $result[0]['watch_count'];
            }

            return $this->watchCount;

        }

    }