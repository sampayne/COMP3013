<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Recent Feedback Comments</h2>
            </div>

            <ul class="list-group">

                <?php if(count($buyerFeedback) == 0): ?>
                    <li class="list-group-item">
                        <div class="alert alert-info" role="alert">You haven't received any feedback as a buyer.</div>
                    </li>
                <?php else: ?>
                    <?php foreach($buyerFeedback as $singleFeedback): ?>
                        <li class="list-group-item">
                            <!--  <?php echo $singleFeedback->id; ?> -->
                            <em><?php echo $singleFeedback->content; ?></em>
                            <p class="text-right">Created at: <?php echo $singleFeedback->created_at; ?></p>
                        </li>
                    <?php endforeach ?>

                <?php endif ?>

                <li class="list-group-item">
                    <a href="/user/<?=$user->id?>/feedback" class="btn btn-default">View All Feedback</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Buyer Ratings</h2>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><span class="badge"><?= $buyerRating['mean_communication'] ?></span>Communication</li>
                    <li class="list-group-item"><span class="badge"><?= $buyerRating['mean_speed_of_payment'] ?></span>Speed of Payment</li>
                    <li class="list-group-item list-group-item-info"><span class="badge"><?= $buyerRating['overall'] ?></span>Overall</li>
                </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Buyer Stats</h2>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><span class="badge"><?php echo $user->getPercentageAuctionsWon(); ?>%</span>Auctions Won</li>
                    <li class="list-group-item"><span class="badge"> <?php echo $user->getBuyerBidCount(); ?></span>Bids Placed</li>
                    <li class="list-group-item"><span class="badge"><?php echo $user->getBuyerWatchCount(); ?></span>Auctions Watched</li>
                </ul>
        </div>
    </div>
</div>
