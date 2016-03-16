<?php declare(strict_types=1);

    namespace App\Utility\Creator;

    use App\Utility\Database as Database;
    use App\Model\ItemCategory;

    class ItemCreator extends Creator {

        public function saveInput(array $input) : int {

            $errors = $this->validateInput($input);

            if(count($errors)){
                fatalError('Tried to save invalid item data');
            }

            if(!isset($input['auction_id'])){
                fatalError('Auction Id Was Not Set on Item (System Error)');
            }

            $name = $input['item_name'];
            $description = $input['item_description'];
            $image = $this->saveImage($input['image'], '/items');
            $auction_id = $input['auction_id'];
            $categories = $input['categories'];

            Database::insert('INSERT INTO Item (name, description,image_url,auction_id) VALUES (?,?,?,?)', [$name, $description, $image, $auction_id]);

            $item_id = Database::lastID();

            foreach($categories as $category){

                Database::insert('INSERT INTO ItemCategory (item_id, category_id) VALUES (?,?)', [$item_id, $category]);
            }

            return $item_id;
        }

        public function validateInput(array $input) : array {

            $this->current_input = $input;

            $errors = [];

            if($this->isNonEmptyString('item_name') === false){

                $errors[] = 'Missing Item Name';
            }

            if($this->isNonEmptyString('item_description') === false){

                $errors[] = 'Missing Item Description';
            }

            if($this->isArray('item_category') === false){

                $errors[] = 'Missing Item Category';
            }

            if($this->isFile('image') === false){

                $errors[] = 'Missing Item Image';
            }

            $this->current_input = NULL;

            return $errors;
        }
    }