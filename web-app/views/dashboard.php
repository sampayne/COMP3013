<div id="dashboard" class="col-md-12">
        
    <h2>Welcome, <?= $user->email ?>!</h2>
    <br />

    <!-- <p>id: <?= $user->id ?></p>
    <p>User is a buyer: <?= $user->buyer_role_id ?> </p>
    <p>User is a seller: <?= $user->seller_role_id ?> </p> -->
    
    <form method="get" action="/auction/create">  
        <button type="submit">Create Auction</button>
    </form>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#seller-dashboard">Seller</a></li>
        <li><a data-toggle="tab" href="#buyer-dashboard">Buyer</a></li>
    </ul>

    <div id="dashboard-content" class="tab-content">
   
        <div id="seller-dashboard" class="tab-pane fade in active col-md-12">

            <h3>Live Auctions</h3>

            <table class="pane">

                <?php foreach($liveSellerAuctions as $auction){ ?>
    				<tr>
        				<td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                        <td  class="col-md-10">
                            <?php echo $auction->id; ?> <br />
                            <h4><strong><?php echo $auction->name; ?></strong></h4>
                            <p><em><?php echo $auction->description; ?> </em></p>
                            <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                            Until: <?php echo $auction->end_date; ?> <br />
                            Highest Bid: <strong><?php echo $auction->getHighestBid(); ?></strong> <br />
                            Number of Bids: <?php echo $auction->getBidCount(); ?> <br />
                            Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                            Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p> 
                            <form method="get" action="/auction/<?php echo $auction->id; ?>/edit">  
                            <button type="submit">Edit Auction</button>
                            </form>
                        </td>
    				</tr>
    			<?php } ?>
			</table>


            <h3>Completed Auctions </h3>

            <table class="pane">
                <?php foreach($completedSellerAuctions as $auction){ ?>
                   <tr>
                        <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                        <td  class="col-md-10">
                            <?php echo $auction->id; ?> <br />
                            <h4><strong><?php echo $auction->name; ?></strong></h4>
                            <p><em><?php echo $auction->description; ?> </em></p>
                            <p>Starting Price: <?php echo $auction->starting_price; ?> <br />
                            Until: <?php echo $auction->end_date; ?> <br />
                            Highest Bid: <strong><?php echo $auction->getHighestBid(); ?></strong> <br />
                            Number of Bids: <?php echo $auction->getBidCount(); ?> <br />
                            Number of Views: <?php echo $auction->getViewCount(); ?> <br />
                            Number of Watches: <?php echo $auction->getWatchCount(); ?> <br /></p> 
                            <form method="get" action="/auction/<?php echo $auction->id; ?>/edit">  
                            <button type="submit">Edit Auction</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>

        
            <div class="col-md-8">

            <h3>Feedback</h3>

            <table class="pane">
                <?php foreach($sellerFeedback as $singleFeedback){ ?>
                    <tr>
                        <td><?php echo $singleFeedback->id; ?>
                        <em><?php echo $singleFeedback->content; ?></em>
                        <p>Create at: <?php echo $singleFeedback->created_at; ?></p></td>
                    </tr>
                <?php } ?>
            </table>

            </div>
            <div class="col-md-4">

            <h3>Stats</h3>
               <div class="pane">

                <p>Item as described: <?php echo $sellerRating['mean_item_as_described']; ?></p>
                <p>Communication: <?php echo $sellerRating['mean_communication']; ?></p>
                <p>Dispatch Time <?php echo $sellerRating['mean_dispatch_time']; ?></p>
                <p>Posting: <?php echo $sellerRating['mean_posting']; ?></p>
                <p>Number of ratings: <?php echo $sellerRating['no_feedback']; ?></p>

                </div>
            </div>
          


        

        </div>

        <div id="buyer-dashboard" class="tab-pane fade col-md-12">
           
            <h3> Live Bid Auctions </h3>

            <table class="pane">

                <?php foreach($liveBidBuyerAuctions as $auction) { ?>
                    <tr>
                        <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                        <td  class="col-md-10">
                           <?php echo $auction->id; ?> <br />
                            <h4><strong><?php echo $auction->name; ?></strong></h4>
                            <p><em><?php echo $auction->description; ?> </em></p>
                            <p>User Bid: <?php echo $auction->getHighestBidForUser($user) ?> <br />
                            Max bid: <?php echo $auction->getHighestBid(); ?> <br />
                            End date: <?php echo $auction->end_date; ?><br /></p> 
                            <form method="post" action="/auction/<?php echo $auction->id; ?>/bid">  
                            <input type="number" name="bid_value" min="<?php echo $auction->getHighestBid() + 1; ?>">
                            <button type="submit">Bid</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>

            <h3> Completed Bid Auctions </h3>

            <table class="pane">

                <?php foreach($completedBidBuyerAuctions as $auction){ ?>
                    <tr>
                        <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                        <td  class="col-md-10">
                           <?php echo $auction->id; ?> <br />
                            <h4><strong><?php echo $auction->name; ?></strong></h4>
                            <p><em><?php echo $auction->description; ?> </em></p>
                            <p>User Bid: <?php echo $auction->getHighestBidForUser($user) ?> <br />
                            Max bid: <?php echo $auction->getHighestBid(); ?> <br />
                            End date: <?php echo $auction->end_date; ?><br /></p> 
                        </td>
                    </tr>
                <?php } ?>
            </table>


            <h3> Live Watched Auctions </h3>

            <table class="pane">
                <?php foreach($liveWatchedBuyerAuctions as $auction){ ?>
                    
                    <tr>
                        <td class="col-md-2"> <img class="col-md-12" src="/images/default.gif"></td>
                        <td  class="col-md-10">
                           <?php echo $auction->id; ?> <br />
                            <h4><strong><?php echo $auction->name; ?></strong></h4>
                            <p><em><?php echo $auction->description; ?> </em></p>
                            Starting price: <?php echo $auction->starting_price; ?><br /></p> 
                            Max bid: <?php echo $auction->getHighestBid(); ?> <br />
                            Bid Count: <?php echo $auction->getBidCount(); ?> <br />
                            End date: <?php echo $auction->end_date; ?><br /></p> 
                            <form method="post" action="/auction/<?php echo $auction->id; ?>/bid">  
                            <input type="number" name="bid_value" min="<?php echo $auction->getHighestBid() + 1; ?>">
                            <button type="submit">Bid</button>
                            </form>
                        </td>

                    </tr>
                <?php } ?>
            </table>

            <div class="col-md-8">

            <h3>Feedback</h3>

            <table class="pane">
                <?php foreach($buyerFeedback as $singleFeedback){ ?>
                    <tr>
                        <td><?php echo $singleFeedback->id; ?>
                        <em><?php echo $singleFeedback->content; ?></em>
                        <p>Create at: <?php echo $singleFeedback->created_at; ?></p></td>
                    </tr>
                <?php } ?>
            </table>

            </div>
            <div class="col-md-4">

            <h3>Rating</h3>
               <div class="pane">

                <p>Rating: <?php echo $buyerRating['mean_rating']; ?></p>
                <p>Number of ratings: <?php echo $buyerRating['no_feedback']; ?></p>

                </div>

            <h3>Stats</h3>
                <div class="pane">
                    <p>Auctions won/lost: </p>
                    <p>Number of bids placed: </p>
                    <p>Auctions followed: </p>
                </div>
            </div>

            

        </div>

        
    </div>
</div>
