<?php declare(strict_types=1);

    namespace App\Model;
    use App\Utility\Database;

    class BuyerFeedback {


        /*

            REMINDER TO SAM:

            Buyer Feedback is Feedback FOR BUYERS


        */

        public $id;
        public $content;
        public $rating;
        public $auction_id;
        public $created_at;
        public $updated_at;

        public function __construct(array $sqlResultRow) {

            $this->id = $sqlResultRow['id'];
            $this->content = $sqlResultRow['content'];
            $this->rating = $sqlResultRow['rating'];
            $this->auction_id = $sqlResultRow['auction_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];

        }

        public static function getFeedbackWithId(int $id) {

            $results = Database::query('SELECT * FROM BuyerFeedback WHERE id = ?', [$id]);
            return new SellerFeedback($results);
        }

        public static function getFeedbackWithAuctionId(int $auction_id) {

            $results = Database::query('SELECT * FROM BuyerFeedback WHERE auction_id = ?', [$auction_id]);
            return new SellerFeedback($results);
        }

        private static function processFeedbackResultSetSql(array $sql_results) : array {

            $feedback = Array();
            foreach($sql_results as $row) {
                $feedback[] = new BuyerFeedback($row);
            }
            return $feedback;

        }

        public static function getFeedbackForUser(int $userrole_id) : array {
            
            $results = Database::query('SELECT * FROM BuyerFeedback WHERE auction_id 
                IN (SELECT id FROM AuctionsWinners WHERE userrole_id_winner = ?)', [$userrole_id]);
            
            return self::processFeedbackResultSetSql($results);

        }

        public static function getMeanRatingForUser(int $userrole_id) : array {
            //slightly wrong as above
            $results = Database::query('SELECT avg(rating) as mean_rating,
                                               count(*) as no_feedback
                                               FROM BuyerFeedback WHERE auction_id IN
                (SELECT b.auction_id FROM (SELECT DISTINCT(auction_id) FROM Bid WHERE userrole_id = ?) as b 
                JOIN (SELECT id FROM Auction) as a ON b.auction_id = a.id)', [$userrole_id]);
            return $results[0];

        }
    }

