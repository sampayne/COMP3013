<?php
// require the Faker autoloader
require_once 'Faker-master/src/autoload.php';
// alternatively, use another PSR-0 compliant autoloader (like the Symfony2 ClassLoader for instance)

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();

$categories = array("Baby", "Cars, Motorcycles & Vehicles", "Collectables", "Crafts", "Garden & Patio", "Health & Beauty", 
"Home, Furniture & DIY", "Jewellery & Watches", "Musical Instruments", "Sporting Goods", "Sports Memorabilia", "Stamps",
"Vehicle Parts & Accessories");

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

/*for ($x = 0; $x < 150; $x++) {
	
	$sql = "INSERT INTO User (email, password, created_at)
	VALUES ('$faker->email', '$faker->password', now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} 

//get user ids
$result = $conn->query("SELECT id FROM User");
$userIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $userIds[] =  $row[0];  
}


foreach($userIds as $id) {
	echo "$id ";
}

//add UserRole data
$x = 0;
foreach($userIds as $id) {

	if($x % 10 !== 0) {
		$sql = "INSERT INTO UserRole (user_id, role_id, created_at)
		VALUES ('$id', 2, now())";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	
	}

	if($x % 5 == 0 || $x % 10 === 0) {

		$sql = "INSERT INTO UserRole (user_id, role_id, created_at)
		VALUES ('$id', 1, now())";

		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	
	}

	$x++;
}

$result = $conn->query("SELECT id FROM Auction");
$auctionIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $auctionIds[] =  $row[0];  
}

foreach($auctionIds as $id) {
	echo "$id ";
}*/


//adding categories
/*foreach($categories as $category) {

	$sql = "INSERT INTO Category (name, created_at)
	VALUES ('$category', now())";
	if ($conn->query($sql) === TRUE) {
    	echo "New record created successfully";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
	}

}*/

//Adding 100 auctions
/*for ($x = 1; $x <= 100; $x++) {
	$end_date = $faker->dateTimeBetween('now', '1 years')->format('Y-m-d H:i:s');
	$description = $faker->paragraph(3, true);
	$price = $faker->numberBetween(0, 101000);
	$sql = "INSERT INTO Auction (name, description, starting_price, end_date, created_at)
	VALUES ('$x', '$description', $price, '$end_date', now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} */

/*//retrieving auctions id
$result = $conn->query("SELECT id FROM Auction");
$auctionIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $auctionIds[] =  $row[0];  
}

foreach($auctionIds as $id) {
	echo "$id ";
}*/

/*//adding SellerFeedback

for ($x = 0; $x < 200; $x++) {
	$content = $faker->paragraph(3, true);
	$auction_id = $faker->randomElement($auctionIds);
	$sql = "INSERT INTO SellerFeedback (content, auction_id, created_at)
	VALUES ('$content', $auction_id, now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} 


//adding BuyerFeedback

for ($x = 0; $x < 100; $x++) {
	$content = $faker->paragraph(3, true);
	$auction_id = $faker->randomElement($auctionIds);
	$sql = "INSERT INTO BuyerFeedback (content, auction_id, created_at)
	VALUES ('$content', $auction_id, now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} 
*/


/*//adding Items

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
	$sql = "INSERT INTO Item (name, description, auction_id, created_at)
	VALUES ('$item', '$description', $auction_id, now())";
	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
}*/

//Retrieve cateogories ids

/*$result = $conn->query("SELECT id FROM Category");
$categoryIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $categoryIds[] =  $row[0];  
}

//Retriving items ids
$result = $conn->query("SELECT id FROM Item");
$itemIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $itemIds[] =  $row[0];  
}


$x = 0;
foreach($itemIds as $id) {
	
	$category_id = $faker->randomElement($categoryIds);
	$sql = "INSERT INTO ItemCategory (item_id, category_id, created_at)
		VALUES ($id, '$category_id', now())";
	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;

	//one more time
	if($x % 4 === 0) {

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id, created_at)
		VALUES ($id, '$category_id', now())";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	//twice more
	if($x % 9 === 0) {

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id, created_at)
		VALUES ($id, '$category_id', now())";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;

		$category_id = $faker->randomElement($categoryIds);
		$sql = "INSERT INTO ItemCategory (item_id, category_id, created_at)
		VALUES ($id, '$category_id', now())";
		if ($conn->query($sql) !== TRUE)
    		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$x++;
}*/


/*//Add Views

$result = $conn->query("SELECT id FROM UserRole");
$userIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $userIds[] =  $row[0];  
}

$result = $conn->query("SELECT id FROM Auction");
$auctionIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $auctionIds[] =  $row[0];  
}

for ($x = 0; $x < 1000; $x++) {
	$user_id = $faker->randomElement($userIds);
	$auction_id = $faker->randomElement($auctionIds);
	$sql = "INSERT INTO View (userrole_id, auction_id, created_at)
	VALUES ('$user_id', $auction_id, now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} */

//Add watches

$result = $conn->query("SELECT id FROM UserRole");
$userIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $userIds[] =  $row[0];  
}

$result = $conn->query("SELECT id FROM Auction");
$auctionIds = Array();
while ($row = $result->fetch_array(MYSQLI_NUM)) {
    $auctionIds[] =  $row[0];  
}

for ($x = 0; $x < 300; $x++) {
	$user_id = $faker->randomElement($userIds);
	$auction_id = $faker->randomElement($auctionIds);
	$sql = "INSERT INTO Watch (userrole_id, auction_id, created_at)
	VALUES ('$user_id', $auction_id, now())";

	if ($conn->query($sql) !== TRUE)
    	echo "Error: " . $sql . "<br>" . $conn->error;
    
} 



echo "New records created successfully <br />";
$conn->close();
?> 
