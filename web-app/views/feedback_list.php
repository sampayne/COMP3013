<div class="container">

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h1 class="panel-title">Feedback For <?= $related_user->email ?></h1></div>
            <div class="panel-body">
                <div>















                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">

                        <?php if( $related_user->isBuyer() ): ?>

                            <li role="presentation" class="active">
                                <a href="#buyer" aria-controls="buyer" role="tab" data-toggle="tab">As Buyer</a>
                            </li>

                        <?php endif ?>

                        <?php if( $related_user->isSeller() ): ?>

                            <li role="presentation" <?= !$related_user->isBuyer() ? 'class="active"' : '' ?>>
                                <a href="#seller" aria-controls="profile" role="tab" data-toggle="tab">As Seller</a>
                            </li>

                        <?php endif ?>


                  </ul>

                  <!-- Tab panes -->
                      <div class="tab-content">
                        <?php if($related_user->isBuyer() ): ?>

                            <div role="tabpanel" class="tab-pane active" id="buyer">

                                <?php foreach(array_chunk($related_user->getBuyerFeedback(),3) as $feedback_chunk):?>

                                    <div class="row">

                                        <?php foreach($feedback_chunk as $feedback): ?>
                                                <div class="col-md-4">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <?= $feedback->content ?>
                                                        </div>
                                                        <ul class="list-group">
                                                            <li class="list-group-item"><span class="badge"><?= $feedback->communication ?></span>Communication</li>
                                                             <li class="list-group-item"><span class="badge"><?= $feedback->speed_of_payment ?></span>Speed of Payment</li>
                                                            <li class="list-group-item list-group-item-info"><span class="badge"><?= $feedback->mean() ?></span>Overall</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                        <?php endforeach ?>

                                    </div>

                                <?php endforeach ?>

                                <?php if(empty($related_user->getBuyerFeedback())): ?>
                                    <div class="alert alert-info" role="alert"><?= $related_user->email ?> hasn't received any feedback as a buyer.</div>
                                <?php endif ?>

                            </div>

                        <?php endif ?>

                        <?php if( $related_user->isSeller() ): ?>

                            <div role="tabpanel" class="tab-pane <?= !$related_user->isBuyer() ? 'active' : '' ?>" id="seller">

                                <?php if(empty($related_user->getSellerFeedback())): ?>

                                    <div class="alert alert-info" role="alert"><?= $related_user->email ?> hasn't received any feedback as a seller.</div>

                                <?php else: ?>

                                    <?php $mean_ratings = $related_user->getSellerMeanRating() ?>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Summary
                                                </div>
                                                <ul class="list-group">
                                                    <li class="list-group-item"><span class="badge"><?= $mean_ratings['mean_item_as_described'] ?></span>Item As Described</li>
                                                    <li class="list-group-item"><span class="badge"><?= $mean_ratings['mean_communication'] ?></span>Communication</li>
                                                    <li class="list-group-item"><span class="badge"><?= $mean_ratings['mean_dispatch_time'] ?></span>Dispatch Time</li>
                                                    <li class="list-group-item"><span class="badge"><?= $mean_ratings['mean_posting'] ?></span>Packaging</li>
                                                    <li class="list-group-item list-group-item-info"><span class="badge"><?= $mean_ratings['overall'] ?></span>Overall</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <?php foreach(array_chunk($related_user->getSellerFeedback(),3) as $feedback_chunk):?>

                                        <div class="row">

                                            <?php foreach($feedback_chunk as $feedback): ?>
                                                    <div class="col-md-4">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <?= $feedback->getRelatedAuction()->name ?>
                                                            </div>
                                                            <ul class="list-group">
                                                                <li class="list-group-item"><?= $feedback->content ?></li>
                                                                <li class="list-group-item"><span class="badge"><?= $feedback->item_as_described ?></span>Item as Described</li>
                                                                <li class="list-group-item"><span class="badge"><?= $feedback->communication ?></span>Communication</li>
                                                                <li class="list-group-item"><span class="badge"><?= $feedback->dispatch_time ?></span>Dispatch Time</li>
                                                                <li class="list-group-item"><span class="badge"><?= $feedback->posting ?></span>Packaging</li>
                                                                <li class="list-group-item list-group-item-info"><span class="badge"><?= $feedback->mean() ?></span>Overall</li>

                                                            </ul>

                                                        </div>
                                                    </div>
                                            <?php endforeach ?>

                                        </div>

                                    <?php endforeach ?>

                                <?php endif ?>

                            </div>

                        <?php endif ?>

                      </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>