<?php
require('fpdf.php');


class PDF extends FPDF
{
	public $report;
	public $header1;
	public $header2;

	//$report='REPORT';
	//$header1='HEADER1';
	//$header2='HEADER2';
	
	//Page header
	public function Header(){
  		//$header1='header1'; $header2='header2';
  		$this->SetFont('Arial','B',16);
		$this->Cell(0,5,$this->report,0,1);
		$this->SetFont('Arial','B',14);
		$this->Cell(0,5,$this->header1,0,1);
		$this->Cell(0,5,$this->header2,0,1);
		$this->Line(10,25,100,25);
		$this->ln();
  	}
  
  	public function Footer(){
   		//Position at 1.5 cm from bottom
   		$this->SetY(-25);
   		//Arial italic 8
   		$this->SetFont('Arial','I',8);
   		//Page number
   		$this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb}',0,0,'C');
  	}
}

