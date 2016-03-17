
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Recent Feedback Comments</h2>
            </div>

            <ul class="list-group">

                <?php if(count($sellerFeedback) == 0): ?>
                    <li class="list-group-item">
                        <div class="alert alert-info" role="alert">You haven't received any feedback as a seller.</div>
                    </li>
                <?php else: ?>
                    <?php foreach($sellerFeedback as $singleFeedback): ?>
                        <li class="list-group-item">
                            <!--  <?php echo $singleFeedback->id; ?> -->
                            <em><?php echo $singleFeedback->content; ?></em>
                            <p class="text-right">Created at: <?php echo $singleFeedback->created_at; ?></p>
                        </li>
                    <?php endforeach ?>

                <?php endif ?>


            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Seller Ratings</h2>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><span class="badge"><?= $sellerRating['mean_item_as_described'] ?></span>Item as described</li>
                    <li class="list-group-item"><span class="badge"><?= $sellerRating['mean_communication'] ?></span>Communication</li>
                    <li class="list-group-item"><span class="badge"><?= $sellerRating['mean_dispatch_time'] ?></span>Dispatch Time</li>
                    <li class="list-group-item"><span class="badge"><?= $sellerRating['mean_posting'] ?></span>Packaging</li>
                    <li class="list-group-item"><span class="badge"><?= $sellerRating['no_feedback'] ?></span>Total Ratings</li>
                </ul>
        </div>
    </div>
    <div class="col-md-12">

    <a href="/user/<?=$user->id?>/feedback" class="btn btn-default">View All Feedback</a>
    </div>
</div>
