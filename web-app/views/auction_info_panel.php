<div class="panel panel-default">
            <ul class="list-group">
                <li class="list-group-item">
                    Starting Price: &pound; <?= $auction->getFormattedStartingPrice() ?>
                </li>
                <?php if($auction->getReserve() > 0): ?>
                <li class="list-group-item">
                    Reserve Price: &pound; <?= $auction->getFormattedReservePrice() ?>
                </li>
                <?php endif ?>
                <li class="list-group-item">
                    Bid Count: <?= $auction->getBidCount(); ?>
                </li>
                <li class="list-group-item">Views: <?= $auction->getViewCount() ?></li>
                <li class="list-group-item">Number of Watches: <?= $auction->getWatchCount()?></li>

                <?php if($auction->isFinished()): ?>

                    <?php if($auction->wasSold()): ?>

                        <li class="list-group-item text-center">
                            Sold &pound;<?= $auction->getFormattedHighestBid() ?>
                        </li>

                        <?php if(!isset($user) || $auction->buyer()->id != $user->id):?>
                        <li class="list-group-item">
                            To: <a href="/user/<?=$auction->buyer()->id?>/feedback"><?= $auction->buyer()->email ?></a>
                        </li>
                        <?php endif ?>

                        <?php if(isset($user) && $auction->buyer()->id == $user->id): ?>
                            <li class="list-group-item list-group-item-success text-center">
                                You won this item!!
                            </li>
                        <?php elseif(isset($user) && $auction->seller()->id == $user->id): ?>
                            <li class="list-group-item list-group-item-success text-center">
                                You sold this item!!
                            </li>
                        <?php elseif(isset($user) && $auction->getHighestBidForUser($user) > 0): ?>
                            <li class="list-group-item list-group-item-danger text-center">
                                You lost this item!!
                            </li>
                        <?php endif ?>




                        <?php if(isset($user) && $auction->seller()->id == $user->id && !$auction->hasBuyerFeedback()):?>
                         <li class="list-group-item">

                            <a href="/auction/<?=$auction->id?>/feedback/create" class="btn btn-primary center-block">Leave Feedback for Buyer</a>
                         </li>
                        <?php elseif(isset($user) && $auction->buyer()->id == $user->id && !$auction->hasSellerFeedback()):?>
                         <li class="list-group-item">

                            <a href="/auction/<?=$auction->id?>/feedback/create" class="btn btn-primary center-block">Leave Feedback for Seller</a>
                         </li>
                        <?php endif ?>

                    <?php else:?>

                        <li class="list-group-item list-group-item-danger text-center">
                            Unsold
                        </li>

                    <?php endif ?>

                <?php else:?>

                    <li class="list-group-item <?= isset($user) && $auction->seller()->id == $user->id && $auction->getHighestBid() > 0 ? 'list-group-item-success' : '' ?>
                                               <?= isset($user) && $auction->seller()->id == $user->id && $auction->getHighestBid() == 0 ? 'list-group-item-warning' : '' ?>
                                            ">
                        Current Bid: &pound;<?= $auction->getFormattedHighestBid() ?>
                    </li>





                    <?php if($auction->getHighestBid() > 0): ?>

                        <?php if(isset($user) && $auction->highestBidder()->id == $user->id):?>
                            <li class="list-group-item list-group-item-success text-center">
                                You are the highest bidder!!
                            </li>
                        <?php elseif(isset($user) && $auction->getHighestBidForUser($user) > 0):?>
                            <li class="list-group-item list-group-item-danger text-center">
                                You have been outbid!!
                            </li>
                        <?php else:?>
                            <li class="list-group-item">
                                By: <a href="/user/<?=$auction->highestBidder()->id?>/feedback"><?= $auction->highestBidder()->email ?></a>
                            </li>
                        <?php endif ?>

                    <?php endif ?>



                    <?php if(isset($user) && $auction->seller()->id !== $user->id):?>
                        <li class="list-group-item">
                                    <form action="/auction/<?= $auction->id ?>/bid" method="post">                                  <div class="input-group">

                                          <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Bid Now:</button>
                                          </span>

                                          <input type="number" min="<?=$auction->getFormattedNextBidValue()?>" class="form-control" name="bid-bar" placeholder="All bids need to be placed in £">

                                        </div>
                                                                                      <p class="help-block">Next minimum bid: &pound;<?=$auction->getFormattedNextBidValue()?></p>

                                        </form>
                        </li>
                    <?php endif ?>

                <?php endif ?>


                <!-- Add feedback link -->

            </ul>
         </div>
