<?php
   
namespace App\Http\Controllers\Api;

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Product;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Account;
use App\Http\Model\Trans;
use App\Http\Model\TransDetail;
use App\Http\Model\Order;
use App\Http\Model\OrderDetail;
use App\Http\Model\Payment;
use App\Http\Model\PaymentDetail;

class ApiController extends MainController {
	
	// api tuk get data
	function getdata($jr, $id='', Request $req) {
		$db = $jr;
		//master
		if (in_array($db, ['product','productprice','customer','supplier','profile','bank','coa'])) $db = 'master'.$db;
		//trans
		if (in_array($jr, ['SO','PO'])) $db = 'orderhead';
		if($id=='') {
			// $data = DB::table($db)->where('Token','ZZXXAA');
			//$data = DB::table($db)->where('Token', session('token'));
			$data = DB::table($db);
			if (isset($req->trans_id)) $data=$data->where('trans_id', $req->trans_id);
			if (isset($req->code)) $data=$data->where('code', $req->code);
			$data = $data->get();
		} else {
			$data = DB::table($db)
					->where('Token', session('token'))
					->where('id',$id)
					->first();
		}
		return ($data);
		//return json_encode($data);
	}


	// api tuk save data
	function datasave($jr='',$id='', Request $req) {
		if (strtolower($id)=='new') $id = '';
		$master = ['product','customer','supplier','profile','bank','coa'];
		if (in_array($jr, $master)) {
			return $this->mastersave($jr, $id, $req);
		} else {
			return $this->transsave($jr, $id, $req);
		}
		//if(!isset($model)) return $this->responseError("no save jr from $jr ");
	}

	// save master
	function mastersave($jr='',$id='', Request $req) {
		//return dd($id);
		if (strtolower($id)=='new') $id = '';
		// master data save     
		if ($jr == 'product') $model = new Product;
		if ($jr == 'customer') $model = new CustomerSupplier;
		if ($jr == 'supplier') $model = new CustomerSupplier;
		if ($jr == 'profile') $model = new Profile;
		if ($jr == 'coa') $model = new Account;
		if ($jr == 'bank') $model = new Bank;
		if(!isset($model)) return $this->responseError("no save jr from $jr ");

		$save = $req->all();
		unset($save['id']);
		try {
			if (empty($id)) {
				if($jr=='coa') { //if coa buat id dari AccNo
					$id = preg_replace('/[^0-9]+/', '', $save['AccNo']);
					$data = $model->updateOrCreate(
						['id'=>$id],
						$save);
				} else {
					// create new
					$data = $model->create($save);
					$save['id'] = $data->id;
				}
			} else {
				// update
				$data = $model->find($id)->update($save);
			}
			return $this->responseOk(['data'=>$save]);
		}
		catch (Exception $e) {
			return response()->json(['error'=>$e->getMessage()]);
		}
	}

	// save transaction
	function transsave($jr='',$id='', Request $req) {
		$err= '';
		if (in_array($jr, ['SO','PO'])) { $model = new Order; $modeldetail = new OrderDetail; }
		if (in_array($jr, ['SI','DI','PI'])) { $model = new Trans; $modeldetail = new TransDetail; }
		if (in_array($jr, ['AR','AP'])) { $model = new Payment; $modeldetail = new PaymentDetail; }
		if (!isset($model)) return $this->responseError("no save jr from $jr ");

		$save = $req->all();
		$save = $this->alterDateFormat($save);
		DB::beginTransaction();
		try {
			// HEAD update
			if (empty($id)) {
				$save['TransNo'] = $this->createNewTransNo($jr, $model);
				if(in_array($jr, ['SI','DI','PI'])) $save['DoNo'] = str_replace($jr.'.', 'DO.', $save['TransNo']);
				$data = $model->create($save);
				$save['id'] = $data->id;
			} else {
				$model->find($id)->update($save);
			}
			
			// DETAIL update
			$save['detail'] = [];
			$detail = json_decode($req->detail);
			if (!empty($detail)) {
				$modeldetail->where("TransNo", $req->TransNo)
						->where("Token", $req->Token)
						->delete();
				foreach($detail as $r) {
					if(isset($r->ProductCode)&&empty($r->ProductCode)) continue; //not save if detail key is blank
					$r->TransNo		= $save['TransNo'];
					$r->trans_id	= $save['id'];
					$r->Token 		= $req->Token;
					$out = (array)$r;
					$save['detail'][] = $modeldetail->create($out);
				}
			}

			//commit data to save
			DB::commit();
			return $this->responseOk(['data'=>$save]);
			//return response()->json(['status'=>'OK']);
		} 
		catch (Exception $e) {
			DB::rollback();
			return response()->json(['error'=>$e->getMessage()]);
	  	}
	}

	function responseOk($resp) {
		return response()->json([
			'status' => 'OK',
			'data' => $resp['data']??''
		]);
	}

	function responseError($resp) {
		//dd($resp);
		return response()->json([
			'status' => 'Error',
			'message' => $resp??''
		]);
	}

	function createNewTransNo($jr, $model) {
		$dat = $model->whereRaw("left(TransNo,2)='$jr' ")
				->orderBy('TransNo', 'desc')
				->limit(1)
				->first();
		if(empty($dat)) {
			$lastno = 0;
		} else {
			$lastno = (int)substr($dat->TransNo, 5);
		}
		$newno = $lastno + 1;
		$newno = $jr.".".date('y').str_repeat('0',5-strlen($newno)).$newno;
		return $newno;
	}

	function alterDateFormat($arr) {
		$fieldDate = ['transdate','duedate','dodate'];
		foreach($arr as $key => $val) {
			if (!in_array(strtolower($key), $fieldDate)) continue;
			$arr[$key] = substr($val,6).'-'.substr($val,3,2).'-'.substr($val,0,2);
		}
		return $arr;
	}

	

	
	
	// api ini hanya dipakai tuk ambil data tuk android
	function trans_list($jr) {
		switch($jr) {
			case 'DO':
			case 'SI':
			case 'IN':
				$dat = DB::table('transhead')->selectRaw("TransNo,TransDate,AccName,Total,'' as Status,CreatedBy,id")->whereRaw("left(TransNo,2)='$jr' ")
						->limit(5)
						->get();
				foreach($dat as $dt) {
					$dt->Status = 'OPEN'; //$this->gettransstatus($link, $jr);   
				}
				$data = [
					'jr'        => $jr,
					'caption'   => $this->makeCaption($jr),
					'data'      => $dat,
				];
				break;
			
			default:
				return "no list from $jr";
				break;
		}
		return $data;
	}

	// api ini hanya dipakai tuk ambil data tuk android
	function trans($jr, $id) {
		switch($jr) {
			case 'DO':
			case 'SI':
			case 'IN':
				$dat = DB::table('transhead as th')
						->whereRaw("left(TransNo,2)='$jr' ")
						->where('th.TransNo', $id)
						->get();
				$detail = DB::table('transdetail as td')
						->where('td.TransNo', $id)
						->get();
				$data = [
					'jr'        => $jr,
					'caption'   => $this->makeCaption($jr).' #'.$id,
					'data'      => $dat,
					'detail'    => $detail,
				];
				break;
			
			default:
				return "no list from $jr";
				break;
		}
		return $data;
	}

}
