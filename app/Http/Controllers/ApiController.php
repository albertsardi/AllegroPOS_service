<?php
   
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Transaction;

class ApiController extends MainController {
	
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

	public function getdata($jr, $id='') {
		return "getdata $jr $id";
		return json_encode($jr);
		$db = $jr;
		if (in_array($jr, ['product'])) $db ='masterproduct';
		if (in_array($jr, ['customer','supplier'])) $db ='masteraccount';
		if ($id!='') {
			$dat=$dat->find($id);
						//->limit(5)
						//->get();
		} else {
			$dat = $dat->get();
			if ($jr=='customer') $dat=$dat->where('AccType','C')->get();
			if ($jr=='supplier') $dat=$dat->where('AccType','S')->get();
		}
		return json_encode($dat);
	}

	public function datasave(Request $req) {

		$err='';
		try{
			$save = $req->all();
			//dump($save);
			DB::beginTransaction();
			if (in_array(req->jr=='product')) {
				$data = Product::where('Code', $req->Code)->first();
				if (empty($data->id)) {
					//add new
					Product::save($save);
				} else {
					//update
					Product::where("id", $data->id)->update($save);
				}
				//return response()->json(['success'=>'data saved', 'input'=>$input]);
				$message='Sukses!!';
			}
			if (in_array(req->jr,['customer','supplier'])) {
				$accType = ($req->jr=='customer')? 'C':'S';
				$data = CustomerSupplier::updateOrCreate(
					['AccType'=>$accType ],
					$save);

				DB::commit();
				//return response()->json(['success'=>'data saved', 'input'=>$input]);
				$message='Sukses!!';
			}
			return redirect(url( $req->path() ))->with('success', $message);
		}
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			return redirect(url( $req->path() ))->with('error', $e->getMessage());
		}
	}

}
