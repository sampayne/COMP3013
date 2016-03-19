<div id="seller-dashboard" role="tabpanel" class="tab-pane <?= !$user->isBuyer() ? 'active' : '' ?>" >
<div class="row">
    <div class="col-md-12 text-center">
        <a href="/auction/create" class="btn btn-primary btn-lg">Create Auction</a>
    </div>
</div>
<br>
<?php include('live_auctions.php'); ?>
<?php include('completed_auctions.php'); ?>
<?php include('seller_feedback.php'); ?>

</div>
