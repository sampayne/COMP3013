<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Live Auctions</h2>
            </div>
            <ul class="list-group">
                <?php foreach($liveSellerAuctions as $auction): ?>
    				<li class="list-group-item">
    				    <div class="row">
        				    <img class="col-md-2" src="<?= $auction->getFirstItem()->image_url ?>">
                            <div  class="col-md-8">
    <!--                             <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                                Until: <?php echo $auction->end_date; ?> <br />
                                Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                                Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p>
                                <form role="form" method="get" action="/auction/<?php echo $auction->id; ?>/edit">
                                <button type="submit" class="btn btn-default">Edit Auction</button>
                                </form>
                            </div>
                            <div class="col-md-2">
                                    <?php if($auction->getBidCount() != 0): ?>
                                        <h3 class="text-success"><strong> <?php echo $auction->getBidCount(); ?> bids</strong></h3>
                                        <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                    <?php else: ?>
                                        <h3 class="text-danger"><strong>No bids</strong></h3>
                                    <?php endif; ?>
                            </div>
    				    </div>
                    </li>
    			<?php endforeach ?>
            </ul>
        </div>
    </div>
</div>