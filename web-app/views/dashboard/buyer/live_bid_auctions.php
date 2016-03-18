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
                            <?php include(__DIR__.'/../../single_auction.php') ?>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>
