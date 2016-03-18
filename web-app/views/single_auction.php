<div class="row">

    <img class="col-md-2" src="<?= strlen($auction->getFirstItem()->image_url) > 0 ? $auction->getFirstItem()->image_url : '/images/default.gif' ?>">
    <div class="col-md-7">
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
                <li class="list-group-item">
                    Seller: <a href="/user/<?=$auction->seller()->id?>/feedback"><?= $auction->seller()->email ?></a><?= $auction->seller()->id == $user->id ? ' (You)' : '' ?>
                </li>
            </ul>
        </div>
        <a href="/auction<?= $auction->id ?>" class="btn btn-primary">View</a>

    </div>
    <div class="col-md-3">

         <?php include('auction_info_panel.php') ?>

    </div>

</div>