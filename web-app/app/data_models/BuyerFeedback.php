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

        public function mean() : float {

            return $this->rating;

        }

        public static function existsForAuctionID(int $auction_id) : bool {

            return Database::checkExists($auction_id, 'auction_id', 'BuyerFeedback');

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

            /*
                - still slightly wrong

                - The data in the database does not account for restrictions
                (e.g. only winner of auctions can have feedback from sellers)

                - If we want to take feedback only for winners of auctions - the good way?

                SELECT BuyerFeedback.content FROM BuyerFeedback where BuyerFeedback.auction_id IN
                (SELECT a.auction_id FROM (SELECT max(value) as max_bid, auction_id FROM Bid GROUP BY auction_id) as a
                JOIN (SELECT max(value) as max_bid, auction_id, userrole_id FROM Bid GROUP BY auction_id, userrole_id) as u
                ON a.auction_id = u.auction_id AND a.max_bid = u.max_bid
                JOIN Auction ON a.auction_id = Auction.id WHERE u.userrole_id = ? AND Auction.end_date <= now())

            */

            $results = Database::query('SELECT * FROM BuyerFeedback where auction_id IN
                (SELECT b.auction_id FROM (SELECT DISTINCT(auction_id) FROM Bid WHERE userrole_id = ?) as b
                JOIN (SELECT id FROM Auction) as a ON b.auction_id = a.id)', [$userrole_id]);
                //AND Auction.end_date <= now()

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

