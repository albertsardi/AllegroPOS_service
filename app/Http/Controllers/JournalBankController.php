<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Model\Journal;

class JournalBankController extends MainController {
	
	function accountdetaillist($id)
	{
		//return $jr;
		//show view
		$jr='accountdetail';
		$data['jr'] = $jr;
		$data['grid'] = $this->makeTableList($jr);
      	$data['caption'] = $this->makeCaption($jr);
      	$data['_url'] = env('API_URL').'/api/trans/accdetail/'.$id;
		return view('translist', $data);
	}

	function transedit($id)
	{
		$jr = substr($id, 0, 2);
      	$data = [
			'modal' => '',
			'jsmodal' => '',
			'jr' => $jr,
      		'id' => $id,
         	'caption' => $this->makeCaption($jr, $id)
		];
		  
		if ($jr == 'CR' || $jr == 'CD') {
			$data = array_merge($data, [
            	'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
			   	//'mType'  => ['Raw material','Finish good'],
			   	// 'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
            	// 'mHpp'   => ['Average'],
            	'data'   => [],
        	]);
		
        	// get data
			$res = Journal::Get($id);
				if ($res->status=='OK') {
				$data['data'] = $res->data;
				$data['griddata'] = json_encode($res->data->detail);
			}
			return view('form-bank-edit', $data); // using agGrid
    	}
		
	}

	function datasave() {
		$err= '';
		$jr=Input::get('formtype'); //return $jr;

		//save PI
		if($jr=='PI') {
			// 1. setting validasi
			$validator = Validator::make(Input::all(),
						[
							"TransNo"                 => "required",
							"AccCode"                 => "required",
						]
					);
			// 2a. jika semua validasi tidak terpenuhi keluar dari program
			if (!$validator->passes()) return 'ERROR: validasi error<br/>'.strval($validator);

			//save data to database
			$err= '';
			$db= 'transhead';
			$fld=['TransNo', 'Transdate','AccCode'];
			// $dat= [  //panel1
			// 		'Code'=> Input::get('Code'),
			// 		'Name'=> Input::get('Name'),
			// 		'SellPrice'=> Input::get('SellPrice'),
			// 		'Barcode'=> Input::get('Barcode'),
			// 		'Category'=> Input::get('Category'),
			// 		'Type'=> Input::get('Type'),
			// 		'HppBy'=> Input::get('HppBy'),
			// 		'ActiveProduct'=> Input::get('ActiveProduct'),
			// 		'StockProduct'=> Input::get('StockProduct'),
			// 		'canBuy'=> Input::get('canBuy'),
			// 		'canSell'=> Input::get('canSell'),
			// 		//panel2
			// 		'UOM'=> Input::get('UOM'),
			// 		'ProductionUnit'=> Input::get('ProductionUnit'),
			// 		'MinStock'=> Input::get('MinStock'),
			// 		'MaxStock'=> Input::get('MaxStock'),
			// 		'SellPrice'=> Input::get('SellPrice'),
			// 		'AccHppNo'=> Input::get('AccHppNo'),
			// 		'AccSellNo'=> Input::get('AccSellNo'),
			// 		'AccInventoryNo'=> Input::get('AccInventoryNo'),
			// ];
			$dat = $this->field_array($fld);
		}
		//save AP & AR
		if( in_array($jr, ['AP','AR'])) {
			// $validator = Validator::make(Input::all(),
			// 			[
			// 				"TransNo"                 => "required",
			// 				"AccCode"                 => "required",
			// 			]
			// 		);
			// if (!$validator->passes()) return 'ERROR: validasi error<br/>'.strval($validator);

			$err= '';
			$id= Input::get('TransNo');

			//save form query
			$err= '';
			$db= 'transpaymenthead';
			$fld=['TransNo', 'TransDate','TaxNo','AccCode','toAccNo','Memo','Total'];
			$dat = $this->field_array($fld);
			$dat['AccName']= 'AccName'; //todo this
			$dat['CreatedBy'] = 'USER';
			$dat['CreatedDate']= date("Y-m-d H:m:s");
			$query1= $this->form_query($db, $fld, $dat);
			// dd($query1);

			//save grid query
			$db= 'transpaymentarap';
			$fld= ['InvNo', 'AmountPaid'];
			$query2= $this->egrid_query($db, $fld, 'InvNo', ['TransNo'=>$id, 'Memo'=>'']);
		}
		//save IN, DO
		if( in_array($jr, ['DO','IN']) ) {
			// TODO: insert validator here$validator = Validator::make(Input::all(),

			$err= '';
			$id= Input::get('TransNo');

			//save form query
			$db1= 'transhead';
			$fld=['TransNo', 'TransDate','AccCode','Warehouse','TaxNo','Total'];
			$dat['AccName']= 'AccName'; //todo this
			$dat['CreatedBy'] = 'USER';
			$dat['CreatedDate']= date("Y-m-d H:m:s");
			$query1= $this->form_query($db1, $fld, $dat);
			// dd($query1);

			//save grid query
			$db2= 'transdetail';
			$key= 'ProductCode';
			$post= $_POST;
			$dat='';
			for($a=0;$a<count($post["grid-$key"]);$a++) {
				if ($post["grid-$key"][$a]!='') {
					$r=[];
					$r['TransNo']= $id;
					$r['InvNo']= '';
					$r['ProductCode']= $this->cell($a, 'ProductCode');
					$r['ProductName']= $this->cell($a, 'ProductName');
					$r['Qty']= $this->cell($a, 'Qty');
					$r['UOM']= $this->cell($a, 'UOM');
					$r['Price']= $this->cell($a, 'Price');
					$r['Cost']= 0; //TODO
					$fs= implode(',', array_keys($r));
					$rs= "('".implode("','", array_values($r))."'),";
					$dat.= $rs;
				}
			}
			$dat= substr($dat,0,-1);
			$query2="insert into $db2($fs) values".$dat;
			// dd($query2);
		}
		//save SI
		// if($jr=='IN') {
		// }

		//save data FORM
		/*$post = DB::insert($query1);
		if ($post) {
			return 'Data Saved.';
		} else {
			// 2b. jika tidak, kembali ke halaman form registrasi
			// return Redirect::to("product-edit/BENANG-KARET")
			//        ->withErrors($validator)
			//        ->withInput();
			return 'ERROR: updating record :<br/> '.$validator;
		}
		*/

		DB::beginTransaction();
		try {
			//save data FORM
			//DB::insert($query1);
			//if (!$post) return 'ERROR: updating record :<br/> ';
			//save data GRID
			DB::delete("delete from sstransdetail where TransNo='$id' ");
			//if (!$post) return 'ERROR: deleting record :<br/> ';
			//dd("delete from transdetail where TransNo='$id' ");
			//$post = DB::insert($query2);
			//if (!$post) return 'ERROR: updating record :<br/> ';
			DB::commit();
			echo 'Data suksesfull save';
		} catch (\Exception $e) {
			DB::rollback();
			throw $e;
			//echo 'Data error '.$e->getErrors();

		}

	}

}
