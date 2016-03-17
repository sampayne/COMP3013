<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Live Bid Auctions</h2>
            </div>
            <ul class="list-group">
                <?php if(count($liveBidBuyerAuctions) == 0): ?>

                    <li class="list-group-item">
                        <div class="alert alert-info" role="alert">No Auctions</div>
                    </li>

                <?php else: ?>

                    <?php foreach($liveBidBuyerAuctions as $auction) : ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2"><img class="col-md-12" src="/images/default.gif"></div>

                                <div  class="col-md-8">
                                 <!--   <?php echo $auction->id; ?> <br /> -->
                                    <h4><strong><?php echo $auction->name; ?></strong></h4>
                                    <p><em><?php echo $auction->description; ?> </em></p>
                                    <p>End date: <?php echo $auction->end_date; ?><br /></p>

                                </div>

                                <div class="col-md-2">
                                    <?php if($auction->getHighestBid() == $auction->getHighestBidForUser($user)): ?>
                                        <h3 class="text-success"><strong>OK</strong></h3>
                                        <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                            <form role="form" method="post" action="/auction/<?php echo $auction->id; ?>/bid">
                                                <div class="form-group">
                                                <label>Bid more</label>
                                                <input class="form-control" type="text" name="bid_value" size="8" min="<?php echo $auction->getHighestBid() + 1; ?>"/>
                                                </div>
                                                <button type="submit" class="btn btn-default">Bid</button>
                                            </form>

                                    <?php else: ?>
                                        <h3 class="text-danger"><strong>Outbid</strong></h3>
                                        <p>Your Bid: <?php echo $auction->getHighestBidForUser($user); ?> <br />
                                        Highest Bid: <?php echo $auction->getHighestBid(); ?></p>

                                            <form role="form" method="post" action="/auction/<?php echo $auction->id; ?>/bid">
                                                <div class="form-group"><label>Place another bid</label>
                                                <input class="form-control" type="text" name="bid_value" size="8" min="<?php echo $auction->getHighestBid() + 1; ?>"/>
                                                </div>
                                                <button type="submit" class="btn btn-default">Bid</button>
                                            </form>

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
