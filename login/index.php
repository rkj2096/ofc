<?php 
    require_once("../include/connection.php");
	require_once("../include/utility.php"); 
	if(isset($_POST["submit"]))
	{
		session_start();
		$email=$_POST["email"];
		$password=$_POST["password"];
		
		$query ="SELECT * ";
		$query.="FROM faculty ";
		$query.="WHERE email='{$email}' and password='{$password}'"; 
		//echo $query."<br>";
		$result=mysqli_query($connection,$query);
		if(!$result)
			die("Database query failed.");
		$member=mysqli_fetch_assoc($result);
		if($member)
		{
			session_regenerate_id();
			$_SESSION['SESS_MEMBER_ID'] = $member['email'];
			$_SESSION['SESS_NAME'] = $member['fname'];
			session_write_close();
			
			$now = new DateTime("now");
			
			$username = $email;
			$logtime  = $now->format('Y-m-d H:i:s');
			$ipaddr   = getIP();
			
			$query  = "INSERT INTO loginfo( ipaddr,username,logtime) ";
			$query .= "VALUES ('{$ipaddr}','{$username}','{$logtime}')";
			
			$result = mysqli_query($connection , $query);
			
			if(!$result)
				die("Database query failed");
				
			redirect_to("../");
			exit();
		}
		else
		{   
			$message="Username or Password is not correct.";
			$errmsg=array();
			$errmsg[]=$message;
			$_SESSION['ERRMSG_ARR'] = $errmsg;
			session_write_close();
		}

	}
	else 
		$message="";
	//print_r($_POST);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<!-- Optional Bootstrap theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
 		<style>
		        div#main {
				display: block;
				margin: 8% auto;
				width:385px;
				height:440px;
				background-color:#F5F5F5;
				border-radius: 10px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				border: 1px outset #fcffff;
				overflow: hidden;
				vertical-align:top;
			}
			.logo img{
				display: block;
				margin-top: 2%;
				margin-left: auto;
				margin-right: auto
			}
			.logo{
				text-align: center;
				color:#009688;
				font-family: open sans;
			}
			.header{
				font-size:27px;
			}
			form {
				margin:0px 22px;
			}
       			button{
				width: 367px; 
				margin-left: auto;
				margin-right:auto;
			}
			#footer{
				background-color:#333;
				height:25px;
				display:block;
				overflow:hidden;
				position:fixed;
				bottom:0;
				width: 100%;
				color:#aaa;
				padding:5px;
				text-align:center;
			}
		</style>
	</head>
	<body>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<div id="main">
			<div class="logo">
				<img src="../images/iitr.png" width=90 height=90 />
				<h3 class="header">Online Feedback Collection</h3>
       				<p style="font-size:16px">Department of Computer Science and Engineering</p>
				<p style="color:red; font-size:12px;"><?php echo $message; ?></p>
			</div>
			<form class="form-horizontal" method="post" action="index.php"">
				<div class="form-group">
					<label for="app_name" class="cols-sm-2 control-label">Email</label>
					<div class="cols-sm-6">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="email" placeholder="email" required/>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="app_id" class="cols-sm-2 control-label">Password</label>
					<div class="cols-sm-6">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
							<input type="password" class="form-control" name="password" placeholder="password" required/>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="button-input btn btn-primary" name="submit" value="submit" style="success" id="submit">Login</button>
				</div>
			</form>
		</div>
	</body>
</html>
<?php require_once("../include/footer.php"); ?>
