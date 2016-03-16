<?php declare(strict_types=1);

    namespace App\Utility\Creator;

    use App\Utility\Database;

    class AuctionCreator extends Creator {

        public function saveInput(array $input) : int {

            $errors = $this->validateInput($input);

            if(count($errors)){
                fatalError($errors);
            }

            $parameters = [     $input['auction_name'],
                                $input['auction_description'],
                                $input['starting_price'],
                                $input['end_date_time'],
                                $input['userrole_id']

                            ];

            Database::insert('INSERT INTO Auction (name,description,starting_price,end_date,userrole_id) VALUES (?,?,?,?,?)', $parameters);

            $auction_id = Database::lastID();

            return $auction_id;

        }

        public function validateInput(array $input) : array {

            $this->current_input = $input;

            $errors = [];

            if($this->isNonEmptyString('auction_name') === false){

                $errors[] = 'Auction must have a name';
            }

            if($this->isNonEmptyString('auction_description') === false){

                $errors[] = 'Auction must have a description';
            }

            if($this->isNumeric('starting_price') === false || $this->isGreaterThan('starting_price', 0) === false){

                $errors[] = 'Auction must set a starting price greater than 0';
            }

            if($this->isValidDate('end_date_time') === false){

                $errors[] = 'Auction have must have an end date';
            }

            if($this->isNumeric('userrole_id') === false){

                $errors[] = 'Not user ID set on Auction data. (System Error)';
            }

            return $errors;

        }
    }