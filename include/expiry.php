<?php
		require_once('connection.php');
		
		$query  = "SELECT expiary ";
		$query .= "FROM eof ";
		$query .= "WHERE id=1";
		
		$result = mysqli_query($connection, $query);
		if(!$result)
			die("Database query failed.1");
		
        $row = mysqli_fetch_assoc($result);
		
		$exp = new DateTime($row['expiary']);
		$now = new DateTime("now");
		
		if($exp>$now)
		{
			$timeout  = false;
			$interval = $now->diff($exp);
			$time     = $interval->format("%R%a days %H hrs %I mins %S secs");
			$time     = trim($time,'+');
		}
		else
		{
			$timeout = true;
			$time = "Time-Out";
		}
		mysqli_free_result($result);
?>
