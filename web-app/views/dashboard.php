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
    				</tr>
    			<?php } ?>
			</table>

        </div>

    </body>

</html>