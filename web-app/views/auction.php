<?php if($auction_exists){ ?>

	<p>Name: <?php echo $name?></p><br>
	<p>Description: <?php echo $description?></p><br>
	<p>Starting Price: <?php echo $starting_price?></p><br>
	<p>Seller Id: <?php echo $userrole_id?></p><br>

<?php }else{ ?>

	<div class="jumbotron">
		<h1 style="text-align: center">Sorry, auction not found...</h1>
	</div>

<?php } ?>
