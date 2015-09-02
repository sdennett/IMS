<?php

/*
 * Following code is for populating the database / Data Entry
 */

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
$invdate  = date("y/m/d");
$errors     = array();  	// array to hold validation errors
$data 			= array(); 		// array to pass back data

// validate the variables ======================================================
	if (empty($_POST['description'])) {
		$errors['description'] = 'Description is required.';
	} else {
		$description = $_POST['description'];
	}

	if (empty($_POST['cost'])) {
		$errors['cost'] = 'Cost is required.';
	} else {
		$cost = $_POST['cost'];
	}

	if (empty($_POST['priced'])) {
		$errors['priced'] = 'Priced is required.';
	} else {
		$priced = $_POST['priced'];
	}

	$comment = $_POST['comment'];
	$sold = $_POST['sold'];

// return a response ===========================================================

	// response if there are errors
	if ( ! empty($errors)) {

		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
    // if there are no errors, update database and return a message
		// String replace any ' with `

		$description = str_replace("'", "`",$description);
		$comment = str_replace("'", "`",$comment);

		// Check for Sold

		if (empty($sold)) {
		   $solddate = '';
		   $net = 0;
		} else {
		   $solddate = date("y/m/d");
		   $net = $sold-($sold * .05);
		   $invdate = '';
		}

			// Insert the new item into the DB
			$sql = "INSERT INTO inventory (`InvDate`, `SoldDate`,`Description`,`Cost`,`Priced`,`Sold`,`Commission`,`Net`,`Comments`)
							VALUES ('$invdate', '$solddate', '$description', '$cost', '$priced', '$sold', '.05', '$net', '$comment');";

			$result = mysql_query($sql) or die(mysql_error());

			// Get the ID of the new item and return it's value
			$sql = "SELECT ID FROM inventory WHERE Description = '$description'";
			$result = mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($result) > 0 ) {
				while ($row = mysql_fetch_array($result)) {
					 $returnID = $row['ID'];
				}
			}

		// if there are no errors, return a message
		$data['success'] = true;
		// TO-DO: Return ID for the new item entered.
		$data['message'] = "Success! --  ID: " . $returnID . " Description: " . $description;
	}

	// return all our data to an AJAX call
	echo json_encode($data);
