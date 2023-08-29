<?php
require('fpdf_table.php');


class formPDF extends FPDF_Table
{
	public $report;
	public $header1;
	public $header2;
	public $isFinished;

	//$report='REPORT';
	//$header1='HEADER1';
	//$header2='HEADER2';
	
	//Page header
	public function Header(){
  		//Page number
  		$this->SetFont('Arial','I',8);
  		$this->SetXY(-25,5);
	   	$this->Cell(0,5,'Halaman '.$this->PageNo().'/{nb}',0,1,'R');
  		//Header
  		$this->SetFont('Arial','UB',10);
		$this->Cell(0,5,$this->report,0,1,'C');
		$this->SetFont('Arial','',9);
		$this->Cell(0,5,$this->header1,0,1,'C');
		$this->ln();
  	}
  
  	public function Footer(){
   		//Page number
	   	//$this->Cell(0,10,'Halaman LAST'.$this->PageNo().'/{nb}',0,0,'C');
   		
   		if($this->isFinished) { //on last page  only
	   		//Arial  9
	   		$this->SetFont('Arial','',9);
	   		$this->SetY(-25);
	   		$this->Cell(45,5,'Diterima Oleh',0,0,'C');
	   		$this->Cell(45,5,'Delivery',0,0,'C');
	   		$this->Cell(45,5,'Diperiksa Oleh',0,0,'C');
	   		$this->Cell(45,5,'Ditetapkan Oleh',0,0,'C');
	   		$this->Ln(15);
	   		for($a=0;$a<4;$a++) {
	   			$this->Cell(45,3,str_repeat('_',15),0,0,'C');
	   		}
   		}
  	}
}

