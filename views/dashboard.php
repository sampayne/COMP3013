<div>
        
    <p>This is the dashboard for user: <?= $user->email ?> id: <?= $user->id ?></p>
    <p>User is a buyer: <?= $user->buyer_role_id ?> </p>
    <p>User is a seller: <?= $user->seller_role_id ?> </p>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#seller-dashboard">Seller</a></li>
        <li><a data-toggle="tab" href="#buyer-dashboard">Buyer</a></li>
    </ul>

    <div class="tab-content">
   
        <div id="seller-dashboard" class="tab-pane fade in active">

            <h3> Live Auctions </h3>

            <table cellpadding="1">
   		 		<tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Starting Price</th>
                    <th>End Date</th>
                    <th>Maximum Bid</th>
                    <th>Bid Count</th>                    
                    <th>View Count</th>
                    <th>Watch Count</th>
                    <th>Edit Auction</th>
                </tr>

                <?php foreach($liveSellerAuctions as $auction){ ?>
    				<tr>
        				<td><?php echo $auction['id']; ?></td>
        				<td><?php echo $auction['name']; ?></td>
        				<td><?php echo $auction['description']; ?></td>
        				<td><?php echo $auction['starting_price']; ?></td>
        				<td><?php echo $auction['end_date']; ?></td>
                        <td><?php echo $auction['max_bid']; ?></td>
                        <td><?php echo $auction['bid_count']; ?></td>
                        <td><?php echo $auction['view_count']; ?></td>
                        <td><?php echo $auction['watch_count']; ?></td>
        				<td><form method="get" action="/auction/<?php echo $auction['id']; ?>/edit">  
          	 				<button type="submit">Edit Auction</button>
        					</form>
        				</td>
    				</tr>
    			<?php } ?>
			</table>


            <h3> Completed Auctions </h3>

            <table>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Starting Price</th>
                    <th>End Date</th>
                    <th>Maximum Bid</th>
                    <th>Bid Count</th>                    
                    <th>View Count</th>
                    <th>Watch Count</th>
                    <th>Edit Auction</th>
                </tr>
                <?php foreach($completedSellerAuctions as $auction){ ?>
                    <tr>
                        <td><?php echo $auction['id']; ?></td>
                        <td><?php echo $auction['name']; ?></td>
                        <td><?php echo $auction['description']; ?></td>
                        <td><?php echo $auction['starting_price']; ?></td>
                        <td><?php echo $auction['end_date']; ?></td>
                        <td><?php echo $auction['max_bid']; ?></td>
                        <td><?php echo $auction['bid_count']; ?></td>
                        <td><?php echo $auction['view_count']; ?></td>
                        <td><?php echo $auction['watch_count']; ?></td>
                        <td><form method="get" action="/auction/<?php echo $auction['id']; ?>/edit">  
                            <button type="submit">Edit Auction</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>


			<form method="get" action="/auction/create">  
          	 <button type="submit">Create Auction</button>
        	</form>

            <h3>Feedback </h3>

        	<table>
   		 		<tr>
                    <th>Id</th>
                    <th>Content</th>
                    <th>Auction Id</th>
                    <th>Created At</th>
                </tr>
                <?php foreach($feedback as $singleFeedback){ ?>
    				<tr>
        				<td><?php echo $singleFeedback['id']; ?></td>
        				<td><?php echo $singleFeedback['content']; ?></td>
        				<td><?php echo $singleFeedback['auction_id']; ?></td>
        				<td><?php echo $singleFeedback['created_at']; ?></td>
    				</tr>
    			<?php } ?>
			</table>

            <h3>Aggregate Feedback</h3>

            <p>Item as described: <?php echo $aggregateFeedback['mean_item_as_described']; ?></p>
            <p>Communication: <?php echo $aggregateFeedback['mean_communication']; ?></p>
            <p>Dispatch Time <?php echo $aggregateFeedback['mean_dispatch_time']; ?></p>
            <p>Posting: <?php echo $aggregateFeedback['mean_posting']; ?></p>
            <p>Number of ratings: <?php echo $aggregateFeedback['no_feedback']; ?></p>

        </div>

        <div id="buyer-dashboard" class="tab-pane fade">
           
            <h3> Live Bid Auctions </h3>

            <table cellpadding="1">
                
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>User Bid</th>
                    <th>Maximum Bid</th>
                    <th>End Date</th>
                    <th>Bid</th>
                </tr>

                <?php foreach($liveBidBuyerAuctions as $auction){ ?>
                    <tr>
                        <td><?php echo $auction['id']; ?></td>
                        <td><?php echo $auction['name']; ?></td>
                        <td><?php echo $auction['description']; ?></td>
                        <td><?php echo $auction['user_bid']; ?></td>
                        <td><?php echo $auction['max_bid']; ?></td>
                        <td><?php echo $auction['end_date']; ?></td>
                        <td><form method="post" action="/auction/<?php echo $auction['id']; ?>/bid">  
                            <input type="number" name="bid_value" min="<?php echo $auction['max_bid'] + 1; ?>">
                            <button type="submit">Bid</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>


            <h3> Completed Bid Auctions </h3>

            <table>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>User Bid</th>
                        <th>Maximum Bid</th>
                        <th>End Date</th>
                    </tr>

                <?php foreach($completedBidBuyerAuctions as $auction){ ?>
                    <tr>
                        <td><?php echo $auction['id']; ?></td>
                        <td><?php echo $auction['name']; ?></td>
                        <td><?php echo $auction['description']; ?></td>
                        <td><?php echo $auction['user_bid']; ?></td>
                        <td><?php echo $auction['max_bid']; ?></td>
                        <td><?php echo $auction['end_date']; ?></td>
                    </tr>
                <?php } ?>
            </table>


            <h3> Live Watched Auctions </h3>

            <table cellpadding="1">
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Starting Price</th>
                        <th>Maximum Bid</th>
                        <th>Number of Bids</th>
                        <th>End Date</th>
                        <th>Bid</th>
                    </tr>

                <?php foreach($liveWatchedBuyerAuctions as $auction){ ?>
                    <tr>
                        <td><?php echo $auction['id']; ?></td>
                        <td><?php echo $auction['name']; ?></td>
                        <td><?php echo $auction['description']; ?></td>
                        <td><?php echo $auction['starting_price']; ?></td>
                        <td><?php echo $auction['max_bid']; ?></td>
                        <td><?php echo $auction['bid_count']; ?></td>
                        <td><?php echo $auction['end_date']; ?></td>
                        <td><form method="post" action="/auction/<?php echo $auction['id']; ?>/bid">  
                            <input type="number" name="bid_value" min="<?php echo $auction['max_bid'] + 1; ?>">
                            <button type="submit">Bid</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        
    </div>
</div>
