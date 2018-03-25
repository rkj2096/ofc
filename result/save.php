<?php
	require_once("../include/a_auth.php");
?>
<?php
	if(isset($_POST['submit']))
	{
		$now = new DateTime("now");
		require_once('../PHPExcel-1.8/Classes/PHPExcel.php');
		//create PHPExcel object
		$objPHPExcel = new PHPExcel;
		// set default font
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
		// set default font size
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
		//set reply header
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"OFC_feedback_collected_{$now->format('d-m-Y')}.xls\"");
		header("Cache-Control: max-age=0");
		// create the writer
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
		// writer already created the first sheet for us, let's get it
		$objSheet = $objPHPExcel->getActiveSheet();
		// rename the sheet
		$objSheet->setTitle('FeedBack Result');
		//set header font 
		$objSheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(10);
		// write header
		$objSheet->getCell('A1')->setValue('S.No.');
		$objSheet->getCell('B1')->setValue('Applicant ID');
		$objSheet->getCell('C1')->setValue('Applicant Name');
		$objSheet->getCell('D1')->setValue('Teaching Aggregate');
		$objSheet->getCell('E1')->setValue('Research Aggregate');
		$objSheet->getCell('F1')->setValue('Overall Aggregate');
	}
?>
<?php
	  require_once('../include/connection.php');
	  $query  = "select a.id as id,a.app_id,a.app_name,sum(f.research) as rsh,sum(f.teaching) as tch,sum(f.overall) as ovl ";
	  $query .= "from feedback f,application a ";
	  $query .= "where f.applicant = a.id ";
	  $query .= "group by f.applicant";
	  
	  $result = mysqli_query($connection, $query);
	  
	  if(!$result)
		  die("Failed in fetching result.");
	  $sno=2;
	  while($row = mysqli_fetch_assoc($result))
	  {
		  if(isset($_POST['submit']))
		  {
				$objSheet->getCell('A'.$sno)->setValue($sno-1);
				$objSheet->getCell('B'.$sno)->setValue($row['app_id']);
				$objSheet->getCell('C'.$sno)->setValue($row['app_name']);
				$objSheet->getCell('D'.$sno)->setValue($row['tch']);
				$objSheet->getCell('E'.$sno)->setValue($row['rsh']);
				$objSheet->getCell('F'.$sno)->setValue($row['ovl']);
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
			//ob_clean();
			$objWriter->save("php://output");
	  }
	  mysqli_free_result($result);
?>
