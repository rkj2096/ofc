<?php
	require_once("../include/o_auth.php");
?>
<?php
	require_once("../include/connection.php");
	$changed = false;
	$flag   = false;
	if(isset($_POST['submit']))
	{	
		//check current password
		$query  = "select * from faculty where email = '{$_SESSION['SESS_MEMBER_ID']}'";
		
		$result = mysqli_query($connection, $query);
		if(!$result)
			die("Failed to fetch database");
		
		$row = mysqli_fetch_assoc($result);
		$errmsg = "";
		
		if($row['password']!=$_POST['cp'])
		{ 
			$flag   = true;
			$errmsg = "Current password is not correct.";
		}
		//check new passwords
		if($_POST['np']!=$_POST['npa'])
		{
			$flag   = true;
			$errmsg.= "New passwords are not matching.";
		}
		mysqli_free_result($result);
		
		//update password
		if(!$flag)
		{
			$query  = "UPDATE faculty SET password = '{$_POST['np']}' WHERE email = '{$_SESSION['SESS_MEMBER_ID']}'";
			
			$result = mysqli_query($connection, $query);
			if(!$result)
				die("Failed to change password.");
			$changed = true;
			//mysqli_close($connection);
		}
	}
?>
<?php
		//if added send mail
		if($changed)
		{
			require_once('../mail_lib/PHPMailerAutoload.php');
			
$message  = "Dear Sir/Madam,</p><p>Your <a href='http://www.ofc.iitr.ac.in'>Online Feedback Collection (OFC)</a> account password has been changed. Your account details are as follows:\n
<p>Username: {$_SESSION['SESS_MEMBER_ID']}<br>
Password: {$_POST['np']}</p>
<p>NB: This is an automatically generated email by the OFC server.</p>";
						
			$subject  = "[OFC] Your Account Details - Password Changed";
			$to       = $_SESSION['SESS_MEMBER_ID'];
			
			$email    = '';
			$password = '';
			
			$mail = new PHPMailer;
			
			$mail->isSMTP();
			$mail->Host = '192.168.180.11';
			$mail->Port = 587;
			//$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;
			$mail->Username = $email;
			$mail->Password = $password;
			
			$mail->From = $email;
			$mail->FromName = 'OFC, FSC-CSE';
			$mail->addAddress($to);
			//$mail->addCC();
			$mail->Subject = $subject;
			$mail->Body = $message;
			$mail->isHTML(true);
			if (!$mail->send())
			echo "Mailer Error: " . $mail->ErrorInfo;
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
			if($flag)
			{
				echo "<div class='panel panel-danger'>
						<div class='panel-heading'>{$errmsg}</div>
					 </div>";
			}
		?>
		<?php
			if($changed)
			{
				echo "<div class='panel panel-success'>
						<div class='panel-heading'>Successfully changed</div>
					 </div>";
			}
		?>
		<h3 class="header">Change Password</h3>
	    <form class="form-horizontal" method="post" action="index.php" enctype="multipart/form-data">
			<div class="form-group">
				<label for="cp" class="cols-sm-2 control-label">Current Password</label>
				<div class="cols-sm-6">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" class="form-control" name="cp" placeholder="" required/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="np" class="cols-sm-2 control-label">New Password <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="length(6-15)"></span></label>
				<div class="cols-sm-6">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" class="form-control" name="np" placeholder="" required/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="npa" class="cols-sm-2 control-label">Re-enter New Password <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="length(6-15)"></span></label>
				<div class="cols-sm-6">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
						<input type="password" class="form-control" name="npa" placeholder="" required/>
					</div>
				</div>
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
