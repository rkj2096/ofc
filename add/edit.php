<?php
	require_once("../include/a_auth.php");
?>
<?php
	require_once("../include/connection.php");
	//prevoius data
	$query  = "Select * ";
	$query .= "From application ";
	$query .= "Where id = {$_GET['id']}";
	
	$result = mysqli_query($connection, $query);
	if(!$result)
		die("Failed to fetch database.");
	
	$row = mysqli_fetch_assoc($result);
	//app_info
	if($row)
	{
		$app_name  = $row['app_name'];
		$app_id    = $row['app_id'];
		$app_post  = $row['post'];
		$gs_link   = $row['gs_link'];
		$dblp_link = $row['dblp_link'];
		$application  = $row['application'];
		$teaching  = $row['teaching'];
		$research  = $row['research'];
		$resume    = $row['resume'];
		$extra     = $row['extra'];
	}
	mysqli_free_result($result);
	//submission
	$added = false;
	if(isset($_POST["submit"]))
	{   
		//delete previous entry
		$query  = "DELETE ";
		$query .= "FROM application ";
		$query .= "WHERE id = {$_GET['id']}";
		
		$result = mysqli_query($connection, $query);
		if(!$result||mysqli_affected_rows($connection)<1)
			die("Failed to make changes.");
		//add new entry
		$post = array(1=>"Assistant Professor",2=>"Assistant Professor(On Contract)",3=>"Visiting Professor",4=>"DST Inspire Faculty");
		$app_name = $_POST["app_name"];
		$app_id   = $_POST["app_id"];
		$app_post = $post[$_POST["app_post"]];
		
		if(isset($_POST["gs_link"]))
			$gs_link = $_POST["gs_link"];
		else 
			$gs_link = "#";
		if(isset($_POST["db_link"]))
			$dblp_link = $_POST["db_link"];
		else
			$dblp_link = "#";
		//echo "{$app_id}:{$app_name}:{$app_post}";
		
		$errmsg="";
		$files=array(-1,-1,-1,-1,-1);
		//1. Online application
		if(isset($_FILES['application']))
		{
			if($_FILES['application']['error']==0)
			{   
				//store file
				$name = mysqli_real_escape_string($connection,$_FILES['application']['name']);
				$mime = mysqli_real_escape_string($connection,$_FILES['application']['type']);
				$data = mysqli_real_escape_string($connection,file_get_contents($_FILES['application']['tmp_name']));
				$size = intval($_FILES['application']['size']);
				
				$query  = "INSERT INTO file( name, mime, size, data)";
				$query .= "VALUES ('{$name}','{$mime}','{$size}','{$data}')";
				
				$result = mysqli_query($connection,$query);
				
				if(!$result)
				die("Failed to upload application.");
				
			    $files[0]= intval(mysqli_insert_id($connection));
			}
			else
				$errmsg.="Something wrong with application file.";
		}
		
		//2.Research statement
		if(isset($_FILES["research"]))
		{
			if($_FILES["research"]["error"]==0)
			{   
				//store file
				$name = mysqli_real_escape_string($connection,$_FILES["research"]["name"]);
				$mime = mysqli_real_escape_string($connection,$_FILES["research"]["type"]);
				$data = mysqli_real_escape_string($connection,file_get_contents($_FILES["research"]["tmp_name"]));
				$size = intval($_FILES["research"]["size"]);
				
				$query  = "INSERT INTO file( name, mime, size, data)";
				$query .= "VALUES ('{$name}','{$mime}','{$size}','{$data}')";
				
				$result = mysqli_query($connection,$query);
				
				if(!$result)
				die("Failed to upload research statement.");
				
				//fetch file id
				$files[1]= intval(mysqli_insert_id($connection));
			}
			else
				$errmsg.="Something wrong with research statement file.";
		}
		//3.Teaching Statement
		if(isset($_FILES["teaching"]))
		{
			if($_FILES["teaching"]["error"]==0)
			{   
				//store file
				$name = mysqli_real_escape_string($connection,$_FILES["teaching"]["name"]);
				$mime = mysqli_real_escape_string($connection,$_FILES["teaching"]["type"]);
				$data = mysqli_real_escape_string($connection,file_get_contents($_FILES["teaching"]["tmp_name"]));
				$size = intval($_FILES["teaching"]["size"]);
				
				$query  = "INSERT INTO file( name, mime, size, data)";
				$query .= "VALUES ('{$name}','{$mime}','{$size}','{$data}')";
				
				$result = mysqli_query($connection,$query);
				
				if(!$result)
				die("Failed to upload teaching statement.");
				
				//fetch file id
				$files[2]= intval(mysqli_insert_id($connection));
			}
			else
				$errmsg.="Something wrong with teaching statement file.";
		}
		//4.Resume
		if(isset($_FILES["resume"]))
		{
			if($_FILES["resume"]["error"]==0)
			{   
				//store file
				$name = mysqli_real_escape_string($connection,$_FILES["resume"]["name"]);
				$mime = mysqli_real_escape_string($connection,$_FILES["resume"]["type"]);
				$data = mysqli_real_escape_string($connection,file_get_contents($_FILES["resume"]["tmp_name"]));
				$size = intval($_FILES["resume"]["size"]);
				
				$query  = "INSERT INTO file( name, mime, size, data)";
				$query .= "VALUES ('{$name}','{$mime}','{$size}','{$data}')";
				
				$result = mysqli_query($connection,$query);
				
				if(!$result)
				die("Failed to upload resume.");
				
				//fetch file id
				$files[3]= intval(mysqli_insert_id($connection));
			}
			else
				$errmsg.="Something wrong with resume file.";
		}
		//5.Extra
		if(isset($_FILES["extra"]))
		{
			if($_FILES["extra"]["error"]==0)
			{   
				//store file
				$name = mysqli_real_escape_string($connection,$_FILES["extra"]["name"]);
				$mime = mysqli_real_escape_string($connection,$_FILES["extra"]["type"]);
				$data = mysqli_real_escape_string($connection,file_get_contents($_FILES["extra"]["tmp_name"]));
				$size = intval($_FILES["extra"]["size"]);
				
				$query  = "INSERT INTO file( name, mime, size, data)";
				$query .= "VALUES ('{$name}','{$mime}','{$size}','{$data}')";
				
				$result = mysqli_query($connection,$query);
				
				if(!$result)
				die("Failed to upload extra file.");
				
				//fetch file id
				$files[4]= intval(mysqli_insert_id($connection));
			}
			else
				$errmsg.="Something wrong with miscellaneous file.";
		}
		$query = "INSERT INTO application(id, app_name, app_id, post, gs_link, dblp_link";
		$value = "VALUES ('{$_GET['id']}','{$app_name}','{$app_id}','{$app_post}','{$gs_link}','{$dblp_link}'";
		
		if($files[0]!=-1)
		{
			$query .= ", application";
			$value .= ",'{$files[0]}'";
		}
		if($files[1]!=-1)
		{
			$query .= ", research";
			$value .= ",'{$files[1]}'";
		}
		if($files[2]!=-1)
		{
			$query .= ", teaching";
			$value .= ",'{$files[2]}'";
		}
		if($files[3]!=-1)
		{
			$query .= ", resume";
			$value .= ",'{$files[3]}'";
		}
		if($files[4]!=-1)
		{
			$query .= ", extra";
			$value .= ",'{$files[4]}'";
		}
		$query .= ")";
		$value .= ")";
		
		$query .=$value;
		
		$result = mysqli_query($connection, $query);
		if(!$result)
			die("Failed to add application.");
		//mysqli_close($connection);
		$added = true;
	}
?>
<?php 
	require_once("../include/head.php"); 
?>
	<link rel='stylesheet' type='text/css' href='../styles/global.css'>
	<style>
		.main{
			margin:30px 25%;
			padding:10px;
			background-color:#F5F5F5;
			//-webkit-box-shadow: 9px 15px 61px -21px rgba(135,125,135,1);
			//-moz-box-shadow: 9px 15px 61px -21px rgba(135,125,135,1);
			//box-shadow: 9px 15px 61px -21px rgba(135,125,135,1);
			border: 0px solid #006699; 
			-webkit-border-radius: 4px; 
			-moz-border-radius: 4px; 
		    border-radius: 4px; 
		}
		form{
			margin:10px 20%;
		}
		.header {
			margin:20px 18%;
			color:#5bc0de;
			font-size:23px;
			font-family:open sans;
		 }
	</style>
<?php 
	require_once('../include/admin.php');
	require_once("../include/o_nav.php"); 
?>
<div class="main">
<?php
		if($added)
		{
			echo "<div class = 'panel panel-success'>
					<div class='panel-heading'>Successfully added</div>
				  </div>";
		}
?>
<h2 class="header">Add Application</h2>
<form class="form-horizontal" method="post" action="edit.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
	<div class="form-group">
		<label for="app_name" class="cols-sm-2 control-label">Applicant Name</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="app_name" value="<?php if($row) echo $app_name; ?>" required/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="app_id" class="cols-sm-2 control-label">Applicant ID</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="app_id" value="<?php if($row) echo $app_id; ?>" required/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="app_post" class="cols-sm-2 control-label">Post</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
				<select name="app_post" class="form-control">
				   <option value="1" <?php if($row&& $app_post=="Assistant Professor") echo 'selected'; ?>>Assistant Professor</option>
				   <option value="2" <?php if($row&& $app_post=="Assistant Professor(On Contract)") echo 'selected'; ?>>Assistant Professor(On Contract)</option>
				   <option value="3" <?php if($row&& $app_post=="Visiting Professor") echo 'selected'; ?>>Visiting Professor</option>
				   <option value="4" <?php if($row&& $app_post=="DST Inspire Faculty") echo 'selected'; ?>>DST Inspire Faculty</option>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="gs_link" class="cols-sm-2 control-label">Google Scholar Link</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-external-link" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="gs_link" value="<?php if($row) echo $gs_link; ?>" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="db_link" class="cols-sm-2 control-label">DBLP Link</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-external-link" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="db_link" value="<?php if($row) echo $dblp_link; ?>" />
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="application" class="cols-sm-2 control-label">Online Application</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<input name="application" class="input-file btn btn-default" type="file"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="research" class="cols-sm-2 control-label">Research Statement</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<input name="research" class="input-file btn btn-default" type="file"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="teaching" class="cols-sm-2 control-label">Teaching Statement</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<input name="teaching" class="input-file btn btn-default" type="file"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="resume" class="cols-sm-2 control-label">Resume</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<input name="resume" class="input-file btn btn-default" type="file"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="extra" class="cols-sm-2 control-label">Miscellaneous</label>
		<div class="cols-sm-6">
			<div class="input-group">
				<input name="extra" class="input-file btn btn-default" type="file"/>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="button-input btn btn-success" name="submit" value="submit" style="success" id="submit">Submit</button>
	</div>
</form>
</div>
<?php require_once("../include/footer.php"); ?>
