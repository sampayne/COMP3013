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

        <div class="panel panel-default">

            <div class="panel-heading"><h1 class="panel-title">Welcome, <?= $user->email ?>!</h1></div>
            <div class="panel-body">
                <div>




                <ul class="nav nav-tabs" role="tablist">

                        <?php if( $user->isBuyer() ): ?>

                            <li role="presentation" class="active">
                                <a href="#buyer-dashboard" aria-controls="buyer" role="tab" data-toggle="tab">Buying</a>
                            </li>

                        <?php endif ?>

                        <?php if( $user->isSeller() ): ?>

                            <li role="presentation" <?= !$user->isBuyer() ? 'class="active"' : '' ?>>
                                <a href="#seller-dashboard" aria-controls="profile" role="tab" data-toggle="tab">Selling</a>
                            </li>

                        <?php endif ?>


                  </ul>

                  <div id="dashboard-content" class="tab-content">

                    <?php if($user->isBuyer()): ?>

                        <?php include('dashboard/buyer/buyer_dashboard.php'); ?>

                    <?php endif ?>

                    <?php if($user->isSeller()): ?>

                        <?php include('dashboard/seller/seller_dashboard.php'); ?>

                    <?php endif ?>

                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>