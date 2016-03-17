<?php namespace App\Model;

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
            $notification->reference_id = isset($SQLRow['reference_id']) ? $SQLRow['reference_id'] : -1;
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




    }