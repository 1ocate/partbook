<?php

session_start();
// if direct open exit
if (!isset($_SESSION['currenct_require'])){
    exit();
}

$require_id = $_SESSION['currenct_require'];
unset($_SESSION['currenct_require']);

require_once 'config.php';
require_once 'dbh.php';
require "PHPExcel/Classes/PHPExcel.php";
require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php"; 

$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Govinda")
                             ->setLastModifiedBy("Govinda")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");

// Add some data
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Part Name')
            ->setCellValue('C1', 'Machine')
            ->setCellValue('D1', 'Quality')
            ->setCellValue('E1', 'Qty')
            ->setCellValue('F1', 'Price');

$query = $conn->query("SELECT *  FROM require_part_line  WHERE fk_require = '.$require_id.'");

$rowCount="2";



while ($row = $query->fetch_assoc()) {

    //quality 
    switch ($row['quality']) {
    case 0:
        $quality = "Original";
        break;
    case 1:
        $quality = "Kawe";
        break;
    case 2:
        $quality = "Both";
        break;
    }

    // line number
    $cell_num = $rowCount-1;
    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $cell_num);
    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['partname']);
    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['machine']);
    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $quality);
    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['qty']);

    $rowCount++;
}


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('UserList');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment;filename="part_list.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>