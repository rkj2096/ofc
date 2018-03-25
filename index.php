<?php 
	require_once("include/auth.php"); 
	require_once("include/head.php");
?>
	<link rel='stylesheet' type='text/css' href='styles/global.css'>
	<style>
	  #main{
		  margin:10px 10px;
		  font: normal 14px/150% Arial, Helvetica, sans-serif; 
		  background: #F5F5F5; 
		  overflow: hidden; 
		  border: 1px solid #006699; 
		  -webkit-border-radius: 4px; 
		  -moz-border-radius: 4px; 
		  border-radius: 4px; 
	  }
	  table thead tr{
		  background-color:#2196F3;
		  color:#EEEEEE;
		  font-family:open sans;
		  font-size: 11px;
	  }
	  .header {
			margin:12px;
			color:#196D8A;
			font-size:20px;
			font-family:Calibri;
	   }
	</style>

<?php
	require_once('include/admin.php');
	require_once("include/nav.php"); 
?>

<div id="main">
	<h3 class="header">Feedback Collection on the Applications Received for Faculty Positions</h3>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>S.No.</th>
				<th>Application ID</th>
				<th>Name of The Applicant</th>
				<th>Post</th>
				<th>Online Application</th>
				<th>Research Statement</th>
				<th>Teaching Statement</th>
				<th>Resume</th>
				<th>Miscellaneous</th>
				<th>DBLP Link</th>
				<th>G. Scholar Link</th>
				<th>Feedback Link</th>
				<?php
					if(isadmin($_SESSION['SESS_MEMBER_ID']))
					{
						echo "<th>Delete</th>";
						echo "<th>Edit</th>";
					}
				?>
			</tr>
		</thead>
		<tbody>
		<?php
			$query = "SELECT * FROM application";
			$result = mysqli_query($connection, $query);
			if(!$result)
				die("Database query failed.");
			$sno=1;
			while($row = mysqli_fetch_assoc($result))
			{
				echo "<tr>
					 <td>{$sno}</td>
					 <td>{$row['app_id']}</td>
					 <td>{$row['app_name']}</td>
					 <td>{$row['post']}</td>";
                                if($row['application'])
			           echo "<td><a href='doc/index.php?id={$row['application']}'>Application</a></td>";
				else
                                   echo "<td>Application</td>";
                                if($row['research'])
                                   echo "<td><a href='doc/index.php?id={$row['research']}'>Research</a></td>";
				else
				   echo "<td>Research</td>";
                                if($row['teaching'])
	          	           echo  "<td><a href='doc/index.php?id={$row['teaching']}'>Teaching</a></td>";
				else
				   echo  "<td>Teaching</td>";
				if($row['resume'])
				   echo  "<td><a href='doc/index.php?id={$row['resume']}'>CV</a></td>";
                                else
				   echo  "<td>CV</td>";
                                if($row['extra'])
			           echo "<td><a href='doc/index.php?id={$row['extra']}'>Extra</a></td>";
                                else
                                   echo "<td>Extra</td>";
				if($row['dblp_link']=='')
					echo  "<td>DBLP</td>";
				else	
			        echo  "<td><a href='{$row['dblp_link']}'>DBLP<a></td>";
			    if($row['gs_link']=='')
			        echo  "<td>G. Scholar</td>";
			    else      
				    echo  "<td><a href='{$row['gs_link']}'>G. Scholar<a></td>";
				echo "<td><a href='feedback/index.php?id={$row['id']}'>Feedback</a></td>";
				if(isadmin($_SESSION['SESS_MEMBER_ID']))
				{
					echo "<td><a onclick='return d_warn()' href='del.php?id={$row['id']}' ><img src='images/fdel.png' height=20 width=20/></a></td>";
					echo "<td><a onclick='return warn()' href='add/edit.php?id={$row['id']}' ><img src='images/edt.png' height=20 width=20/></a></td>";
				}
				echo "</tr>";
				$sno++;
			}

			mysqli_free_result($result);
			mysqli_close($connection);
		?>
		</tbody>
	</table>
	<script type="text/javascript">
		function warn()
		{
			return confirm("Are you sure to edit this application?");
		}
		function d_warn()
		{
			return confirm("Are you sure to delete this application?");
		}
	</script>
</div>
<?php require_once("include/footer.php"); ?>
	
