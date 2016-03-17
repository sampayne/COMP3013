<?php namespace App\Model;

    use App\Utility\Database;

    use App\Model\ItemCategory;

    class Item {

        public $categories = [];

        public function __construct(array $sqlResultRow) {

            $this->id = (int) $sqlResultRow['id'];
            $this->name = $sqlResultRow['name'];
            $this->description = $sqlResultRow['description'];
            $this->image_url = $sqlResultRow['image_url'];
            $this->auction_id = $sqlResultRow['auction_id'];
            $this->created_at = $sqlResultRow['created_at'];
            $this->update_at = $sqlResultRow['updated_at'];
        }

        public static function fromSQLRows(array $rows) {

            $items = [];

            foreach($rows as $row){

                $items[] = new Item($row);
            }

            return $items;


        }

        public static function getItemWithId($id) {

            $results = Database::query('SELECT * FROM Item WHERE id = ?', [$id]);

            return new Item($results[0]);
        }

        public static function getItemsForAuction($auction_id) {

            $results = Database::query('SELECT * FROM Item WHERE auction_id = ?', [$auction_id]);

            return self::fromSQLRows($results);
        }

        public function getCategories() {

            if(count($this->categories) == 0){

                $this->categories = ItemCategory::categoriesForItem($this->id);

            }

            return $this->categories;
        }

        public static function getItemsForCategory($category_id)  {
            $results = Database::query('SELECT Item.* FROM ItemCategory
                JOIN Item ON Item.id = ItemCategory.item_id WHERE ItemCategory.category_id = ?', [$category_id]);
            return self::fromSQLRows($results);

        }

        public static function getWonItemsForUser($userrole_id) {

            $results = Database::query('SELECT Item.*
                FROM AuctionsWinners JOIN Item ON AuctionsWinners.id = Item.auction_id
                WHERE userrole_id_winner = ?', [$userrole_id]);
            return self::fromSQLRows($results);

        }

    }