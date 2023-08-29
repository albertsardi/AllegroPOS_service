<?php
   
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\MainapiController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Transaction;

class SelectController extends MainController {
	
	// api tuk populate select2
	function selectData($jr, Request $req) {
		if (in_array($jr, ['salesman','coa'])) $jr='master'.$jr;
		switch($jr) {
			//master
			case 'payment':
				//$data = DB::table('common')->selectRaw('catid,name1')->where('category','Payment')->get();
				$data = DB::table('common')->selectRaw('catid as id,name1 as text')->where('category','Payment');
				break;
			case 'mastersalesman':
				$data = DB::table($jr)->selectRaw('Code as id,Name as text');
				break;
			case 'mastercoa':
				//$data = DB::table($jr)->selectRaw('id,AccName as text');
				$data = DB::table($jr)->selectRaw('id,AccNo as text');
				if(isset($req->catname)) $data = $data->where('CatName',$req->catname);
				break;
			case 'customer':
			case 'supplier':
				$acctype = ($jr=='customer')? 'C':'S';
				$data = DB::table('masteraccount')->selectRaw("AccCode as id,concat(AccCode,'|',AccName)as text")->where('AccType',$acctype);
				break;

			//trans
			case 'DO':
			case 'SI':
			case 'IN':
				$dat = DB::table('transhead')->selectRaw("TransNo,TransDate,AccName,Total,'' as Status,CreatedBy,id")->whereRaw("left(TransNo,2)='$jr' ")
						->limit(5);
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
		//$data = $data->where('Token','')->get();
		$data = $data->get();

		return json_encode( ['results'=>$data] );
	}


}
