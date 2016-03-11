<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            
				<?php if($auction_exists){ ?>
					<div class="panel-heading"><h1 class="panel-title"><?= $name ?></h1></div>
				    <div class="panel-body">

				    	<!--<div class="row"> <img class= "col-md-4 col-md-offset-4" src="<?= $auction->getFirstItem()->image_url ?>"></div> -->
				    	<div class="row"> <img class= "col-md-4 col-md-offset-4" src="/images/default.gif"></div>
				    	<div class="row">

				    		<div class= "col-md-5 col-md-offset-1 shadedPanel">
				    			<p>Description: <span class="descriptionText">"<?php echo $description?> "</span></p><br>
				    			<p>End Date: <?php echo $auction->end_date ?></p><br>
				    			<p>Starting Price: <span style="color:green;"><?php echo $starting_price?>£</span></p><br>
								<p>Minimum Price To Bid: <span style="color:green;"><?php echo $min_bid?>£</span></p>

								<?php if($isUserBuyer && $expired){ ?>

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
				    			<ul class="list-group" id="accordion" role="tablist" aria-multiselectable="true">
								  
								  	<?php $itemCounter = 0;
								  	      foreach($items as $item){ 
								  	      	$itemCounter = $itemCounter + 1;?>

									  <li class="list-group-item">
									  	<table>
										  	<div class="row">
											  	<tr>
											  		<td style="vertical-align: middle;" class="col-md-8 myListHeaders">
											  		<div > <span class="glyphicon glyphicon-ok" style="color:green;" aria-hidden="true"></span> <?= $item->name ?></div></td>

											  		<td class="col-md-4">
												    <button type="button" class="btn btn-sm btn-primary collapsed" data-toggle="collapse" data-parent="#accordion" href=<?php echo("#collapse".$itemCounter);?> aria-controls=<?php echo("collapse".$itemCounter);?> aria-expanded="false">
													  <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Description
													</button></td>

												</tr>
											</div>
										</table>
									  </li>

									  <div id=<?php echo("collapse".$itemCounter);?> class="panel-collapse collapse" role="tabpanel" aria-labelledby=<?php echo("heading".$itemCounter);?>>

									      <div class="panel-body shadedPanel descriptionText" style="border-style: none;">
									        <?php echo($item->description); ?>
									      </div>

									  </div>

									<?php }?>
								  
								</ul>
								
								<?php if($isUserBuyer && $expired){ ?>
								
									<form method="post" action=<?php echo("/auction/".$id."/watch");?>>

										<?php if($isWatched){ ?>
											<input type="hidden" name="watch" value="0">
											<button type="submit" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Stop Watching</button>

										<?php }else{ ?>
											<input type="hidden" name="watch" value="1">
											<button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> Watch</button>

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