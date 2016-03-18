<?php namespace App\Utility;

    use App\Model\Auction;

    abstract class NotificationType {

        public static function outbid(){

        return 1;
        }

        public static function bidRecieved(){

        return 2;
        }

        public static function watchRecieved(){

            return 3;

        }

        public static function itemEnded(){

            return 4;


        }

        public static function itemWon(){

            return 5;

        }

    }


    class NotificationSender {

        public static function sendNotification($content, $user_id, $auction_id, $type){

            if(is_null($user_id) || $user_id < 1){

                fatalError('Notification cannot be sent to null user');
            }

            Database::insert('INSERT INTO Notification (user_id, auction_id, content, type) VALUES (?,?,?,?)',[$user_id, $auction_id, $content, $type]);

        }

        public static function sendOutbidNotification($user_id, Auction $auction){

            $content = 'You have been outbid on: '.$auction->name;

            self::sendNotification($content, $user_id, $auction->id, NotificationType::outbid());

        }

        public static function sendBidRecievedNotification(Auction $auction){

            $content = 'You have received a bid on: '.$auction->name;

            self::sendNotification($content, $auction->seller()->id, $auction->id, NotificationType::bidRecieved());

        }

        public static function sendWatchRecievedNotification(Auction $auction){

            $content = 'Someone started watching: '.$auction->name;

            self::sendNotification($content, $auction->seller()->id, $auction->id, NotificationType::watchRecieved());

        }

        public static function sendItemEndedNotification(Auction $auction){

            $content = 'Auction Ended: '.$auction->name;

            self::sendNotification($content, $auction->seller()->id, $auction->id, NotificationType::itemEnded());

        }

        public static function sendItemWonNotification(Auction $auction){

            $content = 'You won auction: '.$auction->name;

            if(is_null($auction->buyer())){

                fatalError("Won auction had no buyer");

            }

            self::sendNotification($content, $auction->buyer()->id, $auction->id, NotificationType::itemWon());


        }

        public static function scanForItemEndedNotifications(){

            $query = 'SELECT * FROM Auction WHERE end_date < NOW() AND id NOT IN (SELECT auction_id FROM Notification WHERE type = ?)';

            $results = Database::select($query, [NotificationType::itemEnded()]);

            if(count($results)){

                $auctions = Auction::arrayFromSQLRows($results);

                foreach($auctions as $auction){

                    self::sendItemEndedNotification($auction);

                }

            }

        }


        public static function scanForItemWonNotifications(){

            $query = 'SELECT au.*, aumb.max_bid FROM Auction AS au
                      JOIN AuctionsMaxBid AS aumb ON aumb.auction_id = au.id
                      WHERE au.end_date < NOW() AND au.id NOT IN
                     (SELECT auction_id FROM Notification WHERE type = ?)';

            $results = Database::select($query, [NotificationType::itemWon()]);

            if(count($results)){

                $auctions = Auction::arrayFromSQLRows($results);

                foreach($auctions as $auction){

                    self::sendItemWonNotification($auction);

                }

            }



        }


    }