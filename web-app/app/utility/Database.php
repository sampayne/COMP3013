<?php declare(strict_types=1);

    namespace App\Utility;

    use \PDO as PDO;
    use \PDOStatement as PDOStatement;

    abstract class Database{

        private static $connection = null;

        const HOST          = '37.59.121.18';
        const USER          = 'comp3013';
        const PASSWORD      = 'XxxH6?32couoWufi';
        const Database      = 'comp3013';

        public static function testConnection() : bool{

            $connection = self::connect();
            return !is_null($connection);
        }

        public static function insert(string $SQLString, array $parameters = []) {

            self::runQuery($SQLString, $parameters);

        }

        public static function selectOne(string $SQLString, array $parameters = []) : array {


            $results = self::query($SQLString, $parameters);

            if(count($results)){

                return $results[0];

            }

            return [];


        }

        public static function select(string $SQLString, array $parameters = []) : array {

            return self::query($SQLString, $parameters);

        }

        public static function query(string $SQLString, array $parameters = []) : array {

            if ($statement = self::runQuery($SQLString, $parameters)){

                return $statement->fetchAll();
            }

            return [];
        }

        private static function connect() : PDO {

            if(is_null(self::$connection)){

                self::$connection = new PDO('mysql:host='.Database::HOST.';dbname='.Database::Database, Database::USER, Database::PASSWORD);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if(is_null(self::$connection)){

                    print_r("Database connection failed");
                    exit(0);

                }
            }

            return self::$connection;
        }

        public static function lastID() : int {

            $connection = self::connect();

            return (int) $connection->lastInsertId();
        }

        private static function runQuery(string $SQLString, array $parameters = []) : PDOStatement {

            $connection = self::connect();

            $statement = $connection->prepare($SQLString);

            if (!is_null($statement)){

                foreach($parameters as $index => $value){

                    $statement->bindValue($index + 1,  is_string($value) ? trim($value) : $value);
                }

                $statement->execute();

                return $statement;

            } else {

                print_r("Could not prepare database query: ".$SQLString);
                exit(0);

            }
        }

    	public static function countQuery(string $SQLString, $parameters = []) : int {

    		$results = self::query($SQLString, $parameters);

            return $results[0][0] ?? 0;
    	}

    	public static function existsQuery(string $SQLString, array $parameters = []) : bool {

    		$results = self::query($SQLString,$parameters);

    		return (bool) $results[0][0] ?? false;
    	}

        public static function checkExists(string $value, string $field, string $table) : bool {

            $SQLString = "SELECT EXISTS (SELECT 1 FROM ".$table." WHERE ".$field." = ? LIMIT 1)";

            return self::existsQuery($SQLString, [$value]);
        }
    }
