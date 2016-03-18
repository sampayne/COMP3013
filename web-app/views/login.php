
<div class="container">

<div class="row">

    <div class="col-md-6">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2 class="panel-title">Login</h2>
            </div>
            <div class="panel-body">

                <?php if(isset($login_error)) :?>
                    <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
 <?= $login_error ?></div>
                <?php endif ?>

                <form class="form-horizontal" method="post" action="/login">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Email Address</label>
                        <div class="col-md-9">
                            <input class="form-control" type="email" name="email" placeholder="Email Address..."/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" >Password</label>
                        <div class="col-md-9">
                            <input class="form-control" type="password" name="password" placeholder="Passsword..."/>
                        </div>
                    </div>
                      <div class="form-group">

                    <div class="col-md-offset-3 col-md-7">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="col-md-6">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2 class="panel-title">Sign Up</h2>
            </div>
            <div class="panel-body">

                <?php if(isset($signup_error)) :?>
                    <div class="alert alert-danger" role="alert">                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
<?= $signup_error ?></div>
                <?php endif ?>

                <form method="post" action="/signup">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Email Address</label>
                            <input class="form-control"  type="email" name="email" placeholder="Email Address..." />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input class="form-control"  type="password" name="password" placeholder="Password..." />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Confirm Password</label>
                            <input class="form-control"  type="password" name="confirm_password" placeholder="Confirm Password..."/>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="buyer_account" value="0">
                                <input value="1" name="buyer_account" type="checkbox"> Register as Buyer
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="seller_account" value="0">
                                <input value="1" name="seller_account" type="checkbox"> Register as Seller
                            </label>
                        </div>



                        <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>

            </div>
        </div>
    </div>
</div>

</div>