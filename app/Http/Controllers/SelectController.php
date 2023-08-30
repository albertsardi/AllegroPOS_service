<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Model\Common;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Address;
use App\Http\Model\Product;
use App\Http\Model\Salesman;
use App\Http\Model\Warehouse;
// use App\Http\Model\Account;
// use App\Http\Model\User;
// use App\Http\Model\AccountAddr;
// use App\Http\Model\Profile;
// use App\Http\Model\Bank;
// use App\Http\Model\Order;

class SelectController extends MainController {
	
	function selectFormat($dat) {
		$dat = $dat->toArray();
		foreach($dat as $dt) {
			$key = array_keys($dt);
			$dat2[] = ['id'=>$dt[$key[0]], 'text'=>$dt[$key[1]] ];
			// $dat2[] = [
			// 	'id'=>$dt[$key[0]], 
			// 	'text'=> "<div class='row'><div class='col'>11</div><div class='col'>22</div></div>" 
			// ];
		}
		return json_encode((object)['results'=>$dat2]);
		//return response()->json( (object)['results'=>$dat2] );
	}

	function getSelectData($jr='', Request $req) {
		//dd($req);
		switch($jr) {
			case 'common':
				$dat= Common::select('name2','name1')->where('category',$req->category)->get();
				break;
			case 'product':
				$dat= Product::select('Code','Name')->get();
				break;
			case 'customer':
				$dat= CustomerSupplier::select('AccCode','AccName')->where('AccType','C')->get();
				break;
			case 'supplier':
				$dat= CustomerSupplier::select('AccCode','AccName')->where('AccType','S')->get();
				break;
			case 'salesman':
				$dat= Salesman::select('Code','Name')->get();
				break;
			case 'address':
				$dat= Address::select('Code','Address')->where('AccCode',$req->AccCode)->get();
				break;
			case 'warehouse':
				$dat= Warehouse::select('warehouse','warehousename')->get();
				break;
		}

		return $this->selectFormat($dat);
	}

}
  	
	
	
// 	switch($jr) {
//       	case 'product':
// 			$dat= DB::table('masterproduct')->select('Code','Name','UOM','Category')->get();
// 			$dat= json_decode(json_encode($dat), true);
// 			for($a=0;$a<count($dat);$a++) {
// 				$link=$dat[$a]['Code'];
// 				$dat[$a]['Code']= link_to("product-edit/$link", $link);
// 				$dat[$a]['Qty']= 1234; //$this->getProdBalance($link); //1234;
// 			}
// 			return $this->table_generate($dat,['Product #','Product Name','Unit','Category','Quantity']);
// 			break;
//       	case 'customer':
//           	$dat= DB::select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
//                                        FROM masteraccount
//                                        LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
//                                        WHERE AccType='C' ");
//           	//$dat= DB::table('listaccount')->get();
//           	$dat= DB::table('masteraccount')->get();
//           	$dat= json_decode(json_encode($dat), true);
//           	for($a=0;$a<count($dat);$a++) {
//               //$acc=explode('|', $dat[$a]['Acc']);
//               //$dat[$a]['Acc']= link_to("customer-edit/".$acc[0], $acc[1]);
//               //$dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
//               $dat[$a]['Bal']= 1234567890;
//           	}
//           	return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
//           	break;

//       case 'supplier':
//           $dat= $this->DB_select("SELECT CONCAT(masteraccount.AccCode,'|',AccName)AS Acc,Phone,Email,Address,0 AS Bal
//                               FROM masteraccount
//                               LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
//                               WHERE AccType='S' AND DefAddr=1");
//           for($a=0;$a<count($dat);$a++) {
//               $acc=explode('|', $dat[$a]['Acc']);
//               //$dat[$a]['Acc']= "<a href='supplier-edit.php?id=$acc[0]'>".$acc[1]."</a>";
//               $dat[$a]['Acc']= link_to("supplier-edit/$acc[0]", $acc[1]);
//               $dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'PI' ); //1234567890;
//           }
//           return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
//           break;

//       case 'coa':
//           $dat= $this->db_select("SELECT mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0)
//                                       FROM mastercoa
//                                       LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
//                                       GROUP BY AccNo");
//           for($a=0;$a<count($dat);$a++) {
//               $dat[$a]['AccNo']= link_to("accountdetail/".$dat[$a]['AccNo'], $dat[$a]['AccNo']);
//           }
//           return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
//           break;

//       case 'bank':
//           $dat= $this->db_select("SELECT mastercoa.AccNo,AccName,CatName,ifnull(SUM(Amount),0)
//                                       FROM mastercoa
//                                       LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
//                                       WHERE CatName='Cash & Bank'
//                                       GROUP BY AccNo");
//           for($a=0;$a<count($dat);$a++) {
//               $dat[$a]['AccName']= "<a href='accountdetail.php?id=".$dat[$a]['AccNo']."'>".$dat[$a]['AccName']."</a>";
//           }
//           return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
//           break;

//       case 'bom':
//           $dat= DB::select("SELECT pcode,(select Name from masterproduct where masterproduct_bomhead.PCode=masterproduct.Code)as pname,pcat,ptype
//                               FROM masterproduct_bomhead
//                               ORDER BY pcode,pname ");
//           $dat= json_decode(json_encode($dat), true);
//           for($a=0;$a<count($dat);$a++) {
//               $dat[$a]['pcode']= link_to("bom-edit/".$dat[$a]['pcode'], $dat[$a]['pcode']);
//           }
//           return $this->table_generate($dat,['Product #','Product Name','Category','Type']);
//           break;
//   }
// }





