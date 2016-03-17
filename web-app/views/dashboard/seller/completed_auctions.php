<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Completed Auctions</h2>
            </div>
            <ul class="list-group">
                    <?php foreach($completedSellerAuctions as $auction): ?>
                        <li class="list-group-item">
    				    <div class="row">
        				    <img class="col-md-2" src="<?= $auction->getFirstItem()->image_url ?>">
                            <div  class="col-md-8">
    <!--                        <!--   <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                                Until: <?php echo $auction->end_date; ?> <br />
                                Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                                Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p>
                            </div>
                            <div class="col-md-2">
                                <?php if($auction->getBidCount() != 0): ?>
                                    <h3 class="text-success"><strong>Sold</strong></h3>
                                    <p>Sold for: <?php echo $auction->getHighestBid(); ?></p>
                                    Number of Bids: <?php echo $auction->getBidCount(); ?>
                                    <?php if(!$auction->hasBuyerFeedback()):?>

                                        <a href="/auction/<?=$auction->id ?>/feedback/create">Leave Feedback</a>
                                    <?php endif ?>
                                <?php else: ?>
                                    <h3 class="text-danger"><strong>Not sold</strong></h3>
                                <?php endif; ?>

                            </div>
    				    </div>
                    </li>
                    <?php endforeach ?>
            </ul>
        </div>
    </div>
</div>
