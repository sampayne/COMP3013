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
	<link href="/css/<?= $filename; ?>.css" type="text/css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">
				<li class="pull-left"><a class="navbar-brand" href="/">BuyNow</a></li>
				<li>

					<form class="navbar-form" role="search" method="get" action="/search">
						<div class="form-group">
							<input type="text" name="search-bar" size="100" class="form-control" placeholder="Search...">
						</div>
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" style="color:black" aria-hidden="true"></span></button>
					</form>

				</li>

				<?php if(isset($user)):?>

				    <li class="pull-right"><a href="/logout" style="color:gray">Logout</a></li>

				    <li class="pull-right"><a href="/dashboard" style="color:white">Welcome <?= $user->email ?> | Dashboard</a></li>

				<?php else: ?>

                    <li class="pull-right"><a href="/login" style="color:white">Log In | Sign Up</a></li>

				<?php endif ?>
			</ul>

		</div>
	</nav>


	<div class="container">


		    <?php include($filename.'.php');?>


	</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="/css/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>