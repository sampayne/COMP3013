<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Completed Bid Auctions</h2>
            </div>
            <ul class="list-group">
                <?php if(count($completedBidBuyerAuctions) == 0): ?>

                    <li class="list-group-item">
                        <div class="alert alert-info" role="alert">No Auctions</div>
                    </li>

                <?php else: ?>

                    <?php foreach($completedBidBuyerAuctions as $auction) : ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></div>
                                <div  class="col-md-8">
                                 <!--   <?php echo $auction->id; ?> <br /> -->
                                    <h4><strong><?php echo $auction->name; ?></strong></h4>
                                    <p><em><?php echo $auction->description; ?> </em></p>
                                    Ended on: <?php echo $auction->end_date; ?><br /></p>
                                </div>

                                <div class="col-md-2">
                                    <?php if($auction->getHighestBid() == $auction->getHighestBidForUser($user)): ?>
                                        <h3 class="text-success"><strong>Won</strong></h3>
                                        <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                    <?php else: ?>
                                        <h3 class="text-danger"><strong>Lost</strong></h3>
                                        <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>