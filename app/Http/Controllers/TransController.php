<?php
   
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Report\MyReport;
use App\Http\Model\Common;
use App\Http\Model\Salesman;
use App\Http\Model\Transaction;
use App\Http\Model\Order;
use App\Http\Model\Delivery;
use App\Http\Model\Invoice;
use App\Http\Model\Expense;
use App\Http\Model\Warehouse;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Journal;
use Codedge\Fpdf\Fpdf\Fpdf; //for using Fpdf
use App\Report\Tpdf;
use Session;

class TransController extends MainController {

	public $addNew = null;
	
	function translist($jr) {
		//show view
		switch($jr) {
			case 'DO':
				// $dat = Delivery::selectRaw("transdelivery.TransNo,transdelivery.TransDate,oh.TransNo as OrderNo,oh.TransDate as OrderDate,oh.AccName,transdelivery.Total,transdelivery.Status,transdelivery.CreatedBy,transdelivery.CreatedDate,transdelivery.id")
				// 		->leftJoin('orderhead as oh', 'oh.TransNo', '=', 'transdelivery.OrderNo')
				// 		//->orderBy('transdelivery.TransDate', 'DESC')
				// 		->orderBy('transdelivery.TransNo', 'DESC')
				// 		->get();
				$sql = "select * from transhead where left(TransNo,2)='DO' 
				";
				$dat = DB::select(DB::raw($sql));
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				//  return $dat;
				$data = [
					'jr'        => $jr,
					'grid'      => ['DO #', 'DO Date', 'Order #', 'Date',  'Supplier', 'Total',' Status', 'Created By', 'Created Date'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
				];
				//return $data;
				break;
			case 'PI':
			case 'SI':
			case 'IN':
			// 	//"TransNo,TransDate,AccName,Total,'Status',CreatedBy"
				$dat = DB::table('transinvoice as ti')->selectRaw("ti.TransNo,ti.TransDate,ti.OrderNo,AccName,ti.Total,ti.CreatedBy,ti.CreatedDate,ti.Status,ti.id")
								->leftJoin('orderhead as oh', 'oh.TransNo', '=', 'ti.OrderNo')
								->orderBy('ti.TransDate', 'desc')
								->get();
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Customer', 'Total', ' Status', 'Created By', 'Created Date'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
					];
				break;
			case 'PO':
				$dat = Order::whereRaw("left(TransNo,2)='$jr' ")->get();
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Department', 'Remarks', 'Requester', 'Total', 'Status', 'CreatedBy', 'CreatedDate', 'UpdateDate'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
					];
				//return $data;
				break;
			case 'SO':
				$dat = Order::whereRaw("left(TransNo,2)='$jr' ")->orderBy('TransDate', 'desc')->get();
				foreach($dat as $dt) {
					$dt->Status = "<div class='text-success'>".$this->gettransstatus($jr, $dt->Status).'</div>';   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Customer', 'Remarks', 'Total', 'Status', 'CreatedBy', 'CreatedDate', 'UpdateDate'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
					];
				//return $data;
				break;

			// case 'PI-unpaid':
			// 	$sql = `SELECT *,(Total-TotalPaid)as Unpaid FROM transhead
			// 				WHERE (LEFT(TransNo,2)='PI') AND (Total-TotalPaid<>0) AND AccCode='${req.params.id}'
			// 				ORDER BY TransDate `;
			// 	break;
			// case 'SI-unpaid':
			// 	$sql = `SELECT *,(Total-TotalPaid)as Unpaid FROM transhead
			// 				WHERE (LEFT(TransNo,2)='IN') AND (Total-TotalPaid<>0) AND AccCode='${req.params.id}'
			// 				ORDER BY TransDate `;
			// 	break;
			case 'EX':
				// $sql = "SELECT *,AccName,abs(Amount)as Total FROM journal 
				// 		LEFT JOIN mastercoa on mastercoa.AccNo=journal.AccNo 
				// 		WHERE left(ReffNo,2)='EX' AND Amount<0 ";
				// $dat = DB::select(DB::raw($sql));
				$dat = DB::table('transexpense')->orderBy('TransDate')->get();
				// dd($dat);
				foreach($dat as $dt) {
					// $dt->Status = "<div class='text-success'>".$this->gettransstatus($jr, $dt->Status).'</div>';   
				}
				//return $dat;
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date','Receiver', 'Payment by', 'Total', 'Status', 'Created By', 'Created Date'],
					'caption'   => $this->makeCaption($jr),
					'mAccount'  => json_encode(db::table('mastercoa')->select('AccNo','AccName','CatName')->get() ),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
				];
				//dd($data);
				//return $data;
				break;
			case 'CR':
			case 'CD':
				$sql = "SELECT ReffNo, JRdate, m.AccNo, JRdesc, abs(Amount) as Total FROM journal j
						JOIN  mastercoa m ON m.AccNo=j.AccNo
						WHERE left(ReffNo,2)='$jr' ";
				$dat = DB::select(DB::raw($sql));
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				//return $dat;
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Account', 'Description', 'Total', 'Status', 'Created By', 'Created Date'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
				];
				// var head= ['Transaction #', 'Date', 'Account', 'Description', 'Total'];
				//return $data;
				break;
			// case 'CT':
			// 	$sql = "SELECT * FROM journal WHERE left(ReffNo,2)='$jr' AND Amount>0`";
			// 	//var head= ['Transaction #', 'Date', 'Description', 'Total'];
			// 	break;
			// case 'JR':
			// 	if ($id == 'undefined') {
			// 		$sql = "SELECT *,SUM(Amount)AS Total FROM journal WHERE LEFT(ReffNo,2)='GJ' AND Amount>0
			// 			GROUP BY ReffNo ORDER BY JRDate ";
			// 	} else {
			// 		$sql = "SELECT * FROM journal WHERE ReffNo='${req.params.id}' ";
			// 	}
			// 	$sql = "SELECT *,SUM(Amount)AS Total FROM journal WHERE LEFT(ReffNo,2)='GJ' AND Amount>0
			// 			GROUP BY ReffNo ORDER BY JRDate ";
			// 	//var head= ['Transaction #', 'Date', 'Description', 'Total'];
			// 	break;
			// case 'JRdetail':
			// 	$sql = "SELECT AccNo,JRdate,ReffNo,JRdesc,debet(Amount)as Debet,credit(Amount)as Credit FROM journal WHERE left(ReffNo,2)='JR' ";
			// 	if($id!='undefined') $sql=$sql+"AND ReffNo='$id";
			// 	$sql=$sql+"ORDER BY JRDate,ReffNo,Amount ";
			// 	break;
			// case 'PO':
			// 	$sql = `SELECT *,'OPEN' as Status FROM orderhead WHERE left(TransNo,2)='${req.params.jr}' `;
			// 	break;
			case 'AR':
			case 'AP':
				$sql = "SELECT * FROM transpaymenthead WHERE left(TransNo,2)='$jr' ";
				$dat = DB::select(DB::raw($sql));
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Account', 'Total', 'Status', 'Created By', 'Created Date'],
					//'grid'      => ['Transaction #', 'Date', 'Account', 'Total', 'Created By'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
				];
				//return $data;
				break;
			// //MANUFACTURE
			// case 'MWO':
			// 	//var head= ['Transaction #', 'Date', 'Section', 'Status'];
			// 	//var title = "WOrking Order List"
			// 	break;
			// case 'MMR':
			// 	//var head= ['Transaction #', 'Date', 'Work Order #', 'Memo'];
			// 	//var title = "MMR List"
			// 	break;
			// case 'MPE':
			// 	//var head = ['Transaction #', 'Date', 'Work Order #', 'Order Qty', 'Result Qty', 'Target', 'Memo'];
			// 	//var title = "MPE List"
			// 	break;
			default:
				return "no list from $jr";
				break;
		}
		$data['grid'] = $this->makeTableList($data['grid']);
		return view('translist', $data);
	}

	function makeTableList($caption) {
		$head= '<th>'.implode('</th><th>',$caption).'</th>';
		$head= '<thead><tr>'.$head.'</tr></thead>';
		$tbl= $head.'<tbody></tbody>';
		//$tbl= str_replace('<th>Status</th>','',$tbl); //debug nanti di benerin
		return $tbl;
 	}

	function accountdetaillist($id)
	{
		//show view
        $jr = 'accountdetail';
        $sql = "SELECT JRDate, ReffNo, m.AccNo, m.AccName, JRdesc, Amount
				FROM mastercoa m
				LEFT JOIN journal j ON j.AccNo=m.AccNo
				WHERE m.id=$id
				ORDER BY JRDate ";
		$dat = DB::select(DB::raw($sql));
        $openBal = 0;
        //$dt->bal = $openBal;
        foreach($dat as $dt) {
			$dt->Debet = $this->debet($dt->Amount);
			$dt->Credit = $this->credit($dt->Amount);
            //create balance
			$dt->Bal = $openBal + $dt->Debet - $dt->Credit; 
			$openBal = $dt->Bal;
        }
        $data = [
            'jr'        => $jr,
            'grid'      =>  ['Date', 'Account#', 'Account Name', 'Reff#', 'Descripton', 'Debet', 'Credit', 'Balance'],
            'caption'   => $this->makeCaption($jr),
            '_url'      => env('API_URL').'/api/'.$jr,
            'data'      => $dat,
        ];
        $data['grid'] = $this->makeTableList($data['grid']);
        // return $data;
        return view('translist', $data);
    }

	function makeList($jr = '')
	{
		switch ($jr) {
				//PURCHASE
			case 'PI':
				$dat = $this->DB_array('transhead', "TransNo,TransDate,AccName,Total,'Status',CreatedBy", "left(TransNo,2)='PI' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					//$dat[$a]['TransNo']= "<a href='form-buy.php?id=$link'>".$dat[$a]['TransNo']."</a>";
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
					$dat[$a]['Status'] = $this->gettransstatus($link, $jr);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Supplier', 'Total', 'Status', 'Created By'], ['L', 'C', 'L', 'N', 'C', 'L']);
				break;

				//DO
			case 'DO':
				$dat = $this->DB_array('transhead', "TransNo,TransDate,AccName,Total,'Status',CreatedBy", "left(TransNo,2)='DO' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
					$dat[$a]['Status'] = $this->gettransstatus($link, $jr);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Customer', 'Total', 'Status', 'Created By']);
				break;

				//SALES
			case 'SI':
				$dat = $this->DB_array('transhead', "TransNo,TransDate,AccName,Total,'Status',CreatedBy", "left(TransNo,2)='IN' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
					$dat[$a]['Status'] = $this->gettransstatus($link, $jr);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Supplier', 'Total', 'Status', 'Created By']);
				break;

				//PAYMENT
			case 'AP':
				$dat = $this->DB_array('transpaymenthead', "TransNo,TransDate,AccName,Total", "left(TransNo,2)='AP' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] =  link_to("trans-edit/$link", $link);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Account', 'Total']);
				break;

				//PAYMENT AR
			case 'AR':
				$dat = $this->DB_array('transpaymenthead', "TransNo,TransDate,AccName,Total", "left(TransNo,2)='AR' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
				}
				return table_generate($dat, ['Transaction #', 'Date', 'Account', 'Total']);
				break;


				$dat = DB::select("SELECT ReffNo,JRdate,AccName,JRdesc,Amount
									FROM journal
									LEFT JOIN mastercoa ON mastercoa.AccNo=journal.AccNo
									WHERE JRtype='EX' AND Amount>0
									ORDER BY JRdate DESC");
				$dat = json_decode(json_encode($dat), True);
				$dat = $this->DB_array('transpaymenthead', "TransNo,TransDate,AccName,Total", "left(TransNo,2)='AR' ", "TransNo desc");
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Account', 'Total']);
				break;

				//EXPENSE
			case 'EX':
				$dat = DB::select("SELECT ReffNo,JRdate,AccName,JRdesc,Amount
									FROM journal
									LEFT JOIN mastercoa ON mastercoa.AccNo=journal.AccNo
									WHERE JRtype='EX' AND Amount>0
									ORDER BY JRdate DESC");
				
				//$dat = json_decode(json_encode($dat), True);
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['ReffNo'];
					//$dat[$a]['ReffNo']= "<a href='payar-edit.php?id=$link'>".$dat[$a]['ReffNo']."</a>";
					$dat[$a]['ReffNo'] = link_to("trans-edit/$link", $link);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Account', 'Descripton', 'Total']);
				break;

				//MANUFACTURE
			case 'MWO':
				$dat = DB::select("SELECT wono,transdate,section,STATUS
									FROM wo
									ORDER BY transdate DESC");
				$dat = json_decode(json_encode($dat), True);
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['wono'];
					//$dat[$a]['ReffNo']= "<a href='payar-edit.php?id=$link'>".$dat[$a]['ReffNo']."</a>";
					$dat[$a]['wono'] = link_to("trans-edit/$link", $link);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Section', 'Status']);
				break;
			case 'MMR':
				$dat = DB::select("SELECT TransNo,TransDate,WoNo,Memo
									FROM mfgmaterialrelease
									ORDER BY transdate DESC");
				$dat = json_decode(json_encode($dat), True);
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Work Order #', 'Memo']);
				break;
			case 'MPE':
				$dat = DB::select("SELECT TransNo,TransDate,WoNo,OrderQty,ResultQty,'0%' AS Target,Note
									FROM mfgproductresult
									ORDER BY transdate DESC");
				$dat = json_decode(json_encode($dat), True);
				for ($a = 0; $a < count($dat); $a++) {
					$link = $dat[$a]['TransNo'];
					$dat[$a]['TransNo'] = link_to("trans-edit/$link", $link);
					$dat[$a]['Target'] = percent($dat[$a]['ResultQty'], $dat[$a]['OrderQty'], 2);
				}
				return $this->table_generate($dat, ['Transaction #', 'Date', 'Work Order #', 'Order Qty', 'Result Qty', 'Target', 'Memo']);
				break;


		}
	}

	// Export to Excel using koolreport
    function translist_exportexcel(Request $req) {
        $excelPath = 'exportXls/';
        $report = new MyReport;
        //$data = DB::table('transhead')->whereRaw("left(TransNo,2)='DO'")->selectRaw('TransNo,TransDate,AccName')->get();
		$data = Transaction::Get('DO');
        $report->rdata['data'] = $data;
        return $report->run()->exportToExcel($excelPath.'report_excel')->toBrowser("myreport.xlsx");
    }

	// Export to Pdf using koolreport
	function translist_exportpdf(Request $req) {
		//return 'koolreport - export pdf';
		$pdfPath = 'exportPdf/';
		$report = new MyReport;
		$reportData = DB::table('transhead')->selectRaw('TransNo,TransDate,AccCode,AccName,Total,id')->get();
		$report->rdata['data'] = $reportData->toArray();
		return $report->run()->export($pdfPath.'report_trans_pdf')
						->settings([
							// "useLocalTempFolder" => true,
						])
						->pdf([
							'format'=>'A4',
							'orientation'=>'portrait',
						])->toBrowser("myreport.pdf");
	}





	// Export to Pdf using TPDF
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
			$s.=$this->GenerateWord().' ';
		return substr($s,0,-1);
	}
	function translist_exportpdf_usingTPDF(Request $req) {
		//return 'exportpdf_usingTPDF';
		// install ->composer require codedge/laravel-fpdf
		$pdfPath = 'exportPdf/';
		$reportData = DB::table('transhead')->selectRaw('TransNo,TransDate,AccCode,AccName,Total,id')->get();

		$data = $reportData;
		$report = '#Laporan Rincian persediaan barang';
		$format = [
			["Transaction #", 60, "left"],
			["Date", 21, "center"],
			["Customer Code", 35, "left"],
			["Name", 65, "left"],
			["Total", 25, "num"],
			["id", 10, "num"] 
		];
		$page = 'P';

		//this is example
		/*
		$pdf=new Tpdf;
		$pdf->AddPage();
		$pdf->SetFont('Arial','',14);
		//Table with 20 rows and 4 columns
		$pdf->SetWidths(array(30,50,30,40));
		//srand(microtime()*1000);
		for($i=0;$i<20;$i++)
			$pdf->Row(array($this->GenerateSentence(),$this->GenerateSentence(),$this->GenerateSentence(),$this->GenerateSentence()));
		$pdf->Output();
		exit;
		*/
		
		$pdf = new Tpdf($page,'mm','A4');
		$pdf->report = $report;
		$pdf->header1 = 'header1';
		$pdf->header2 = 'header2';
		$pdf->AddPage();
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(0,10,$pdf->header1,0,1);
		$pdf->Cell(0,10,$pdf->header2,0,1);
		$pdf->Ln();
		//format
		for($a=0;$a<count($format);$a++) {
			$colcaption[$a]=$format[$a][0];
			$colwidth[$a]=$format[$a][1];
			$colalign[$a]=$format[$a][2];
		}
		$pdf->SetWidths($colwidth);
		$pdf->Setaligns($colalign); 
		$xrow=$colcaption;
		$pdf->Row($xrow);
		//detail
		$pdf->SetFont('Arial','',10);
		foreach($data as $row) {
			$xrow=[];
			foreach($row as $col) {
				$xrow[]=$col;
			}
			$xrow[0] = "Delivery Number\n".$xrow[0]."\n"."Date ".$xrow[1]."\n"."From new order\n"."Total Rp. ".number_format((float)$xrow[4],2);
		    $pdf->Row($xrow);
		}
		$pdf->Output();
		exit;
	}

	function getTransStatus($jr, $status)
	{
		/*switch ($jr) {
			case 'PI':
				$dat = $this->DB_array('transpaymentarap', "sum(AmountPaid) as paid", "InvNo='$transno' ");
				$dat = (int) ($dat[0]['paid']);
				if ($dat > 0) return "CLOSED";
				return "";
				break;
			case 'DO':
				$dat = $this->DB_array('transdetail', "InvNo", "TransNo='$transno' ");
				if (count($dat) > 0) return "CLOSED";
				return "";
				break;
			case 'SI':
				$dat = $this->DB_array('transpaymentarap', "sum(AmountPaid)", "InvNo='$transno' ");
				$dat = (int) ($dat);
				if (count($dat) > 0) return "CLOSED";
				return "";
				break;
			default:
				return "none";
		}*/
		if ($status==0) return DRAFT;
		if ($status==1) return APPROVED;
	}

	function selectData($arr) 
	{
		$out = [];
		foreach($arr as $r) {
			switch($r) {
				case 'selPayment':
					$cat = substr($r, 3);
					$dat= Common::select('name2','name1')->where('category',$cat)->get();
					break;
				case 'selProduct':
					$dat= Product::select('Code','Name')->get();
					break;
				case 'selCustomer':
					$dat= CustomerSupplier::select('AccCode','AccName')->where('AccType','C')->get();
					break;
				case 'selSupplier':
					$dat= CustomerSupplier::select('AccCode','AccName')->where('AccType','S')->get();
					break;
				case 'selSalesman':
					$dat= Salesman::select('Code','Name')->get();
					break;
				case 'selWarehouse':
					$dat= Warehouse::select('warehouse','warehousename')->get();
					break;
			}
			$dat = $dat->toArray();
			$dat2=[];
			foreach($dat as $dt) {
				$key = array_keys($dt);
				$dat2[] = ['id'=>$dt[$key[0]], 'text'=>$dt[$key[1]] ];
			}
			$out[$r] = $dat2;
		}
		return (object)$out;
	}

	function transedit($jr, $id)
	{
        $data = [
			'jr' 		=> $jr,
      	    'id' 		=> $id,
            'caption'	=> $this->makeCaption($jr, $id),
			'data'		=> []
        ];
		$view = '';
		if ($jr=='SI') $jr='IN';   

		if ($jr == 'PO') {
			$modal = ['mCat', 'mSupplier','mProduct','mWarehouse'];
			$select = $this->selectData(['selSupplier','selWarehouse']);
			$view = 'form-po';
			$res = Order::Get($jr, $id);
        }
		if ($jr == 'PI') {
			$modal = ['mCat', 'mSupplier', 'mProduct', 'mPurchaseQuotation', 'mPayType', 'mSalesman', 'mWarehouse'];
			$view = 'form-buy';
			$res = Invoice::Get($jr, $id);
		}
		if ($jr == 'SO') {
			$modal = ['mCustomer', 'mProduct', 'mWarehouse', 'mPayType', 'mSalesman', 'mAddr'];
			$select = $this->selectData(['selCustomer','selWarehouse', 'selPayment', 'selSalesman']);
			$button	= ['cmApprove', 'cmCreateInv'];
			$view = 'form-SO';
			$res = Order::Get($jr, $id);
		}
		if ($jr == 'DO') {
			$modal = ['mCat', 'mCustomer', 'mProduct', 'mSO', 'mWarehouse'];
			$select = $this->selectData(['selCustomer','selWarehouse', 'selPayment', 'selSalesman']);
			$button = ['cmReadyToSent', 'cmDeliveryReceived'];
			$view = 'form-do';
			$res = Transaction::Get($jr, $id);
		}
		if ($jr == 'IN') {
			$modal = ['mCat', 'mCustomer', 'mProduct', 'mAccount', 'mSO', 'mPayType', 'mWarehouse', 'mSalesman', 'mDO' ];
			$select = $this->selectData(['selCustomer','selPayment']);
			$view = 'form-si';
			$res = Invoice::Get($jr, $id);
		}
		if ($jr == 'EX') {
			$modal = ['mCat', 'mSupplier', 'mAccount'];
			$view = 'form-ex';
			$res = Expense::Get($jr, $id);
		}

		// get modal
		if(in_array('mCat', $modal)) $data['mCat'] = $this->DB_list('masterproductcategory', 'Category');
		if(in_array('mCustomer', $modal)) $data['mCustomer'] = DB::table('masteraccount')->where('AccType', 'C')->get();
		if(in_array('mSupplier', $modal)) $data['mSupplier'] = DB::table('masteraccount')->select('AccCode','AccName','Category')->where('AccType', 'S')->get();
		if(in_array('mProduct', $modal)) $data['mProduct'] = json_encode(DB::table('masterproduct')->select('Code','Name','Category')->where('ActiveProduct',1)->get());
		if(in_array('mPurchaseQuotation', $modal)) $data['mPurchaseQuotation'] = ['Raw material','Finish good'];
		if(in_array('mWarehouse', $modal)) $data['mWarehouse'] = $this->DB_list('masterwarehouse', ['warehouse','warehousename']); //DB::table('masterwarehouse')->select('warehouse','warehousename')->get(),
		// if(in_array('mPayType', $modal)) $data['mPayType'] =  $this->DB_list('common',['id','name1'], "category='Payment' "); //Common::getData('Payment')->data,
		if(in_array('mPayType', $modal)) $data['mPayType'] =  DB::table('common')->select('id','name1')->where('category','Payment')->get(); //Common::getData('Payment')->data,
		//if(in_array('mSalesman', $modal)) $data['mSalesman'] = $this->DB_list('mastersalesman', 'Name');
		if(in_array('mSalesman', $modal)) $data['mSalesman'] = DB::table('mastersalesman')->select('Code','Name')->get();
		if(in_array('mAddr', $modal)) $data['mAddr'] = []; //json_encode(DB::table('masteraccount')->where('AccCode', 'C')->get() ),
		if(in_array('mCat', $modal)) $data['mCat'] = $this->DB_list('masterproductcategory', 'Category');
		if(in_array('mAccount', $modal)) $data['mAccount'] = json_encode(db::table('mastercoa')->select('AccNo','AccName','CatName')->get() );
		if(in_array('mDO', $modal)) $data['mDO'] = [];
		if(in_array('mSO', $modal)) $data['mSO'] = DB::table('orderhead')->select('TransNo','TransDate','Total','AccCode','AccName','DeliveryTo')
									->whereRaw("left(TransNo,2)='SO' ")->where("Status", "1")
									->orderBy('TransDate', 'desc')->get();
			

		// get select data
		$data['select'] = isset($select)? $select:[];
		
		// get data
		if ($res->status=='OK') {
			if(!empty($res->data)) {
				$res->data->status 	= $this->gettransstatus($id, $jr);
				$data['data']		= $res->data;
				$data['griddata'] 	= $res->data->detail;
				$data['data']->Status = $this->getTransStatus($jr, $data['data']->Status);
			} else {
				$data['data'] =(object)['status' => 'DRAFT'];
				$data['griddata'] = [];
				$resp = $data;
			}
			return view($view, $data); // using agGrid
		} else {
			dd('Error '.$res->status);
		}
	}

	// TransSave data
	// for PI, DO, IN
	function transsave(Request $req) {
		$this->addNew = false;
		if (in_array($req->TransNo, ['', 'NEW'])) {
			$req->TransNo = $this->getNewTransNo($req->jr, $req->TransDate);
			$this->addNew = true;
		}

		//confirm
		if ($req->cmd=='confirm') {
			//dd('confirm');
			if (in_array($req->jr, ['PO','SO'])) return $this->save_confirm($req);
			dd('no confirm form '.$req->jr);
		} else {
			//save
			if (in_array($req->jr, ['PO','SO'])) return $this->save_order($req);
			if (in_array($req->jr, ['PI'])) return $this->save_transaction($req);
			if (in_array($req->jr, ['DO'])) return $this->save_delivery($req);
			//if (in_array($req->jr, ['AR', 'AP'])) return $this->save_payment($req);
			if (in_array($req->jr, ['CR', 'CD'])) return $this->save_bankcash($req);
			dd('no save form '.$req->jr);
		}

		
	}

	function save_order($req) {
		$err= '';
		//$input = $req->all(); dd($input);
		//$detail = json_decode($req->detail);dd($detail);
		//$id = Order::select('id')->where('TransNo',$req->TransNo)->get(); dd($id);
		// $id = Transaction::where('TransNo','DO.1800002')->get(); dd($id);
		//$id = Order::where('TransNo',$req->TransNo)->first()->id;
		DB::beginTransaction();
		try {
			// save cara 1
			// HEAD update
			if(in_array($req->TransNo, ['','NEW'])) {
				$data = new Order();
				//$data->TransNo = $req->TransNo; //generate new transaction
				$this->addNew = true;
			} else {
				$data = Order::where('TransNo', $req->TransNo)->first();
				$this->addNew = false;
			}
			$data->TransDate 		= $this->unixdate($req->TransDate);
			//$data->DeliveryDate 	= $req->DeliveryDate;
			$data->AccCode 			= $req->AccCode;
			$data->AccName 			= $req->AccCodeLabel; //$req->AccName;
			$data->DeliveryCode 	= $req->DeliveryCode;
			$data->Deliveryto 		= $req->Deliveryto;
			$data->Payment 			= $req->Payment;
			$data->Division 		= $req->Division;
			$data->Project 			= $req->Project;
			$data->Salesman 		= $req->Salesman;
			$data->TaxAmount 		= $req->TaxAmount;
			$data->FreightAmount	= $req->FreightAmount;
			$data->DiscPercentH 	= $req->DiscPercentH;
			$data->DiscAmountH 		= $req->DiscAmountH;
			$data->Note 			= $req->Note;
			$data->ReffNo 			= $req->ReffNo;
			$data->Status 			= 0;
			$data->Total 			= $req->Total;
			$data->CreatedBy 		= Session::get('user')->LoginName;
			//$data->CreatedDate 	= date('Y-m-d H:m:s');
			//dd($data);
			$data->save();
			//get id
			$id = Order::where('TransNo',$req->TransNo)->first()->id;

			// DETAIL update
			$detail = json_decode($req->detail);
			$dbDetail = DB::table('orderdetail');
			$dbDetail->where("TransNo", $req->TransNo)->delete();
			foreach($detail as $r) {
				$save = [
					'TransNo' 		=> $req->TransNo,
					'ProductCode' 	=> $r->ProductCode,
					'ProductName' 	=> $r->ProductName,
					'Qty' 			=> $r->Qty,
					// 'UOM' 			=> $r->UOM,
					'Price' 		=> $r->Price,
					// 'DiscPercentD' 	=> $r->DiscPercentD,
					// 'Cost' 			=> $r->Cost,
					// 'Memo' 			=> $r->Memo,
					'trans_id' 		=> $id,
				];
				$dbDetail->insert($save);
			}
			DB::commit();
			$result=[];
			//return response()->json(['success'=>'data saved', 'result'=>$result]);
			$message='Sukses!!';
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}

	function save_transaction($req) {
		// https://medium.com/easyread/mudahnya-mengolah-data-menggunakan-model-dan-eloquent-pada-laravel-80af915c80b5
		$err= '';
		$input = $req->all();
		DB::beginTransaction();
		try {
			// save cara 2
			// HEAD update
			$head = [
				'TransNo' 		=> $req->TransNo,
				'TransDate' 	=> $this->unixdate($req->TransDate),
				'AccCode' 		=> $req->AccCode,
				'AccName' 		=> $req->AccName,
				'DeliveryCode' 	=> $req->DeliveryCode,
				'Deliveryto' 	=> $req->Deliveryto,
				'Warehouse' 	=> $req->Warehouse,
				'Salesman' 		=> $req->Salesman,
				'DueDate' 		=> $req->DueDate,
				'TaxAmount' 	=> $req->TaxAmount,
				'FreightPercent'=> $req->FreightPercent,
				'FreightAmount' => $req->FreightAmount,
				'DiscPercentH' 	=> $req->DiscPercentH,
				'DiscAmountH' 	=> $req->DiscAmountH,
				'ReffNo' 		=> $req->ReffNo,
				'Note' 			=> $req->Note,
				'Total' 		=> $req->Total,
			];
			if ($this->addNew) {
				Transaction::insert($head);
			} else {
				Transaction::where("id", $req->id)->update($head);
			}
			
			// DETAIL update
			$detail = json_decode($req->detail);
			$dbDetail = DB::table('TransDetail');
			$dbDetail->where("TransNo", $req->TransNo)->delete();
			foreach($detail as $r) {
				$detail = [
					'TransNo' 		=> $req->TransNo,
					'InvNo' 		=> $r->InvNo,
					'ProductCode' 	=> $r->ProductCode,
					'ProductName' 	=> $r->ProductName,
					'Qty' 			=> $r->Qty,
					'UOM' 			=> $r->UOM,
					'Price' 		=> $r->Price,
					'DiscPercentD' 	=> $r->DiscPercentD,
					'Cost' 			=> $r->Cost,
					'Memo' 			=> $r->Memo,
					'trans_id' 		=> $req->id,
				];
				$dbDetail->insert($detail);
			}
			DB::commit();
			//return response()->json(['success'=>'data saved', 'input'=>$input]);
			$message='Sukses!!';
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}

	function save_delivery($req) {
		//dd('savedelivery');
		$err= '';
		$input = $req->all();
		DB::beginTransaction();
		try {
			// save cara 3 (dengan mapping)
			// HEAD update
			// $head = [
			// 	'TransNo' 		=> $req->TransNo,
			// 	'TransDate' 	=> $this->unixdate($req->TransDate),
			// 	'OrderNo'		=> $req->OrderNo,
			// 	'CarNo'			=> 'B1023BH',
			// 	'Driver'		=> 'Bp. Budi',

			// 	'AccCode' 		=> $req->AccCode,
			// 	'AccName' 		=> $req->AccName,
			// 	'DeliveryCode' 	=> $req->DeliveryCode,
			// 	'Deliveryto' 	=> $req->Deliveryto,
			// 	'Warehouse' 	=> $req->Warehouse,
			// 	'Salesman' 		=> $req->Salesman,
			// 	'DueDate' 		=> $req->DueDate,
			// 	'TaxAmount' 	=> $req->TaxAmount,
			// 	'FreightPercent'=> $req->FreightPercent,
			// 	'FreightAmount' => $req->FreightAmount,
			// 	'DiscPercentH' 	=> $req->DiscPercentH,
			// 	'DiscAmountH' 	=> $req->DiscAmountH,
			// 	'Note' 			=> $req->Note,
			// 	'Total' 		=> $req->Total,
			// ];
			$head = $req->all();
			$head['TransNo'] = $req->TransNo;
			$head['TransDate'] = $this->unixdate($head['TransDate']);
			$head['OrderDate'] = $this->unixdate($head['OrderDate']);
			
			if ($this->addNew) {
				//Delivery::insert($head);
				Delivery::create($head);
				$data = Delivery::where('TransNo',$req->TransNo)->first();
				$req->id = $data->id;
			} else {
				$save= Delivery::where("TransNo", $req->TransNo)->first();
				$save->update($head);
			}
			
			// DETAIL update
			$detail = json_decode($req->detail);
			$dbDetail = DB::table('TransDetail');
			$dbDetail->where("DONo", $req->TransNo)->delete();
			foreach($detail as $r) {
				$detail = [
					'TransNo' 		=> $req->OrderNo,
					'DONo' 			=> $req->TransNo,
					'ProductCode' 	=> $r->ProductCode,
					'ProductName' 	=> $r->ProductName,
					'OrderQty' 		=> $r->OrderQty??0,
					'SentQty' 		=> $r->Qty??0,
					'UOM' 			=> $r->UOM,
					'Price' 		=> $r->Price??0,
					'DiscPercentD' 	=> $r->DiscPercentD??0,
					'Cost' 			=> $r->Cost??0,
					'Memo' 			=> $r->Memo??'',
					'trans_id' 		=> $req->id,
				];
				$dbDetail->insert($detail);
			}
			DB::commit();
			//return response()->json(['success'=>'data saved', 'input'=>$input]);
			$message='Sukses!!';
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}

	function transpaysave(Request $req) {
		$err= '';
		DB::beginTransaction();
		try {
			// Payment Save
			// $data = Invoice::where('TransNo', 'IN.1800001')->first();
			$data = Invoice::where('TransNo', $req->PayInvNo)->first();
			$jr = substr($req->PayInvNo, 0, 2); 
			//dd($req->PayInvNo);
			//dd($data);
			$data->FirstPaymentAmount = $req->PayAmount;
			$data->save();
			// JURNAL update
			//$pay = DB::table('transpaymentarap')::where('TransNo', $req->TransNo)->first();
			$this->addJournal(
				$req->PayDate,
				($jr=='PI')? 'AP':'AR',
				(string)$req->PayAccNo,
				(string)$req->PayAccNo,
				$req->PayAmount,
				'DP Transaction '.$req->PayInvNo,
				(string)$req->PayInvNo
			);
			DB::commit();
			$result=[];
			$message='Sukses!!';
			if($jr=='IN') $jr='SI';
			return redirect(url( "trans-edit/$jr/$req->PayInvNo" ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			if($jr=='IN') $jr='SI';
			return redirect(url( "trans-edit/$jr/$req->PayInvNo" ))->with('error', $e->getMessage());
	  	}
	}

	function save_confirm($req) {
		$err= '';
		try {
			// save cara 1
			$data = Order::where('TransNo', $req->TransNo)->first();
			$data->Status = 1;
			$data->save();
			$message='Confirm!!';
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}

	function addJournal($jrdate, $jrtype, $accnoDebet, $accnoCredit, $amount, $jrdesc='', $reffno='', $acccode='', $receiver='') {
		DB::beginTransaction();
		try {
			Journal::where("ReffNo", $reffno)->delete();
			for ($a=1;$a<=2;$a++) {
				$data = new Journal();
				$data->JRdate = $jrdate;
				$data->JRtype = (string)$jrtype;
				$data->JRdesc = (string)$jrdesc;
				$data->ReffNo = (string)$reffno;
				$data->AccCode = (string)$acccode;
				$data->Receiver = (string)$receiver;
				if ($a==1) { //1=debet, 2=credit
					$data->AccNo = (string)$accnoDebet;
					$data->Amount = -abs($amount);
				} else { 
					$data->AccNo = (string)$accnoCredit;
					$data->Amount = abs($amount);
				}
				$data->save();
			}
			DB::commit();
			return response()->json(['success'=>'data saved', 'result'=>'data ok']);
		}
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}

	function field_array($fld) {
		$arr = [];
		for($a=0;$a<count($fld);$a++) {
			$nm= $fld[$a];
			// $arr[$nm]= isset(Input::get($nm))?Input::get($nm):'';
			$arr[$nm]= Input::get($nm);
		}
		return $arr;
	}

	function getNewTransNo($jr,  $transdate) {
		$yr = date('y', strtotime($transdate));
		if ($jr == 'DO') {
			$last = Delivery::whereRaw("left(TransNo,5)='$jr.$yr'")->orderBy('TransNo','desc')->first();
		};
		if ($jr == 'PO'||$jr == 'SO') {
			$last = Order::whereRaw("left(TransNo,5)='$jr.$yr'")->orderBy('TransNo','desc')->first();
		};
		if ($jr == 'PI'||$jr == 'SI'||$jr == 'IN') {
			$last = Invoice::whereRaw("left(TransNo,5)='$jr.$yr'")->orderBy('TransNo','desc')->first();
		};
		if ($jr == 'AR'||$jr == 'AP') {
			$last = Payment::whereRaw("left(TransNo,5)='$jr.$yr'")->orderBy('TransNo','desc')->first();
		};

		$lastno = (!empty($last))? intval(substr($last->TransNo, 5, 5)) : 0;
		$newno=$lastno + 1;
		$newno="$jr.$yr".str_repeat('0',5-strlen($newno)).$newno;
		//dd($newno);
		return $newno;
	}

	function createInvoice($transno='') {
		// return json_encode('XXXXXXXXXX '.$transno);
		$jr = substr($transno, 0 , 2);
		$order = Order::Get($jr, $transno); $order = $order->data;
		//return dd($order);
		//return json_encode($order);

		try {
			//inv Head
			$inv = new Invoice();
			$inv->TransDate = date('Y-m-d');
			$inv->TransNo = $this->getNewTransNo('IN', $inv->TransDate);
			$inv->OrderNo = $transno;
			$inv->DONo = "";
			$inv->PaymentType = "";
			$inv->AccountNo = "";
			$inv->DeliveryTo =  $order->Deliveryto;
			$inv->Warehouse = "";
			$inv->Salesman = "";
			$duedate = date_add(date_create($order->TransDate), date_interval_create_from_date_string("30 days"));
			$inv->DueDate = date_format($duedate, 'Y-m-d');
			$inv->TaxAmount = $order->TaxAmount;
			$inv->FreightPercent = 0;
			$inv->FreightAmount = $order->FreightAmount;
			$inv->DiscPercentH = $order->DiscPercentH;
			$inv->DiscAmountH = $order->DiscAmountH;
			$inv->Note = $order->Note;
			$inv->TaxNo = "";
			$inv->ReffNo = $transno;
			$inv->Status = 0;
			$inv->Total = $order->Total;
			$inv->CreatedBy = Session::get('user')->LoginName;
			$inv->CreatedDate = date('Y-m-d H:i:s');
			$inv->save();
		
			return json_encode(['msg'=>'Success, invoice create '.$inv->TransNo, 'invno'=>$inv->TransNo]);
		}
		catch (Exception $e) {
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			return response()->json(['error'=>$e->getMessage()]);
			//return redirect(url( "trans-edit/$req->jr/$req->TransNo" ))->with('error', $e->getMessage());
	  	}
	}



	function CreateModalGrid($id, $dat, $caption)
	{
		if ($dat != []) {
			$key = array_keys($dat[0]);
			$grid = '';
			//caption
			$caption = "<tr><th>" . implode('</th><th>', $caption) . "</th></tr>";
			//detail
			for ($a = 0; $a < count($dat); $a++) {
				$link = $dat[$a][$key[0]];
				$grid .= "<tr>" .
					// "<td><a href='#' onclick='lookupSel($link)'>$link</a></td>".
					"<td><a href='#' class='dialog-link' js-dialog-close>$link</a></td>" .
					"<td>" . $dat[$a][$key[1]] . "</td>" .
					"</tr>";
			}
			$grid .= '</table>';
			return "<table id='gridLookup-$id' class='gridLookup' border='1' class='xtable xtable-bordered xtable-hoer xdisplay' style='font-size:small;border:1px solid black;'>
						<thead>$caption</thead>
						<tbody>$grid</tbody>
						</table>";
		}
	}

	
	function cell($row, $nm) {
		return Input::get("grid-$nm")[$row];
	}
	//----------------------------------------------
	//function Form to update query
	//----------------------------------------------
	function form_query($db, $fld, $otherfld=[]) {
		$dat = [];
		for($a=0;$a<count($fld);$a++) {
			$nm= $fld[$a];
			// $arr[$nm]= isset(Input::get($nm))?Input::get($nm):'';
			$dat[$nm]= Input::get($nm);
		}
		//keys
		$keys=array_keys($dat); $keys=implode(',' ,$keys);
		//values
		$dat=implode("','" ,$dat); $dat="'".$dat."'";
		return "replace into $db($keys) values($dat)";
	}
	//----------------------------------------------
	//function Edit grid to update query
	//----------------------------------------------
	function egrid_query($db, $fld, $key='', $otherfld=[]) {
		$post=$_POST;
		if($key=='') $key=$fld[0];
		$key= 'grid-'.$key;
		$totrow= count($post[$key]);
		$dat='';
		for($a=0;$a<$totrow;$a++) {
			if(!isset($post[$key][$a])) continue; //jika key tidak ada
			if($post[$key][$a]=='') continue; //jika baris kosong
			$r=[];
			foreach($fld as $f) {
				$nm= "grid-$f";
				$v= isset($post[$nm][$a])?$post[$nm][$a]:'';
				$r[$f]=$v;
			}
			$r= array_merge($r, $otherfld);
			$flds= implode(',', array_keys($r));
			$r= array_values($r);
			$dat.= "('".implode("','", $r)."'),";
		}
		$dat= substr($dat,0,-1);
		$dat= "replace into $db($flds) values".$dat;
		return $dat;
	}


	public function allPosts(Request $request)
    {

        $columns = array(
                        0 => "TransNo",
						1 => "TransDate",
						2 => "AccName",
						3 => "Total",
						4 => "Status",
						5 => "CreatedBy"
					);

        $totalData = Post::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Post::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Post::where('id','LIKE',"%{$search}%")
                            ->orWhere('title', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Post::where('id','LIKE',"%{$search}%")
                             ->orWhere('title', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $show =  route('posts.show',$post->id);
                $edit =  route('posts.edit',$post->id);

				//$nestedData['id'] = $post->id;
				$nestedData["TransNo"] = $post->TransNo;
				$nestedData["TransDate"] = $post->TransDate;
				$nestedData["AccName"] = $post->AccName;
				$nestedData["Total"] = $post->Total;
				$nestedData["Status"] = $post->Status;
				$nestedData["CreatedBy"] = $post->CreatedBy;
                //$nestedData['title'] = $post->title;
                //$nestedData['body'] = substr(strip_tags($post->body),0,50)."...";
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->created_at));
                //$nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                          &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        //echo json_encode($json_data);
        return json_encode($json_data);

	}

	public function ajax()
    {
		return view('customers.ajax');
	}

    //test koolreport di web
    function koolreport_translist($jr) {
		//show view
        $report = new MyReport;
        $report->run();
        
		switch($jr) {
			case 'PI':
			case 'DO':
				$dat = DB::table('transhead')->selectRaw("TransNo,TransDate,AccName,Total,'' as Status,CreatedBy,id")->whereRaw("left(TransNo,2)='$jr' ")->get();
                $table = [
                    'dataSource' => $dat,
                    "columns" => [
                        '#' => ['label'=>"No", "start"=>1 ],
                        'TransNo',
                        'TransDate',
                        'AccName',
                        'Total'=>[
                            'formatValue' => function($val)  {
                                if ($val == 0) return '-';
                                return number_format($val, 2);
                            },
                            "footer"=>"sum",
                        ],
                        'Status',
                        'CreatedBy'
                    ],
                    // 'paging' => [
                    //     'pageSize' => 10,
                    //     'pageIndex' => 0,
                    //     'align' => 'center',
                    // ],
                    "options" => [ "paging"=>true,"searching"=>true, ],
                    "showFooter"=>"bottom",
                    'cssClass' => ["table" => "table-bordered table-striped table-hover"],
                    'cssStyle' => ['table td' => 'font-size:10px;']
                ];
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction#', 'Date', 'Supplier', 'Total',' Status', 'Created By'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'table'      => $table,
				];
				break;
			default:
				return "no list from $jr";
				break;
		}
		return view('koolreport_translist', $data);
	}




	// function makeTableList($jr = '')
	// {
	// 	switch ($jr) {
	// 			//PURCHASE
	// 		case 'PI':
	// 			$head= ['Transaction #', 'Date', 'Supplier', 'Total', 'Status', 'Created By'];
	// 			break;

	// 			//DO
	// 		case 'DO':
	// 			$head= ['Transaction #', 'Date', 'Customer', 'Total', 'Status', 'Created By'];
	// 			break;

	// 			//SALES
	// 		case 'SI':
	// 		case 'IN':
	// 			$head= ['Transaction #', 'Date', 'Customer', 'Total', 'Status', 'Created By'];
	// 			break;

	// 			//PAYMENT AP
	// 		case 'AP':
	// 			$head= ['Transaction #', 'Date', 'Account', 'Total'];
	// 			break;

	// 			//PAYMENT AR
	// 		case 'AR':
	// 			$head= ['Transaction #', 'Date', 'Account', 'Total'];
	// 			break;

	// 			//EXPENSE
	// 		case 'EX':
	// 			$head= ['Transaction #', 'Date', 'Account', 'Descripton', 'Total'];
	// 			break;

	// 			//ACCOUNT DETAIL
	// 		case 'accountdetail':
	// 			$head= ['Date', 'Account#', 'Account Name', 'Reff#', 'Descripton', 'Debet', 'Credit', 'Balance'];
	// 			break;

	// 			//MANUFACTURE
	// 		case 'MWO':
	// 			$head= ['Transaction #', 'Date', 'Section', 'Status'];
	// 			break;
	// 		case 'MMR':
	// 			$head= ['Transaction #', 'Date', 'Work Order #', 'Memo'];
	// 			break;
	// 		case 'MPE':
	// 			$head= ['Transaction #', 'Date', 'Work Order #', 'Order Qty', 'Result Qty', 'Target', 'Memo'];
	// 			break;
	// 	}
	// 	$head= '<th>'.implode('</th><th>',$head).'</th>';
	// 	$head= '<thead><tr>'.$head.'</tr></thead>';
	// 	$tbl= $head.'<tbody></tbody>';
	// 	$tbl= str_replace('<th>Status</th>','',$tbl); //debug nanti di benerin
	// 	return $tbl;
	// }

	// function ajax_translist($jr)
	// {
	// 	//return "XXXXX $jr";
	// 	$con=[
	// 		'user'=> env('DB_USERNAME'),
	// 		'pass'=> env('DB_PASSWORD'),
	// 		'db'=> env('DB_DATABASE'),
	// 		'host'=> env('DB_HOST')
	// 	];
	//
	// 	$w="";
	// 	switch($jr) {
	// 		case 'PI':
	// 		case 'DO':
	// 		case 'SI':
	// 		case 'IN':
	// 			$table="transhead";
	// 			$primaryKey = "TransNo";
	// 			/*$col = [
	// 				['db'=>'TransNo', 'dt'=>0],
	// 				['db'=>'TransDate', 'dt'=>1],
	// 				['db'=>'AccName', 'dt'=>2],
	// 				['db'=>'Total', 'dt'=>3],
	// 				['db'=>'CreatedBy', 'dt'=>4]
	// 			];*/
	// 			$col = ['TransNo','TransDate','AccName','Total','CreatedBy'];
	// 			if($jr=='SI') $jr='IN';
	// 			$w= "left(TransNo,2)='$jr'";
	// 			break;
	//
	// 		case 'AP':
	// 		case 'AR':
	// 			$table="transpaymenthead";
	// 			$primaryKey = "TransNo";
	// 			// $col = [
	// 			// 	['db'=>'TransNo', 'dt'=>0],
	// 			// 	['db'=>'TransDate', 'dt'=>1],
	// 			// 	['db'=>'AccName', 'dt'=>2],
	// 			// 	['db'=>'Total', 'dt'=>3],
	// 			// ];
	// 			$col = ['TransNo','TransDate','AccName','Total','CreatedBy'];
	// 			$w= "left(TransNo,2)='$jr' ";
	// 			break;
	//
	// 		case 'EX':
	// 			/*
	// 			SELECT ReffNo,JRdate,AccName,JRdesc,Amount
	// 								FROM journal
	// 								LEFT JOIN mastercoa ON mastercoa.AccNo=journal.AccNo
	// 								WHERE JRtype='EX' AND Amount>0
	// 								ORDER BY JRdate DESC");
	// 								*/
	// 			$table="listex";
	// 			$primaryKey = "ReffNo";
	// 			$col = ['ReffNo','JRdate','AccName','JRdesc','Amount'];
	// 			break;
	//
	// 		case 'MWO':
	// 			$table="wo";
	// 			$primaryKey = "wono";
	// 			// $col = [
	// 			// 	['db'=>'wono', 'dt'=>0],
	// 			// 	['db'=>'transdate', 'dt'=>1],
	// 			// 	['db'=>'section', 'dt'=>2],
	// 			// 	['db'=>'Status', 'dt'=>3]
	// 			// ];
	// 			$col = ['wono','transdate','section','Status'];
	// 			$w= "left(TransNo,2)='$jr' ";
	// 			break;
	// 		case 'MMR':
	// 			$table="mfgmaterialrelease";
	// 			$primaryKey = "TransNo";
	// 			// $col = [
	// 			// 	['db'=>'TransNo', 'dt'=>0],
	// 			// 	['db'=>'TransDate', 'dt'=>1],
	// 			// 	['db'=>'WoNo', 'dt'=>2],
	// 			// 	['db'=>'Memo', 'dt'=>3]
	// 			// ];
	// 			$col = ['TransNo','TransDate','WoNo','Memo'];
	// 			$w= "";
	// 			break;
	// 		case 'MPE':
	// 			$table="mfgproductresult";
	// 			$primaryKey = "TransNo";
	// 			/*$col = [
	// 				['db'=>'TransNo', 'dt'=>0],
	// 				['db'=>'TransDate', 'dt'=>1],
	// 				['db'=>'WoNo', 'dt'=>2],
	// 				['db'=>'OrderQty', 'dt'=>3],
	// 				['db'=>'ResultQty', 'dt'=>4],
	// 				['db'=>'Note', 'dt'=>5]
	// 			];*/
	// 			$col = ['TransNo','TransDate','WoNo','OrderQty','ResultQty','Note'];
	// 			$w= "";
	// 			break;
	// 	}
	//
	// 	$res= json_encode(
	// 		//SSP::simple($_GET, $con, $table, $primaryKey, $col)
	// 		//SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w )
	// 		//$this->SSP( $_GET, "transhead", "TransNo", "TransNo,TransDate,AccName,Total,CreatedBy", "left(TransHead,2)='DO'" )
	// 		$this->SSP( $_GET, $table, $primaryKey, $col, $w )
	// 	);
	// 	return $res;
	// }

	// function SSP ( $request, $table, $primaryKey, $columns,  $where='', $order='' )
	// 	{
	// 		$bindings = array();
	// 		//$db = self::db( $conn );
	// 		$localWhereResult = array();
	//         $localWhereAll = array();
	//         $whereAllSql="";
	//         //if ($where!='') $where="WHERE ".$where;
	// 		//dd($request);
	//
	// 		//$table="transhead";
	// 		//$primaryKey="TransNo";
	// 		//$fld="TransNo,TransDate,AccName,Total,CreatedBy";
	// 		//$where="where left(TransNo,2)='DO'";
	//         //$order="order by Code";
	//
	//
	//
	//         //where
	//         $search=isset($_GET['search']['value'])?$_GET['search']['value']:'';
	//         //$search='FRO'; //debug
	//         if($search!='') {
	//             //$where=$this->combine($where, "$primaryKey like '%$search%' ");
	//             $where=$this->combine($where, "($columns[0] like '%$search%' or $columns[1] like '%$search%') ");
	//         }
	//         if ($where!='') $where="WHERE $where ";
	//
	//         //order by
	//         $order="";
	//         if ($order!='') $where="ORDER BY $order ";
	//
	//         //start
	//         $start=isset($request['start'])?$request['start']:1;
	//
	//         //limit
	//         $limit=isset($request['length'])?$request['length']:10;
	//
	//         //columns
	//         $columns= implode(",", $columns);
	//
	//         $resFilterLength = DB::select("SELECT COUNT(`{$primaryKey}`) as TOT
	// 		FROM   `$table`
	// 		$where");
	//         $recordsFiltered = $resFilterLength[0]->TOT;
	//
	//         $resTotalLength = DB::select("SELECT COUNT(`{$primaryKey}`)
	// 		FROM   `$table` ".
	// 		   $whereAllSql);
	// 		$recordsTotal = $limit;//$resTotalLength;
	//
	// 		$sql="SELECT $columns
	// 					FROM `$table`
	// 					$where
	// 					$order
	// 					limit $start,$limit ";
	//         $data = DB::select($sql);
	//         // dd($sql);
	//
	// 		// Data set length after filtering
	// 		/*$resFilterLength = self::sql_exec( $db, $bindings,
	// 			"SELECT COUNT(`{$primaryKey}`)
	// 			 FROM   `$table`
	// 			 $where"
	// 		);
	// 		$recordsFiltered = $resFilterLength[0][0];*/
	//
	//
	// 		// Total data set length
	// 		/*$resTotalLength = self::sql_exec( $db, $bindings,
	// 			"SELECT COUNT(`{$primaryKey}`)
	// 			 FROM   `$table` ".
	// 			$whereAllSql
	// 		);
	// 		$recordsTotal = $resTotalLength[0][0];*/
	//
	//
	// 		// prepare out
	// 		/*
	// 		$out = array();
	// 		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
	// 			$row = array();
	// 			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
	// 				$column = $columns[$j];
	//
	// 				// Is there a formatter?
	// 				if ( isset( $column['formatter'] ) ) {
	//                     if(empty($column['db'])){
	//                         $row[ $column['dt'] ] = $column['formatter']( $data[$i] );
	//                     }
	//                     else{
	//                         $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
	//                     }
	// 				}
	// 				else {
	//                     if(!empty($column['db'])){
	//                         $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
	//                     }
	//                     else{
	//                         $row[ $column['dt'] ] = "";
	//                     }
	// 				}
	// 			}
	// 			$out[] = $row;
	// 		}*/
	// 		$out=[];
	//         if($data!=[]) {
	//             $data=json_decode(json_encode($data), true);
	//     		for ( $a=0;$a<$limit;$a++) {
	//     			$row = [];
	//     			$key=array_keys($data[0]);
	//     			for ( $b=0;$b<count($key);$b++) {
	//     				array_push($row, $data[$a][$key[$b]]);
	//     			}
	//     			array_push($out,$row);
	//     		}
	//         }
	// 		//dd($out);
	//
	//
	// 		/*
	// 		 * Output
	// 		 */
	// 		return array(
	// 			"draw"            => isset ( $request['draw'] ) ?
	// 				intval( $request['draw'] ) :
	// 				0,
	// 			"recordsTotal"    => intval( $recordsTotal ),
	// 			"recordsFiltered" => intval( $recordsFiltered ),
	// 			"data"            => $out
	// 		);
	//
	//
	// 	}
	//
	//     function combine($s1, $s2) {
	//         $s=$s1." ".$s2." ";
	//         if($s1<>"" and $s2<>"") {
	//             $s=$s1." and ".$s2." ";
	//         }
	//         return $s;
	//     }


	// function ssp_lama ( $request, $table, $primaryKey, $columns,  $whereAll=null )
	// {
	// 	$bindings = array();
	// 	//$db = self::db( $conn );
	// 	$localWhereResult = array();
	// 	$localWhereAll = array();
	// 	$whereAllSql = '';
	// 	//dd($request);
	//
	// 	$table="transhead";
	// 	$primaryKey="TransNo";
	// 	$fld="TransNo,TransDate,AccName,Total,CreatedBy";
	// 	$where="where left(TransNo,2)='DO'";
	// 	$order="order by TransNo";
	// 	$limit=$request['length']; //"10";
	// 	$sql="SELECT $fld
	// 				FROM `transhead`
	// 				$where
	// 				$order
	// 				limit $limit";
	// 	$data = DB::select($sql);
	//
	// 	// Data set length after filtering
	// 	/*$resFilterLength = self::sql_exec( $db, $bindings,
	// 		"SELECT COUNT(`{$primaryKey}`)
	// 		 FROM   `$table`
	// 		 $where"
	// 	);
	// 	$recordsFiltered = $resFilterLength[0][0];*/
	// 	$resFilterLength = DB::select("SELECT COUNT(`{$primaryKey}`)
	// 	FROM   `$table`
	// 	$where");
	// 	$recordsFiltered = $resFilterLength;
	//
	// 	// Total data set length
	// 	/*$resTotalLength = self::sql_exec( $db, $bindings,
	// 		"SELECT COUNT(`{$primaryKey}`)
	// 		 FROM   `$table` ".
	// 		$whereAllSql
	// 	);
	// 	$recordsTotal = $resTotalLength[0][0];*/
	// 	$resTotalLength = DB::select("SELECT COUNT(`{$primaryKey}`)
	// 	FROM   `$table` ".
	// 	   $whereAllSql);
	// 	$recordsTotal = $resTotalLength;
	//
	// 	// prepare out
	// 	/*
	// 	$out = array();
	// 	for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
	// 		$row = array();
	// 		for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
	// 			$column = $columns[$j];
	//
	// 			// Is there a formatter?
	// 			if ( isset( $column['formatter'] ) ) {
   //                  if(empty($column['db'])){
   //                      $row[ $column['dt'] ] = $column['formatter']( $data[$i] );
   //                  }
   //                  else{
   //                      $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
   //                  }
	// 			}
	// 			else {
   //                  if(!empty($column['db'])){
   //                      $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
   //                  }
   //                  else{
   //                      $row[ $column['dt'] ] = "";
   //                  }
	// 			}
	// 		}
	// 		$out[] = $row;
	// 	}*/
	// 	$out=[];
	// 	$data=json_decode(json_encode($data), true);
	// 	for ( $a=0;$a<$limit;$a++) {
	// 		$row = [];
	// 		$key=array_keys($data[0]);
	// 		for ( $b=0;$b<count($key);$b++) {
	// 			array_push($row, $data[$a][$key[$b]]);
	// 		}
	// 		array_push($out,$row);
	// 	}
	// 	//dd($out);
	//
	//
	// 	/*
	// 	 * Output
	// 	 */
	// 	return array(
	// 		"draw"            => isset ( $request['draw'] ) ?
	// 			intval( $request['draw'] ) :
	// 			0,
	// 		"recordsTotal"    => intval( $recordsTotal ),
	// 		"recordsFiltered" => intval( $recordsFiltered ),
	// 		"data"            => $out
	// 	);
	//
	//
	// }






}
