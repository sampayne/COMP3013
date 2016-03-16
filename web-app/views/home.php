<div class="jumbotron">

	<h1 style="text-align: center">Welcome to BuyNow!</h1>
	<p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet.</p>

</div>

<div class="row">

	<?php $counter = 0;?>
	<?php foreach($categories as $category){ ?>

			<?php if($counter % 2 == 0) {?>
				<div class="row">
					<form method="get" action="/search">
						<input type="hidden" name=<?php echo "\"".($category->name)."\"" ?> value=<?php echo "\"".($category->name)."\"" ?>>
						<input type="hidden" name="search-bar" value="">
						<button type="submit" class="col-md-5 categoryCard"><span style="font-size: 20px;" class="glyphicon glyphicon-th-list" aria-hidden="true"> <?php echo $category->name ?></span></button>
					</form>

			<?php }else{ ?>
					<form method="get" action="/search">
						<input type="hidden" name=<?php echo "\"".($category->name)."\"" ?> value=<?php echo "\"".($category->name)."\"" ?>>
						<input type="hidden" name="search-bar" value="">
						<button type="submit" class="col-md-5 col-md-offset-2 categoryCard"><span style="font-size: 20px;" class="glyphicon glyphicon-th-list" aria-hidden="true"> <?php echo $category->name ?></span>
						</button>
					</form>
				</div>
				<br><br>
			<?php }?>

		<?php $counter = $counter + 1; ?>
	<?php }?>

</div>