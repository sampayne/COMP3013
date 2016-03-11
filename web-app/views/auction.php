<?php if($auction_exists){ ?>

	<p>Name: <?php echo $name?></p><br>
	<p>Description: <?php echo $description?></p><br>
	<p>Starting Price: <?php echo $starting_price?></p><br>
	<p>Seller Id: <?php echo $userrole_id?></p><br>
	
	<?php if($isUserBuyer){ ?>
		<form method="post" action=<?php echo("/auction/".$id."/confirmation");?>>

			<?php if($isWatched){ ?>
				<input type="hidden" name="watch" value="0">
				<button type="submit" class="btn btn-default">Stop Watching</button>

			<?php }else{ ?>
				<input type="hidden" name="watch" value="1">
				<button type="submit" class="btn btn-default">Watch</button>

			<?php }?>

		</form>
	<?php }?>


<?php }else{ ?>

	<div class="jumbotron">
		<h1 style="text-align: center">Sorry, auction not found...</h1>
	</div>

<?php } ?>