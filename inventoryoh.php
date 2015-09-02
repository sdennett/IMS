<?php

/*
* Following code will list all the on hand inventory in JSON format
 */

// array for JSON response
$response = array();


// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

// get all on hand inventory from inventory table
// $sql = "SELECT DATE_FORMAT(InvDate, '%m/%d/%y') as InvDate from inventory;";
$positions = mysql_query("SELECT ID, DATE_FORMAT(InvDate, '%m/%d/%y') as InvDate, DATE_FORMAT(SoldDate, '%m/%d/%y') as SoldDate, Description, Cost,Priced,Sold,Commission,Net,Comments FROM inventory WHERE Sold = 0 order by Description, InvDate") or die(mysql_error());

// check for empty result
if (mysql_num_rows($positions) > 0) {
    // looping through all results
    $response["inventory"] = array();
    while ($row = mysql_fetch_array($positions)) {
        // temp user array
        $position = array();
        $position["ID"] = $row["ID"];
        $position["InvDate"] = $row["InvDate"];
        $position["SoldDate"] = $row["SoldDate"];
        $position["Description"] = $row["Description"];
        $position["Cost"] = number_format($row["Cost"],2);
        $position["Priced"] = number_format($row["Priced"],2);
        $position["Sold"] = number_format($row["Sold"],2);
        $position["Commission"] = number_format($row["Commission"],2);
        $position["Net"] = number_format($row["Net"],2);
        $position["Comments"] = $row["Comments"];

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
