<div class="container">

<div class="row">

    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading"><h1 class="panel-title">Feedback For <?= $auction->name ?></h1></div>
            <div class="panel-body">
        <?php if($auction->isFinished() === false):?>

            <div class="alert alert-danger" role="alert">                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
 This auction hasn't finished yet, you cannot leave feedback! Please go back!</div>

        <?php else: ?>

            <?php if($auction->buyer()->id == $user->id):?>

                <?php if($auction->hasSellerFeedback()): ?>

                    <div class="alert alert-info" role="alert">You've already left feedback. Please go back.</div>

                <?php else: ?>

                    <form action="/auction/<?=$auction->id?>/feedback/seller" method="post" >

                        <div class="form-group">
                            <label>Item as Described</label>
                            <input class="form-control" name="item_as_described" type="number" max="5" min="1" />
                        </div>
                        <div class="form-group">
                            <label>Communication</label>
                            <input class="form-control" name="communication" type="number" max="5" min="1"/>
                        </div>
                        <div class="form-group">
                            <label>Dispatch Time</label>
                            <input class="form-control" name="dispatch_time" type="number" max="5" min="1"/>
                        </div>
                        <div class="form-group">
                            <label>Packing</label>
                            <input class="form-control" name="packaging" type="number" max="5"  min="1"/>
                        </div>
                        <div class="form-group">
                            <label>Comments</label>
                            <textarea  class="form-control" name="feedback_comment"/>
                        </div>

                        <button type="submit" class="btn btn-success center-block btn-lg">Save Feedback</button>

                    </form>

                <?php endif ?>

            <?php elseif($auction->seller()->id == $user->id): ?>

                <?php if($auction->hasBuyerFeedback()): ?>

                    <div class="alert alert-info" role="alert">You've already left feedback. Please go back.</div>

                <?php else: ?>

                    <form action="/auction/<?=$auction->id?>/feedback/buyer" method="post">
                        <div class="form-group">
                            <label>Speed of Payment</label>
                            <input class="form-control" name="speed_of_payment" type="number" max="5" min="1" />
                        </div>
                        <div class="form-group">
                            <label>Communication</label>
                            <input class="form-control" name="communication" type="number" max="5" min="1"/>
                        </div>

                        <div class="form-group">
                            <label>Comments</label>
                            <textarea  class="form-control" name="feedback_comment"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success center-block btn-lg">Save Feedback</button>

                    </form>

                <?php endif ?>

            <?php else: ?>

                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    You can't leave feedback for an auction you didn't win or create! Please go back!</div>

            <?php endif ?>

        <?php endif ?>

            </div>
        </div>
    </div>
</div>

</div>