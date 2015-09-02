<?php

/*
 * Following code will list all the inventory
 */

// array for JSON response
$response = array();


// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// get all inventory from inventory table
$sql = "select COUNT(*) as Count, sum(Cost) as Cost, sum(Priced) as Priced, sum(Priced) - sum(Cost) as Dif, sum(Sold) as Sold from inventory;";
$positions = mysql_query($sql) or die(mysql_error());

// check for empty result
if (mysql_num_rows($positions) > 0) {
    // looping through all results
    $response["inventory"] = array();
    while ($row = mysql_fetch_array($positions)) {
        // temp user array
        $position = array();
        $position["Count"] = $row["Count"];
        $position["Cost"] = number_format($row["Cost"],2);
        $position["Priced"] = number_format($row["Priced"],2);
        $position["Dif"] = number_format($row["Dif"],2);
        $position["Sold"] = number_format($row["Sold"],2);

        // push single position into final response array
        array_push($response["inventory"], $position);
    }
    // success
    $response["success"] = 1;

    // echoing JSON response
    echo json_encode($response);
} else {
    // no items found
    $response["success"] = 0;
    $response["message"] = "No inventory found";

    // echo no users JSON
    echo json_encode($response);
}
?>
