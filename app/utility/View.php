<?php declare(strict_types=1);

    namespace App\Utility;

    class View {

        private $filename = '';
        private $data = [];

        public function __construct(string $filename, array $data = []){

            $this->filename = $filename;
            $this->data = $data;
        }

        public function render() : string {
            $filename = $this->filename;

            ob_start();

            $data = $this->data;

            extract($data);

            include('../views/template.php');

            return ob_get_clean();
        }

        public static function renderView(string $filename, array $data = []) : string {

            $view = new View($filename, $data);

            return $view->render();

        }
    }