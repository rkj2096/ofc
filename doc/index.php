<?php
	require_once("../include/o_auth.php");
?>
<?php
    require_once("../include/connection.php");
	if(isset($_GET['id']))
	{
		$query = "select * from file where id={$_GET['id']}";
		
		$result = mysqli_query($connection,$query);
		if(!$result)
			die("Database query failed.");
		$row = mysqli_fetch_assoc($result);
		
		header("Content-Type: ". $row['mime']);
        header("Content-Length: ". $row['size']);
		header("Content-Disposition: attachment; filename=". $row['name']);
		
		echo $row['data'];
		
		mysqli_free_result($result);
		
		mysqli_close($connection);
	}
	else
		echo "This document is not available.";
?>