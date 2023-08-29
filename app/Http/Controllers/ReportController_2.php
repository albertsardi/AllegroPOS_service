<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use \koolreport\inputs\Bindable;
use \koolreport\inputs\POSTBinding;
// use Anouar\Fpdf\Fpdf as baseFpdf;
use Codedge\Fpdf\Fpdf\Fpdf as baseFpdf;

use \Koolreport\widgets\koolphp\Table;
use \Koolreport\inputs\Select2;


// require_once "../../../load.koolreport.php";
// doc raptor Your API Key: 60AHGxlcsR-nH6XtjUtS

class ReportController extends Controller
{
	private $header1=''; //debug
	private $header2=''; //debug
	private $date1='2015-01-01'; //debug
	private $date2='2020-12-31'; //debug
	private $limit='limit 20'; //for developing only
	
	// https://github.com/fpdf-easytable/fpdf-easytable/blob/master/example-1.pdf
	function makereport($reporttype)
	{
		//$this->load->database();
		//$this->load->library('table');
		
		//$reporttype = $_POST['reporttype'];
		//$reporttype=$id;
		//$reporttype='report42'; //debug
		$output="PDF"; //debug
		//$output="WEB"; //debug
		#ShowReportAccount($reporttype);
		#ShowReportPurchase($reporttype);
		#ShowReportSales($reporttype);
		#ShowReportInventory($reporttype);
		
		//global $date1; global $date2; global $header1;
		$this->header1="from $this->date1 to $this->date2 ";
		//$date1=funixdate($_POST['date1']);
		//$date2=funixdate($_POST['date2']);
		//$output="WEB";
		//$output="PDF";
		
		if(substr($reporttype,-2,1)=='1') $this->ShowReportAccount($reporttype);
		if(substr($reporttype,-2,1)=='2') $this->ShowReportPurchase($reporttype);
		if(substr($reporttype,-2,1)=='3') $this->ShowReportSales($reporttype);
		if(substr($reporttype,-2,1)=='4') $this->ShowReportInventory($reporttype);
		//$data['dat'] = $dat;
		
		/*
		if($output=="WEB") {
			$style = [
	        	'table_open'	=> '<table  border="1" cellpadding="2" cellspacing="1" class="xtable table-striped table-hover" id="xdataTables-list">',
	        	'row_start'		=> '<tr class="odd">',
	        	'row_alt_start'	=> '<tr class="even">',
			];
			$this->table->set_template($style);
			$this->table->set_heading($this->set_header($format));
			$this->table->set_footer('<th colspan="4" style="text-align:right">Total:</th><th text-align:right></th>');
			$data['grid'] = $this->table->generate($dat);
			
			//load view
	        $this->load->view('report/gridreport', $data);   
		}
		
		if($output=="PDF") {
			//makepdf($dat, "REPORTGRID",$header1, "", $format);
			makepdfgroup($dat, "REPORTGRID",$header1, "", $format);
			makepdftest($dat, "REPORTGRID",$header1, "", $format);
		}
		*/
		
	}

	//------------------
    // REPORT ACC 
    //------------------
    function ShowReportAccount($reporttype) {
    	$sPeriod="AND(JRdate between '$this->date1' and '$this->date2') ";
        $COAorder="FIELD(catname,'Cash & Bank','Accounts Receivable (A/R)','Fixed Assets','Other Current Assets','Accounts Payable (A/P)','Other Current Liabilities','Equity','Income','Cost of Sales','Expenses','Other Income','Other Expense') ";
		switch ($reporttype) {
		case 'report10': #Laporan Neraca
			$activa = ["Cash & Bank", "Accounts Receivable (A/R)", "Inventory", "Fixed Assets", "Depreciation & Amortization", "Other Current Assets"];
        	$query = "SELECT mastercoa.accname,journal.accno,catname,level,SUM(amount)AS amount 
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.accno<>'' $sPeriod 
                        GROUP BY accname,journal.accno,catname,level
						order by $COAorder 
						$this->limit ";
            $format = [["Activa", 90, "L"],
                        ["Amount", 40, "R"],
                        ["", 5, "C"],
                        ["Pasiva", 90, "L"],
                        ["Amount", 40, "R"]];
			$temp = json_decode(json_encode(DB::select($query) ), True);    
        	$tot=0; $tot1=0; $tot2=0;
        	$p1=0; $p2=0;
        	for($a=0;$a<count($temp);$a++) {
                $dat[$a]=['acc1'=>'','amount1'=>'','space'=>'','acc2'=>'','amount2'=>''];
                $row=$temp[$a];
                $cat = $row["catname"];
                $level = $row["level"];
				$tot = $row['amount'];
                If($tot<>0) {
                    If(in_array($cat, $activa)) {
                        $dat[$p1]["acc1"] = $this->space(5*$level)."( $row[accno] ) $row[accname] ";
                        if($row['accno']<>'') $dat[$p1]["amount1"] = $tot; 
                        $tot1 = $tot1+$tot;
                        $p1++;
					} Else {
                        $dat[$p2]["acc2"] = $this->space(5*$level)."( $row[accno] ) $row[accname] ";
                        if($row['accno']<>'') $dat[$p2]["amount2"] = $tot;
                        $tot2 = $tot2+$tot;
                        $p2++;
					}
				}
			}
			
			#remove blank
            $a=0;
            while($a<count($dat)-1) {
            
                If ($dat[$a]["acc1"].$dat[$a]["acc2"]=='') {
                    //unset($dat[$a]);
                    array_splice($dat,$a);
				} Else {
                    $a++;
				}
			}
			#add total
            $dat[]=["", str_repeat("-",30), "", "", str_repeat("-",30)];
            $dat[]=["TOTAL", $this->fnum($tot1), "", "TOTAL", $this->fnum($tot2)];
            $this->maketablepdf($dat, "Laporan Neraca",$this->header1, "", $format,'L');
        	break;
        	
    	case 'report11': #Laporan Laba-Rugi
        	$query = "SELECT mastercoa.AccNo, mastercoa.AccName,-SUM(IFNULL(amount,0)) AS amount,catname
                        From mastercoa  
                        Left JOIN journal ON journal.accno=mastercoa.accno  
                        Where (catname IN ('Income','Cost of Sales','Expenses','Other Income','Other Expense')) $sPeriod 
                        Group By mastercoa.accno,accname,catname   
						Order by $COAorder 
						$this->limit ";
            $format = [["", 6000, "L"],
                        ["", 1500, "N"]];
			$dat = json_decode(json_encode(DB::select($query) ), True);
			$header = "period $this->date1 to $this->date2 ";   
            $this->makeprofitloss($dat, "Laporan Laba-Rugi",$header, "", $format);
        	break;
        	
    	case 'report12': #Laporan Arus Kas
        	$query = "SELECT Reffno,JRdate,journal.AccNo,mastercoa.AccName,Amount 
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.AccNo<>'' $sPeriod 
						ORDER BY JRdate,Reffno,Amount DESC 
						$this->limit ";
            $format = [["Reffno", 35, "L"],
                        ["Date", 21, "C"],
                        ["Account#",20, "L"],
                        ["AccName", 80, "L"],
                        ["Amount", 30, "N"]];
			// $dat = db_get_array_query($query);
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Arus Kas",$this->header1, "", $format);
        	break;
        	
        case 'report13': #Laporan Jurnal
        	$query = "SELECT AccName,JRdate,Reffno,Amount as debet,0 as credit,0 as bal,jrdesc,CONCAT(Journal.AccNo,' - ',mastercoa.AccName) AS grouphead
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.accno<>'' $sPeriod 
						ORDER BY journal.AccNo,JRdate 
						$this->limit ";
            $format = [["Account #", 50, "L"],
                        ["JRdate", 21, "C"],
                        ["Reffno", 35, "L"],
                        ["Debet", 35, "N"],
                        ["Credit", 35, "N"],
                        ["Balance", 35, "N"],
                        ["Keterangan", 65, "L"]];
			$dat = json_decode(json_encode(DB::select($query) ), True); 
			$bal=0;
			for($a=0;$a<count($dat);$a++) {
				$val= $dat[$a]['debet'];
				$bal=$bal+$val;
				$dat[$a]['debet']= $this->debet($val);
				$dat[$a]['credit']= $this->credit($val);
				$dat[$a]['bal']= $bal;
			}
            //makepdfgroup($dat, "Laporan Jurnal",$header1, "", 'grouphead', $format);
            $this->maketablepdf($dat, "Laporan Jurnal",$this->header1, "", $format,'L');
        	break;
        	
        case 'report14': #Laporan Buku Besar
        	echo "still on progress";
        	break;
		}
	}
	
	//------------------
    // REPORT PURCHASE
    //------------------
    function ShowReportPurchase($reporttype) {
		$sPeriod="AND(Transdate between '$this->date1' and '$this->date2') ";
		$JR="PI";
		switch ($reporttype) {
		case 'report20': #Laporan Pembelian
        	$query = "SELECT transdate,transhead.transno,acccode,accname,deliveryto,total,IFNULL(SUM(amountpaid),0)AS paid,total-IFNULL(SUM(amountpaid),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod 
                        GROUP BY transdate,transno,acccode,accname,deliveryto,total 
                        ORDER BY transdate 
                        $this->limit ";
        	$format = [["Date", 21, "C"],
                        ["Trans No", 35, "L"],
                        ["Supplier #", 40, "L"],
                        ["Supplier Name", 50, "L"],
                        ["Alamat", 50, "L"],
                        ["Total", 25, "N"],
                        ["Paid", 25, "N"],
                        ["Balance", 30, "N"]];
        	//$dat = $this->db->query($query)->result_array();
            // $dat = db_get_array_query($query);
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Daftar Pembelian",$this->header1, "", $format, "L");
        	break;
    	case 'report21': #Laporan Hutang Supplier
        	$query = "SELECT accname,transdate,transhead.transno,total,IFNULL(total-(SUM(amountpaid)),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE (LEFT(transhead.Transno,2) IN ('PI','PR')) $sPeriod 
                        GROUP BY accname,transdate,transhead.transno,total
                        ORDER BY accname,transdate
                        $this->limit ";
                $format = [["Customer", 50, "L"],
                            ["Date", 21, "C"],
                            ["Transno", 35, "L"],
                            ["Total", 40, "N"],
                            ["Unpaid", 40, "N"]];
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Hutang Supplier",$this->header1, "", $format);
        	break;
    	
        case 'report22': #Laporan Pembelian per Supplier
        	$query = "SELECT accname,transdate,transhead.transno,productcode,productname,sentqty as Qty,uom,price,sentqty*price as amount 
                        FROM transhead 
                        INNER JOIN transdetail on transdetail.transno=transhead.transno 
                        WHERE (left(Transhead.Transno,2) in ('PI','PR')) $sPeriod 
                        ORDER BY Accname,transdate 
                        $this->limit ";
            $format = [["Supplier", 40, "C"],
                        ["Date", 21, "C"],
                        ["Transno", 25, "L"],
                        ["Product #", 40, "L"],
                        ["Product Name", 60, "L"],
                        ["Qty", 20, "N"],
                        ["UOM", 20, "C"],
                        ["Price", 25, "N"],
                        ["Amount", 30, "N"]];
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Pembelian per Supplier",$this->header1, "", $format, "L");
        	break;
        	
        case 'report23': #Laporan Usia Hutang
		}
	}
	
	//------------------
    // REPORT SALES
    //------------------
    function ShowReportSales($reporttype) {
		global $date1; global $date2; global $header1;
		$sPeriod="AND(Transdate between '$this->date1' and '$this->date2') ";
		$JR="IN";
		switch ($reporttype) {
		case 'report30': #Laporan Penjualan
        	$query = "SELECT transdate,transhead.transno,acccode,accname,deliveryto,total,IFNULL(SUM(amountpaid),0)AS paid,total-IFNULL(SUM(amountpaid),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod
                        GROUP BY transdate,transno,acccode,accname,deliveryto,total 
                        ORDER BY transdate 
                        $this->limit ";
            $format = [["Date", 21, "C"],
                        ["Trans No", 35, "L"],
                        ["Customer #", 40, "L"],
                        ["Customer Name", 50, "L"],
                        ["Alamat", 50, "L"],
                        ["Total", 25, "N"],
                        ["Paid", 25, "N"],
                        ["Balance", 30, "N"]];
			// $dat = db_get_array_query($query);
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Daftar Penjualan",$this->header1, "", $format, "L");
        	break;
    	case 'report31':  #Laporan Piutang Pelanggan
        	$query = "SELECT AccName,transdate,transhead.transno,total,total-IFNULL(SUM(amountpaid),0)AS unpaid,accname 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2) in ('IN','SR') $sPeriod 
                        GROUP BY AccName,transdate,transhead.transno,total 
                        ORDER BY acccode,transdate 
                        $this->limit ";
            $format = [["Customer", 50, "L"],
                        ["Date", 21, "C"],
                        ["Transno", 35, "L"],
                        ["Total", 40, "N"],
                        ["Unpaid", 40, "N"]];
			//$dat = $this->db->query($query)->result_array();
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Piutang per Pelanggan",$this->header1, "", $format);
            break;
    	case 'report32': #Laporan Penjualan per Pelanggan
        	$query = "SELECT AccName,transdate,transhead.transno,productcode,productname,-sentqty as Qty,uom,price,-sentqty*price as amount,accname 
                        FROM transhead 
                        INNER JOIN transdetail on transdetail.transno=transhead.transno 
                        WHERE (left(Transhead.Transno,2) in ('IN','SR')) $sPeriod 
                        ORDER BY AccCode,transdate 
                        $this->limit ";
            $format = [["Customer", 40, "L"],
                        ["Date", 21, "C"],
                        ["Transno", 25, "L"],
                        ["Product #", 40, "L"],
                        ["Product Name", 60, "L"],
                        ["Qty", 20, "N"],
                        ["UOM", 20, "C"],
                        ["Price", 25, "N"],
                        ["Amount", 30, "N"]];
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Penjualan per Pelanggan",$this->header1, "", $format, "L");
        	break;
        case 'report33': #Laporan Usia Piutang
        	//kosong karea ini report manual, dibuat nanti
        	$query = "SELECT accname,acccode,transhead.transno,total,IFNULL(SUM(amountpaid),0)AS amountpaid,transdate as dodate,transdate AS duedate,0 as age1,0 as age2,0 as age3,0 as age4,0 as age5 
                        FROM transhead LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod
                        GROUP BY accname,acccode,transhead.transno,total,TransDate
                        HAVING (total-IFNULL(SUM(amountpaid),0))>0 
                        ORDER BY acccode,transhead.transno 
                        LIMIT 100 ";
            $format = [["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"],
                        ["Date", 21, "L"]];
			// $dat = db_get_array_query($query);
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Usia Piutang",$this->header1, "", $format,'L');
        	break;
		}
	}
	
	//------------------
    // REPORT PRODUCT
    //------------------
    function ShowReportInventory($reporttype) {
		// global $date1; global $date2; global $header1;
		$sPeriod="AND(Transdate between '$this->date1' and '$this->date2') ";
		//echo $sPeriod; echo $reporttype;
		switch ($reporttype) {
		case 'report40': #Laporan persediaan barag
			$format = [["Product #", 40, "L"],
                    	["Product Name", 60, "L"],
                    	["Qty", 20, "N"],
                    	["UOM", 15, "C"],
                    	["Avg Cost", 25, "N"],
                    	["Value", 30, "N"]];
        	$query = "SELECT Code,Name,sum(SentQty) AS SentQty,masterproduct.uom,IFNULL(SUM(cost)/SUM(SentQty),0)AS avgCost,0 AS totAvgCost
                        FROM masterproduct
                        LEFT JOIN transdetail ON transdetail.productcode=masterproduct.code
                        LEFT JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$this->date2' 
						GROUP BY Code,Name,Uom
                        LIMIT 100 ";
			$dat = json_decode(json_encode(DB::select($query) ), True); 
			foreach($dat as $dt) {
                $dt["avgCost"] = getProductAvgCost($dt["Code"], date('Y-m-d'));
                $dt["totAvgCost"] = $dt['SentQty'] * $dt['avgCost'];
            }
            $this->maketablepdf($dat, "Laporan Persediaan Barang",$this->header1, "", $format);
        	break;
    	case 'report41': #Laporan Rincian persediaan barang
        	$format = [["Product #", 40, "L"],
                        ["Product Name", 60, "L"],
                        ["Date", 21, "C"],
                        ["Transaction", 35, "L"],
                        ["Description", 65, "L"],
                        ["Mutation", 25, "N"],
                        ["Balance", 30, "N"]];
        	$query = "SELECT ProductCode,ProductName,Transdate,transdetail.transno,'Description','' as sqty,0 AS bal,SentQty 
                        FROM transdetail 
                        INNER JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$this->date2'
                        ORDER BY ProductCode,Transdate 
                        limit 20";
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Rincian Persediaan Barang",$this->header1, "", $format,'L');
        	break;
    	case 'report42': #Laporan Nilai Persediaan Barang
        	$format = [["Product #", 40, "L"],
                        ["Product Name", 60, "L"],
                        ["Date", 21, "C"],
                        ["Transaction", 35, "L"],
                        ["Mutation", 25, "R"],
                        ["Stock Qty", 25, "N"],
                        ["Avg Cost", 20, "N"],
                        ["Sell/Buy Price", 25, "N"],
                        ["Value", 25, "N"]];
        	$query = "SELECT ProductCode,ProductName,Transdate,transdetail.transno,'0' AS sqty,0 AS bal,cost,price,SentQty*price AS amount,SentQty 
                        FROM transdetail 
                        INNER JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$this->date2'
						ORDER BY ProductCode,transdate 
						limit 20";
			$dat = json_decode(json_encode(DB::select($query) ), True); 
            $this->maketablepdf($dat, "Laporan Nilai Persediaan Barang",$this->header1, "", $format, "L");
        	break;
		}
	}

	//------------------------
    // REPORT STOCk QUERY
    //------------------------
	// https://www.koolreport.com/examples/reports/advanced/multiple_data_filters/
    function ReportStockQuery(Request $req) {
		// dd('stock');
		$data =[];
		return view('form-stockquery', $data);
	}
	
	function makepdfgroup($data, $report, $header1, $header2, $groupheader, $format, $page='P') {
		//$this->load->library('pdf');

		$pdf = new PDF($page,'mm','A4');
		//$pdf->report=$report;
		//$pdf->header1=$header1;
		//$pdf->header2=$header2;
		$pdf->AddPage();
		
		$group='';
		#detail
		$pdf->SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
				
			#groupheader
			if($group<>$row[$groupheader]) {
				$group=$row[$groupheader];
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(0 ,5,$group,0, 1);
				for($c=0;$c<count($format);$c++) {
					$w=intval($format[$c][1]/50);
					$f='L';
					if($format[$c][2]=='right' ||$format[$c][2]=='num') $f='R';
					if($format[$c][2]=='center') $f='C';
					$pdf->Cell($w ,6,$format[$c][0],'TB', 0, $f );
				}
				$pdf->ln();
			}
		
			$key=array_keys($row);
			for($b=0;$b<count($format);$b++ ) {
				$w=intval($format[$b][1]/50);
				$f='L';
				if($format[$b][2]=='right') $f='R';
				if($format[$b][2]=='center') $f='C';
				if($format[$b][2]=='num') { $f='R'; $row[$key[$b]]=$this->fnum($row[$key[$b]]); }
				$pdf->Cell($w ,5,$row[$key[$b]],0, 0, $f );
			}
			$pdf->ln();
			
			#groupfooter
			$last=isset($data[$a+1][$groupheader])?$data[$a+1][$groupheader]:'';
			if($group<>$last) {
			$pdf->SetFont('Arial','B',10);
			for($c=0;$c<count($format);$c++) {
				$w=intval($format[$c][1]/50);
				$f='L';
				if($format[$c][2]=='right' ||$format[$c][2]=='num') $f='R';
				if($format[$c][2]=='center') $f='C';
				$pdf->Cell($w ,6,'foot '.$c,1, 0, $f );
			}
			$pdf->ln();
			$pdf->ln();
			}
		}
		$pdf->Output();
	}
	
	function makepdf($data, $report, $header1, $header2, $format, $page='P') {
		//echo "make pdf ...";
		//$this->load->library('pdf');
		//require 'fpdf/pdf.php';
		$pdf = new PDF($page,'mm','A4');
		//$pdf::report=$report;
		//$pdf::header1=$header1;
		//$pdf::header2=$header2;
		$pdf::AddPage();
		/*$pdf->SetFont('Arial','B',16);
		$pdf->Cell(0,5,$report,0,1);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,5,$header1,0,1);
		$pdf->Cell(0,5,$header2,0,1);
		$pdf->Line(10,25,100,25);
		$pdf->ln(); */

		#header
		$pdf::SetFont('Arial','B',10);
		for($a=0;$a<count($format);$a++) {
			$w=intval($format[$a][1]/60);
			$f='L';
			if($format[$a][2]=='right' ||$format[$a][2]=='num') $f='R';
			if($format[$a][2]=='center') $f='C';
			$pdf::Cell($w ,6,$format[$a][0],1, 0, $f );
		}
		$pdf::ln();
		#detail
		$pdf::SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			$key=array_keys($row);
			$h=5;
            $x=10;
            $y=$pdf::GetY();
            //for($b=0;$b<count($format);$b++ ) {
            for($b=0;$b<2;$b++ ) {
				$w=intval($format[$b][1]/60);
				$f='L';
				if($format[$b][2]=='right') $f='R';
				if($format[$b][2]=='center') $f='C';
				if($format[$b][2]=='num') { $f='R'; $row[$key[$b]]=$this->fnum($row[$key[$b]]); }
				//$pdf->Cell($w ,5,$row[$key[$b]],1, 0, $f );  //single line
                if($b==0 or $b==0) {
                    //multi line
                    $pdf::SetY($y);
                    $pdf::SetX($x);
                    $pdf::MultiCell($w ,5,$row[$key[$b]],0,$f); 
                    $x=$pdf::GetX()+$w;
                    $w=$pdf::GetStringWidth($row[$key[$b]]);
                    $h=(int)($w/25)*5;
                    $pdf::SetY($y);
                    $pdf::SetX($x);
                } else {
                    //$pdf->SetY($y);
                    //$pdf->SetX($x);
                    $pdf::Cell($w ,5,$row[$key[$b]],0, 0, $f ); 
                    $x=$pdf::GetX()+$w;
                }
			}
			$pdf::ln($h);
		}
		$pdf::Output();
	}

	function maketablepdf($data, $report, $header1, $header2, $format, $page='P') {
		//echo "make table pdf";
		$pdf = new PDF($page,'mm','A4');
		// $pdf = new PDF($page,'mm','A4');
		$pdf->report=$report;
		$pdf->header1=$header1;
		$pdf->header2=$header2;
		$pdf->AddPage();
		// $pdf->SetFont('Arial','',10);
		//format
		for($a=0;$a<count($format);$a++) {
			$colcaption[$a]=$format[$a][0];
			$colwidth[$a]=$format[$a][1];
			$colalign[$a]=$format[$a][2];
		}
		$pdf->SetWidths($colwidth);
		$pdf->Setaligns($colalign); 
		
		//header
		$pdf->SetFont('Arial','B',11);
		// for($b=0;$b<count($format);$b++ ) {
		//     //$pdf->Row(array(GenerateSentence(),GenerateSentence(),GenerateSentence(),GenerateSentence()));
		//     	$xrow[$b]=$format['caption'][$b];
		// } 
		$xrow=$colcaption;
		$pdf->Row($xrow, 'header');
		//detail
		$pdf->SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			$key=array_keys($row);
			for($b=0;$b<count($colcaption);$b++ ) {
		    //$pdf->Row(array(GenerateSentence(),GenerateSentence(),GenerateSentence(),GenerateSentence()));
				$xrow[$b]=$data[$a][$key[$b]];
				// $xrow[$b]=number_format($xrow[$b], 2); //format
		   	}
		    $pdf->Row($xrow);
		    
		}
		$pdf->Output();
		exit;
	}
	
	function makeprofitloss($data, $report, $header1, $header2, $format, $page='P') {
		//his->load->library('pdf');
        // require 'fpdf/pdf.php';
		$pdf = new PDF($page,'mm','A4');
		// $pdf = new Fpdf($page,'mm','A4');
		$pdf->report=$report;
		$pdf->header1=$header1;
		$pdf->header2=$header2;
		$pdf->AddPage();
		
		$cat=''; $tot=0;
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			if($cat<>$row['catname']) { //make header
				$cat=$row['catname'];
				$dat[] = [$cat, ''];
			}
			$dat[] = [str_repeat(' ',10).$row['AccNo'].' - '.$row['AccName'], $this->fnum($row['amount']) ];
			$tot=$tot+$row['amount'];
			$next = isset($data[$a+1]['catname'])? $data[$a+1]['catname']:'';
			if($cat<>$next) { //make footer
				$dat[] = ['Total '.$cat, $this->fnum($tot) ];
				$tot=0;
			}
		}
		$data = $dat;
		#detail
		$pdf->SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			$w=intval($format[0][1]/50);
			$pdf->Cell($w ,5,$row[0],0, 0, 'L' );
			$w=intval($format[1][1]/50);
			$pdf->Cell($w ,5,$row[1],0, 0, 'R' );
			$pdf->ln();
		}
		$pdf->Output();
		exit;
	}
	
	function set_header($arr) {
		for($a=0;$a<count($arr);$a++) {
			$w=intval($arr[$a][1]/10);
			$h[$a]=['data'=>$arr[$a][0], 'style'=>'width:'.$w.'px'];
		}
		return $h;
	}

	function space($num) {
		return str_repeat(' ', $num);
	}
	function fnum($num) {
		$num=intval($num);
		return number_format($num,0);	
  	}

	
	
	//TEST function 
	function examplepdf() {
		$pdf = new Fpdf();
		$pdf::AddPage();
		$pdf::SetFont('Arial','B',18);
		$pdf::Cell(0,10,"Title",0,"","C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetFont('Arial','B',12);
		$pdf::cell(25,8,"ID",1,"","C");
		$pdf::cell(45,8,"Name",1,"","L");
		$pdf::cell(35,8,"Address",1,"","L");
		$pdf::Ln();
		$pdf::SetFont("Arial","",10);
		$pdf::cell(25,8,"1",1,"","C");
		$pdf::cell(45,8,"John",1,"","L");
		$pdf::cell(35,8,"New York",1,"","L");
		$pdf::Ln();
		$pdf::Output();
		exit;
	}
	
	public function test_docraptor() {
		//return 'docraptor';
		// doc raptor Your API Key: 60AHGxlcsR-nH6XtjUtS
		/* install
			composer require guzzlehttp/guzzle:^7.0
		*/
		$apiKey = '60AHGxlcsR-nH6XtjUtS';
		$docraptor = new DocRaptor\DocApi();
		$docraptor->getConfig()->setUsername("YOUR_API_KEY_HERE");
		// $docraptor->getConfig()->setDebug(true);
		$doc = new DocRaptor\Doc();
		$doc->setTest(true);                                                   // test documents are free but watermarked
		//$doc->setDocumentContent("<html><body>Hello World</body></html>");     // supply content directly
		// $doc->setDocumentUrl("http://docraptor.com/examples/invoice.html"); // or use a url
		$doc->setDocumentUrl("https://money.kompas.com/worksmart"); // or use a url
		$doc->setName("docraptor-php.pdf");                                    // help you find a document later
		$doc->setDocumentType("pdf");                                          // pdf or xls or xlsx
		// $doc->setJavascript(true);                                          // enable JavaScript processing
		// $prince_options = new DocRaptor\PrinceOptions();                    // pdf-specific options
		// $doc->setPrinceOptions($prince_options);
		// $prince_options->setMedia("screen");                                // use screen styles instead of print styles
		// $prince_options->setBaseurl("http://hello.com");                    // pretend URL when using document_content
		$create_response = $docraptor->createDoc('d:/'.$doc);
	}
}

// function debet($v) {
// 	return ($v<0)?abs($v):0;	
// }

// function credit($v) {
// 	return ($v>0)?abs($v):0;	
// }

// function space($n) {
// 	return str_repeat(" ", $n);	
// }

function getProductAvgCost($pcode, $dt) {
	return 100;
}

//table
// class FPDF_Table extends baseFpdf
class TPDF extends baseFpdf
{
	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data, $r='detail')
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			if($a=='N' && $r=='detail') { //numeric format
				$a='R';
				$data[$i] = number_format(intval($data[$i]),0);
			};
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this::GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}

//make header & footer
class PDF extends TPDF
{
	public $report;
	public $header1;
	public $header2;

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

// class PDF extends baseFpdf
// {
// 	var $widths;
// 	var $aligns;

// 	public $report;
// 	public $header1;
// 	public $header2;

// 	// $report='REPORT';
// 	// $header1='HEADER1';
// 	// $header2='HEADER2';
	
// 	//Page header
// 	public function Header(){
//   		//$header1='header1'; $header2='header2';
//   		$this->SetFont('Arial','B',16);
// 		$this->Cell(0,5,$this->report,0,1);
// 		$this->SetFont('Arial','B',14);
// 		$this->Cell(0,5,$this->header1,0,1);
// 		$this->Cell(0,5,$this->header2,0,1);
// 		$this->Line(10,25,100,25);
// 		$this->ln();
//   	}
  
//   	public function Footer(){
//    		//Position at 1.5 cm from bottom
//    		$this->SetY(-25);
//    		//Arial italic 8
//    		$this->SetFont('Arial','I',8);
//    		//Page number
//    		$this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb}',0,0,'C');
// 	  }
	  
// 	//added from TPDF
// 	function Row($data) {
// 		//Calculate the height of the row
// 		$nb=0;
// 		for($i=0;$i<count($data);$i++)
// 			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
// 		 $h=5*$nb;
// 		//Issue a page break first if needed
// 		$this->CheckPageBreak($h);
// 		//Draw the cells of the row
// 		for($i=0;$i<count($data);$i++)
// 		{
// 			$w=$this->widths[$i];
// 			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
// 			//Save the current position
// 			$x=$this->GetX();
// 			$y=$this->GetY();
// 			//Draw the border
// 			$this->Rect($x,$y,$w,$h);
// 			//Print the text
// 			$this->MultiCell($w,5,$data[$i],0,$a);
// 			//Put the position to the right of the cell
// 			$this->SetXY($x+$w,$y);
// 		}
// 		//Go to the next line
// 		$this->Ln($h);
// 	}
	
// 	//added from TPDF
// 	function CheckPageBreak($h) {
// 		//If the height h would cause an overflow, add a new page immediately
// 		if($this::GetY()+$h>$this->PageBreakTrigger)
// 			$this->AddPage($this->CurOrientation);
// 	}

// 	//added from TPDF
// 	function NbLines($w,$txt) {
// 		//Computes the number of lines a MultiCell of width w will take
// 		$cw=&$this->CurrentFont['cw'];
// 		if($w==0) $w=$this->w-$this->rMargin-$this->x;
// 		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
// 		$s=str_replace("\r",'',$txt);
// 		$nb=strlen($s);
// 		if($nb>0 and $s[$nb-1]=="\n")
// 			$nb--;
// 		$sep=-1;
// 		$i=0;
// 		$j=0;
// 		$l=0;
// 		$nl=1;
// 		while($i<$nb)
// 		{
// 			$c=$s[$i];
// 			if($c=="\n")
// 			{
// 				$i++;
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$nl++;
// 				continue;
// 			}
// 			if($c==' ')
// 				$sep=$i;
// 			$l+=$cw[$c];
// 			if($l>$wmax)
// 			{
// 				if($sep==-1)
// 				{
// 					if($i==$j)
// 						$i++;
// 				}
// 				else
// 					$i=$sep+1;
// 				$sep=-1;
// 				$j=$i;
// 				$l=0;
// 				$nl++;
// 			}
// 			else
// 				$i++;
// 		}
// 		return $nl;
// 	}


	
	
// }

?>