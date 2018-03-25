<?php
	require_once("../include/o_auth.php");
	require_once("../include/head.php");
?>
<link rel='stylesheet' type='text/css' href='../styles/global.css'>
	<style>
	 #main{
		  margin:10px 10px;
		  font: normal 14px/150% Arial, Helvetica, sans-serif; 
		  background: #fff; 
		  overflow: hidden; 
		  -webkit-border-radius: 4px; 
		  -moz-border-radius: 4px; 
		  border-radius: 4px; 
	  }
	  table thead tr{
		  background-color:#2196F3;
		  color:#EEEEEE;
		  //font-family:cursive;
	  }
	  table{
		  border: 1px solid #006699;
	  }
	</style>
<?php
	require_once('../include/admin.php');
	require_once("../include/o_nav.php");
?>
	<div id="main">
		<form class="form-horizontal" action='save.php' method='post' style='float:right; margin:5px 20px;'>
			<div class="form-group">
				<button type="submit" onclick='alert("Email has been sent.")' class="button-input btn btn-info" name="submit" value="submit" style="success" id="submit">Email the Receipt of All Feedback You Have Given</button>
			</div>
		</form>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Applicantion ID</th>
					<th>Name of The Applicant</th>
					<th>Research Statement</th>
					<th>Teaching Statement</th>
					<th>Overall</th>
					<th>Recommendation</th>
					<th>Comment</th>
					<th>Edit</th>
				</tr>
			</thead>
			<tbody>
					<?php
						  $query  = "Select a.id as fid,a.app_id,a.app_name,f.research,f.teaching,f.overall,f.comment,f.recommendation as rc ";
						  $query .= "From feedback f ";
						  $query .= "Inner join application a ";
						  $query .= "On f.applicant = a.id ";
						  $query .= "Where  f.faculty = '{$_SESSION['SESS_MEMBER_ID']}'";
						  
						  $result = mysqli_query($connection, $query);
						  
						  if(!$result)
							  die("Failed in fetching result.");
						  $sno=1;
						  while($row = mysqli_fetch_assoc($result))
						  {   
							  if($row['rc']==1)
									$rcmd = 'No';
							  else if($row['rc']==0)
									$rcmd = 'Yes';
							  else
							        $rcmd = 'Neutral';
							  $cmt = wordwrap($row['comment'],40,'<br>');     		
							  echo "<tr>
									<td>{$sno}</td>
									<td>{$row['app_id']}</td>
									<td>{$row['app_name']}</td>
									<td>{$row['research']}</td>
									<td>{$row['teaching']}</td>
									<td>{$row['overall']}</td>
									<td>{$rcmd}</td>
									<td>{$cmt}</td>
									<td><a  onclick='return warn()' href='../feedback/index.php?id={$row['fid']}' ><img src='../images/edt.png' width=20 height=20/></a></td>
									</tr>";
							  $sno++;		
						  }
						  mysqli_free_result($result);
						  mysqli_close($connection);
					?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		function warn()
		{
			return confirm("Are you sure to edit this feedback?");
		}
	</script>
<?php require_once("../include/footer.php"); ?>  
