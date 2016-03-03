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

                        <?php if( $related_user->isBuyer() ): ?>

                            <div role="tabpanel" class="tab-pane active" id="buyer">

                                <?php foreach($related_user->getBuyerFeedback() as $feedback): ?>



                                <? endforeach ?>


                            </div>

                        <?php endif ?>

                        <?php if( $related_user->isSeller() ): ?>

                            <div role="tabpanel" class="tab-pane <?= !$related_user->isBuyer() ? 'active' : '' ?>" id="seller">

                                <?php foreach($related_user->getSellerFeedback() as $feedback):?>



                                <? endforeach ?>

                            </div>

                        <?php endif ?>

                      </div>
                </div>
            </div>
        </div>
    </div>
</div>