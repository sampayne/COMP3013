<?php declare(strict_types=1);

    namespace App\Model;

    class User {

        public $username;
        public $id;

        public $buyer_role_id  = -1;
        public $seller_role_id = -1;

        public function __construct(int $id){

            $this->id = $id;

        }

        public function isSeller() : bool {

            return $seller_role_id > 0;

        }

        public function isBuyer() : bool {

            return $buyer_role_id > 0;

        }


    }