<?php

/*
 * Following code is for updating the database
 */

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$invdate  = date("y/m/d");

 $itemnumber = $_GET['itemnumber'];
 $description = $_GET['description'];
 $cost = $_GET['cost'];
 $priced = $_GET['priced'];
 $sold = $_GET['sold'];
 $comment = $_GET['comment'];
 $remove = $_GET['remove'];

 $sql = "SELECT * FROM inventory WHERE ID = '$itemnumber';";
 $results = mysql_query($sql)  or die(mysql_error());;

 // check for empty result
 if (mysql_num_rows($results) > 0) {
     // looping through all results
     $response["inventory"] = array();
     while ($row = mysql_fetch_array($results)) {
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
     $response["startdate"] = $startdate;
     $response["enddate"] = $enddate;

     // echoing JSON response
     echo json_encode($response);
 } else {
     // no items found
     $response["success"] = 0;
     $response["message"] = "No results found";

     // echo no users JSON
     echo json_encode($response);
 }
?>
