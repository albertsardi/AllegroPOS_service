<?php
   
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Report\MyReport;
// use App\Http\Model\Common;
// use App\Http\Model\Salesman;
// use App\Http\Model\Transaction;
// use App\Http\Model\Order;
// use App\Http\Model\Delivery;
use App\Http\Model\Invoice;
// use App\Http\Model\Expense;
// use App\Http\Model\Warehouse;
// use App\Http\Model\Journal;
use Codedge\Fpdf\Fpdf\Fpdf; //for using Fpdf
use App\Report\Tpdf;
use Session;

class InvoiceController extends MainController {

	public $addNew = null;

	function transedit($id) {
		if(str_contains($_SERVER['REQUEST_URI'], 'edit/SI')) $jr='SI';
		if(str_contains($_SERVER['REQUEST_URI'], 'edit/PI')) $jr='PI';
        $data = [
			'jr' 		=> $jr,
      	    'id' 		=> $id,
            'caption'	=> $this->makeCaption($jr, $id),
			'data'		=> []
        ];
		// dd($data);
		$view = '';
		if ($jr=='IN') $jr='SI';   

		if ($jr == 'PI') {
			//$modal = $this->modalData(['mCat', 'mSupplier', 'mProduct', 'mPurchaseQuotation', 'mPayType', 'mSalesman', 'mWarehouse']);
			//$select = $this->selectData(['selSupplier','selWarehouse']);
			$data = array_merge($data, [
				'select'	=> $this->selectData(['selSupplier','selPayment','selWarehouse']),
				'mProduct'	=> ($this->modalData(['modProduct'])),
				// 'mSupplier'	=> ($this->modalData(['modSupplier'])),
			]);
			dump($data);
			$view = 'form-sipi';
			//$res = Invoice::getdata($jr, $id);
		}
		if ($jr == 'SI') {
			//$modal = $this->modalData(['mCat', 'mCustomer', 'mProduct', 'mAccount', 'mSO', 'mPayType', 'mWarehouse', 'mSalesman', 'mDO' ]);
			//$select = $this->selectData(['selCustomer','selPayment']);
			$data = array_merge($data, [
				'select'		=> $this->selectData(['selCustomer','selPayment','selSalesman','selWarehouse']),
				'mProduct'	=> ($this->modalData(['modProduct'])),
				//'mCustomer'		=> ($this->modalData(['modCustomer'])),
				'mSO'		=> ($this->modalData(['modSO'])),
			]);
			$view = 'form-sipi';
			//$res = Invoice::getdata($jr, $id);
		}

		// get modal data
		//dd($modal);
		//if (isset($modal)) $data = array_merge($data, $modal);

		// get select data
		//$data['select'] = isset($select)? $select:[];
		
		// get data
		$res = Invoice::getdata($jr, $id);
		if ($res->status=='OK') {
			if(!empty($res->data)) {
				//$res->data->status 	= $this->gettransstatus($id, $jr);
				$res->data->duedate='2023-01-22';
				$data['data']		= $res->data;
				$data['griddata'] 	= $res->data->detail;
				$data['data']->Status = $this->getTransStatus($jr, $data['data']->Status);
			} else {
				$data['data'] =(object)['status' => 'DRAFT','TransDate'=>date('d/m/Y'), 'DueDate'=>date('d/m/Y')];
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

}
