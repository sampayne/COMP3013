<?php namespace App\Utility;

    use \PDO as PDO;
    use \PDOStatement as PDOStatement;

    abstract class Database{

        private static $connection = null;

        const HOST          = 'comp301355.cy4slszn5xsl.eu-west-1.rds.amazonaws.com';
        const USER          = 'comp3013';
        const PASSWORD      = '9Z3-7h3-2LJ-z8Q';
        const Database      = 'comp3013';

        public static function testConnection() {

            $connection = self::connect();
            return !is_null($connection);
        }

        public static function insert($SQLString, array $parameters = []) {

            self::runQuery($SQLString, $parameters);

        }

        public static function delete($SQLString, array $parameters = []) {

            self::runQuery($SQLString, $parameters);

        }

        public static function selectOne($SQLString, array $parameters = []) {


            $results = self::query($SQLString, $parameters);

            if(count($results)){

                return $results[0];

            }

            return [];


        }

        public static function select($SQLString, array $parameters = []) {

            return self::query($SQLString, $parameters);

        }

        public static function query($SQLString, array $parameters = []) {

            if ($statement = self::runQuery($SQLString, $parameters)){

                return $statement->fetchAll();
            }

            return [];
        }

        private static function connect() {

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

        public static function lastID() {

            $connection = self::connect();

            return (int) $connection->lastInsertId();
        }

        private static function runQuery($SQLString, array $parameters = []) {

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

    	public static function countQuery($SQLString, array $parameters = []) {

    		$results = self::query($SQLString, $parameters);

            return isset($results[0][0]) ? $results[0][0] : 0;
    	}

    	public static function existsQuery($SQLString, array $parameters = []) {

    		$results = self::query($SQLString,$parameters);

    		return (bool) (isset($results[0][0]) ? $results[0][0] : false);
    	}

        public static function checkExists( $value, $field, $table)  {

            $SQLString = "SELECT EXISTS (SELECT 1 FROM ".$table." WHERE ".$field." = ? LIMIT 1)";

            return self::existsQuery($SQLString, [$value]);
        }
    }
