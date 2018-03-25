<?php
	require_once("../include/a_auth.php");
?>
<?php 
	require_once("../include/connection.php");
	
	$now = new DateTime("now");
	$name = "OFC_Usage_log_{$now->format('d-m-Y')}";
?>
<?php
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
		$objSheet->setTitle('Uses_Log');
		//set header font 
		$objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);
		// write header
		$objSheet->getCell('A1')->setValue('S.No.');
		$objSheet->getCell('B1')->setValue('Username');
		$objSheet->getCell('C1')->setValue('IP Address');
		$objSheet->getCell('D1')->setValue('Login time');
?>
<?php
	  $query  = "Select * From loginfo";	  
	  $result = mysqli_query($connection, $query);
	  
	  if(!$result)
		  die("Failed in fetching result.");
	  $sno=2;
	  while($row1 = mysqli_fetch_assoc($result))
	  { 
		$objSheet->getCell('A'.$sno)->setValue($sno-1);
		$objSheet->getCell('B'.$sno)->setValue($row1['username']);
		$objSheet->getCell('C'.$sno)->setValue($row1['ipaddr']);
		$objSheet->getCell('D'.$sno)->setValue($row1['logtime']);
		$sno++;
	  }
	 
	  $objSheet->getColumnDimension('A')->setAutoSize(true);
	  $objSheet->getColumnDimension('B')->setAutoSize(true);
	  $objSheet->getColumnDimension('C')->setAutoSize(true);
	  $objSheet->getColumnDimension('D')->setAutoSize(true);
	  $objWriter->save("php://output");
	  mysqli_free_result($result);
?>
