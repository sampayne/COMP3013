<?php namespace App\Model;

    abstract class Role {

        public static function buyer() {

            return 2;
        }

        public static function seller() {

            return 1;
        }
    }
