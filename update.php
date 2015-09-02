<?php

/*
 * Following code is for updating the database
 */

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$invdate  = date("y/m/d");
$errors     = array();  	// array to hold validation errors
$data 			= array(); 		// array to pass back data

// validate the variables ======================================================

// There is no itemnumber so report the error(s) and exit -- nothing to do.
if(empty($_GET['itemnumber'])) {
  $errors['itemnumber'] = "ID is required";
  $data['success'] = false;
  $data['message'] = "ID is required";
  $data['errors']  = $errors;
  echo json_encode($data);
  exit();   // There is no itemnumber so exit.
} else {
  $itemnumber = $_GET['itemnumber'];
}
// All remaining variables are optional
$description = $_GET['description'];
$cost = $_GET['cost'];
$priced = $_GET['priced'];
$sold = $_GET['sold'];
$comment = $_GET['comment'];
$remove = $_GET['remove'];

// Remove an item from inventory
if ($remove == 1) {
   $sql = "delete from inventory where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // check for empty result
   if (mysql_num_rows($positions) == 0) {
       // if there are no errors, return a message
       $data['success'] = true;
       // TO-DO: Return ID for the new item entered.
       $data['message'] = "ID: " . $itemnumber . " Was removed";
       // return all our data to an AJAX call
       echo json_encode($data);
       // Nothing left to do so exit
       exit();
   } else {
       $errors['removed'] = "Error removing item" . $itemnumber;
       $data['errors']  = $errors;
       $data['success'] = false;
       $data['message'] = "ID: " . $itemnumber . " Was NOT removed";
       echo json_encode($data);
       // Nothing left to do so exit
       exit();
 }
}

// Update Description
if (!empty($description)) {
   $description = str_replace("'", "`",$description);
   $sql = "update inventory set Description = '$description' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// Update Cost
if (!empty($cost)) {
   $sql = "update inventory set Cost = '$cost' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// Update Priced
if (!empty($priced)) {
   $sql = "update inventory set Priced = '$priced' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// Update Sold
if (!empty($sold)) {
   $sql = "update inventory set Sold = '$sold' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   if ($sold == 0) {
      $solddate = '';
      $net = 0;
      $sql = "update inventory set Net = '$net' where ID = $itemnumber;";
      $result = mysql_query($sql) or die(mysql_error());
   } else {
      $solddate = date("y/m/d");
      $net = $sold-($sold * .05);
      $sql = "update inventory set Net = '$net' where ID = $itemnumber;";
      $result = mysql_query($sql) or die(mysql_error());
   }
   $sql = "update inventory set SoldDate = '$solddate' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// Check for Sold = 0. If = 0 then remove sale
if ($sold == 0) {
   $solddate = '';
   $net = 0;
   $sql = "update inventory set Net = '$net' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   $sql = "update inventory set SoldDate = '$solddate' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   $sql = "update inventory set Sold = '$sold' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// Update Comment
if (!empty($comment)) {
   $comment = str_replace("'", "`",$comment);
   $sql = "update inventory set Comments = '$comment' where ID = $itemnumber;";
   $result = mysql_query($sql) or die(mysql_error());
   // if there are no errors, return a message
   $data['success'] = true;
   // TO-DO: Return ID for the new item entered.
   $data['message'] = "ID: " . $itemnumber . " Update Successful! ";
}

// return all our data to an AJAX call
echo json_encode($data);
?>
