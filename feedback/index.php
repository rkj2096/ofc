<?php
	//check login 
	require_once("../include/o_auth.php");
?>
<?php
	//handle form submission
	require_once("../include/connection.php");
	require_once('../include/expiry.php');
	
	$query  = "SELECT * ";
	$query .= "FROM feedback ";
	$query .= "WHERE faculty='{$_SESSION['SESS_MEMBER_ID']}' and applicant='{$_GET['id']}'";
	
	$result = mysqli_query($connection, $query);
	if(!$result)
		die('Failed to access databsase');
	$row = mysqli_fetch_assoc($result);
	if($row)
	{
		$fid = $row['id'];
		$teaching = $row['teaching'];
		$research = $row['research'];
		$overall  = $row['overall'];
		$comment  = $row['comment'];
		$recommend = $row['recommendation'];

		//echo "{$fid}:{$teaching}:{$research}:{$overall}:{$comment}";
		mysqli_free_result($result);
	}
	$added   = false;
	$changed = false;
	if(isset($_POST["submit"]))
	{ 
		if(!$timeout)
		{
			if(!$row)
			{   
				$teaching = intval($_POST["teaching"]);
				if(isset($_POST["research"]))
					$research = intval($_POST["research"]);
				else
					$research = 0;
				$overall = intval($_POST["overall"]);
				$recommend = intval($_POST["recommend"]);
				$comment = $_POST["comment"];

				$query  = "INSERT INTO feedback ( faculty, applicant, research, teaching, overall, recommendation, comment) ";
			        $query .= "VALUES ( '{$_SESSION['SESS_MEMBER_ID']}','{$_GET['id']}','{$research}','{$teaching}','{$overall}','{$recommend}','{$comment}')";

				$result = mysqli_query($connection, $query);
				if(!$result)
					die("Failed to add feedback into database.");
				//successfully added
				$added = true;
			}
			else
			{   
				$research = intval($_POST["research"]);
				if(isset($_POST["teaching"]))
					$teaching = intval($_POST["teaching"]);
				else
					$teaching = 0;
				$recommend = intval($_POST['recommend']);
				$overall = intval($_POST["overall"]);
				$comment = $_POST["comment"];
				
				$query  = "UPDATE feedback ";
				$query .= "SET teaching = '{$teaching}', ";
				$query .= "research = '{$research}', ";
				$query .= "overall = '{$overall}', ";
				$query .= "recommendation = '{$recommend}', ";
				$query .= "comment = '{$comment}' ";
				$query .= "WHERE id = '{$fid}'";
				
				$result = mysqli_query($connection, $query);
				if(!$result)
					die("Database query failed.");
				$changed = true;
			}
		}
	}
?>
<?php require_once("../include/head.php"); ?>
<link rel='stylesheet' type='text/css' href='../styles/global.css'>
	<style>
	 #main{
		  background-color:#F5F5F5;
		  margin:30px 25%;
		  padding:10px;
		 -webkit-border-radius: 4px; 
		 -moz-border-radius: 4px; 
		   border-radius: 4px; 
	  }
	 .header {
		  font-size:16px;
		  font-family:open sans;
	  }
	  .required{
		 color:red; 
	  }
	  .radio-group{
	  }
	  label{
	     margin-right:40px;
	  }
	  .form-group{
		 padding-bottom:20px;
	  }
	</style>
<?php 
	require_once('../include/admin.php');
	require_once("../include/o_nav.php");
?>
<div id="main">
<?php 
		if($added)
		{
			echo "<div class='panel panel-success'>
					<div class='panel-heading'>Successfully added</div>
				  </div>";
		}
		if($changed)
		{
			echo "<div class='panel panel-success'>
					<div class='panel-heading'>Saved</div>
				  </div>";
		}
		if($timeout)
		{
			echo "<div class='panel panel-danger'>
					<div class='panel-heading'>You cannot modify your feedback. Timeout Occurred.</div>
				  </div>";
		}
?>
<form id="form-horizontal" style="margin:20px 10px;" method="post" action="index.php?id=<?php echo $_GET['id'];?>" >
	<div class="form-group">
		<?php
			$query  = "Select app_id, app_name ";
			$query .= "From application ";
			$query .= "Where id = '{$_GET['id']}'";
			
			$result = mysqli_query($connection, $query);
			if(!$result)
				die("Failed to fetch application info");			

			$row_1  = mysqli_fetch_assoc($result);
			if($row_1)
			{
				echo "<h2 class='header'><b>Feedback for the Application:</b> <font color=red>[{$row_1['app_id']}] {$row_1['app_name']}</font></h2>";
			}	
		?>
	</div>
	<div class="form-group">
		<label for="teaching" class="fb-radio-group-label">Teaching Statement <span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Low to High"></span></label>
		<div class="radio-group" >
			<input value="1" type="radio" class="radio-group" name="teaching" aria-required="true" <?php if($row&& $teaching==1) echo 'checked'; ?> required><label for="teaching-0">1</label>
			<input value="2" type="radio" class="radio-group" name="teaching" aria-required="true" <?php if($row&& $teaching==2) echo 'checked'; ?>><label for="teaching-1">2</label>
			<input value="3" type="radio" class="radio-group" name="teaching" aria-required="true" <?php if($row&& $teaching==3) echo 'checked'; ?>><label for="teaching-2">3</label>
			<input value="4" type="radio" class="radio-group" name="teaching" aria-required="true" <?php if($row&& $teaching==4) echo 'checked'; ?>><label for="teaching-3">4</label>
			<input value="5" type="radio" class="radio-group" name="teaching" aria-required="true" <?php if($row&& $teaching==5) echo 'checked'; ?>><label for="teaching-4">5</label><br>
		</div>
	</div>
	<div class="form-group" >
		<label for="research" class="fb-radio-group-label">Research Statement <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Low to High"></span></label>
		<div class="radio-group" >
			<input value="1" type="radio" class="radio-group" name="research" aria-required="true" <?php if($row&& $research==1) echo 'checked'; ?>><label for="research-0">1</label>
			<input value="2" type="radio" class="radio-group" name="research" aria-required="true" <?php if($row&& $research==2) echo 'checked'; ?>><label for="research-1">2</label>
			<input value="3" type="radio" class="radio-group" name="research" aria-required="true" <?php if($row&& $research==3) echo 'checked'; ?>><label for="research-2">3</label>
			<input value="4" type="radio" class="radio-group" name="research" aria-required="true" <?php if($row&& $research==4) echo 'checked'; ?>><label for="research-3">4</label>
			<input value="5" type="radio" class="radio-group" name="research" aria-required="true" <?php if($row&& $research==5) echo 'checked'; ?>><label for="research-4">5</label><br>
		</div>
	</div>
	<div class="form-group" >
		<label for="overall" class="fb-radio-group-label">Overall <span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Low to High"></span></label>
		<div class="radio-group">
			<input value="1" type="radio" class="radio-group" name="overall" aria-required="true" <?php if($row&& $overall==1) echo 'checked'; ?> required><label for="overall-0">1</label>
			<input value="2" type="radio" class="radio-group" name="overall" aria-required="true" <?php if($row&& $overall==2) echo 'checked'; ?>><label for="overall-1">2</label>
			<input value="3" type="radio" class="radio-group" name="overall" aria-required="true" <?php if($row&& $overall==3) echo 'checked'; ?>><label for="overall-2">3</label>
			<input value="4" type="radio" class="radio-group" name="overall" aria-required="true" <?php if($row&& $overall==4) echo 'checked'; ?>><label for="overall-3">4</label>
			<input value="5" type="radio" class="radio-group" name="overall" aria-required="true" <?php if($row&& $overall==5) echo 'checked'; ?>><label for="overall-4">5</label><br>
		</div>
	</div>
	<div class="form-group" >
		<label for="recommend" class="fb-radio-group-label">Recommendation <span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Recommended / Not Recommended?"></span></label>
		<div class="radio-group">
			<input value="0" type="radio" class="radio-group" name="recommend" aria-required="true" <?php if($row&& $recommend==0) echo 'checked'; ?> required ><label for="recommend-0">Yes</label>
			<input value="1" type="radio" class="radio-group" name="recommend" aria-required="true" <?php if($row&& $recommend==1) echo 'checked'; ?>><label for="recommend-1">No</label>
			<input value="-1" type="radio" class="radio-group" name="recommend" aria-required="true" <?php if($row&& $recommend==-1) echo 'checked'; ?>><label for="recommend-2">Neutral</label><br>
		</div>
	</div>
	<div class="form-group">
		<label for="comment" class="fb-textarea-label">Comments  <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Not necessary"></span></label>
		<textarea type="textarea" placeholder="Comments..." rows="5" class="form-control" name="comment" id="textarea-comment" ><?php if($row) echo $comment; ?></textarea>
	</div>
	<div class="form-group">
		<button type="submit" class="button-input btn btn-success" name="submit" value="submit" style="success" id="submit">Submit</button>
	</div>
</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();   
	});
</script>
<?php require_once("../include/footer.php"); ?>     
