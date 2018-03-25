<?php
	require_once("../include/o_auth.php");
?>
<?php
	if(isset($_POST['submit']))
	{
		require_once('../MPDF57/mpdf.php');
		date_default_timezone_set('Asia/Kolkata');
		$now = new DateTime("now");
		$header .= "<div class='page'>";
		$header .= "<div class='head'>";
		$header .= "<h3>Indian Institute of Technology Roorkee</h3>";
		$header .= "<h3>Department of Computer Science and Engineering</h3>";
		$header .= "</div>";
		$header .= "<div class='content'>";
		$header .= "<p><b>Date and Time: {$now->format('d-M-Y, g:i:s A')}</b></p>";
		$header .= "<p><b>Dear Dr./Prof. {$_SESSION['SESS_NAME']},</b></p>";
		$header .= "<p>You have submitted the following feedback on the applications for faculty positions in Dept. of CSE, IIT Roorkee.</p>";
		$header .= "</div>";
		$header .= "<table>";
		$header .= "<thead>";
		$header .= "<tr>";
		$header .= "<th>S.No.</th>";
		$header .= "<th align='left'>ID</th>";
		$header .= "<th align='left'>Name</th>";
		$header .= "<th>Score*<br>(T-R-O)</th>";
		$header .= "<th>Reco</th>";
		$header .= "<th align='left'>Comment</th>";
		$header .= "</tr>";
		$header .= "</thead>";
		$header .= "<tbody>";
	}
?>
<?php
	  require_once('../include/connection.php');
	  $query  = "Select a.id as fid, a.app_id, a.app_name, f.research as rsh, f.teaching as tch, f.overall as ovl, f.recommendation as rc, f.comment as cmt ";
	  $query .= "From feedback f ";
	  $query .= "Inner join application a ";
	  $query .= "On f.applicant = a.id ";
	  $query .= "Where  f.faculty = '{$_SESSION['SESS_MEMBER_ID']}'";
	  
	  $result = mysqli_query($connection, $query);
	  
	  if(!$result)
		  die("Failed in fetching result.");
	  $sno=1;
	  $data = '';
	  while($row = mysqli_fetch_assoc($result))
	  {
                if($row['rc']==1)
			$rcmd = 'No';
		else if($row['rc']==0)
			$rcmd = 'Yes';
		else
			$rcmd = 'Neutral';
		 $comment = wordwrap($row['cmt'],40,'<br>');
		 $data .= "<tr>";
		 $data .= "<td>{$sno}</td>";
		 $data .= "<td>{$row['app_id']}</td>";
		 $data .= "<td>{$row['app_name']}</td>";
		 $data .= "<td>{$row['tch']}-{$row['rsh']}-{$row['ovl']}</td>";
		 $data .= "<td>{$rcmd}</td>";
		 $data .= "<td>{$comment}</td>";
		 $data .= "</tr>";
		 //$data .= "<br>";
		 $sno++;
		 //echo $comment;
	  }
	  if(isset($_POST['submit']))
	  { 
		//echo $data;
		$header .= $data;
		$header .= "</tbody>";
		$header .= "</table>";
		$header .= "<p>*T: Teaching Statement, R: Research Statement, O: Overall</p>";
		$header .= "<div class='foot'>";
		$header .= "<p>This is OFC system generated receipt on acknowledgement of your feedback. Thank you for your time to give us the feedback.
</p>";
		$header .= "<p>Regards.<br>
Convener, FSC,<br>
Department of Computer Science and Engineering,<br>
Indian Institute of Technology Roorkee,<br>
Roorkee, India
</p>";
		$header .= "</div>";
		$header .= "</div>";
		
		$mpdf = new mPDF();
		$style_data = file_get_contents("page.css");
		
		// Make it DOUBLE SIDED document with 4mm bleed
		$mpdf->mirrorMargins = 1;
		$mpdf->bleedMargin = 4;
		
		
		// Set left to right text
		$mpdf->SetDirectionality('ltr');
		
		// Write the stylesheet
                $mpdf->WriteHTML($style_data, 1); 
		
		// Write the main text 
		$mpdf->WriteHTML($header, 2);
		
		// Set the metadata
		$mpdf->SetTitle("My Feedback");
		$mpdf->SetAuthor("OFC");
		//$mpdf->SetCreator("");
		//$mpdf->SetSubject("");
		//$mpdf->SetKeywords("");
		$fname = str_replace(' ','_',$_SESSION['SESS_NAME']);
		$name  = "{$fname}_OFC_Feedback_{$now->format('d-m-Y')}.pdf";
		// Generate the PDF file
		$mpdf->Output($name,'F');
	  }
	  mysqli_free_result($result);
?>
<?php
		//if added send mail
		if(isset($_POST['submit']))
		{
			require_once('../mail_lib/PHPMailerAutoload.php');
			
$message  = "Dear Sir/Madam, \n\nPlease find attached the PDF file as the receipt on acknowledgement of your feedback on the applications for faculty positions in Dept. of CSE, IIT Roorkee. Thank you for your time.\n
Regards.
Convener, FSC,
Department of Computer Science and Engineering,
Indian Institute of Technology Roorkee\n
NB: This is an automatically generated email by OFC server.";
						
			$subject  = "[OFC] Feedback Collected from Dr./Prof. {$_SESSION['SESS_NAME']}";
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
			//$mail->addCC('sudiproy.fcs@iitr.ac.in');
			$mail->addCC('fsc.cse@iitr.ac.in');
			$mail->addAttachment($name);
			$mail->Subject = $subject;
			$mail->Body = $message;
			
			if (!$mail->send())
		        	echo "Mailer Error: " . $mail->ErrorInfo;
		}
		header('location: ../res/');
?>
