<?php namespace App\Model;

    use App\Utility\Database;
    use App\Model\User;

    class Bid {


        public static function forAuction($auction_id){

            $results = Database::query('SELECT b.*, u.email, u.id AS user_id FROM Bid AS b
                                        JOIN UserRole AS ur ON b.userrole_id = ur.id
                                        JOIN User AS u ON ur.user_id = u.id
                                        WHERE auction_id = ? ORDER BY b.created_at DESC', [$auction_id]);


            return self::arrayFromSQLRows($results);


        }

        public static function arrayFromSQLRows($results){

            $bids = [];

            foreach($results as $result){

                $bids[] = self::fromSQLRow($result);

            }

            return $bids;

        }

        public static function fromSQLRow($SQLRow){

            $bid = new Bid();

            $bid->value = $SQLRow['value'];

            $user = new User($SQLRow['user_id']);
            $user->email = $SQLRow['email'];

            $bid->user = $user;

            return $bid;

        }

        public function user(){

            if(!isset($this->user)){

                fatalError('User not set on bid');

            }

            return $this->user;


        }

        public function formattedValue(){


            return round($this->value/100, 2);


        }

    }