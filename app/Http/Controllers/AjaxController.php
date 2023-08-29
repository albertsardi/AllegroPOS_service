<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
// use HTML;
// use Validator;
// use SSP;
use App\Http\Model\Common;
use App\Http\Model\Product;
use App\Http\Model\Account;
use App\Http\Model\AccountAddr;
use App\Http\Model\Transhead;

class AjaxController extends MainController {

//LIST
   //Data List
   function ajax_datalist($jr)
   {
     $con=[
         'user'=> env('DB_USERNAME'),
         'pass'=> env('DB_PASSWORD'),
         'db'=> env('DB_DATABASE'),
         'host'=> env('DB_HOST')
     ];

     $w="";
     switch($jr) {
         case 'customer':
             //$table="masteraccount";
             //$table="listaccount";
             //$table="listcustomersupplier";
             $table="listcustomer";
             $primaryKey = "AccCode";
             $col = ['AccCode','AccName','Phone','Email','Address','Bal'];
             $w= "AccType='C'";
             break;
         case 'supplier':
             //$table="listcustomersupplier";
             $table="listsupplier";
             $primaryKey = "AccCode";
             $col = ['AccCode','AccName','Phone','Email','Address','Bal'];
             $w= "AccType='S'";
             break;
         case 'product':
             // $table="masterproduct";
             $table="listproduct";
             $primaryKey = "Code";
             /*$col = [
                 ['db'=>'Code', 'dt'=>0],
                 ['db'=>'Name', 'dt'=>1],
                 ['db'=>'UOM', 'dt'=>2],
                 ['db'=>'Category', 'dt'=>3],
                 ['db'=>'Bal', 'dt'=>4]
             ];*/
             $col = ['Code','Name','UOM','Category','Bal'];
             break;
         case 'coa':
                 $table="listcoa";
                 $primaryKey = "AccNo";
                 $col = ['AccNo','AccName','CatName','Amount'];
                 $w= "";
                 break;
     }

     //produt
     //$col="Code,Name,UOM,Category,Bal";

     //customer
     //$col="AccCode,AccName,Phone,Email,Address,Bal";

     //dd($_GET);
     $res= json_encode(
         //SSP::simple($_GET, $con, $table, $primaryKey, $col)
         //SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w )AccName,AccCode,Phone,Email,Address,Bal

         $this->SSP( $_GET, $table, $primaryKey, $col, $w, "" )
     );

     //dd(SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w ));

     //$xxx=$_GET;
     //$res=SSP::complex( $_GET, $con, $table, $primaryKey, $col, null, $w );

     return $res;
   }

   //Trans List
   // function ajax_translist($jr)
   // {
   // 	//return "XXXXX $jr";
   // 	$con=[
   // 		'user'=> env('DB_USERNAME'),
   // 		'pass'=> env('DB_PASSWORD'),
   // 		'db'=> env('DB_DATABASE'),
   // 		'host'=> env('DB_HOST')
   // 	];

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

   // 	$res= json_encode(
   // 		//SSP::simple($_GET, $con, $table, $primaryKey, $col)
   // 		//SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w )
   // 		//$this->SSP( $_GET, "transhead", "TransNo", "TransNo,TransDate,AccName,Total,CreatedBy", "left(TransHead,2)='DO'" )
   // 		$this->SSP( $_GET, $table, $primaryKey, $col, $w )
   // 	);
   // 	return $res;
   // }

   function ssp ( $request, $table, $primaryKey, $columns,  $where='', $order='' )
   {
   	$bindings = array();
   	//$db = self::db( $conn );
   	$localWhereResult = array();
         $localWhereAll = array();
         $whereAllSql="";
         //if ($where!='') $where="WHERE ".$where;
   	//dd($request);

   	//$table="transhead";
   	//$primaryKey="TransNo";
   	//$fld="TransNo,TransDate,AccName,Total,CreatedBy";
   	//$where="where left(TransNo,2)='DO'";
         //$order="order by Code";



         //where
         $search=isset($_GET['search']['value'])?$_GET['search']['value']:'';
         //$search='FRO'; //debug
         if($search!='') {
             //$where=$this->combine($where, "$primaryKey like '%$search%' ");
             $where=$this->combine($where, "($columns[0] like '%$search%' or $columns[1] like '%$search%') ");
         }
         if ($where!='') $where="WHERE $where ";

         //order by
         $order="";
         if ($order!='') $where="ORDER BY $order ";

         //start
         $start=isset($request['start'])?$request['start']:1;

         //limit
         $limit=isset($request['length'])?$request['length']:10;

         //columns
         $columns= implode(",", $columns);

         $resFilterLength = DB::select("SELECT COUNT(`{$primaryKey}`) as TOT
   	                                   FROM   `$table`
                                         $where");
         $recordsFiltered = $resFilterLength[0]->TOT;

         $resTotalLength = DB::select("SELECT COUNT(`{$primaryKey}`)
   	                                FROM   `$table` ".
                                     $whereAllSql);
         $recordsTotal = $limit;//$resTotalLength;

   	   $sql="SELECT $columns
   				FROM `$table`
   				$where
   				$order
   				limit $start,$limit ";
         $data = DB::select($sql);

   	// Data set length after filtering
   	/*$resFilterLength = self::sql_exec( $db, $bindings,
   		"SELECT COUNT(`{$primaryKey}`)
   		 FROM   `$table`
   		 $where"
   	);
   	$recordsFiltered = $resFilterLength[0][0];*/


   	// Total data set length
   	/*$resTotalLength = self::sql_exec( $db, $bindings,
   		"SELECT COUNT(`{$primaryKey}`)
   		 FROM   `$table` ".
   		$whereAllSql
   	);
   	$recordsTotal = $resTotalLength[0][0];*/


   	// prepare out
   	/*
   	$out = array();
   	for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
   		$row = array();
   		for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
   			$column = $columns[$j];

   			// Is there a formatter?
   			if ( isset( $column['formatter'] ) ) {
                     if(empty($column['db'])){
                         $row[ $column['dt'] ] = $column['formatter']( $data[$i] );
                     }
                     else{
                         $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
                     }
   			}
   			else {
                     if(!empty($column['db'])){
                         $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
                     }
                     else{
                         $row[ $column['dt'] ] = "";
                     }
   			}
   		}
   		$out[] = $row;
   	}*/
         $out=[];
         if($data!=[]) {
             $data=json_decode(json_encode($data), true);
             //dd($data);
        		//for ( $a=0;$a<$limit;$a++) {
            $a=0;
            foreach($data as $x) {
        			$row = [];
        			$key=array_keys($data[0]);
        			for ( $b=0;$b<count($key);$b++) {
        				array_push($row, $data[$a][$key[$b]]);
        			}
        			array_push($out,$row);
               $a++;
        		}
         }
   	     //dd($out);


   	/*
   	 * Output
   	 */
   	return array(
   		"draw"            => isset ( $request['draw'] ) ?
   			intval( $request['draw'] ) :
   			0,
   		"recordsTotal"    => intval( $recordsTotal ),
   		"recordsFiltered" => intval( $recordsFiltered ),
   		"data"            => $out
   	);


   }

   function combine($s1, $s2) {
      $s=$s1." ".$s2." ";
      if($s1<>"" and $s2<>"") {
          $s=$s1." and ".$s2." ";
      }
      return $s;
   }

    //get data from database via ajax
    function getDBRow($db, Request $req) {
        $input = $req->all();
        $where = $input;
        if ($where==[]) {
            $data = DB::table($db)->first();
        } else {
            $data = DB::table($db)->where($where)->first();
        }
        return json_encode($data);
    }

    //get data from database via ajax
    function getDBData($db, Request $req) {
        $input = $req->all();
        $where = $input;
        if ($where==[]) {
            $data = DB::table($db)->get();
        } else {
            $data = DB::table($db)->where($where)->get();
        }
        return json_encode($data);
    }

    // Masterdata form load
    function ajax_getProduct($id='') {
       return 'ajax_getProduct';
      //if(isset($_GET)) dd($_GET);
      //$xx=$_GET['draw'];
      //if(isset($_GET['draw'])) json_encode($_GET);
      //json_encode($_GET);
      $pageStart = isset($_GET['start'])?$_GET['start']:0;
      $pageTot = isset($_GET['length'])?$_GET['length']:10; 
      //$_GET['TotalRecords']=10000;
      //$_GET['TotalDisplayRecords']=100;
      //$_GET['recordsTotal']=10000;
      //$_GET['recordsFiltered']=100;
       //$dat = (array)DB::table('masterproduct')->where('Code', $id)->first();
      $dat['status']= 'OK';
      if($id<>''){ 
         $dat['data']= Product::where('Code', $id)->first();
         $dat['data']->Qty=1234567890;
      } else {
         $dat['data']= Product::all(); //jika paging bukan server side
         //$dat['data']= Product::offset($pageStart)->limit($pageTot)->get(); //jika paging server side
         foreach($dat['data'] as $r) {
            $r->Qty = 1234567890;
         }
      }
      //$dat['data']->fill(['Qty' => 1234567890]);
     //dd($dat);
      return json_encode($dat);
    }
    function ajax_getCustomer($id='') {
         $dat['status']= 'OK';
         if($id<>'') {
            $dat['data']= Account::where('AccCode', $id)->first();
            $dat['data']->Bal=1234567890;
            //add addr
            //$addr=['Alamat'=>'xxxxxxxxxxxxxxxxxxxxx','City'=>'yyyyyyyyyyyyyyyyyyyyy'];
            $addr=['Alamat'=>'xxxxxxxxxxxxxxxxxxxxx'];
            $dat['data']['addr']= $addr;
         } else {
            $dat['data']= Account::all();
            foreach($dat['data'] as $r) {
               $r->Bal = 1234567890;
            }
         }
         return json_encode($dat);
    }
    function ajax_getSupplier($id='') {
        $dat['status']= 'OK';
        if($id<>'') {
            $dat['data']= Account::where('AccCode', $id)->first();
            $dat['data']->Bal=1234567890;
        } else {
            $dat['data']= Account::all();
            foreach($dat['data'] as $r) {
               $r->Bal = 1234567890;
            }
         }
        return json_encode($dat);
   }

   // Trans form load
   function ajax_getTrans($jr,$id='') {
    //$dat = (array)DB::table('masterproduct')->where('Code', $id)->first();
     $dat['status']= 'OK';
     if($id<>'') {
         $dat['data']= Transhead::where('TransNo', $id)->first();
     }else{
         //$dat['data']= Transhead::where('left(TransNo,2)', $jr)->get();
         if($jr=='SI') $jr='IN';
         $dat['data']= DB::select("select * from transhead where left(TransNo,2)='$jr' ");
     }
     return json_encode($dat);
 }


/*function post_datalist($jr, $page, $find) {
  //return $jr;
  $where='';
  $limit="limit $page ";
  switch($jr) {
      case 'product':
          if($find<>'-') $where="WHERE Name like '%$find%' ";
          //$dat= DB::select("SELECT Code,Name,UOM,Category from masterproduct $where $limit");
          //$dat= json_decode(json_encode($dat), true);
          $dat= DB::select("SELECT Code,Name,UOM,Category,(select sum(qty) from transdetail where transdetail.ProductCode=masterproduct.Code)as totQty from masterproduct $where $limit");
          $s='';
          foreach ($dat as $row)  {
              $s.= "<tr>
                  <td><a href='product-edit/$row->Code'>$row->Code</a></td>
                  <td>$row->Name</td>
                  <td>$row->UOM</td>
                  <td>$row->Category</td>
                  <td>$row->totQty</td>
                  </tr>";
          }
          return $s;
          break;

      case 'customer':
          if($find<>'-') $where="and AccName like '%$find%' ";
          $dat= DB::select("SELECT masteraccount.AccCode,AccName,Phone,Email,Address,0 as Bal
                                          FROM masteraccount
                                          LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                                          WHERE AccType='C'
                                          $where
                                          $limit ");
          $s='';
          foreach ($dat as $row)  {
              $acc= explode('|', $row->Acc);
              //$bal= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
              $bal= 12345;

          }
          return $dat;
          //$totRows= count($dat);
          //return $s;
      break;
      case 'customer-lama':
          if($find<>'-') $where="and AccName like '%$find%' ";
          $dat= DB::select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
                                          FROM masteraccount
                                          LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                                          WHERE AccType='C'
                                          $where
                                          $limit ");
          $s='';
          foreach ($dat as $row)  {
              $acc= explode('|', $row->Acc);
              $bal= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
              $s.= "<tr>
              <td><a href='customer-edit/$acc[0]'>$acc[1]</a></td>
              <td>$row->Phone</td>
              <td>$row->Email</td>
              <td>$row->Address</td>
              <td>$bal</td>
              </tr>";
              //$acc=explode('|', $dat[$a]['Acc']);
              //$dat[$a]['Acc']= link_to("customer-edit/".$acc[0], $acc[1]);
              //$dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
              //$dat[$a]['Bal']= 1234567890;
          }
          $totRows= count($dat);
          return $s;
      break;

      case 'supplier':
          $dat= DB::select("SELECT CONCAT(masteraccount.AccCode,'|',AccName)AS Acc,Phone,Email,Address,0 AS Bal
                              FROM masteraccount
                              LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                              WHERE AccType='S' AND DefAddr=1
                              $limit ");
          $s='';
          foreach ($dat as $row)  {
              $acc= explode('|', $row->Acc);
              $bal= $this->getAccBalance( $acc[0], 'PI' );
              $s.= "<tr>
                  <td><a href='supplier-edit/$acc[0]'>$acc[1]</a></td>
                  <td>$row->Phone</td>
                  <td>$row->Email</td>
                  <td>$row->Address</td>
                  <td>$bal</td>
                  </tr>";
          }
          return $s;
          break;

      case 'coa':
          $dat= DB::select("SELECT mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0)as Amount
                                      FROM mastercoa
                                      LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
                                      GROUP BY AccNo
                                      $limit ");
          $s='';
          foreach ($dat as $row)  {
              $s.= "<tr>
                  <td><a href='accountdetail/$row->AccNo'>$row->AccNo</a></td>
                  <td>$row->AccName</td>
                  <td>$row->CatName</td>
                  <td>$row->Amount</td>
                  </tr>";
          }
          return $s;
          break;

      case 'bank':
          $dat= DB::select("SELECT mastercoa.AccNo,AccName,CatName,ifnull(SUM(Amount),0)as Amount
                                      FROM mastercoa
                                      LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
                                      WHERE CatName='Cash & Bank'
                                      GROUP BY AccNo
                                      $limit ");
          $s='';
          foreach ($dat as $row)  {
              $s.= "<tr>
                  <td><a href='accountdetail/$row->AccNo'>$row->AccNo</a></td>
                  <td>$row->AccName</td>
                  <td>$row->CatName</td>
                  <td>$row->Amount</td>
                  </tr>";
          }
          return $s;
          break;

      case 'bom':
          $dat= DB::select("SELECT pcode,(select Name from masterproduct where masterproduct_bomhead.PCode=masterproduct.Code)as pname,pcat,ptype
                              FROM masterproduct_bomhead
                              ORDER BY pcode,pname ");
          $s='';
          foreach ($dat as $row)  {
              $s.= "<tr>
                  <td><a href='bom-edit/$row->pcode'>$row->pcode</a></td>
                  <td>$row->pname</td>
                  <td>$row->pcat</td>
                  <td>$row->ptype</td>
                  </tr>";
          }
          return $s;
          break;
  }
}*/

// MASTERDATA SAVE 
public function datasave(Request $req) {
    $input=$req->all();
    

    if ($req->table=='masterproductcategory') {
        $pm = new Common;
        $pm->insert($req->save);
    }
    if ($req->table=='common') {
        //return $req->save['id'];
        //$pm = new Common;
        //$pm->insert($req->save);
        Common::where("id", $req->save['id'])->update($req->save);
        
    }


   //$data = ['data'=>$req->save];
   //return json_encode($data);
   return response()->json(['success'=>'data saved', 'input'=>$input]);

} 
function datasave_product(Request $req) { //cara yangdipakai contoh LARAVEL 5.3
   //return "saving ............";
   //$nama = $request->input('Code');
   //$alamat = $request->input('Name');
   //$formtype = $request->input('formtype');
   $input=$req->all();
   $input['Barcode']='777';
   try{
      //$dat=Product::find(1);
      $dat=Product::where('Code', $input['Code'])->first();
      //$dat['Barcode']=$input['Barcode'];
      $dat->fill($input);
      //$dat=Product::create($input);
      $dat->save();

   } catch (Exception $err) {
      return "Error updating record <br> ".$err;
   }




   return response()->json(
      ['success'=>'YES !!!Got Simple Ajax Request.',
      'data'=>$input
   ]);

    /*try {
        DB::table('masterproduct')
            ->updateOrInsert(
                ['Code' => $request->input('Code')],
                ['Name' => $request->input('Name'),
                    'Barcode' => $request->input('Barcode'),
                    'Category' => $request->input('Category'),
                    'Type' => $request->input('Type'),
                    'HppBy' => $request->input('HppBy'),
                    'UOM' => $request->input('Unit'),
                    'BuildUnit' => $request->input('BuildUnit'),
                    'MinStock' => $request->input('MinStock'),
                    'MaxStock' => $request->input('MaxStock'),
                    'SellPrice' => $request->input('SellPrice'),
                    'ActiveProduct' => ($request->input('ActiveProduct')==null)?0:1,
                    'StockProduct' => 1,
                    'canBuy' => 1,
                    'canSell' => 1,
                    'AccHppNo' => $request->input('AccHppNo'),
                    'AccSellNo' => $request->input('AccSellNo'),
                    'AccInventoryNo' => $request->input('AccInventoryNo')
                ]
            );
        return "OK";
    } catch (Exception $err) {
        return "Error updating record <br> ".$err;
    }*/
  }

/*function datasave_product(Request $req) { //cara yangdipakai contoh LARAVEL 5.3
   //return "saving ............";
   $input=$req->all();
   return response()->json(
      ['success'=>'YES !!!Got Simple Ajax Request.',
      'data'=>$input
   ]);
    //$nama = $request->input('Code');
    //$alamat = $request->input('Name');
    //$formtype = $request->input('formtype');

    try {
        DB::table('masterproduct')
            ->updateOrInsert(
                ['Code' => $request->input('Code')],
                ['Name' => $request->input('Name'),
                    'Barcode' => $request->input('Barcode'),
                    'Category' => $request->input('Category'),
                    'Type' => $request->input('Type'),
                    'HppBy' => $request->input('HppBy'),
                    'UOM' => $request->input('Unit'),
                    'BuildUnit' => $request->input('BuildUnit'),
                    'MinStock' => $request->input('MinStock'),
                    'MaxStock' => $request->input('MaxStock'),
                    'SellPrice' => $request->input('SellPrice'),
                    'ActiveProduct' => ($request->input('ActiveProduct')==null)?0:1,
                    'StockProduct' => 1,
                    'canBuy' => 1,
                    'canSell' => 1,
                    'AccHppNo' => $request->input('AccHppNo'),
                    'AccSellNo' => $request->input('AccSellNo'),
                    'AccInventoryNo' => $request->input('AccInventoryNo')
                ]
            );
        return "OK";
    } catch (Exception $err) {
        return "Error updating record <br> ".$err;
    }
  }*/
  function datasave_customer(Request $request) { //cara yangdipakai contoh LARAVEL 5.3
    try {
        DB::table('masteraccount')
            ->updateOrInsert(
                ['AccCode' => $request->input('AccCode')],
                ['AccName' => $request->input('AccName'),
                    'AccType' => 'C',
                    'Category' => 'Category',
                    'SubCategory' => '',
                    'CreditLimit' => '',
                    'CreditActive' => '',
                    'Taxno' => '',
                    'Taxname' => '',
                    'TaxAddr' => '',
                    'Salesman' => '',
                    'Memo' => '',
                    'Area1' => '',
                    'Area2' => '',
                    'PriceChannel' => '',
                    'AccNo' => '',
                    'CreatedBy' => 'ADMIN',
                    'CreatedDate' => '2020-10-10 15:00'
                ]
            );
        return "OK";
        } catch (Exception $err) {
            return "Error updating record <br> ".$err;
    }
  }
  function datasave_supplier(Request $request) { //cara yangdipakai contoh LARAVEL 5.3
    try {
        DB::table('masteraccount')
            ->updateOrInsert(
                ['AccCode' => $request->input('AccCode')],
                ['AccName' => $request->input('AccName'),
                    'AccType' => 'S',
                    'Category' => 'Category',
                    'SubCategory' => '',
                    'CreditLimit' => '',
                    'CreditActive' => '',
                    'Taxno' => '',
                    'Taxname' => '',
                    'TaxAddr' => '',
                    'Salesman' => '',
                    'Memo' => '',
                    'Area1' => '',
                    'Area2' => '',
                    'PriceChannel' => '',
                    'AccNo' => '',
                    'CreatedBy' => 'ADMIN',
                    'CreatedDate' => '2020-10-10 15:00'
                ]
            );
        return "OK";
    } catch (Exception $err) {
        return "Error updating record <br> ".$err;
    }
  }

  function getProductSumQty($Pcode) {
    try {
        $dat = $this->getProdBalance($Pcode);
        return json_encode($dat);
    } catch (Exception $err) {
        return "Error get product summary <br> ".$err;
    }
  }
  
  function getAccSumAmount($AccCode) {
    try {
        return $this->getProdBalance($Pcode);
    } catch (Exception $err) {
        return "Error get account summary <br> ".$err;
    }
  }

  
  //FUNCTION
  function debet($val) {
    if($val>0) { return $val; } else {return 0; }
  }
  
  function credit($val) {
    if($val<0) { return -$val; } else {return 0; }
  }





// function getAccBalance($AccCode, $jr) {
//   $row = $this->DB_select("SELECT AccCode,(Total-IFNULL(AmountPaid,0))as Bal
//                               FROM transhead
//                               LEFT JOIN transpaymentarap ON transpaymentarap.InvNo=transhead.TransNo
//                               WHERE LEFT(transhead.transno,2)='$jr' AND AccCode='$AccCode' ");
//   if($row==[]) return 0;
//   return $row[0]['Bal'];
// }

// function getProdBalance($Pcode) {
//   $row = $this->DB_select("SELECT -SUM(Qty) AS Qty
//           FROM transdetail
//           WHERE ProductCode='$Pcode' ");
//   //return isset($row[0]['Qty'])?$row[0]['Qty']:0;
//   return $row[0]['Qty'];
// }








}
