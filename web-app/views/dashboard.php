<div class="row">
    <div id="dashboard" class="col-md-12">

        <h2>Welcome, <?= $user->email ?>!</h2>
        <br />

       <!--  <p>id: <?= $user->id ?></p>
        <p>User is a buyer: <?= $user->buyerID() ?> </p>
        <p>User is a seller: <?= $user->sellerID() ?> </p> -->

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

        <?php if($user->isSeller() && $user->isBuyer()): ?>
             <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#seller-dashboard">Seller</a></li>
                <li><a data-toggle="tab" href="#buyer-dashboard">Buyer</a></li>
            </ul>
        <?php endif; ?>


        <div id="dashboard-content" class="tab-content">

            <?php if($user->isSeller()): ?>

            <div id="seller-dashboard" class="tab-pane fade in active col-md-12">

            <br />
            <form role="form" method="get" action="/auction/create">
                <button type="submit" class="btn btn-default">Create Auction</button>
            </form>

                <h3>Live Auctions</h3>

                <table class="pane">

                    <?php foreach($liveSellerAuctions as $auction){ ?>
        				<tr>
            				<td class="col-md-2"> <img class="col-md-12" src="<?= $auction->getFirstItem()->image_url ?>"></td>
                            <td  class="col-md-8">
    <!--                             <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                                Until: <?php echo $auction->end_date; ?> <br />
                                Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                                Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p>
                                <form role="form" method="get" action="/auction/<?php echo $auction->id; ?>/edit">
                                <button type="submit" class="btn btn-default">Edit Auction</button>
                                </form>
                            </td>


                        <td class="col-md-2">
                                <?php if($auction->getBidCount() != 0): ?>
                                    <h3 class="text-success"><strong> <?php echo $auction->getBidCount(); ?> bids</strong></h3>
                                    <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                <?php else: ?>
                                    <h3 class="text-danger"><strong>No bids</strong></h3>
                                <?php endif; ?>
                        </td>

                        </tr>
        			<?php } ?>
    			</table>

                <h3>Completed Auctions </h3>

                <table class="pane">
                    <?php foreach($completedSellerAuctions as $auction){ ?>
                       <tr>
                            <td class="col-md-2"> <img class="col-md-12" src="<?= $auction->getFirstItem()->image_url ?>"></td>
                            <td  class="col-md-8">
                              <!--   <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                                Until: <?php echo $auction->end_date; ?> <br />
                                Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                                Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p>
                            </td>

                            <td class="col-md-3">
                                <?php if($auction->getBidCount() != 0): ?>
                                    <h3 class="text-success"><strong>Sold</strong></h3>
                                    <p>Sold for: <?php echo $auction->getHighestBid(); ?></p>
                                    Number of Bids: <?php echo $auction->getBidCount(); ?>
                                <?php else: ?>
                                    <h3 class="text-danger"><strong>Not sold</strong></h3>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>


                <div class="col-md-8">

                <h3>Feedback</h3>

                <table class="pane">
                    <?php foreach($sellerFeedback as $singleFeedback){ ?>
                        <tr>
                            <td>
                            <!--  <?php echo $singleFeedback->id; ?> -->
                            <em><?php echo $singleFeedback->content; ?></em>
                            <p class="text-right">Created at: <?php echo $singleFeedback->created_at; ?></p></td>
                        </tr>
                    <?php } ?>
                </table>

                </div>
                <div class="col-md-4">

                <h3>Rating</h3>
                   <div class="pane">

                    <p><strong>Item as described:</strong> <?php echo $sellerRating['mean_item_as_described']; ?></p>
                    <p><strong>Communication:</strong> <?php echo $sellerRating['mean_communication']; ?></p>
                    <p><strong>Dispatch Time:</strong> <?php echo $sellerRating['mean_dispatch_time']; ?></p>
                    <p><strong>Posting:</strong> <?php echo $sellerRating['mean_posting']; ?></p>
                    <p><strong>Number of ratings:</strong> <?php echo $sellerRating['no_feedback']; ?></p>

                    </div>
                </div>

            </div>

            <?php endif; ?>

            <?php if($user->isBuyer()): ?>

                <?php if($user->isSeller()): ?>

                    <div id="buyer-dashboard" class="tab-pane fade col-md-12">

                <?php else: ?>

                    <div id="buyer-dashboard" class="col-md-12">

                <?php endif; ?>

                <h3> Live Bid Auctions </h3>

                <?php if(count($liveBidBuyerAuctions) == 0): ?>

                    <h4 class="pane">No Auctions.</h4>

                <?php else: ?>

                <table class="pane">

                    <?php foreach($liveBidBuyerAuctions as $auction) { ?>
                        <tr>
                            <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>

                            <td  class="col-md-8">
                             <!--   <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>End date: <?php echo $auction->end_date; ?><br /></p>

                            </td>

                            <td class="col-md-2">
                                <?php if($auction->getHighestBid() == $auction->getHighestBidForUser($user)): ?>
                                    <h3 class="text-success"><strong>OK</strong></h3>
                                    <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                        <form role="form" method="post" action="/auction/<?php echo $auction->id; ?>/bid">
                                            <div class="form-group">
                                            <label>Bid more</label>
                                            <input class="form-control" type="text" name="bid_value" size="8" min="<?php echo $auction->getHighestBid() + 1; ?>"/>
                                            </div>
                                            <button type="submit" class="btn btn-default">Bid</button>
                                        </form>

                                <?php else: ?>
                                    <h3 class="text-danger"><strong>Outbid</strong></h3>
                                    <p>Your Bid: <?php echo $auction->getHighestBidForUser($user); ?>
                                    Highest Bid: <?php echo $auction->getHighestBid(); ?></p>

                                        <form role="form" method="post" action="/auction/<?php echo $auction->id; ?>/bid">
                                            <div class="form-group"><label>Place another bid</label>
                                            <input class="form-control" type="text" name="bid_value" size="8" min="<?php echo $auction->getHighestBid() + 1; ?>"/>
                                            </div>
                                            <button type="submit" class="btn btn-default">Bid</button>
                                        </form>

                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php } ?>
                </table>

                <?php endif; ?>

                <h3> Completed Bid Auctions </h3>


                <?php if(count($completedBidBuyerAuctions) == 0): ?>

                    <h4 class="pane">No Auctions.</h4>

                <?php else: ?>

                <table class="pane">

                    <?php foreach($completedBidBuyerAuctions as $auction){ ?>
                        <tr>
                            <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                            <td  class="col-md-8">
                             <!--   <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                Ended on: <?php echo $auction->end_date; ?><br /></p>
                            </td>

                            <td class="col-md-2">
                                <?php if($auction->getHighestBid() == $auction->getHighestBidForUser($user)): ?>
                                    <h3 class="text-success"><strong>Won</strong></h3>
                                    <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                <?php else: ?>
                                    <h3 class="text-danger"><strong>Lost</strong></h3>
                                    <p>Highest Bid: <?php echo $auction->getHighestBid(); ?></p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

                <?php endif; ?>

                <h3> Live Watched Auctions </h3>

                <?php if(count($liveWatchedBuyerAuctions) == 0): ?>

                    <h4 class="pane">No Auctions.</h4>

                <?php else: ?>

                <table class="pane">
                    <?php foreach($liveWatchedBuyerAuctions as $auction){ ?>

                        <tr>
                            <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                            <td  class="col-md-8">
                            <!--    <?php echo $auction->id; ?> <br /> -->
                                <h4><strong><?php echo $auction->name; ?></strong></h4>
                                <p><em><?php echo $auction->description; ?> </em></p>
                                <p>End date: <?php echo $auction->end_date; ?><br /></p>

                            </td>

                            <td class="col-md-2">
                                <p>Started at: <?php echo $auction->starting_price; ?>
                                Highest Bid: <?php echo $auction->getHighestBid(); ?>
                                Bid Count: <?php echo $auction->getBidCount(); ?></p>
                                <form role="form" method="post" action="/auction/<?php echo $auction->id; ?>/bid">
                                <div class="form-group">
                                <input class="form-control" type="text" name="bid_value" size="8"min="<?php echo $auction->getHighestBid() + 1; ?>"/>
                                </div>
                                <button type="submit" class="btn btn-default">Bid</button>
                                </form>
                            </td>

                        </tr>
                    <?php } ?>
                </table>

                <?php endif; ?>

                <div class="col-md-8">

                <h3>Feedback</h3>


                <?php if(count($buyerFeedback) == 0): ?>

                    <h4 class="pane">No Feedback.</h4>

                <?php else: ?>

                <table class="pane">
                    <?php foreach($buyerFeedback as $singleFeedback){ ?>
                        <tr>
                           <!--  <td><?php echo $singleFeedback->id; ?> -->
                            <em><?php echo $singleFeedback->content; ?></em>
                            <p class="text-right">Created at: <?php echo $singleFeedback->created_at; ?></p></td>
                        </tr>
                    <?php } ?>
                </table>

                <?php endif; ?>

                </div>
                <div class="col-md-4">

                <h3>Rating</h3>

                    <?php if(count($buyerFeedback) == 0): ?>

                        <h4 class="pane">No Rating.</h4>

                    <?php else: ?>

                    <div class="pane">

                    <p><strong>Rating:</strong> <?php echo $buyerRating['mean_rating']; ?></p>
                    <p><strong>Number of ratings:</strong> <?php echo $buyerRating['no_feedback']; ?></p>

                    </div>

                    <?php endif; ?>

                <h3>Stats</h3>
                    <div class="pane">
                        <p><strong>Auctions won/lost:</strong> </p>
                        <p><strong>Number of bids placed:</strong> </p>
                        <p><strong>Auctions followed:</strong> </p>
                    </div>
                </div>

            </div>

            <?php endif; ?>
        </div>
    </div>
</div>
