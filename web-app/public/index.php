<?php declare(strict_types=1);

    require('../app/controllers/include.php');
    require('../app/data_models/include.php');
    require('../app/utility/include.php');

    session_start();

    $request = new App\Utility\Request('/', $_SERVER, $_GET, $_POST, $_FILES, $_SESSION ?? [], $_ENV, $_COOKIE, $_REQUEST);

    $handler = new App\Utility\RequestHandler();

    $output = $handler->handleRequest($request);

    echo $output;

    exit(0);