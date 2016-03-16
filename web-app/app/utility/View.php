<?php namespace App\Utility;

    class View {

        private $filename = '';
        private $data = [];

        public function __construct($filename, array $data = []){

            $this->filename = $filename;
            $this->data = $data;
        }

        public function render() {

            $filename = $this->filename;

            ob_start();

            $data = $this->data;

            extract($data);

            include('../views/template.php');

            return ob_get_clean();
        }

        public static function renderView($filename, array $data = []) {

            $view = new View($filename, $data);

            return $view->render();

        }
    }