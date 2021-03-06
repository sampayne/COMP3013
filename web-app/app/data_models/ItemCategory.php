<?php namespace App\Model;

    use App\Utility\Database;

    class ItemCategory {

        public function __construct($id, $name){

            $this->id = $id;
            $this->name = $name;

        }

        public static function fromSQLRow(array $SQLRow) {

            $category = new ItemCategory((int) $SQLRow['id'], $SQLRow['name']);
            $category->icon_name = isset($SQLRow['icon_name']) ? $SQLRow['icon_name'] : '';
            return $category;

        }

        public static function arrayFromSQLRows(array $rows) {

            $categories = [];

            foreach($rows as $row){

                $categories[] = self::fromSQLRow($row);
            }

            return $categories;
        }


        public static function all() {

            $results = Database::select('SELECT * FROM Category ORDER BY name ASC');

            return self::arrayFromSQLRows($results);

        }

        public static function categoriesForItem($item_id) {

            $results = Database::select('SELECT * FROM Category WHERE id IN (SELECT category_id FROM ItemCategory WHERE item_id = ?)', [$item_id]);

            return self::arrayFromSQLRows($results);

        }

        public function getItems() {

            return Item::getItemsForCategory($this->id);

        }

    }