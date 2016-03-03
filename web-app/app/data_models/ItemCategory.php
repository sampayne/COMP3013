<?php declare(strict_types=1);

    namespace App\Model;

    use App\Utility\Database;

    class ItemCategory {

        public function __construct(int $id, string $name){

            $this->id = $id;
            $this->name = $name;

        }

        public static function fromSQLRow(array $SQLRow) : ItemCategory {

            return new ItemCategory((int) $SQLRow['id'], $SQLRow['name']);

        }

        public static function arrayFromSQLRows(array $rows) : array {

            $categories = [];

            foreach($rows as $row){

                $categories[] = self::fromSQLRow($row);
            }

            return $categories;
        }


        public static function all() : array {

            $results = Database::select('SELECT * FROM Category ORDER BY name ASC');

            return self::arrayFromSQLRows($results);

        }

        public static function categoriesForItem($item_id) : array {

            $results = Database::select('SELECT * FROM Category WHERE id IN (SELECT category_id FROM ItemCategory WHERE item_id = ?)', [$item_id]);

            return self::arrayFromSQLRows($results);

        }

    }