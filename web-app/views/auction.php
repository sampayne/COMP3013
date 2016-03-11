<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            
				<?php if($auction_exists){ ?>
					<div class="panel-heading"><h1 class="panel-title"><?= $name ?></h1></div>
				    <div class="panel-body">

				    	<div class="row"> <img class= "col-md-4 col-md-offset-4" src="<?= $auction->getFirstItem()->image_url ?>"></div>
				    	<div class="row">

				    		<div class= "col-md-5 col-md-offset-1 shadedPanel">
				    			<p>Description: <span class="descriptionText">"<?php echo $description?> "</span></p><br>
				    			<p>Starting Price: <span style="color:green;"><?php echo $starting_price?>£</span></p><br>
								<p>Minimum Price To Bid: <span style="color:green;"><?php echo $min_bid?>£</span></p>

								<?php if($isUserBuyer){ ?>

									<form method="post" action=<?php echo("/auction/".$id."/bid");?>>

									    <div class="input-group">

									      <span class="input-group-btn">
									        <button class="btn btn-success" type="submit">Bid Now:</button>
									      </span>

									      <input type="text" class="form-control" name="bid-bar" placeholder="All bids need to be placed in £">

									    </div>

									</form>
								<?php }?>

				    		</div>

				    		<div class= "col-md-5 col-md-offset-1">
				    			<p>Item1</p><br>
				    			<p>Item2</p><br>
				    			<p>Item3</p><br>
								
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

								<?php }?>
							</div>
						</div>
					</div>


				<?php }else{ ?>
					
					<div class="panel-body">
						<div class="jumbotron">
							<h1 style="text-align: center">Sorry, auction not found...</h1>
						</div>
					</div>

				<?php } ?>

		</div>
	</div>
</div>