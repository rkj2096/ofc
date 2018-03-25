<?php
	require_once('include/a_auth.php');
	require_once('include/connection.php');
	
	$query  = "DELETE ";
	$query .= "FROM application ";
	$query .= "WHERE id= {$_GET['id']} LIMIT 1";
	
	$result = mysqli_query($connection, $query);
    if(!$result||mysqli_affected_rows($connection)!=1)
		die("Database query failed.");
	header("location: index.php");
?>