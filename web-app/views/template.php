<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BuyNow</title>

	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Tangerine">
    <link href="/css/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="/css/style.css" type="text/css" rel="stylesheet">
	<link href="/css/<?= $filename; ?>.css?v=<?=rand()?>" type="text/css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">


        <div class="navbar-header">
          <a class="navbar-brand" href="/">Buy Now</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <form class="navbar-form navbar-left" role="search" method="get" action="/search">
                <div class="form-group">
                    <input type="text" name="search-bar" size="100" class="form-control" placeholder="Search...">
                </div>
                <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" style="color:black" aria-hidden="true"></span></button>
            </form>

              <ul class="nav navbar-nav navbar-right">

				<?php if(isset($user)):?>

				    <li><p class="navbar-text">Welcome <?= $user->email ?></p></li>
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Notifications
                              <span class="badge"><?=count($user->notifications())?>
                              </span>
                              <span class="caret"></span>
                            </a>

                          <ul class="dropdown-menu">

                              <?php foreach($user->notifications() as $notification): ?>

                                <li><a href="/auction/<?=$notification->auction_id?>"><?=$notification->content?></a></li>
                                <li role="separator" class="divider"></li>

                              <?php endforeach ?>

                              <?php if(count($user->notifications()) === 0):?>
                                <li class="disabled"><a href="#">No notifications</a></li>
                            <?php else: ?>

                                <li class="text-center"><a href="/notifications/clear">Clear</a></li>

                            <?php endif ?>
                          </ul>
                    </li>
                    <li><a href="/logout">Logout</a></li>


				<?php else: ?>

                    <li><a href="/login">Log In | Sign Up</a></li>

				<?php endif ?>
			</ul>

        </div>
        </div>
	</nav>

    <?php include($filename.'.php') ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/css/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
