<div class="row">

    <div class="col-md-4">
        <img src="<?= $auction->getFirstItem()->image_url?>">
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <ul class="list-group">
                <li class="list-group-item">
                    <?= $auction->name ?>
                </li>
                <li class="list-group-item">
                    <?= $auction->description ?>

                </li>
                <li class="list-group-item">
                    <?php if($auction->isFinished()):?>

                        Ended on <?=$auction->end_date ?>

                    <?php else: ?>

                        Ends on <?=$auction->end_date ?>

                    <?php endif ?>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">

         <div class="panel panel-default">
            <ul class="list-group">
                <li class="list-group-item">
                    Starting Price: &pound; <?= $auction->starting_price/100 ?>
                </li>
                <?php if($auction->isFinished() && $auction->getHighestBid() > 0): ?>

                    <li class="list-group-item">
                        Sold &pound;<?= $auction->getHighestBid()/100 ?>
                    </li>

                <?php elseif($auction->isFinished() && $auction->getHighestBid() < 1):?>

                    <li class="list-group-item">
                        Unsold
                    </li>

                <?php elseif(!$auction->isFinished()):?>
                    <li class="list-group-item">
                        Current Bid: &pound; <?= $auction->getHighestBid() ?>
                    </li>
                    <li class="list-group-item">
                        Current Bid: &pound; <?= $auction->getHighestBid() ?>
                    </li>


                <?php endif ?>
                <li class="list-group-item">

                Bid Count: <?= $auction->getBidCount(); ?>
                </li>

            </ul>
         </div>


    </div>

</div>