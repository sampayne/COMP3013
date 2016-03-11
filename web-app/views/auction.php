<?php if($auction_exists){ ?>

	<p>Name: <?php echo $name?></p><br>
	<p>Description: <?php echo $description?></p><br>
	<p>Starting Price: <?php echo $starting_price?>£</p><br>
	<p>Minimum Price To Bid: <?php echo $min_bid?>£</p>
	
	<?php if($isUserBuyer){ ?>
		<form method="post" action=<?php echo("/auction/".$id."/watch");?>>

			<?php if($isWatched){ ?>
				<input type="hidden" name="watch" value="0">
				<button type="submit" class="btn btn-default">Stop Watching</button>

			<?php }else{ ?>
				<input type="hidden" name="watch" value="1">
				<button type="submit" class="btn btn-default">Watch</button>

			<?php }?>

		</form>

		<form method="post" action=<?php echo("/auction/".$id."/bid");?>>

		    <div class="input-group">

		      <span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Bid Now:</button>
		      </span>

		      <input type="text" class="form-control" name="bid-bar" placeholder="All bids need to be placed in £">

		    </div>

		</form>

	<?php }?>


<?php }else{ ?>

	<div class="jumbotron">
		<h1 style="text-align: center">Sorry, auction not found...</h1>
	</div>

<?php } ?>