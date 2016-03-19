<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if(!is_null($message)): ?>
                <div class="alert alert-success" role="alert">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    <?= $message ?>
                </div>
            <?php endif ?>

            <?php if(!is_null($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <?= $error ?>
                </div>
            <?php endif ?>

            <?php if($auction_exists): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><h1 class="panel-title"><?= $name ?></h1></div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                            <div class="col-md-4">
                            <img class="img-responsive" src="<?= strlen($auction->getFirstItem()->image_url) > 0 ? $auction->getFirstItem()->image_url : '/images/default.gif' ?>">
                            </div>
                            <div class="col-md-4">

                                <div class="panel panel-default">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <?= $auction->description ?>

                                        </li>
                                        <li class="list-group-item">
                                            <?php if($auction->isFinished()):?>

                                                Ended on: <?=$auction->end_date ?>

                                            <?php else: ?>

                                                Ends on: <?=$auction->end_date ?>

                                            <?php endif ?>
                                        </li>
                                        <li class="list-group-item">
                                            Seller: <a href="/user/<?=$auction->seller()->id?>/feedback">
                                                <?= $auction->seller()->email ?></a>
                                                <?= isset($user) && $auction->seller()->id == $user->id ? ' (You)' : '' ?>
                                        </li>

                                        <?php if(isset($user) && $user->isBuyer() && !$auction->isFinished()):?>
                                            <li class="list-group-item">

                                                <form method="post" action='/auction/<?= $auction->id ?>/watch' class="text-center">

                                                    <?php if($isWatched): ?>
                                                        <input type="hidden" name="watch" value="0">
                                                        <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Stop Watching</button>

                                                    <?php else: ?>
                                                        <input type="hidden" name="watch" value="1">
                                                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> Watch</button>

                                                    <?php endif ?>

                                                </form>

                                            </li>
                                        <?php endif ?>


                                </ul>
                                </div>

                            </div>
                            <div class="col-md-4">

                                    <?php include('auction_info_panel.php') ?>

                            </div>


                        </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><h1 class="panel-title">Items</h1></div>
                                        <ul class="list-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <?php $itemCounter = 0 ?>
                                            <?php foreach($items as $item): ?>
                                                <?php $itemCounter++ ?>
                                                <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-md-9">
                                                                <span class="glyphicon glyphicon-ok" style="color:green;" aria-hidden="true"></span> <?= $item->name ?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="button" class="btn btn-sm btn-primary collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$itemCounter ?>" aria-controls="collapse<?=$itemCounter?>" aria-expanded="false">
                                                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Details
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div id="collapse<?=$itemCounter?>" class="row panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$itemCounter?>">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <img  class="img-responsive" src="<?= strlen($item->image_url) > 0 ? $item->image_url : '/images/default.gif' ?>">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p><?=$item->description ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </li>
                                            <?php endforeach ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><h1 class="panel-title">Bid History</h1></div>
                                        <ul class="list-group" role="tablist">

                                            <?php if(count($auction->bids()) === 0):?>
                                                <li class="list-group-item">
                                                    <div class="alert alert-info" role="alert">No Bids</div>
                                                </li>
                                            <?php else:?>

                                                <?php foreach($auction->bids() as $bid):?>

                                                    <li class="list-group-item"><span class="badge">&pound; <?= $bid->formattedValue() ?></span>
                                                        <a href="/user/<?=$bid->user()->id?>/feedback"><?=$bid->user()->email?></a>
                                                    </li>

                                                <?php endforeach ?>

                                            <?php endif ?>

                                        </ul>
                                    </div>
                            </div>
                            </div>
                        </li>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>Sorry, auction not found...
                </div>
            <?php endif ?>
        </div>
    </div>
</div>