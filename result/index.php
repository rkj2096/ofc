<?php
	require_once("../include/a_auth.php");
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
		<form class="form-horizontal" action='save.php' method='post' style='float:right; margin:5px 20px;'>
			<div class="form-group">
				<button type="submit" class="button-input btn btn-info" name="submit" value="submit" style="success" id="submit">Download Excel Sheet</button>
			</div>
		</form>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>S.No.</th>
					<th>Applicantion ID</th>
					<th>Name of The Applicant</th>
					<th>Research Aggregate</th>
					<th>Teaching Aggregate</th>
					<th>Overall Aggregate</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
					<?php
						  $query  = "select a.id as id,a.app_id,a.app_name,sum(f.research) as rsh,sum(f.teaching) as tch,sum(f.overall) as ovl ";
						  $query .= "from feedback f,application a ";
						  $query .= "where f.applicant = a.id ";
						  $query .= "group by f.applicant";
						  
						  $result = mysqli_query($connection, $query);
						  
						  if(!$result)
							  die("Failed in fetching result.");
						  $sno=1;
						  while($row = mysqli_fetch_assoc($result))
						  {
							  echo "<tr>
									<td>{$sno}</td>
									<td><a href='../appl/index.php?id={$row['id']}'>{$row['app_id']}</a></td>
									<td>{$row['app_name']}</td>
									<td>{$row['rsh']}</td>
									<td>{$row['tch']}</td>
									<td>{$row['ovl']}</td>
									<td><a  onclick='return warn()' href='../rdel.php?id={$row['id']}' ><img src='../images/rdel.png' width=20 height=20/></a></td>
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
			return confirm("Are you sure to delete this feedback?");
		}
	</script>
<?php require_once("../include/footer.php"); ?>  
