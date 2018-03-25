<?php
		require_once('../include/expiry.php');
		date_default_timezone_set('Asia/Kolkata');
		$now = new DateTime("now");
		echo "</head>";
		echo "<body>";
		echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>";
		echo "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
		echo "<div id='header'>";
		echo "<div class='logo'><img src='../images/iitr.png' height=100 width=100/> <font color=black>Department of Computer Science and Engineering, IIT Roorkee</font></div><br><br>";
		echo "<div class='side' style='float:right; font-size:15px; margin-right:30px;'>";
		echo "<img src='../images/clock.jpg' height=25 width=20/> <font color=black> Current time in OFC server:</font> <font color=black style=BACKGROUND-COLOR:yellow>{$now->format('d-M-Y, g:i:s A')}</font><br>";
		if(isadmin($_SESSION['SESS_MEMBER_ID']))
			echo "<img src='../images/timer.png' height=20 width=20/> <font color=black>Time Remaining:</font> <a href='../time'><font color=#ffffff style=BACKGROUND-COLOR:#ff0000>&nbsp;{$time} </font></a> <br>";
		else
			echo "<img src='../images/timer.png' height=20 width=20/> <font color=black>Time Remaining:</font> <font color=#ffffff style=BACKGROUND-COLOR:#ff0000>&nbsp;{$time} </font> <br>";
		echo "<img src='../images/user.png' width=20 height=20/> <font color=black><b>{$_SESSION['SESS_NAME']}</b></font> <font color=black>|</font> <a href='../cp/'>Change Password</a> <font color=black>|</font> <a href='../include/o_logout.php'>Logout</a>";
		echo "</div>";
		echo "<p>Online Feedback Collection (OFC)</p>";
		echo "</div>";
		echo "<div class='navtop'>";
		echo "<ul>";
		echo "<li><a href='../'>Applications</a></li>";
		echo "<li><a href='../res/'>Your Feedback</a></li>";
		//only for admin
		if(isadmin($_SESSION['SESS_MEMBER_ID']))
		{
			echo "<li><a href='../fadd/'>Add Faculty</a></li>
				  <li><a href='../add/'>Add Application</a></li>
				  <li><a href='../result/'>Feedback Collected</a></li>
				  <li><a href='../log/log.php'>Usage Log</a></li>";
		}
		//end
		
		//echo "<li><a href='#'>ABOUT</a></li>";
		//echo "<li><a href='#'>CONTACT</a></li>";
		echo "</ul>";
		echo "</div>";
?>
