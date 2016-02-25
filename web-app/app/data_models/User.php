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

        public function getAuctions() : array {

            return Auction::getAuctionsForUser($this->seller_role_id);
        }

        public function getLiveAuctions() : array {

            return Auction::getLiveAuctionsForUser($this->seller_role_id);
        }

        public function getCompletedAuctions() : array {

            return Auction::getCompletedAuctionsForUser($this->seller_role_id);
        }

        public function getLiveWatchedAuctions() : array {

            return Auction::getLiveWatchedAuctionsForUser($this->buyer_role_id);
        }

        public function getLiveBidAuctions() : array {

            return Auction::getLiveBidAuctionsForUser($this->buyer_role_id);
        }

        public function getCompletedBidAuctions() : array {

            return Auction::getCompletedBidAuctionsForUser($this->buyer_role_id);
        }

    }