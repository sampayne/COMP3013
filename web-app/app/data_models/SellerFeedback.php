<?php declare(strict_types=1);

    namespace App\Model;
    use App\Utility\Database;
    use App\Model\Auction;

    class SellerFeedback {

        /*

            REMINDER TO SAM:

            Seller Feedback is Feedback FOR SELLERS


        */


        public $id;
        public $content;
        public $item_as_described;
        public $communication;
        public $dispatch_time;
        public $posting;
        public $auction_id;
        public $created_at;
        public $updated_at;

        private $auction;

        public static $number_of_ratings = 4;

        public function __construct(array $sqlResultRow) {

            $this->id = (int) $sqlResultRow['id'];
            $this->content = $sqlResultRow['content'];
            $this->item_as_described = $sqlResultRow['item_as_described'];
            $this->communication = $sqlResultRow['communication'];
            $this->dispatch_time = $sqlResultRow['dispatch_time'];
            $this->posting = $sqlResultRow['posting'];
            $this->auction_id = $sqlResultRow['auction_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];

        }

        public function getRelatedAuction() : Auction {

            if(is_null($this->auction)){

                $this->auction = Auction::getAuctionWithId((int) $this->auction_id);

            }

            return $this->auction;

        }

        public function mean() : float {

            return ($this->item_as_described + $this->communication + $this->dispatch_time + $this->posting)/self::$number_of_ratings;

        }

        public static function existsForAuctionID(int $auction_id) : bool {

            return Database::checkExists($auction_id, 'auction_id', 'SellerFeedback');

        }



        public static function getFeedbackWithId(int $id) : SellerFeedback {

            $results = Database::query('SELECT * FROM SellerFeedback WHERE id = ?', [$id]);
            return new SellerFeedback($results);
        }

        public static function getFeedbackWithAuctionId(int $auction_id) {
            $results = Database::query('SELECT * FROM SellerFeedback WHERE auction_id = ?', [$auction_id]);
            return new SellerFeedback($results);
        }

        private static function processFeedbackResultSetSql(array $sql_results) : array {

            $feedback = Array();
            foreach($sql_results as $row) {
                $feedback[] = new SellerFeedback($row);
            }
            return $feedback;

        }

        public static function getFeedbackForUser(int $userrole_id) : array {

            $results = Database::query('SELECT * FROM SellerFeedback WHERE auction_id IN
                (SELECT id FROM Auction WHERE userrole_id = ?)', [$userrole_id]);
            return self::processFeedbackResultSetSql($results);
        }

        public static function getMeanRatingForUser(int $userrole_id) : array {

            //unproccesed result
            $results = Database::query('SELECT avg(item_as_described) as mean_item_as_described,
                avg(communication) as mean_communication,
                avg(dispatch_time) as mean_dispatch_time,
                avg(posting) as mean_posting,
                count(*) as no_feedback
                FROM SellerFeedback JOIN Auction ON SellerFeedback.auction_id = Auction.id
                WHERE Auction.userrole_id = ?
                GROUP BY Auction.userrole_id', [$userrole_id]);

            if(count($results) == 0) {
                $results['mean_item_as_described'] = 0;
                $results['mean_communication'] = 0;
                $results['mean_dispatch_time'] = 0;
                $results['mean_posting'] = 0;
                $results['no_feedback'] = 0;
                return $results;

            }

            return $results[0];

        }
    }

