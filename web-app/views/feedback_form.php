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

                    <form>

                        <div class="form-group">
                            <label></label>
                            <input class="form-control" name=""/>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <input class="form-control" name=""/>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <input class="form-control" name=""/>
                        </div>
                        <div class="form-group">
                            <label></label>
                            <input class="form-control" name=""/>
                        </div>
                        <div class="form-group">
                            <label>Comments</label>
                            <textarea  class="form-control" name="feedback_comment"/>
                        </div>


                    </form>



                <?php endif ?>

            <?php elseif($auction->seller()->id == $user->id): ?>

                <?php if($auction->hasBuyerFeedback()): ?>

                    <div class="alert alert-info" role="alert">You've already left feedback. Please go back.</div>

                <?php else: ?>




                <?php endif ?>

            <?php else: ?>

                <div class="alert alert-danger" role="alert">                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
 You can't leave feedback for an auction you didn't win or create! Please go back!</div>

            <?php endif ?>

        <?php endif ?>

            </div></div>
    </div>

</div>