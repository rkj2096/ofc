<?php 
	$db_host="localhost";
	$db_user="ofc_admin";
	$db_password="utnin@66";
	$db_name="ofc";
	$connection=mysqli_connect($db_host,$db_user,$db_password,$db_name);
	if(mysqli_connect_errno())
	{
		die("Database connection failed. ".mysqli_connect_error()." ".mysqli_connect_errno());
	}
?>
