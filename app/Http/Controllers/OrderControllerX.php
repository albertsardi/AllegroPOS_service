<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TransController extends MainController {
	
// function translist($jr) {
// 	//show view
// 	switch($jr) {
// 		case 'PO':
// 			$sql = `SELECT *,'OPEN' as Status FROM orderhead WHERE left(TransNo,2)='${req.params.jr}' `;
// 			break;
// 	}
   
//    $data['grid'] = $this->makeTableList($data['grid']);
//    return view('translist', $data);
// }

function makeTableList($caption) {
   $head= '<th>'.implode('</th><th>',$caption).'</th>';
   $head= '<thead><tr>'.$head.'</tr></thead>';
   $tbl= $head.'<tbody></tbody>';
   $tbl= str_replace('<th>Status</th>','',$tbl); 
   return $tbl;
 }

	function accountdetaillist($id)
	{
		//return $jr;
		//show view
		$jr='accountdetail';
		$data['jr'] = $jr;
		$data['grid'] = $this->makeTableList($jr);
      $data['caption'] = $this->makeCaption($jr);
      $data['_url'] = env('API_URL').'/api/trans/accdetail/'.$id;
		// dd('xxx');
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
				$dat = json_decode(json_encode($dat), True);
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

























	

	function gettransstatus($transno, $jr)
	{
		switch ($jr) {
			case 'PI':
				$dat = $this->DB_array('transpaymentarap', "sum(AmountPaid)", "InvNo='$transno' ");
				$dat = (int) ($dat);
				if (count($dat) > 0) return "CLOSED";
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
		}
	}

	

	function transedit($id)
	{
		$data['modal'] = '';
		$data['jsmodal'] = '';
      $jr = substr($id, 0, 2); $data['id'] = $id;
      $data['jr'] = $jr; $data['id'] = $id;
	   $data['caption'] = $this->makeCaption($jr, $id);

		if ($jr == 'PI') {
			$post = DB::table('transhead')->where('TransNo', $id)->first();
			$post = json_decode(json_encode($post), true);
			config(['post' => $post]);
			$data['post'] = $post;
			$dat2 = DB::select("SELECT ProductCode,ProductName,abs(Qty)as Qty,Price
									FROM transdetail
									WHERE TransNo='$id' ");
			$dat2 = json_decode(json_encode($dat2), true);
			config(['grid' => $dat2]);
			//print_r($dat2);

			//for combolist
			//$data['mWarehouse']= DB::table('masterwarehouse')->select('warehouse')->get();
			//$data['mWarehouse']= json_decode(json_encode($data['mWarehouse']), true);
			$data['mWarehouse'] = $this->DB_list('masterwarehouse', 'warehouse');
			$data['mAcc'] = $this->DB_list('masteraccount', 'AccCode,AccName');
			// $data['mAcc'] = $this->DB_list('masteraccount', 'AccCode');
			$data['mType'] = ['Raw material', 'Finish good'];
			$data['mHpp'] = ['Average'];

			//for modal Supplier
			$dat = $this->DB_array('masteraccount', 'AccCode,AccName', "AccType='S' ");
			$caption = ['Supplier #', 'Supplier Name'];
			$data['modal'] = Metro::CreateLookupTable('modal-supplier', $dat, $caption);
			$data['jsmodal'] = jsarray($dat, 'msupplier');
			//for modal Product
			$dat = $this->DB_array('masterproduct', 'Code,Name', 'ActiveProduct=1');
			$caption = ['Account#', 'AccountName'];
			$data['modal'] .= Metro::CreateLookupTable('modal-product', $dat, $caption);
			$data['jsmodal'] .= jsarray($dat, 'mproduct');
			$data['jsmodal'] = '<script>' . $data['jsmodal'] . '</script>';

			$data['code'] = $id;
			$data['jr'] = $jr;
			$data['user'] = ['Code' => '123'];
			return view('form-buy', $data);
			//return view('form-buy-awal', $data);
		}
		if ($jr == 'DO') {
         //for combolist
			$data['mCat']= $this->DB_list('masterproductcategory', 'Category');
			$data['mType']=['Raw material','Finish good'];
			$data['mHpp']=['Average'];
		
         //form
         $data['data']=[];
			$res = $this->api('GET', 'api/trans/DO/'.$id);
			if ($res->status=='OK') {
				$data['data']=$res->data;
         }
         
         //grid
         $data['grid_url'] = env('API_URL').'/api/gridload/DO/'.$id;
         return view('form-do', $data);
         
         
         
         //modal lama
			$post = DB::table('transhead')->where('TransNo', $id)->first();
			$post = json_decode(json_encode($post), true);
			config(['post' => $post]);
			$data['post'] = $post;
			// $dat2 = DB::table('transdetail')->where('TransNo', $id)->get();
			$dat2 = DB::select("SELECT ProductCode,ProductName,abs(Qty)as Qty,Price
									FROM transdetail
									WHERE TransNo='$id' ");
			$dat2 = json_decode(json_encode($dat2), true);
			config(['grid' => $dat2]);

			//for combolist
			$data['mWarehouse'] = $this->DB_list('masterwarehouse', 'warehouse');
			$data['mAcc'] = $this->DB_list('masteraccount', 'AccCode,AccName');
			$data['mType'] = ['Raw material', 'Finish good'];
			$data['mHpp'] = ['Average'];

			//for modal Product
			$dat = $this->DB_array('masterproduct', 'Code,Name', 'ActiveProduct=1');
			$caption = ['Account#', 'AccountName'];
			$data['modal'] .= Metro::CreateLookupTable('modal-product', $dat, $caption);
			$data['jsmodal'] .= jsarray($dat, 'mproduct');
			$data['jsmodal'] = '<script>' . $data['jsmodal'] . '</script>';

			$data['code'] = $id;
			$data['jr'] = $jr;
			$data['user'] = ['Code' => '123'];
			return view('form-do', $data);
			
		}
		if ($jr == 'IN') {
			// $post = DB::table('transhead')->where('TransNo', $id)->first();
			//$h= Transhead::find($id);
			//$d= Transdetail::where('TransNo', $id);
			// $post = json_decode(json_encode($post), true);
			// config(['post' => $post]);
			// $data['post'] = $post;
			$dat2 = DB::table('transdetail')->selectRaw('ProductCode,ProductName,abs(Qty)as Qty,Price')
						->where('InvNo', $id)->get();
			// $dat2 = json_decode(json_encode($dat2), true);
			// config(['grid' => $dat2]);

			//for combolist
			$data['mWarehouse'] = []; //$this->DB_list('masterwarehouse', 'warehouse');
			$data['mAcc'] = []; //$this->DB_list('masteraccount', 'AccCode,AccName');
			// $data['mProduct'] = $this->DB_list('masterproduct', 'Code,Name', "ActiveProduct='1' ");

			//for modal Product
			$dat = $this->DB_list('masterproduct', 'Code,Name', 'ActiveProduct=1');
			$caption = ['Product#', 'Product Name'];
			//$data['modal'] .= Metro::CreateLookupTable('modal-product', $dat, $caption);
			//$data['jsmodal'] .= jsarray($dat, 'mproduct');
			//$data['jsmodal'] = '<script>' . $data['jsmodal'] . '</script>';
			
			$data['user'] = ['Code' => '123'];
			return view('form-si', $data);
		}
		if ($jr == 'EX') {
			$post = DB::table('journal')->where('ReffNo', $id)->first();
			$post = json_decode(json_encode($post), true);
			config(['post' => $post]);
			$data['post'] = $post;
			$dat2 = DB::table('transdetail')->where('TransNo', $id)->get();
			$data['grid'] = json_decode(json_encode($dat2), true);

			//for combolist
			//$data['mWarehouse']= DB::table('masterwarehouse')->select('warehouse')->get();
			//$data['mWarehouse']= json_decode(json_encode($data['mWarehouse']), true);
			//$data['mWarehouse']= $this->DB_list('masterwarehouse', 'warehouse');
			//$data['mType']=['Raw material','Finish good'];
			//$data['mHpp']=['Average'];

			//for modal & js modal
			//$dat= $this->DB_array('masteraccount', 'AccCode,AccName');
			//$caption=['Account#','AccountName'];
			//$data['modal']= CreateLookupTable('modal-supplier', $dat, $caption);
			//$data['jsmodal']= jsarray($dat, 'msupplier');

			//for modal
			$dat = $this->DB_array('mastercoa', 'AccNo,AccName');
			$caption = ['Account#', 'AccountName'];
			$data['modal'] = CreateLookupTable('modal-account', $dat, $caption);
			//for js modal
			//$dat= $this->DB_array('mastercoa', 'AccNo,AccName');
			//$caption=['Account#','AccountName'];
			//$data['jsmodal'] = jsarray($dat, 'mcoa');

			$data['code'] = $id;
			$data['jr'] = $jr;
			$data['user'] = ['Code' => '123'];
			//return view('form-buy', $data);
			return view('form-ex', $data);
		}
		if ($jr == 'AR') {
			$post = DB::table('transpaymenthead')->where('TransNo', $id)->first();
			$post = json_decode(json_encode($post), true);
			config(['post' => $post]);
			$data['post'] = $post;
			// $dat2 = DB::table('transpaymentarap')
			// 	->leftjoin('transhead', 'TransNo', 'InvNo')
			// 	->where('TransNo', $id)
			// 	//->order('id')
			// 	->get();
			$dat2 = DB::select("SELECT InvNo,TransDate AS InvDate,Total as InvTotal,AmountPaid
                                FROM TransPaymentArap
                                LEFT JOIN TransHead ON transhead.TransNo=transpaymentarap.InvNo
                                WHERE transpaymentarap.TransNo = '$id'
                                ORDER BY transpaymentarap.id");
			$dat2 = json_decode(json_encode($dat2), true);
			config(['grid' => $dat2]);

			//for combolist
			//$data['mWarehouse'] = $this->DB_list('masterwarehouse', 'warehouse');

			//for modal Invoice
			$dat = $this->DB_array('transhead', 'TransNo,Transdate,Total', "AccCode='".$post['AccCode']."' ");
			$caption = ['Invoice#', 'Invoice Date', 'Total'];
			$data['modal'] .= Metro::CreateLookupTable('modal-inv', $dat, $caption);
			$data['jsmodal'] .= jsarray($dat, 'minv');
			$data['jsmodal'] = '<script>' . $data['jsmodal'] . '</script>';

			$data['code'] = $id;
			$data['jr'] = $jr;
			$data['user'] = ['Code' => '123'];
			return view('form-arap', $data);
		}
		if ($jr == 'AP') {
			$post = DB::table('transpaymenthead')->where('TransNo', $id)->first();
			$post = json_decode(json_encode($post), true);
			config(['post' => $post]);
			$data['post'] = $post;
			$dat2 = DB::select("SELECT InvNo,TransDate AS InvDate,Total as InvTotal,AmountPaid
                                FROM TransPaymentArap
                                LEFT JOIN TransHead ON transhead.TransNo=transpaymentarap.InvNo
                                WHERE transpaymentarap.TransNo = '$id'
                                ORDER BY transpaymentarap.id ");
			$dat2 = json_decode(json_encode($dat2), true);
			config(['grid' => $dat2]);
			// dd($dat2);

			//for combolist
			$data['mBank'] = $this->DB_list('mastercoa', 'AccNo,AccName',"CatName='Cash & Bank' ");
			$data['mSupplier'] = $this->DB_list('masteraccount', 'AccCode,AccName',"AccType='S' ");

			//for modal Invoice
			$dat = $this->DB_array('transhead', 'TransNo,Transdate,Total', "AccCode='".$post['AccCode']."' ");
			$caption = ['Invoice#', 'Invoice Date', 'Total'];
			$data['modal'] .= Metro::CreateLookupTable('modal-inv', $dat, $caption);
			$data['jsmodal'] .= jsarray($dat, 'minv');
			$data['jsmodal'] = '<script>' . $data['jsmodal'] . '</script>';

			$data['code'] = $id;
			$data['jr'] = $jr;
			$data['user'] = ['Code' => '123'];
			return view('form-ap', $data);
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

	function createModal($type)
	{
		switch ($type) {
			case 'supplier':
				$dat = (array) DB::table('masteraccount')->where('AccType', 'S')->get();
				$caption = ['Supplier #', 'Supplier Name'];
				return $this->MetroCreateLookupTable("lookup-$type", $dat, $caption);
				break;
			case 'product':
				$dat = $this->DB_array('masterproduct');
				$caption = ['Product #', 'Product Name'];
				return $this->MetroCreateLookupTable("lookup-$type", $dat, $caption);
				break;
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











}
