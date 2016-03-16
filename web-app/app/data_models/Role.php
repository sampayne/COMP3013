<?php declare(strict_types=1);

    namespace App\Model;

    abstract class Role {

        public static function buyer() : int {

            return 2;
        }

        public static function seller() : int {

            return 1;
        }
    }
