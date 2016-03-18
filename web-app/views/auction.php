

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



                <?php if($auction_exists): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h1 class="panel-title"><?= $name ?></h1></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                <img class="img-responsive" src="<?= strlen($auction->getFirstItem()->image_url) > 0 ? strlen($auction->getFirstItem()->image_url) > 0 : '/images/default.gif' ?>">
                                </div>
                                <div class="col-md-4">

                                    <div class="panel panel-default">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <?= $auction->description ?>

                                            </li>
                                            <li class="list-group-item">
                                                <?php if($auction->isFinished()):?>

                                                    Ended on <?=$auction->end_date ?>

                                                <?php else: ?>

                                                    Ends on <?=$auction->end_date ?>

                                                <?php endif ?>
                                            </li>
                                            <li class="list-group-item">
                                                Seller: <a href="/user/<?=$auction->seller()->id?>/feedback">
                                                    <?= $auction->seller()->email ?></a>
                                                    <?= $auction->seller()->id == $user->id ? ' (You)' : '' ?>
                                            </li>

                                            <?php if($user->isBuyer() && !$auction->isFinished()):?>
                                                <li class="list-group-item">

                                                    <form method="post" action='/auction/<?= $auction->id ?>/watch'>

                                                        <?php if($isWatched): ?>
                                                            <input type="hidden" name="watch" value="0">
                                                            <button type="submit" class="btn btn-danger btn-block"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Stop Watching</button>

                                                        <?php else: ?>
                                                            <input type="hidden" name="watch" value="1">
                                                            <button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> Watch</button>

                                                        <?php endif ?>

                                                    </form>

                                                </li>
                                            <?php endif ?>


                                    </ul>
                                    </div>

                                </div>
                                <div class="col-md-4">

                                        <?php include('auction_info_panel.php') ?>

                                </div>


                            </div>

                            <div class="row">
                                <div class= "col-md-12">
                                    <ul class="list-group" id="accordion" role="tablist" aria-multiselectable="true">

                                        <?php $itemCounter = 0;
                                              foreach($items as $item){
                                                $itemCounter = $itemCounter + 1;?>

                                          <li class="list-group-item">
                                            <table>
                                                <div class="row">
                                                    <tr>
                                                        <td style="vertical-align: middle;" class="col-md-8 myListHeaders">
                                                        <div > <span class="glyphicon glyphicon-ok" style="color:green;" aria-hidden="true"></span> <?= $item->name ?></div></td>

                                                        <td class="col-md-4">
                                                        <button type="button" class="btn btn-sm btn-primary collapsed" data-toggle="collapse" data-parent="#accordion" href=<?php echo("#collapse".$itemCounter);?> aria-controls=<?php echo("collapse".$itemCounter);?> aria-expanded="false">
                                                          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View Description
                                                        </button></td>

                                                    </tr>
                                                </div>
                                            </table>
                                          </li>

                                          <div id=<?php echo("collapse".$itemCounter);?> class="panel-collapse collapse" role="tabpanel" aria-labelledby=<?php echo("heading".$itemCounter);?>>

                                              <div class="panel-body shadedPanel descriptionText" style="border-style: none;">
                                                <?php echo($item->description); ?>
                                              </div>

                                          </div>

                                        <?php }?>

                                    </ul>

                                </div>


                            </div>



                            </div>
                        </div>
                    </div>

                <?php else: ?>

                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        Sorry, auction not found...
                </div>

                <?php endif ?>

        </div>
    </div>
</div>