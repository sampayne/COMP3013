<div id="seller-dashboard" role="tabpanel" class="tab-pane <?= !$user->isBuyer() ? 'active' : '' ?>" >

                <a href="/auction/create" class="btn btn-default">Create Auction</a>

                <?php include('live_auctions.php'); ?>
                <?php include('completed_auctions.php'); ?>
                <?php include('seller_feedback.php'); ?>

</div>
