<?php
	require_once("../include/a_auth.php"); //only for admin
?>
<?php
	require_once("../include/connection.php");
	
	$changed  = false;
	if(isset($_POST['submit']))
	{
		$time  = $_POST['time'];
		//$feedback = 'Feedback 2017';
		
		$query  = "UPDATE eof ";
		$query .= "SET expiary ='{$time}' ";
		$query .= "WHERE id=1";
		
		$result = mysqli_query($connection, $query);
		if(!$result || mysqli_affected_rows($connection)!=1)
			die('Failed to change.');
		$changed = true;
	}
?>
<?php require_once("../include/head.php"); ?>
<link rel='stylesheet' type='text/css' href='../styles/global.css'>
	<style>
		.main{
			margin:30px 25%;
			padding:10px;
			background-color:#F5F5F5;
			-webkit-border-radius: 9px;
			-moz-border-radius: 9px;
			box-border-radius: 9px;
		}
		form{
			margin:20px 20%;
		}
		.header {
			margin:20px 18%;
			color:#5bc0de;
			font-size:23px;
			font-family:open sans;
		 }
		 label {
			 margin-right:25px;
		 }
		 .required{
			 color:red;
		 }
	</style>
<?php 
	require_once('../include/admin.php');
	require_once("../include/o_nav.php");
?>
<div class="main">
	<?php
		if($changed)
		{
			echo "<div class='panel panel-success'>
					<div class='panel-heading'>Successfully Changed.</div>
				 </div>";
		}
	?>
	<h3 class="header">Set Expiry Time</h3>
	<form class="form-horizontal" method="post" action="index.php" enctype="multipart/form-data">
		<div class="form-group">
			<label for="time" class="cols-sm-2 control-label">Expiry Time<span class="required">*</span></label>
			<div class="cols-sm-6">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
					<input type="datetime-local" class="form-control" name="time" required/>
				</div>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="button-input btn btn-success" name="submit" value="submit" style="success" id="submit">Save</button>
		</div>
	</form>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>
<?php require_once("../include/footer.php"); ?>