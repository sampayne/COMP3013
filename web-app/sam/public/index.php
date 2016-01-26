<?php

    require('../app/View.php');

    require('../app/Request.php');
    require('../app/RequestHandler.php');

    //echo 'hits index.php URL is '.$_SERVER['REQUEST_URI'];

    //print_r($_SERVER);

    $request = new App\Request('/sam/public', $_SERVER, $_GET, $_POST, $_FILES, $_SESSION ?? [], $_ENV, $_COOKIE, $_REQUEST);

    $handler = new App\RequestHandler();

    $output = $handler->handleRequest($request);

    echo $output;

    exit(0);