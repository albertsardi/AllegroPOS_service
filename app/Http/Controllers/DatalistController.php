<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Http\Model\User;
use App\Http\Model\Product;
// use App\Http\Model\AccountAddr;
use App\Http\Model\CustomerSupplier;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Profile;
use App\Http\Model\Account;
// use App\Http\Model\Bank;

use App\Http\Model\Transaction;
use App\Http\Model\Order;
use App\Http\Model\Payment;
use App\Http\Model\Invoice;
use App\Http\Model\Expense;

use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
use Session;

class DatalistController extends MainController {

    // Data list
    function datalist($jr) {
        $today = date('Y-m-d');
        //dump(session('user')->Token);
        //show view
        switch($jr) {
            case 'product':
                //$res = Product::selectRaw('Code,Name,UOM,Category,ActiveProduct,id')->where('Token', session('user')->Token)->get();
                $res = Product::selectRaw('Code,Name,UOM,Category,ActiveProduct,id')->get();
                foreach($res as $r) {
                    $r->Qty = DB::select(" CALL getProductQty('$r->Code','$today') ")[0]->Total ?? 0;
                }
                $data = [
                    'jr'        => $jr,
                    'grid'      => ['Product #','Product Name','Unit','Category','Quantity','Status'],
                    'caption'   => $this->makeCaption($jr),
                    '_url'      => env('API_URL').'/api/'.$jr,
                    // 'data'      => $this->db_query('masterproduct','Code,Name,UOM,Category,12345 as Qty'),
                    'data'      => $res,
                    'xxdatacol'   => json_encode([
                        [ 'data' => 'Code'],
                        [ 'data' => 'Name'],
                        [ 'data' => 'UOM'],
                        [ 'data' => 'Category']
                    ])
                ];
                break;
            case 'supplier':
            case 'customer':
                // $dat =CustomerSupplier::selectRaw('AccName,m.AccCode,Phone,Email,Address,Active,m.id')
                // 		->leftJoin('masteraccountaddr as ma', 'm.id', '=', 'ma.AccountId')
                // 		->where('DefAddr',1)
                // 		->where('Token', Session::get('Token'));
                //  $dat = ($jr=='supplier')? $dat->where('AccType','S')->get() : $dat->where('AccType','C')->get();
                //$res = CustomerSupplier::select('id','AccCode','AccName','AccType')->Get($jr);
                $res = CustomerSupplier::getdata($jr);
                // dump($res);
                // dd($res);
                $data = [
                    'jr'        => $jr,
                    'grid'      => ['Display Name','Code','Phone','Email','Address', 'Balance (Rp)', 'Status'],
                    'caption'   => $this->makeCaption($jr),
                    '_url'      => env('API_URL').'/api/'.$jr,
                    'data'      => $res->data,
                    // 'datacol'   => []
                ];
                break;
            case 'coa':
                $data = [
                    'jr'        => $jr,
                    'grid'      => ['Account #','Account Name','Category','Amount (Rp)',' '],
                    'caption'   => $this->makeCaption($jr),
                    '_url'      => env('API_URL').'/api/'.$jr,
                    'data'      => []
                ];
                //$res= Account::getdata()->data;
                $res = $this->api('GET', 'api/mastercoa');
			    if (!empty($res)) {
                    foreach($res as $r) {
                        $r->Bal = DB::select(" CALL getAccountAmount('".Session::get('Token')."','$r->id','$today') ")[0]->Total ?? 0;;
                    }
                    $data['data'] = json_encode($res);
                }
                
                
                break;
            case 'bank':
                $dat =  DB::table('masterbank')->selectRaw('BankAccName, BankAccNo, AccNo, BankType, 1234567 as Bal,id')->get();
                foreach($dat as $dt) {
                    $dt->Bal = Account::getAmount($dt->id);
                    }
                $data = [
                    'jr'        => $jr,
                    'grid'      => ['Bank Name', 'Bank Account#', 'Account#','Bank Type','Amount (Rp)', ' '],
                    'caption'   => $this->makeCaption($jr),
                    '_url'      => env('API_URL').'/api/'.$jr,
                    'data'      => $dat
                ];
                break;
        }
        
        $head= '<th>'.implode('</th><th>',$data['grid']).'</th>';
        $head= '<thead><tr>'.$head.'</tr></thead>';
        $data['grid'] = $head.'<tbody></tbody>';
        if ($jr=='coa') return view('test.coalist', $data);
        return view('datalist', $data);
    }

    // Trans list
    function translist($jr) {
		//show view
		if ($jr=='IN') $jr='SI';
		switch($jr) {
			case 'DO':
				// $dat = Delivery::selectRaw("transdelivery.TransNo,transdelivery.TransDate,oh.TransNo as OrderNo,oh.TransDate as OrderDate,oh.AccName,transdelivery.Total,transdelivery.Status,transdelivery.CreatedBy,transdelivery.CreatedDate,transdelivery.id")
				// 		->leftJoin('orderhead as oh', 'oh.TransNo', '=', 'transdelivery.OrderNo')
				// 		//->orderBy('transdelivery.TransDate', 'DESC')
				// 		->orderBy('transdelivery.TransNo', 'DESC')
				// 		->get();
				//$sql = "select * from transhead where left(TransNo,2)='DO' ";
				//$dat = DB::select(DB::raw($sql));
				$dat = Transaction::getdata($jr)->data;
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
				// $dat = DB::table('transinvoice as ti')->selectRaw("ti.TransNo,ti.TransDate,ti.OrderNo,ti.AccName,ti.Total,ti.CreatedBy,ti.CreatedDate,ti.Status,ti.id")
				// 				->leftJoin('orderhead as oh', 'oh.TransNo', '=', 'ti.OrderNo')
				// 				->whereRaw("left(ti.TransNo,2)='$jr' ")
				// 				->orderBy('ti.TransDate', 'desc')
				// 				->get();
				$dat = Invoice::getdata($jr)->data;
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
				//$dat = Order::whereRaw("left(TransNo,2)='$jr' ")->get();
				$dat = Order::getdata($jr)->data;
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
				//$dat = Order::whereRaw("left(TransNo,2)='$jr' ")->orderBy('TransDate', 'desc')->get();
				$dat = Order::getdata($jr)->data;
				foreach($dat as $dt) {
					$dt->Status = $this->gettransstatus($jr, $dt->Status);   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date', 'Customer', 'Remarks', 'Total', 'Status', 'CreatedBy', 'CreatedDate', 'UpdateDate'],
					'caption'   => $this->makeCaption($jr),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
					];
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
				//$dat = Expense::orderBy('TransDate')->get();
				$dat = Expense::getdata()->data;
				foreach($dat as $dt) {
					// $dt->Status = "<div class='text-success'>".$this->gettransstatus($jr, $dt->Status).'</div>';   
				}
				$data = [
					'jr'        => $jr,
					'grid'      => ['Transaction #', 'Date','Receiver', 'Payment by', 'Total', 'Created By', 'Created Date'],
					'caption'   => $this->makeCaption($jr),
					//'mAccount'  => json_encode(db::table('mastercoa')->select('AccNo','AccName','CatName')->get() ),
					'_url'      => env('API_URL').'/api/'.$jr,
					'data'      => $dat,
				];
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
				//$sql = "SELECT * FROM transpaymenthead WHERE left(TransNo,2)='$jr' ";
				//$dat = DB::select(DB::raw($sql));
				$dat = Payment::getdata($jr)->data;
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

		$head= '<th>'.implode('</th><th>',$data['grid']).'</th>';
        $head= '<thead><tr>'.$head.'</tr></thead>';
        $data['grid'] = $head.'<tbody></tbody>';
        return view('translist', $data);
	}
    
    // function db_query($db, $fld='*') {
    //     return DB::table($db)->get();
    // }

    
      

    

    function makeList($jr='') {
    switch($jr) {
        case 'product':
            $dat= DB::table('masterproduct')->select('Code','Name','UOM','Category')->get();
            $dat= json_decode(json_encode($dat), true);
            for($a=0;$a<count($dat);$a++) {
                $link=$dat[$a]['Code'];
                $dat[$a]['Code']= link_to("product-edit/$link", $link);
                $dat[$a]['Qty']= 1234; //$this->getProdBalance($link); //1234;
            }
            return $this->table_generate($dat,['Product #','Product Name','Unit','Category','Quantity']);
            break;

        // case 'product':
        //     $dat= $this->db_array('masterproduct', 'Code,Name,UOM,Category,0 as Qty');
        //     for($a=0;$a<count($dat);$a++) {
        //         $link=$dat[$a]['Code'];
        //         $dat[$a]['Code']= link_to("product-edit/$link", $link);
        //         $dat[$a]['Qty']= $this->getProdBalance($link); //1234;
        //     }
        //     return $this->table_generate($dat,['Product #','Product Name','Unit','Category','Quantity']);
        //     break;

        case 'customer':
            // $dat= $this->db_select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
            //                             FROM masteraccount
            //                             LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
            //                             WHERE AccType='C' ");
            // $dat= DB::table('masteraccount')->select("concat(masteraccount.AccCode,'|',AccName)","Phone","Email","Address","AccName")
            //                                     ->leftJoin('masteraccountaddr', 'masteraccountaddr.AccCode', '=', 'masteraccount.AccCode')->get();
            $dat= DB::select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
                                        FROM masteraccount
                                        LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                                        WHERE AccType='C' ");
            //$dat= DB::table('listaccount')->get();
            $dat= DB::table('masteraccount')->get();
            $dat= json_decode(json_encode($dat), true);
            for($a=0;$a<count($dat);$a++) {
                //$acc=explode('|', $dat[$a]['Acc']);
                //$dat[$a]['Acc']= link_to("customer-edit/".$acc[0], $acc[1]);
                //$dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
                $dat[$a]['Bal']= 1234567890;
            }
            return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
            break;

        case 'supplier':
            $dat= $this->DB_select("SELECT CONCAT(masteraccount.AccCode,'|',AccName)AS Acc,Phone,Email,Address,0 AS Bal
                                FROM masteraccount
                                LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                                WHERE AccType='S' AND DefAddr=1");
            for($a=0;$a<count($dat);$a++) {
                $acc=explode('|', $dat[$a]['Acc']);
                //$dat[$a]['Acc']= "<a href='supplier-edit.php?id=$acc[0]'>".$acc[1]."</a>";
                $dat[$a]['Acc']= link_to("supplier-edit/$acc[0]", $acc[1]);
                $dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'PI' ); //1234567890;
            }
            return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
            break;

        case 'coa':
            //   $dat= $this->db_select("SELECT mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0)
            //                               FROM mastercoa
            //                               LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
            //                               GROUP BY AccNo");
            $dat= Account::Get()->data;
            for($a=0;$a<count($dat);$a++) {
                $dat[$a]['AccNo']= link_to("accountdetail/".$dat[$a]['AccNo'], $dat[$a]['AccNo']);
            }
            return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
            break;

        case 'bank':
            $dat= $this->db_select("SELECT mastercoa.AccNo,AccName,CatName,ifnull(SUM(Amount),0)
                                        FROM mastercoa
                                        LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
                                        WHERE CatName='Cash & Bank'
                                        GROUP BY AccNo");
            for($a=0;$a<count($dat);$a++) {
                $dat[$a]['AccName']= "<a href='accountdetail.php?id=".$dat[$a]['AccNo']."'>".$dat[$a]['AccName']."</a>";
            }
            return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
            break;

        case 'bom':
            $dat= DB::select("SELECT pcode,(select Name from masterproduct where masterproduct_bomhead.PCode=masterproduct.Code)as pname,pcat,ptype
                                FROM masterproduct_bomhead
                                ORDER BY pcode,pname ");
            $dat= json_decode(json_encode($dat), true);
            for($a=0;$a<count($dat);$a++) {
                $dat[$a]['pcode']= link_to("bom-edit/".$dat[$a]['pcode'], $dat[$a]['pcode']);
            }
            return $this->table_generate($dat,['Product #','Product Name','Category','Type']);
            break;
    }
    }

}

