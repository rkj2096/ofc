<?php
	require_once("../include/a_auth.php"); //only for admin
?>
<?php
	function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
        }
?>
<?php
	require_once("../include/connection.php");
	$flag   = false;
	$added  = false;
	$errmsg = "";
	if(isset($_POST['submit']))
	{
		$name  = $_POST['name'];
		$email = $_POST['email'];
		$password = generateRandomString(8);
		$admin = intval($_POST['admin']);
		
		$query  ="SELECT * ";
		$query .="FROM faculty ";
		$query .="WHERE email = '{$email}'";
		
		$result = mysqli_query($connection,$query);
		if(!$result)
			die("Database query failed.");
		$member=mysqli_fetch_assoc($result);
		
		if(!$member)
		{   
		    mysqli_free_result($result);

			$query  = "INSERT INTO faculty ( fname, email, password, admin) ";
			$query .= "VALUES ( '{$name}','{$email}','{$password}','{$admin}')";
			
			$result = mysqli_query($connection, $query);
			if(!$result)
				die("Failed to add to database.");
			
			$added   = true;

			//mysqli_close($connection);
		}
		else
		{
			$errmsg = "This email is already in use.";
			$flag = true;
		}
	}
?>
<?php
		//if added send mail
		if($added)
		{
			require_once('../mail_lib/PHPMailerAutoload.php');
			
$message  = "Dear Sir/Madam,</p><p>Your account has been created in <a href='http://www.ofc.iitr.ac.in'>Online Feedback Collection (OFC)</a> system for collecting your feedback on the applications for faculty position received by the Faculty Search Committee (FSC) of the Department. Your account details are as follows:\n
<p>Username: {$email}<br>
Password: {$password}</p>
<p>NB: This is an automatically generated email by the OFC server.</p>";

						
			$subject  = "[OFC] Your Account Details";
			$to       = $email;
			
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
		if($added)
		{
			echo "<div class='panel panel-success'>
					<div class='panel-heading'>Successfully added.</div>
				 </div>";
		}
		if($flag)
		{
			echo "<div class='panel panel-danger'>
					<div class='panel-heading'>Failed to add.{$errmsg}</div>
				 </div>";
		}
	?>
	<h3 class="header">Add Faculty</h3>
	<form class="form-horizontal" method="post" action="index.php" enctype="multipart/form-data">
		<div class="form-group">
			<label for="name" class="cols-sm-2 control-label">Full name<span class="required">*</span></label>
			<div class="cols-sm-6">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
					<input type="text" class="form-control" name="name" placeholder="full name" required/>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="cols-sm-2 control-label">Email<span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="Used for login and Sending message."></span></label>
			<div class="cols-sm-6">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
					<input type="email" class="form-control" name="email" placeholder="email" required/>
				</div>
			</div>
		</div>	
		<?php
		/*<div class="form-group">
			<label for="password" class="cols-sm-2 control-label">Password<span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="length(6-15)"></span></label>
			<div class="cols-sm-6">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
					<input type="password" class="form-control" name="password" placeholder="password" value=<?php echo generateRandomString(8)?> required/>
				</div>
			</div>
		</div>*/
		?>
		<div class="form-group">
			<label for="admin" class="fb-radio-group-label">Admin<span class="required">*</span> <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="right" title="All Privileges"></span></label>
			<div class="radio-group" >
				<input value="1" type="radio" class="radio-group" name="admin" aria-required="true" required><label for="admin-1">YES</label>
				<input value="0" type="radio" class="radio-group" name="admin" aria-required="true"><label for="admin-0">NO</label>
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
