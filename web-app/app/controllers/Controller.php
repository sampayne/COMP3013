<?php

    namespace App\Controller;

    class Controller {

        public function __construct(){}

        public function redirectTo($url) {

            header('Location: '.$url);
            return '';
        }
    }
