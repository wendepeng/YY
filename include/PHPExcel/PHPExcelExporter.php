<?php
require_once dirname(__FILE__) . '/PHPExcel.php';
class PHPExcelExporter{
	public static function saveArray2Excel($arrData,$path){
		$objPHPExcel=static::Array2Excel($arrData);
		if(!$objPHPExcel)
			return false;

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save($path);
	}

	public static function outputArray2Excel($arrData,$outputFileName){
		$objPHPExcel=static::Array2Excel($arrData);
		if(!$objPHPExcel)
			return false;
		
		ob_end_clean();
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
       header("Pragma: public");
       header("Expires: 0");
       header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
       header("Content-Type:application/force-download");
       header("Content-Type: application/vnd.ms-excel;");
       header("Content-Type:application/octet-stream");
       header("Content-Type:application/download");
       header("Content-Disposition:attachment;filename=".$outputFileName);
       header("Content-Transfer-Encoding:binary");
       $objWriter->save("php://output"); 
	}

	static function outputQueryResult2Excel($result,$filename){
		if (!$result) {
			return false;
		}

		while($arr=mysql_fetch_assoc($result)){
			$data[]=$arr;
		}
		static::outputArray2Excel($data,$filename);
	}

	public static function outputArray2Pdf($arrData,$outputFileName){
		$objPHPExcel=static::Array2Excel($arrData);
		if(!$objPHPExcel)
			return false;

	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
       	$objWriter->setSheetIndex(0);

       	header("Pragma: public");
       	header("Expires: 0");
       	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
       	header("Content-Type:application/force-download");
       	header("Content-Type: application/pdf");
       	header("Content-Type:application/octet-stream");
       	header("Content-Type:application/download");
       	header("Content-Disposition:attachment;filename=".$outputFileName);
       	header("Content-Transfer-Encoding:binary");
       	$objWriter->save("php://output"); 
	}

	static function Array2Excel($arrData){
		//验证有效性
		if(count($arrData)<1||count($arrData[0])<1)
			return null;

		//必要的变量
		$rowNum=count($arrData);
		$headField=$arrData[0];
		$fieldNum=count($headField);
		$fieldStartRow=1;
		$fieldStartColum='A';
		$fieldWidth=15;
		$fileColor='12AA23';
		$dataStartRow=2;
		$dataStartColum='A';

		//创建PHPExcel对象
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		//$objPHPExcel->getActiveSheet()->setTitle(pathinfo($path,PATHINFO_BASENAME));

		//写入列名并设置宽度和颜色和居中
		$i=0;
		foreach ($headField as $key => $value) {
			$objPHPExcel->getActiveSheet()->setCellValue(chr(ord($fieldStartColum)+$i).$fieldStartRow,$key);
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr(ord($fieldStartColum)+$i))->setWidth(strlen($value)<17?17:strlen($value));
			$objPHPExcel->getActiveSheet()->getStyle(chr(ord($fieldStartColum)+$i).$fieldStartRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
			$objPHPExcel->getActiveSheet()->getStyle(chr(ord($fieldStartColum)+$i).$fieldStartRow)->getFill()->getStartColor()->setARGB($fileColor);
			$objPHPExcel->getActiveSheet()->getStyle(chr(ord($fieldStartColum)+$i).$fieldStartRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$i++;
		}

		//设置第一列颜色
		
		//写入数据
		for ($i=0; $i < $rowNum; $i++) { 
			$j=0;
			foreach ($arrData[$i] as $key => $value) {
				$objPHPExcel->getActiveSheet()->setCellValue(chr(ord($fieldStartColum)+$j).($dataStartRow+$i),$value);
				$objPHPExcel->getActiveSheet()->getStyle(chr(ord($fieldStartColum)+$j).($dataStartRow+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$j++;
			}
		}
		
		//保存在$path
		$objPHPExcel->setActiveSheetIndex(0);
		return $objPHPExcel;
	}
}
