<?php

    namespace App;

    use App\Request as Request;
    use App\View;

    class RequestHandler {

        public function __construct(){

            //At the moment, do nothing.

        }

        public function handleRequest(Request $request){

            if($request->matches('GET', '/')){

            }else if($request->matches('GET','/dashboard')){

            }else if($request->matches('GET','/login')){

            }else if($request->matches('POST','/login')){

            }else if($request->matches('POST','/signup')){

            }else if($request->matches('GET','/auction/??')){

                $id = $request->url_array[1];

                $injected_context = 'Injected String';

                $view = new View('auction', ['injected_context'=>$injected_context]);

                return $view->render();

            }else if($request->matches('POST','/auction/??/bid')){

                $id = $request->url_array[1];

            }else if($request->matches('POST','/auction/??/watch')){

                $id = $request->url_array[1];

            }else if($request->matches('GET', '/auction/create')){


            }else if($request->matches('GET', '/auction/??/edit')){

                $id = $request->url_array[1];

            }else if($request->matches('POST', '/auction')){


            }else if($request->matches('GET', '/search')){

                $search_term = $request->get['s'];
                $category = $request->get['c'];

            }
        }
    }