<?php

/*
 * Following code will list all the inventory in JSON format
 */

// variables
$response = array();            // array for JSON response
$errors   = array();  	        // array to hold validation errors
$sql = "";                      // string to hold SQL Statement

// validate the variables ======================================================

  if (empty($_GET['search'])) {
    //$errors['search'] = 'Start Date is required.';
  } else {
    $search = $_GET['search'];
  }

  if (empty($_GET['inventory'])) {
    //$errors['inventory'] = 'Start Date is required.';
  } else {
    $inventory = $_GET['inventory'];
  }

	if (empty($_GET['startdate'])) {
		//$errors['startdate'] = 'Start Date is required.';
    $startdate = "0000-00-00";
	} else {
		$startdate = date("Y-m-d", strtotime($_GET['startdate']));
	}

  if (empty($_GET['enddate'])) {
		//$errors['enddate'] = 'End Date is required.';
    $enddate = date("Y-m-d");
	} else {
		$enddate = date("Y-m-d", strtotime($_GET['enddate']));
	}

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

switch ($inventory) {
  case 'all':
    $sql = "SELECT ID, DATE_FORMAT(InvDate, '%m/%d/%y') as InvDate, DATE_FORMAT(SoldDate, '%m/%d/%y')
            as SoldDate, Description, Cost,Priced,Sold,Commission,Net,Comments FROM inventory
            WHERE Description LIKE '%$search%' AND SoldDate >= '$startdate' and SoldDate <= '$enddate' ORDER BY Description";
    break;
  case 'onhand':
    $sql = "SELECT ID, DATE_FORMAT(InvDate, '%m/%d/%y') as InvDate, DATE_FORMAT(SoldDate, '%m/%d/%y')
            as SoldDate, Description, Cost,Priced,Sold,Commission,Net,Comments FROM inventory
            WHERE Description LIKE '%$search%' AND Sold = 0 AND SoldDate >= '$startdate' and SoldDate <= '$enddate' ORDER BY Description";
    break;
  case 'sold':
    $sql = "SELECT ID, DATE_FORMAT(InvDate, '%m/%d/%y') as InvDate, DATE_FORMAT(SoldDate, '%m/%d/%y')
            as SoldDate, Description, Cost,Priced,Sold,Commission,Net,Comments FROM inventory
            WHERE Description LIKE '%$search%' AND Sold > 0 AND SoldDate >= '$startdate' and SoldDate <= '$enddate' ORDER BY Description";
    break;

  default:
  // no items found
  $response["success"] = 0;
  $response["message"] = "No inventory found";
  // echo no users JSON
  echo json_encode($response);
  exit();
    break;
}
$positions = mysql_query($sql)  or die(mysql_error());;

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
