<?php declare(strict_types=1);

    namespace App\Utility;

    class View {

        private $filename = '';
        private $data = [];

        public function __construct(string $filename, array $data){

            $this->filename = $filename;
            $this->data = $data;

        }

        public function render() : string {

            ob_start();

            $data = $this->data;

            extract($data);

            include('../views/'.$this->filename.'.php');

            return ob_get_clean();

        }

    }