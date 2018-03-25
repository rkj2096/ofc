<?php 
    require_once('connection.php');
	
	function isadmin($email)
	{
		$query  = "SELECT email ";
		$query .= "FROM faculty ";
		$query .= "WHERE admin = true";
		global $connection;
		$result = mysqli_query($connection, $query);
		$index  = 0;
		
		while($row = mysqli_fetch_assoc($result))
		{
			if($row['email']==$email)
			{
				mysqli_free_result($result);
				//mysqli_close($connection);
				
				return true;
			}
		}
		mysqli_free_result($result);
		//mysqli_close($connection);
		
		return false;
	}
?>