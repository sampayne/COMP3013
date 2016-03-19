<?php namespace App\Model;

    use App\Utility\Database;

    class BuyerFeedback {

        public $id;
        public $content;
        public $speed_of_payment;
        public $communication;
        public $auction_id;
        public $created_at;
        public $updated_at;

        public static $number_of_ratings = 2;

        public function __construct(array $sqlResultRow) {

            $this->id = $sqlResultRow['id'];
            $this->content = $sqlResultRow['content'];
            $this->speed_of_payment = (float) $sqlResultRow['speed_of_payment'];
            $this->communication = (float) $sqlResultRow['communication'];
            $this->auction_id = $sqlResultRow['auction_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];

        }

        public function mean() {

            return round(($this->speed_of_payment + $this->communication)/self::$number_of_ratings, 1);

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
            //unprocessed results
            $results = Database::query('SELECT avg(communication) as mean_communication,
                                               avg(speed_of_payment) as mean_speed_of_payment,
                                               count(*) as no_feedback
                                               FROM BuyerFeedback WHERE auction_id IN
                (SELECT id FROM AuctionsWinners WHERE userrole_id_winner = ?)', [$userrole_id]);

            $mean_rating['mean_communication'] = isset($results[0]['mean_communication']) ? $results[0]['mean_communication'] : 0;
            $mean_rating['mean_speed_of_payment'] = isset($results[0]['mean_speed_of_payment']) ? $results[0]['mean_speed_of_payment'] : 0;

            return $mean_rating;

        }
    }

