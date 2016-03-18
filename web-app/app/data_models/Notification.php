<?php namespace App\Model;


    use App\Utility\Database;


    class Notification {

       public function __construct($id) {

            $this->id = $id;

       }

       public static function fromSQLRow(array $SQLRow) {

            if(!isset($SQLRow['id'])){

                fatalError('Notification didnt have id in SQL row');

            }

            $notification = new Notification($SQLRow['id']);

            $notification->content = isset($SQLRow['content']) ? $SQLRow['content'] : '';
            $notification->user_id = isset($SQLRow['user_id']) ? $SQLRow['user_id'] : -1;
            $notification->auction_id = isset($SQLRow['auction_id']) ? $SQLRow['auction_id'] : -1;
            $notification->created_at = isset($SQLRow['created_at']) ?  $SQLRow['created_at']:'';
            $notification->updated_at = isset($SQLRow['updated_at'])? $SQLRow['updated_at'] :'';

            return $notification;
       }


       public static function arrayFromSQLRows(array $SQLRows){

           $notifications = [];

           foreach($SQLRows as $row){

               $notifications[] = self::fromSQLRow($row);

           }

           return $notifications;

       }


       public static function forUser($user_id){

           $results = Database::select('SELECT * FROM Notification WHERE user_id = ? AND cleared = 0',[$user_id]);

           return self::arrayFromSQLRows($results);

       }

       public static function clearForUser($user_id){

           Database::insert('UPDATE Notification SET cleared = 1 WHERE user_id = ?', [$user_id]);



       }


    }