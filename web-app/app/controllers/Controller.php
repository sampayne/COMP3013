<?php declare(strict_types=1);

    namespace App\Controller;

    class Controller {

        public function __construct(){}

        public function redirectTo(string $url) : string {

            header('Location: '.$url);
            return '';
        }
    }
