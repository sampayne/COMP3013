<div class="container">

<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h1 class="panel-title">Error</h1></div>
            <div class="panel-body">

                <?php if(isset($errors)):?>
                    <?php foreach($errors as $error):?>
                       <div class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <?= $error ?>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                   <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <?= $message ?>
                </div>

                <?php endif ?>


            </div>
        </div>
    </div>
</div>

</div>