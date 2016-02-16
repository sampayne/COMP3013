<html>

    <body>

        <div>
            <p>This is the dashboard for user: <?= $user->email ?> id: <?= $user->id ?></p>
            <p>User is a buyer: <?= $user->buyer_role_id ?> </p>
            <p>User is a seller: <?= $user->seller_role_id ?> </p>

            <h1> Auctions </h1>

            <table>
   		 		<?php foreach($auctions as $auction){ ?>
    				<tr>
        				<td><?php echo $auction['id']; ?></td>
        				<td><?php echo $auction['name']; ?></td>
        				<td><?php echo $auction['description']; ?></td>
        				<td><?php echo $auction['starting_price']; ?></td>
        				<td><?php echo $auction['end_date']; ?></td>
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

        	<table>
   		 		<?php foreach($feedback as $singleFeedback){ ?>
    				<tr>
        				<td><?php echo $singleFeedback['id']; ?></td>
        				<td><?php echo $singleFeedback['content']; ?></td>
        				<td><?php echo $singleFeedback['auction_id']; ?></td>
        				<td><?php echo $singleFeedback['created_at']; ?></td>
    				</tr>
    			<?php } ?>
			</table>

        </div>

    </body>

</html>