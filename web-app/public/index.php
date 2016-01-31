<?php

    require('../app/View.php');
    require('../app/Request.php');
    require('../app/RequestHandler.php');

    $request = new App\Request('/', $_SERVER, $_GET, $_POST, $_FILES, $_SESSION ?? [], $_ENV, $_COOKIE, $_REQUEST);

    $handler = new App\RequestHandler();

    $output = $handler->handleRequest($request);

    echo $output;

    exit(0);