<?php
	require '../helper_database.php';
	//require 'fpdf/fpdf.php';
	$cn=db_connect();

	$date1='2018-01-01';
	$date2='2018-12-31';
	makereport();

	function makereport()
	{
		//$this->load->database();
		//$this->load->library('table');
		
		//$reporttype = $_POST['reporttype'];
		$reporttype=$_GET['id'];
		//$reporttype='report42'; //debug
		$output="PDF"; //debug
		//$output="WEB"; //debug
		#ShowReportAccount($reporttype);
		#ShowReportPurchase($reporttype);
		#ShowReportSales($reporttype);
		#ShowReportInventory($reporttype);
		
		global $date1; global $date2; global $header1;
		$header1="from $date1 to $date2 ";
		//$date1=funixdate($_POST['date1']);
		//$date2=funixdate($_POST['date2']);
		//$output="WEB";
		//$output="PDF";
		
		if(substr($reporttype,-2,1)=='1') ShowReportAccount($reporttype);
		if(substr($reporttype,-2,1)=='2') ShowReportPurchase($reporttype);
		if(substr($reporttype,-2,1)=='3') ShowReportSales($reporttype);
		if(substr($reporttype,-2,1)=='4') ShowReportInventory($reporttype);
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
    	global $date1; global $date2; global $header1;
    	$sPeriod="AND(JRdate between '$date1' and '$date2') ";
        $COAorder="FIELD(catname,'Cash & Bank','Accounts Receivable (A/R)','Fixed Assets','Other Current Assets','Accounts Payable (A/P)','Other Current Liabilities','Equity','Income','Cost of Sales','Expenses','Other Income','Other Expense') ";
		switch ($reporttype) {
		case 'report10': #Laporan Neraca
			$activa = ["Cash & Bank", "Accounts Receivable (A/R)", "Inventory", "Fixed Assets", "Depreciation & Amortization", "Other Current Assets"];
        	$query = "SELECT mastercoa.accname,journal.accno,catname,level,SUM(amount)AS amount 
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.accno<>'' $sPeriod 
                        GROUP BY journal.accno
                        order by $COAorder ";
            $format = [["Activa", 90, "left"],
                        ["Amount", 40, "right"],
                        ["", 5, "center"],
                        ["Pasiva", 90, "left"],
                        ["Amount", 40, "right"]];
            $temp = db_get_array_query($query);
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
                        $dat[$p1]["acc1"] = Space(5*$level)."( $row[accno] ) $row[accname] ";
                        if($row['accno']<>'') $dat[$p1]["amount1"] = $tot; 
                        $tot1 = $tot1+$tot;
                        $p1++;
					} Else {
                        $dat[$p2]["acc2"] = Space(5*$level)."( $row[accno] ) $row[accname] ";
                        if($row['accno']<>'') $dat[$p2]["amount2"] = $tot;
                        $tot2 = $tot2+$tot;
                        $p2++;
					}
				}
			}
			
			#remove blank
            $a=0;
            while($a<count($dat)-1) {
            
            	//if(!isset($dat[$a]["acc1"])) $dat[$a]["acc1"]='';
            	//if(!isset($dat[$a]["acc2"])) $dat[$a]["acc2"]='';
                If ($dat[$a]["acc1"].$dat[$a]["acc2"]=='') {
                    //unset($dat[$a]);
                    array_splice($dat,$a);
				} Else {
                    $a++;
				}
			}
			#add total
            $dat[]=["", str_repeat("-",40), "", "", str_repeat("-",40)];
            $dat[]=["TOTAL", fnum($tot1), "", "TOTAL", fnum($tot2)];
            //print_r($dat);
            maketablepdf($dat, "Laporan Neraca",$header1, "", $format,'L');
        	break;
        	
    	case 'report11': #Laporan Laba-Rugi
        	$query = "SELECT mastercoa.AccNo, mastercoa.AccName,-SUM(IFNULL(amount,0)) AS amount,catname
                        From mastercoa  
                        Left JOIN journal ON journal.accno=mastercoa.accno  
                        Where (catname IN ('Income','Cost of Sales','Expenses','Other Income','Other Expense')) $sPeriod 
                        Group By mastercoa.accno   
                        Order by $COAorder ";
            $format = [["", 6000, "left"],
                        ["", 1500, "num"]];
        	$dat = db_get_array_query($query);
            makeprofitloss($dat, "Laporan Laba-Rugi",$header1, "", $format);
        	break;
        	
    	case 'report12': #Laporan Arus Kas
        	$query = "SELECT Reffno,JRdate,journal.AccNo,mastercoa.AccName,Amount 
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.AccNo<>'' $sPeriod 
                        ORDER BY JRdate,Reffno,Amount DESC ";
            $format = [["Reffno", 35, "left"],
                        ["Date", 21, "center"],
                        ["Account#",20, "left"],
                        ["AccName", 80, "left"],
                        ["Amount", 30, "right"]];
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Arus Kas",$header1, "", $format);
        	break;
        	
        case 'report13': #Laporan Jurnal
        	$query = "SELECT AccName,JRdate,Reffno,Amount as debet,0 as credit,0 as bal,jrdesc,CONCAT(Journal.AccNo,' - ',mastercoa.AccName) AS grouphead
                        FROM journal 
                        LEFT JOIN mastercoa ON mastercoa.accno=journal.accno 
                        WHERE journal.accno<>'' $sPeriod 
                        ORDER BY journal.AccNo,JRdate ";
            $format = [["Account #", 50, "center"],
                        ["JRdate", 21, "center"],
                        ["Reffno", 35, "left"],
                        ["Debet", 35, "num"],
                        ["Credit", 35, "num"],
                        ["Balance", 35, "num"],
                        ["Keterangan", 65, "left"]];
        	$dat = db_get_array_query($query);
            //makepdfgroup($dat, "Laporan Jurnal",$header1, "", 'grouphead', $format);
            maketablepdf($dat, "Laporan Jurnal",$header1, "", $format,'L');
        	break;
        	
        case 'report14': #Laporan Buku Besar
        	echo "stilll on progress";
        	break;
		}
	}
	
	//------------------
    // REPORT PURCHASE
    //------------------
    function ShowReportPurchase($reporttype) {
		global $date1; global $date2; global $header1;
		$sPeriod="AND(Transdate between '$date1' and '$date2') ";
		$JR="PI";
		switch ($reporttype) {
		case 'report20': #Laporan Pembelian
        	$query = "SELECT transdate,transhead.transno,acccode,accname,deliveryto,total,IFNULL(SUM(amountpaid),0)AS paid,total-IFNULL(SUM(amountpaid),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod 
                        GROUP BY transno 
                        ORDER BY transdate 
                        LIMIT 100 ";
        	$format = [["Date", 21, "center"],
                        ["Trans No", 35, "left"],
                        ["Supplier #", 40, "left"],
                        ["Supplier Name", 50, "left"],
                        ["Alamat", 50, "left"],
                        ["Total", 25, "num"],
                        ["Paid", 25, "num"],
                        ["Balance", 30, "num"]];
        	//$dat = $this->db->query($query)->result_array();
            $dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Daftar Pembelian",$header1, "", $format, "L");
        	break;
    	case 'report21': #Laporan Hutang Supplier
        	$query = "SELECT accname,transdate,transhead.transno,total,IFNULL(total-(SUM(amountpaid)),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE (LEFT(transhead.Transno,2) IN ('PI','PR')) $sPeriod 
                        GROUP BY transhead.transno
                        ORDER BY accname,transdate
                        LIMIT 100 ";
                $format = [["Customer", 50, "left"],
                            ["Date", 21, "center"],
                            ["Transno", 35, "left"],
                            ["Total", 40, "num"],
                            ["Unpaid", 40, "num"]];
        	//$dat = $this->db->query($query)->result_array();
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Hutang Supplier",$header1, "", $format);
        	break;
    	
        case 'report22': #Laporan Pembelian per Supplier
        	$query = "SELECT accname,transdate,transhead.transno,productcode,productname,qty as Qty,uom,price,qty*price as amount 
                        FROM transhead 
                        INNER JOIN transdetail on transdetail.transno=transhead.transno 
                        WHERE (left(Transhead.Transno,2) in ('PI','PR')) $sPeriod 
                        ORDER BY Accname,transdate 
                        LIMIT 100 ";
            $format = [["Supplier", 40, "center"],
                        ["Date", 21, "center"],
                        ["Transno", 25, "left"],
                        ["Product #", 40, "left"],
                        ["Product Name", 60, "left"],
                        ["Qty", 20, "right"],
                        ["UOM", 20, "center"],
                        ["Price", 25, "num"],
                        ["Amount", 30, "num"]];
        	//$dat = $this->db->query($query)->result_array();
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Pembelian per Supplier",$header1, "", $format, "L");
        	break;
        	
        case 'report23': #Laporan Usia Hutang
		}
	}
	
	//------------------
    // REPORT SALES
    //------------------
    function ShowReportSales($reporttype) {
		global $date1; global $date2; global $header1;
		$sPeriod="AND(Transdate between '$date1' and '$date2') ";
		$JR="IN";
		switch ($reporttype) {
		case 'report30': #Laporan Penjualan
        	$query = "SELECT transdate,transhead.transno,acccode,accname,deliveryto,total,IFNULL(SUM(amountpaid),0)AS paid,total-IFNULL(SUM(amountpaid),0)AS unpaid 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod
                        GROUP BY transno 
                        ORDER BY transdate 
                        LIMIT 100 ";
            $format = [["Date", 21, "center"],
                        ["Trans No", 35, "left"],
                        ["Customer #", 40, "left"],
                        ["Customer Name", 50, "left"],
                        ["Alamat", 50, "left"],
                        ["Total", 25, "num"],
                        ["Paid", 25, "num"],
                        ["Balance", 30, "num"]];
        	//$dat = $this->db->query($query)->result_array();
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Daftar Penjualan",$header1, "", $format, "L");
        	break;
    	case 'report31':  #Laporan Piutang Pelanggan
        	$query = "SELECT AccName,transdate,transhead.transno,total,total-IFNULL(SUM(amountpaid),0)AS unpaid,accname 
                        FROM transhead 
                        LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2) in ('IN','SR') $sPeriod 
                        GROUP BY transhead.transno 
                        ORDER BY acccode,transdate 
                        LIMIT 100 ";
            $format = [["Customer", 50, "left"],
                        ["Date", 21, "center"],
                        ["Transno", 35, "left"],
                        ["Total", 40, "num"],
                        ["Unpaid", 40, "num"]];
            //$dat = $this->db->query($query)->result_array();
            $dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Piutang per Pelanggan",$header1, "", $format);
            break;
    	case 'report32': #Laporan Penjualan per Pelanggan
        	$query = "SELECT AccName,transdate,transhead.transno,productcode,productname,-qty as Qty,uom,price,-qty*price as amount,accname 
                        FROM transhead 
                        INNER JOIN transdetail on transdetail.transno=transhead.transno 
                        WHERE (left(Transhead.Transno,2) in ('IN','SR')) $sPeriod 
                        ORDER BY AccCode,transdate 
                        LIMIT 100 ";
            $format = [["Customer", 40, "center"],
                        ["Date", 21, "center"],
                        ["Transno", 25, "left"],
                        ["Product #", 40, "left"],
                        ["Product Name", 60, "left"],
                        ["Qty", 20, "right"],
                        ["UOM", 20, "center"],
                        ["Price", 25, "num"],
                        ["Amount", 30, "num"]];
        	//$dat = $this->db->query($query)->result_array();
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Penjualan per Pelanggan",$header1, "", $format, "L");
        	break;
        case 'report33': #Laporan Usia Piutang
        	//kosong karea ini report manual, dibuat nanti
        	$query = "SELECT accname,acccode,transhead.transno,total,IFNULL(SUM(amountpaid),0)AS amountpaid,dodate,transdate AS duedate,0 as age1,0 as age2,0 as age3,0 as age4,0 as age5 
                        FROM transhead LEFT JOIN transpaymentarap ON transpaymentarap.invno=transhead.transno 
                        WHERE LEFT(transhead.transno,2)='$JR' $sPeriod
                        GROUP BY transhead.transno  
                        HAVING (total-IFNULL(SUM(amountpaid),0))>0 
                        ORDER BY acccode,transhead.transno 
                        LIMIT 100 ";
            $format = [["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"],
                        ["Date", 21, "left"]];
            //$dat = $this->db->query($query)->result_array();
            $dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Usia Piutang",$header1, "", $format,'L');
        	break;
		}
	}
	
	//------------------
    // REPORT PRODUCT
    //------------------
    function ShowReportInventory($reporttype) {
		global $date1; global $date2; global $header1;
		$sPeriod="AND(Transdate between '$date1' and '$date2') ";
		//echo $sPeriod; echo $reporttype;
		switch ($reporttype) {
		case 'report40': #Laporan persediaan barag
			$format = [["Product #", 40, "left"],
                    	["Product Name", 60, "left"],
                    	["Qty", 20, "right"],
                    	["UOM", 15, "center"],
                    	["Avg Cost", 25, "num"],
                    	["Value", 30, "num"]];
        	$query = "SELECT Code,Name,sum(Qty) AS Qty,masterproduct.uom,IFNULL(SUM(cost)/SUM(qty),0)AS avgCost,0 AS totAvgCost
                        FROM masterproduct
                        LEFT JOIN transdetail ON transdetail.productcode=masterproduct.code
                        LEFT JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$date2' 
                        GROUP BY ProductCode
                        LIMIT 100 ";
        	//$dat = $this->db->query($query)->result_array();
            $dat = db_get_array_query($query);
            for($a=0;$a<count($dat);$a++) {
                $dat[$a]["avgCost"] = getProductAvgCost($dat[$a]["Code"], Now());
                $dat[$a]["totAvgCost"] = $dat[$a]['Qty'] * $dat[$a]['avgcost'];
            }
            //print_r($dat);
            maketablepdf($dat, "Laporan Persediaan Barang",$header1, "", $format);
        	break;
    	case 'report41': #Laporan Rincian persediaan barang
        	$format = [["Product #", 40, "left"],
                        ["Product Name", 60, "left"],
                        ["Date", 21, "center"],
                        ["Transaction", 35, "left"],
                        ["Description", 65, "left"],
                        ["Mutation", 25, "num"],
                        ["Balance", 30, "num"]];
        	$query = "SELECT ProductCode,ProductName,Transdate,transdetail.transno,'Description','' as sqty,0 AS bal,qty 
                        FROM transdetail 
                        INNER JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$date2'
                        ORDER BY ProductCode,Transdate 
                        LIMIT 100 ";
        	//$dat = $this->db->query($query)->result_array();
        	$dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Rincian Persediaan Barang",$header1, "", $format,'L');
        	break;
    	case 'report42': #Laporan Nilai Persediaan Barang
        	$format = [["Product #", 40, "left"],
                        ["Product Name", 60, "left"],
                        ["Date", 21, "center"],
                        ["Transaction", 35, "left"],
                        ["Mutation", 25, "right"],
                        ["Stock Qty", 25, "num"],
                        ["Avg Cost", 20, "num"],
                        ["Sell/Buy Price", 25, "num"],
                        ["Value", 25, "num"]];
        	$query = "SELECT ProductCode,ProductName,Transdate,transdetail.transno,'0' AS sqty,0 AS bal,cost,price,qty*price AS amount,qty 
                        FROM transdetail 
                        INNER JOIN transhead ON transhead.transno=transdetail.transno 
                        WHERE transdate<='$date2'
                        ORDER BY ProductCode,transdate ";
            //$dat = $this->db->query($query)->result_array();
            $dat = db_get_array_query($query);
            maketablepdf($dat, "Laporan Nilai Persediaan Barang",$header1, "", $format, "L");
        	break;
		}
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
		require 'fpdf/pdf.php';
		$pdf = new PDF($page,'mm','A4');
		$pdf->report=$report;
		$pdf->header1=$header1;
		$pdf->header2=$header2;
		$pdf->AddPage();
		/*$pdf->SetFont('Arial','B',16);
		$pdf->Cell(0,5,$report,0,1);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,5,$header1,0,1);
		$pdf->Cell(0,5,$header2,0,1);
		$pdf->Line(10,25,100,25);
		$pdf->ln(); */

		#header
		$pdf->SetFont('Arial','B',10);
		for($a=0;$a<count($format);$a++) {
			$w=intval($format[$a][1]/60);
			$f='L';
			if($format[$a][2]=='right' ||$format[$a][2]=='num') $f='R';
			if($format[$a][2]=='center') $f='C';
			$pdf->Cell($w ,6,$format[$a][0],1, 0, $f );
		}
		$pdf->ln();
		#detail
		$pdf->SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			$key=array_keys($row);
			$h=5;
            $x=10;
            $y=$pdf->GetY();
            //for($b=0;$b<count($format);$b++ ) {
            for($b=0;$b<2;$b++ ) {
				$w=intval($format[$b][1]/60);
				$f='L';
				if($format[$b][2]=='right') $f='R';
				if($format[$b][2]=='center') $f='C';
				if($format[$b][2]=='num') { $f='R'; $row[$key[$b]]=fnum($row[$key[$b]]); }
				//$pdf->Cell($w ,5,$row[$key[$b]],1, 0, $f );  //single line
                if($b==0 or $b==0) {
                    //multi line
                    $pdf->SetY($y);
                    $pdf->SetX($x);
                    $pdf->MultiCell($w ,5,$row[$key[$b]],0,$f); 
                    $x=$pdf->GetX()+$w;
                    $w=$pdf->GetStringWidth($row[$key[$b]]);
                    $h=(int)($w/25)*5;
                    $pdf->SetY($y);
                    $pdf->SetX($x);
                } else {
                    //$pdf->SetY($y);
                    //$pdf->SetX($x);
                    $pdf->Cell($w ,5,$row[$key[$b]],0, 0, $f ); 
                    $x=$pdf->GetX()+$w;
                }
			}
			$pdf->ln($h);
		}
		$pdf->Output();
	}

	function maketablepdf($data, $report, $header1, $header2, $format, $page='P') {
		//echo "make table pdf";
		require('fpdf/tpdf.php');
		$pdf = new TPDF($page,'mm','A4');
		$pdf->report=$report;
		$pdf->header1=$header1;
		$pdf->header2=$header2;
		$pdf->AddPage();
		//$pdf->SetFont('Arial','',10);
		//format
		for($a=0;$a<count($format);$a++) {
			$colcaption[$a]=$format[$a][0];
			$colwidth[$a]=$format[$a][1];
			$colalign[$a]=$format[$a][2];
		}
		$pdf->SetWidths($colwidth);
		$pdf->Setaligns($colalign); 
		
		/*
		for($b=0;$b<count($format);$b++ ) {
			$xw[$b]=$format[$b][1]/60;
		} 
		$pdf->SetWidths($xw); */
		
		
		//srand(microtime()*1000000);
		//print_r($data);
		
		//header
		$pdf->SetFont('Arial','B',11);
		/*for($b=0;$b<count($format);$b++ ) {
		    //$pdf->Row(array(GenerateSentence(),GenerateSentence(),GenerateSentence(),GenerateSentence()));
		    	$xrow[$b]=$format['caption'][$b];
		} */
		$xrow=$colcaption;
		$pdf->Row($xrow);
		//detail
		$pdf->SetFont('Arial','',10);
		for($a=0;$a<count($data);$a++) {
			$row=$data[$a];
			$key=array_keys($row);
			for($b=0;$b<count($colcaption);$b++ ) {
		    //$pdf->Row(array(GenerateSentence(),GenerateSentence(),GenerateSentence(),GenerateSentence()));
		    	$xrow[$b]=$data[$a][$key[$b]];
		   	}
		    $pdf->Row($xrow);
		    
		}
		$pdf->Output();
	}
	function GenerateWord() {
    	//Get a random word
    	$nb=rand(3,10);
    	$w='';
    	for($i=1;$i<=$nb;$i++)
        	$w.=chr(rand(ord('a'),ord('z')));
    	return $w;
	}
	function GenerateSentence() {
    	//Get a random sentence
    	$nb=rand(1,10);
    	$s='';
    	for($i=1;$i<=$nb;$i++)
        	$s.=GenerateWord().' ';
    	return substr($s,0,-1);
	}
	
	function makeprofitloss($data, $report, $header1, $header2, $format, $page='P') {
		//his->load->library('pdf');
        require 'fpdf/pdf.php';
		$pdf = new PDF($page,'mm','A4');
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
			$dat[] = [str_repeat(' ',10).$row['AccNo'].' - '.$row['AccName'], fnum($row['amount']) ];
			$tot=$tot+$row['amount'];
			$next = isset($data[$a+1]['catname'])? $data[$a+1]['catname']:'';
			if($cat<>$next) { //make footer
				$dat[] = ['Total '.$cat, fnum($tot) ];
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
	}
	
	function set_header($arr) {
		for($a=0;$a<count($arr);$a++) {
			$w=intval($arr[$a][1]/10);
			$h[$a]=['data'=>$arr[$a][0], 'style'=>'width:'.$w.'px'];
		}
		return $h;
	}
	
	
	
	
	
	
	function fUnixDate($date='') {
		if ($date=='') return date('Y-m-d');
		$date=explode('/',$date);
		return date('Y-m-d', mktime(0,0,0,$date[1],$date[0],$date[2]));
	}
	
	function fnum($num) {
		$num=intval($num);
		return number_format($num,0);	
	}
	 
	function space($num) {
		return str_repeat(' ',$num);	
	}

?>
