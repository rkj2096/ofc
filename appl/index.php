<?php
	require_once("../include/a_auth.php"); //only for admin
	require_once("../include/head.php");
?>
<link rel='stylesheet' type='text/css' href='../styles/global.css'>
	<style>
	 #main{
		  margin:10px 10px;
		  font: normal 14px/150% Arial, Helvetica, sans-serif; 
		  background: #fff; 
		  overflow: hidden;  
	  }
	  table{
		 border: 1px solid #006699; 
		 -webkit-border-radius: 4px; 
		 -moz-border-radius: 4px; 
		 border-radius: 4px;  
	  }
	  table thead tr{
		  background-color:#2196F3;
		  color:#EEEEEE;
	  }
	</style>
<?php 
	require_once('../include/admin.php');
	require_once("../include/o_nav.php");
?>
	<div id="main">
		<form class="form-horizontal" action="save.php?id=<?php echo $_GET['id']; ?>" method='post' style='float:right; margin:2px 20px;'>
			<div class="form-group">
				<button type="submit" class="button-input btn btn-info" name="submit" value="submit" style="success" id="submit">Download Excel Sheet</button>
			</div>
		</form>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title" style="font-size:25">Applicant's Information:</h3>
			</div>
			<?php 
					$query  = "select * from application where id = '{$_GET['id']}'";
					$result = mysqli_query($connection, $query);
					if(!$result)
						die("Failed to fetch applicant info.");
					$row = mysqli_fetch_assoc($result);
					
					$app_id   = $row['app_id'];
					$app_name = $row['app_name'];
					
					mysqli_free_result($result);
			?>
			<div class="panel-body" style="color:#135D75">Application ID: <?php echo $app_id;?><br>Name of the Applicant: <?php echo $app_name;?></div>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Faculty Name</th>
					<th>Faculty email</th>
					<th>Research Statement</th>
					<th>Teaching Statement</th>
					<th>Overall</th>
					<th>Recommendation</th>
					<th>Comment</th>
				</tr>
			</thead>
			<tbody>
					<?php
						  $query  = "Select fname,email,research,teaching,overall,comment,recommendation as rc ";
						  $query .= "From feedback b ";
						  $query .= "Inner join faculty f ";
						  $query .= "On b.faculty = f.email ";
						  $query .= "Where  b.applicant = '{$_GET['id']}'";
						  
						  $result = mysqli_query($connection, $query);
						  
						  if(!$result)
							  die("Failed in fetching result.");
						  $sno=1;
						  while($row = mysqli_fetch_assoc($result))
						  {   
							  if($row['rc']==1)
								$rcmd='No';
							  else if($row['rc']==0)
								$rcmd ='Yes';
							  else $rcmd='Neutral';
							  $cmt = wordwrap($row['comment'],70,"<br/>\n");
							  echo "<tr>
									<td>{$sno}</td>
									<td>{$row['fname']}</td>
									<td>{$row['email']}</td>
									<td>{$row['research']}</td>
									<td>{$row['teaching']}</td>
									<td>{$row['overall']}</td>
									<td>{$rcmd}</td>
									<td>{$cmt}</td>
									</tr>";
							  $sno++;		
						  }
						  
						  mysqli_free_result($result);
						  mysqli_close($connection);
					?>
			</tbody>
		</table>
	</div>
<?php require_once("../include/footer.php"); ?>
