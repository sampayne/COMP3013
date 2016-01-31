<?php

    namespace App;

    class Request {

        public $request = [];
        public $environment = [];
        public $post = [];
        public $get = [];
        public $files = [];
        public $server = [];
        public $cookie = [];
        public $session = [];

        public $method = '';
        public $url = '';
        public $url_array = [];



        // $request = new Request($_SERVER, $_GET, $_POST, $_FILES, $_SESSION, $_ENV, $_COOKIE, $_REQUEST);

        public function __construct(    string $start_url,
                                        array $server,
                                        array $get,
                                        array $post,
                                        array $files,
                                        array $session,
                                        array $environment,
                                        array $cookie,
                                        array $request

                                        ) {

                $this->method = $server['REQUEST_METHOD'];

                $start_url = trim($start_url, '/');

                $this->url = trim(str_replace($server['QUERY_STRING'], '', $server['REQUEST_URI']),'?');

                $this->url = str_replace($start_url, '', $this->url);

                $this->url = trim($this->url, '/');

                $this->url_array = explode('/', $this->url);

                $this->request = $request;
                $this->environment = $environment;
                $this->post = $post;
                $this->get = $get;
                $this->files = $files;
                $this->server = $server;
                $this->cookie = $cookie;
                $this->session = $session;

        }


        public function matches(string $method, string $url){

            $url = trim($url, '/');
            $url = explode('/', $url);

            if($method === $this->method && count($url) === count($this->url_array)){

                foreach($url as $index => $component){

                    if($component !== '??' && $this->url_array[$index] !== $component){

                        return false;

                    }
                }

                return true;

            }

            return false;
        }

        public function isMethod(string $method){

            return $method === $this->method;
        }

    }