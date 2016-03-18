<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Live Auctions</h2>
            </div>
            <ul class="list-group">
                <?php foreach($liveSellerAuctions as $auction): ?>
    				<li class="list-group-item">
                            <?php include(__DIR__.'/../../single_auction.php') ?>
                    </li>
    			<?php endforeach ?>
            </ul>
        </div>
    </div>
</div>