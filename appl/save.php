<?php
	require_once("../include/a_auth.php");
?>
<?php 
	require_once("../include/connection.php");
	$query  = "select * from application where id = '{$_GET['id']}'";
	$result = mysqli_query($connection, $query);
	if(!$result)
		die("Failed to fetch applicant info.");
	$row = mysqli_fetch_assoc($result);
	
	$app_id   = $row['app_id'];
	$app_name = $row['app_name'];
	
	$name = $app_id."_".$app_name;
	mysqli_free_result($result);
?>
<?php
	if(isset($_POST['submit']))
	{
		require_once('../PHPExcel-1.8/Classes/PHPExcel.php');
		//create PHPExcel object
		$objPHPExcel = new PHPExcel;
		// set default font
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
		// set default font size
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		//set reply header
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment;filename="'.$name.'.xls"');
		header("Cache-Control: max-age=0");
		// create the writer
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
		// writer already created the first sheet for us, let's get it
		$objSheet = $objPHPExcel->getActiveSheet();
		// rename the sheet
		$objSheet->setTitle($app_id.'_Result');
		//set header font 
		$objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);
		// write header
		$objSheet->getCell('A1')->setValue('S.No.');
		$objSheet->getCell('B1')->setValue('Faculty Name');
		$objSheet->getCell('C1')->setValue('Faculty Email');
		$objSheet->getCell('D1')->setValue('Teaching Statement');
		$objSheet->getCell('E1')->setValue('Research Statement');
		$objSheet->getCell('F1')->setValue('Overall');
		$objSheet->getCell('G1')->setValue('Recommendation');
		$objSheet->getCell('H1')->setValue('Comment');
	}
?>
<?php
	  $query  = "Select fname,email,research as rsh,teaching as tch,overall as ovl,comment,recommendation as rcd ";
	  $query .= "From feedback b ";
	  $query .= "Inner join faculty f ";
	  $query .= "On b.faculty = f.email ";
	  $query .= "Where  b.applicant = '{$_GET['id']}'";
	  
	  $result = mysqli_query($connection, $query);
	  
	  if(!$result)
		  die("Failed in fetching result.");
	  $sno=2;
	  while($row1 = mysqli_fetch_assoc($result))
	  { 
		 if($row1['rcd']==1)
                         $rcmd='No';
                  else if($row1['rcd']==0)
                         $rcmd='Yes';
                  else
                         $rcmd='Neutral';
		  $cmt = wordwrap($row1['comment'],70,"<br/>\n");
		  if(isset($_POST['submit']))
		  {
				$objSheet->getCell('A'.$sno)->setValue($sno-1);
				$objSheet->getCell('B'.$sno)->setValue($row1['fname']);
				$objSheet->getCell('C'.$sno)->setValue($row1['email']);
				$objSheet->getCell('D'.$sno)->setValue($row1['tch']);
				$objSheet->getCell('E'.$sno)->setValue($row1['rsh']);
				$objSheet->getCell('F'.$sno)->setValue($row1['ovl']);
				$objSheet->getCell('G'.$sno)->setValue($rcmd);
				$objSheet->getCell('H'.$sno)->setValue($cmt);
		  }
		  $sno++;
	  }
	  if(isset($_POST['submit']))
	  {
			$objSheet->getColumnDimension('A')->setAutoSize(true);
			$objSheet->getColumnDimension('B')->setAutoSize(true);
			$objSheet->getColumnDimension('C')->setAutoSize(true);
			$objSheet->getColumnDimension('D')->setAutoSize(true);
			$objSheet->getColumnDimension('E')->setAutoSize(true);
			$objSheet->getColumnDimension('F')->setAutoSize(true);
			$objSheet->getColumnDimension('G')->setAutoSize(true);
			$objSheet->getColumnDimension('H')->setAutoSize(true);
			$objWriter->save("php://output");
	  }
	  mysqli_free_result($result);
?>
