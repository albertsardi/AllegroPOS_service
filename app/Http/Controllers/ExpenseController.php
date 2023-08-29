<?php
   
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Input;
// use Illuminate\Support\Facades\DB;
// use App\Report\MyReport;
use App\Http\Model\Expense;
// use Codedge\Fpdf\Fpdf\Fpdf; //for using Fpdf
// use App\Report\Tpdf;
use Session;

class ExpenseController extends MainController {

	public $addNew = null;

	function transedit($id)
	{
		$jr = 'EX';
		$data = [
			'jr' 		=> $jr,
      	    'id' 		=> $id,
            'caption'	=> $this->makeCaption($jr, $id),
			'data'		=> []
        ];

		$data = array_merge($data, [
			'select'	=> $this->selectData(['selSupplier','selWarehouse']),
			// 'mProduct'	=> ($this->modalData(['modProduct'])),
			// 'mSupplier'	=> ($this->modalData(['modSupplier'])),
		]);
		//$modal = $this->modalData(['mCat', 'mSupplier','mProduct','mWarehouse']);
		//$select = $this->selectData(['selSupplier','selWarehouse']);
		$view = 'form-ex';
		$res = Expense::getdata($id);
        

		// get modal data
		//if (isset($modal)) $data = array_merge($data, (array)$modal);

		// get select data
		//$data['select'] = isset($select)? $select:[];
		
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

}
