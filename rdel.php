<?php
	require_once('include/a_auth.php');
	require_once('include/connection.php');
	
	$query  = "DELETE ";
	$query .= "FROM feedback ";
	$query .= "WHERE applicant = {$_GET['id']}";
	
	$result = mysqli_query($connection, $query);
    if(!$result||mysqli_affected_rows($connection)<1)
		die("Database query failed.");
	header("location: result/");
?>