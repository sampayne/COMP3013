<?php

require_once 'Faker-master/src/autoload.php';

$faker = Faker\Factory::create();

$servername = "comp3013.sam-payne.co.uk";
$username = "comp3013";
$password = "XxxH6?32couoWufi";
$dbname = "comp3013";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addUsers() {
	for ($x = 0; $x < 150; $x++) {
	
	$sql = "INSERT INTO User (email, password)
	VALUES ('$faker->email', '$faker->password',";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
	} 

}

function getUserIds() : array {

	$result = $conn->query("SELECT id FROM User");
	$userIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$userIds[] =  $row[0];  
	}


	foreach($userIds as $id) {
		echo "$id ";
	}

	return $userIds;

}

function addUserRoles() {

	$x = 0;
	foreach($userIds as $id) {

	if($x % 10 !== 0) {
		$sql = "INSERT INTO UserRole (user_id, role_id)
		VALUES ('$id', 2)";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	
	}

	if($x % 5 == 0 || $x % 10 === 0) {

		$sql = "INSERT INTO UserRole (user_id, role_id)
		VALUES ('$id', 1";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	
	}

	$x++;
	}

}

function addCategories() {

	$categories = array("Baby", "Cars, Motorcycles & Vehicles", "Collectables", "Crafts", "Garden & Patio", "Health & Beauty", 
"Home, Furniture & DIY", "Jewellery & Watches", "Musical Instruments", "Sporting Goods", "Sports Memorabilia", "Stamps",
"Vehicle Parts & Accessories");

	foreach($categories as $category) {

		$sql = "INSERT INTO Category (name, created_at)
		VALUES ('$category')";
		if ($conn->query($sql) === TRUE) {
    		echo "New record created successfully";
		} else {
    		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

function addAuctions(mysqli $conn, array $userRoleIds) {

	for ($x = 1; $x <= 100; $x++) {
		$end_date = $faker->dateTimeBetween('now', '1 years')->format('Y-m-d H:i:s');
		$description = $faker->paragraph(3, true);
		$price = $faker->numberBetween(0, 101000);
		$userrole_id = $faker->randomElement($userRoleIds);
		$sql = "INSERT INTO Auction (name, description, starting_price, end_date, userrole_id)
		VALUES ('$x', '$description', $price, '$end_date', )";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	} 
}


function getAuctionIds(mysqli $conn) : array {

	$result = $conn->query("SELECT id FROM Auction");
	$auctionIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$auctionIds[] =  $row[0];
    }  

    /*foreach($auctionIds as $id) {
		echo "$id ";
	}*/

	return $auctionIds;

}

function addSellerFeedback(mysqli $conn, array $auctionIds) {

	for ($x = 0; $x < 100; $x++) {
		global $faker;
		$content = $faker->paragraph(3, true);
		$item_as_described = $faker->numberBetween(1, 5);
		$communication = $faker->numberBetween(1, 5);
		$dispatch_time = $faker->numberBetween(1, 5);
		$posting = $faker->numberBetween(1, 5);
		$auction_id = $faker->randomElement($auctionIds);
		$sql = "INSERT INTO SellerFeedback (content, item_as_described, communication, dispatch_time, posting, auction_id)
		VALUES ('$content', $item_as_described, $communication, $dispatch_time, $posting, $auction_id)";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
    
	} 

}

function addBuyerFeedback(mysqli $conn, array $auctionIds) {
	global $faker;
	for ($x = 0; $x < 100; $x++) {
		$content = $faker->paragraph(3, true);
		$auction_id = $faker->randomElement($auctionIds);
		$rating = $faker->numberBetween(1, 5);
		$sql = "INSERT INTO BuyerFeedback (content, rating, auction_id)
		VALUES ('$content', $rating, $auction_id)";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
    
	} 
}

function addItems () {

	$items = Array();
	$handle = fopen("list_of_items.txt", "r");
	if ($handle) {
    	while (($line = fgets($handle)) !== false) {
        	$items[] = $line;
    }

    fclose($handle);
	} else {
    // error opening the file.
	} 

	foreach($items as $item) {

		$description = $faker->paragraph(3, true);
		$auction_id = $faker->randomElement($auctionIds);
		$sql = "INSERT INTO Item (name, description, auction_id)
		VALUES ('$item', '$description', $auction_id)";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	}

}

function getCategories() {
	$result = $conn->query("SELECT id FROM Category");
	$categoryIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$categoryIds[] =  $row[0];  
	}
	return $categoryIds;

}

function getItems() : array {

	$result = $conn->query("SELECT id FROM Item");
	$itemIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$itemIds[] =  $row[0];  
	}
	return $itemIds;

}


function addItemCategories(array $itemIds, array $categoryIds) {

	$x = 0;
foreach($itemIds as $id) {
	
	$category_id = $faker->randomElement($categoryIds);
	$sql = "INSERT INTO ItemCategory (item_id, category_id)
		VALUES ($id, '$category_id')";
	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;

	//one more time
	if($x % 4 === 0) {

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id)
		VALUES ($id, '$category_id')";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	//twice more
	if($x % 9 === 0) {

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id)
		VALUES ($id, '$category_id')";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id)
		VALUES ($id, '$category_id')";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$x++;
	}

}

function getItemsForAuction(mysqli $conn, $auctionId) {

	$result = $conn->query("SELECT id, name FROM Item WHERE auction_id = $auctionId");
	$items = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$items[] =  $row;  
	}
	return $items;

}

function updateAuctionNames(mysqli $conn) {
	global $faker;
	$adjectives = Array('Best', 'Fantastic', 'Cheap', 'Wonderful', 'Splendid', 'Almost New', 'Unpacked');
	$auctionIds = getAuctionIds($conn);
	foreach($auctionIds as $auction) {
		$items = getItemsForAuction($conn, $auction);
		$name = $faker->randomElement($adjectives);
		$i = 0;
		foreach($items as $item) {
			if($i == 3)
				break;
			$name = $name . ' ' . $item[1];
			$i++;
		}

		$sql = "UPDATE Auction SET name = '$name' WHERE id = $auction";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;

	}
}

function updateAuctionsUserRoleIds(mysqli $conn) {
	global $faker;
	$auctionIds = getAuctionIds($conn);
	$sellerIds = getUserRoleSellerIds($conn);
	foreach($auctionIds as $auction) {
		$userrole_id = $faker->randomElement($sellerIds);
		$sql = "UPDATE Auction SET userrole_id = $userrole_id WHERE id = $auction";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;

	}
}

function getUserRoleIds(mysqli $conn) : array {
	$result = $conn->query("SELECT id FROM UserRole");
	$userRoleIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$userRoleIds[] =  $row[0]; 

	}
	return $userRoleIds;
}

function getUserRoleSellerIds(mysqli $conn) : array {
	$result = $conn->query("SELECT id FROM UserRole WHERE role_id = 1");
	$userRoleIds = Array();
	while ($row = $result->fetch_array(MYSQLI_NUM)) {
    	$userRoleIds[] =  $row[0];  
	}
	return $userRoleIds;
}


function addViews(array $userRoleIds, array $auctionIds)  {

	for ($x = 0; $x < 1000; $x++) {
		$user_id = $faker->randomElement($userRoleIds);
		$auction_id = $faker->randomElement($auctionIds);
		$sql = "INSERT INTO View (userrole_id, auction_id)
		VALUES ('$user_id', $auction_id)";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;

	}

}



function addWatches(array $userRoleIds, array $auctionIds) {

	for ($x = 0; $x < 300; $x++) {
		$user_id = $faker->randomElement($userRoleIds);
		$auction_id = $faker->randomElement($auctionIds);
		$sql = "INSERT INTO Watch (userrole_id, auction_id)
		VALUES ('$user_id', $auction_id)";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
    
	} 

}

//adding Bids logic

function getCurrentAuctions(mysqli $conn) {

	$result = $conn->query("SELECT id, starting_price FROM Auction WHERE end_date > now()");
	$currentAuctions = Array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    	$currentAuctions[] =  $row;  
	}
	/*foreach($currentAuctions as $auction) {
		echo $auction["id"] . " " . $auction["starting_price"] . " <br />";
	}*/
	return $currentAuctions;

}

function getAuctionsAndMaxBid(mysqli $conn) {

	$result = $conn->query("SELECT auction_id, max(value) as max_bid
							FROM Bid
							GROUP BY auction_id");
	$auctionMaxBids = Array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    	$auctionMaxBids[] =  $row;  
	}

	/*foreach($auctionMaxBids as $auction) {
		echo $auction["auction_id"] . " " . $auction["max_bid"] . " <br />";
	}*/
	return $auctionMaxBids;
}

function getHighestBidForAuction(mysqli $conn, $auction_id) {

	$result = $conn->query("SELECT max(value) AS max_value FROM Bid WHERE auction_id = $auction_id");

    if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    	return $row["max_value"]; 
	}

	return NULL;
}

function addSingleBid($conn, $userRoleId, $auctionId, $bidValue) {

	$sql = "INSERT INTO Bid (userrole_id, auction_id, value)
		VALUES ($userRoleId, $auctionId, $bidValue)";
	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;

}

function addBids(mysqli $conn, array $userIds, array $auctionIds) {
	//needs testing
	global $faker;
	$auctions = getCurrentAuctions($conn);
	$auctionsNo = count($auctions);
	//add 5 "rounds" of bids
	$bidsToAdd = $auctionsNo;
	for($i = 0; $i < 5; $i++) {
		
		if($i !== 0) {//add a bid for all of them
			$bidsToAdd = $bidsToAdd / 2;

		}
		
		for($j = 0; $j < $bidsToAdd; $j++) {

			$userRoleId = $faker->randomElement($userIds);
			if($i === 0) {//add a bid for all of them
				
				$auctionId = $auctions[$j]["id"];
				$bidValue =  $auctions[$j]["starting_price"] + rand(1, 1000);
				addSingleBid($conn, $userRoleId, $auctionId, $bidValue);

			}
			else {
				$addBidIndex = rand(0, $auctionsNo - 1);
				$auctionId = $auctions[$addBidIndex]["id"];
				$bidValue =  getHighestBidForAuction($conn, $auctionId) + rand(1, 1000);
				addSingleBid($conn, $userRoleId, $auctionId, $bidValue);
			}
		}

	}
}

//call functions


//$userRoleSellerIds = getUserRoleSellerIds($conn);
//addAuctions($conn, $userRoleSellerIds);

//$userRoleIds = getUserRoleIds($conn);
//$auctionIds = getAuctionIds($conn);
//echo $auctionIds[0] . $auctionIds[9];
//addBids($conn, $userRoleIds, $auctionIds);

//addSellerFeedback($conn, $auctionIds);
//updateAuctionNames($conn);
updateAuctionsUserRoleIds($conn);

//addBuyerFeedback($conn, $auctionIds);
echo "New records created successfully <br />";
$conn->close();
?> 
