<?php
	require_once("admin.php");
	//Start session
	session_start();
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESS_MEMBER_ID']) || !isadmin(trim($_SESSION['SESS_MEMBER_ID']))) {
		header("location: ../login/");
		exit();
	}
?>