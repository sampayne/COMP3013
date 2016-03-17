<?php namespace App\Model;

    use App\Utility\Database;

    class BuyerFeedback {

        public $id;
        public $content;
        public $rating;
        public $auction_id;
        public $created_at;
        public $updated_at;

        public function __construct(array $sqlResultRow) {

            $this->id = $sqlResultRow['id'];
            $this->content = $sqlResultRow['content'];
            $this->rating = (float) $sqlResultRow['rating'];
            $this->auction_id = $sqlResultRow['auction_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];

        }

        public function mean() {

            return $this->rating;

        }

        public static function existsForAuctionID($auction_id) {

            return Database::checkExists($auction_id, 'auction_id', 'BuyerFeedback');

        }

        public static function getFeedbackWithId($id) {

            $results = Database::query('SELECT * FROM BuyerFeedback WHERE id = ?', [$id]);
            return new BuyerFeedback($results);
        }

        public static function getFeedbackWithAuctionId($auction_id) {

            $results = Database::query('SELECT * FROM BuyerFeedback WHERE auction_id = ?', [$auction_id]);
            return new BuyerFeedback($results);
        }

        private static function processFeedbackResultSetSql(array $sql_results) {

            $feedback = Array();
            foreach($sql_results as $row) {
                $feedback[] = new BuyerFeedback($row);
            }
            return $feedback;

        }

        public static function getFeedbackForUser($userrole_id) {

            $results = Database::query('SELECT * FROM BuyerFeedback WHERE auction_id
                IN (SELECT id FROM AuctionsWinners WHERE userrole_id_winner = ?)', [$userrole_id]);

            return self::processFeedbackResultSetSql($results);

        }

        public static function getMeanRatingForUser($userrole_id) {
            //slightly wrong as above
            $results = Database::query('SELECT avg(rating) as mean_rating,
                                               count(*) as no_feedback
                                               FROM BuyerFeedback WHERE auction_id IN
                (SELECT b.auction_id FROM (SELECT DISTINCT(auction_id) FROM Bid WHERE userrole_id = ?) as b
                JOIN (SELECT id FROM Auction) as a ON b.auction_id = a.id)', [$userrole_id]);
            return $results[0];

        }
    }

