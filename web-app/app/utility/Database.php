<?php declare(strict_types=1);

    namespace App\Utility;

    use \PDO as PDO;

    abstract class Database{

        private static $conn = null;

        const HOST          = 'localhost';
        const USER          = 'comp3013';
        const PASSWORD      = 'XxxH6?32couoWufi';
        const Database      = 'comp3013';

        public static function testConnection() : bool{

            $conn = self::connect();
            return !is_null($conn);

        }

        private static function connect() : PDO {

            if(is_null(self::$conn)){

                self::$conn = new PDO('mysql:host='.Database::HOST.';dbname='.Database::Database, Database::USER, Database::PASSWORD);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if(is_null(self::$conn)){

                    print_r("Database connection failed");
                    exit(0);

                }
            }

            return self::$conn;
        }


        public static function lastID() : int {

            $conn = self::connect();

            return $conn->lastInsertId();

        }

        public static function insert(string $SQLString, array $parameters = []) : void {

            $conn = self::connect();

            $stmt = $conn->prepare($sqlString);

            if (is_null($stmt)){

                foreach($parameters as $index => $value){

                    $stmt->bindValue($index + 1, trim($value));

                }

                $stmt->execute();

            } else {

                print_r("Could not prepare database query: ".$SQLString);
                exit(0);

            }
        }


        public static function query(string $SQLString, array $parameters = []) : array{

            $conn = self::connect();

            $stmt = $conn->prepare($sqlString);

            if (is_null($stmt)){

                foreach($parameters as $index => $value){

                    $stmt->bindValue($index + 1, trim($value));

                }

                $stmt->execute();

                return $stmt->fetchAll();

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

    		$results = self::Query($SQLString,$parameters);

    		return (bool) $results[0][0] ?? false;

    	}

        public static function checkExists(string $table, string $value, string $field) : bool {

            $SQLString = "SELECT EXISTS (SELECT 1 FROM ".$table." WHERE ".$field." = ? LIMIT 1)";

            return $self::existsQuery($SQLString, [$field]);

        }
    }
