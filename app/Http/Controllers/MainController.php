<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Input;

// constanta
define("PAID", "Paid");
define("DRAFT", "Draft");
define("APPROVED", "Approved");
define("READYDELIVERY", "ReadyDelivery");
define("DELIVERED", "Delivered");
define("ACTIVE", "Active");

class MainController extends Controller {

function debet($val) {
  if($val>0) { return $val; } else {return 0; }
}

function credit($val) {
  if($val<0) { return -$val; } else {return 0; }
}

function getAccBalance($AccCode, $jr) {
  $row = $this->DB_select("SELECT AccCode,(Total-IFNULL(AmountPaid,0))as Bal
                              FROM transhead
                              LEFT JOIN transpaymentarap ON transpaymentarap.InvNo=transhead.TransNo
                              WHERE LEFT(transhead.transno,2)='$jr' AND AccCode='$AccCode' ");
  if($row==[]) return 0;
  return $row[0]['Bal'];
}

function fUnixDate($date='') {
  if ($date=='') return date('Y-m-d');
  $date=explode('/',$date);
  return date('Y-m-d', mktime(0,0,0,$date[1],$date[0],$date[2]));
}

public function fnum($num) {
  $num=intval($num);
  return number_format($num,0);	
}
 
function space($num) {
  return str_repeat(' ',$num);	
}

function getProdBalance($Pcode) {
  $stockIn = DB::table('transdetail')->select('ReceiveQty')->where('ProductCode',$Pcode)->sum('ReceiveQty');
	$stockOut = DB::table('transdetail')->select('SentQty')->where('ProductCode',$Pcode)->sum('SentQty');
  return (object)['Qty'=>$stockIn-$stockOut, 'In'=>$stockIn, 'Out'=>$stockOut];
}

function makeCaption($jr='', $nm='') {
	/*if($nm=='') {
	 	if($jr=='customer') return 'Customer List';
		if($jr=='supplier') return 'Supplier List';
	 	if($jr=='product') return 'Product List';
	 	if ($jr == 'PI') return 'Purchase Invoices List';
   	if ($jr == 'DO') return 'Delivery Orders List';
   	if ($jr == 'AP') return 'Bill Payments List';
   	if ($jr == 'AR') return 'Receive Payments List';
   	if ($jr == 'EX') return 'Expenses List';
   	if ($jr == 'CR') return 'Cash / Bank Receive List';
   	if ($jr == 'CD') return 'Cash / Bank Spend List';
   	if ($jr == 'CT') return 'Cash / Transfer List';
 } else {
	 	if($jr=='customer') return 'Data Customer '.$nm;
		if($jr=='supplier') return 'Data Supplier '.$nm;
	 	if($jr=='product') return 'Data Product '.$nm;
	 	if ($jr == 'PI') return 'Data Purchase Invoices '.$nm;
   	if ($jr == 'DO') return 'Data Delivery Orders '.$nm;
   	if ($jr == 'AP') return 'Data Bill Payments '.$nm;
   	if ($jr == 'AR') return 'Data Receive Payments '.$nm;
   	if ($jr == 'EX') return 'Data Expenses '.$nm;
   }*/
   
    $label ='';
    if ($jr=='customer') $label = 'Customer';
    if ($jr=='supplier') $label =  'Supplier';
    if ($jr=='product') $label =  'Product';
    if ($jr == 'SI' || $jr == 'IN') $label = 'Sales Invoices';
    if ($jr == 'PI') $label = 'Purchase Invoices';
    if ($jr == 'DO') $label = 'Delivery Orders';
    if ($jr == 'AP') $label = 'Bill Payments';
    if ($jr == 'AR') $label = 'Receive Payments';
    if ($jr == 'EX') $label = 'Expenses';
    if ($jr == 'CR') $label = 'Cash / Bank Receive';
    if ($jr == 'CD') $label = 'Cash / Bank Spend';
    if ($jr == 'CT') $label = 'Cash / Transfer';
    if ($label=='') return $label=$jr;
    return ($nm=='')? $label.' List' : 'Data '.$label.' '.$nm;

}

function makeTableList($caption) {
  $head= '<th>'.implode('</th><th>',$caption).'</th>';
  $head= '<thead><tr>'.$head.'</tr></thead>';
  $tbl= $head.'<tbody></tbody>';
  // $tbl= str_replace('<th>Status</th>','',$tbl); //debug nanti di benerin
  return $tbl;
}


public function api($type='GET', $url, $defValue=[]) {
   try {
      $client = new Client();
      //$url    = !empty($api['url']) ? $api['url'] : $this->cpm_uri();
      //$api    = $client->request($method, env('API_LUMEN') . $url, $param);
      //$res    = json_decode($api->getBody());
             
      /*$api = $client->request('GET',"http://localhost:8000/ajax_getCustomer/C-0166", [
         'auth' => ['user', 'pass']
      ]);*/
      //$api = $client->request('GET',"http://localhost:500/ajax_getCustomer/C-0163"); //ini yang jalan
      //$base_url='http://localhost:500/';
      $base_url=env('API_URL');
      if(substr($base_url,-1)!='/') $base_url.='/';

      $api = $client->request($type, $base_url.$url); //ini yang jalan
      //dd($api);
      $res    = json_decode($api->getBody());

      //$client = new GuzzleHttp\Client(['base_uri' => 'http://localhost:8000']);
      //$api = $client->request('GET', "/ajax_getCustomer/C-0166", ['allow_redirects' => false]);
      //return $api->getBody();
      //return $api->getStatusCode();
         return $res;

      /*if ( $res->success ) {
            return $res->data;
      } else  {
         switch ( $res->errors->error_code )
         {
            case 401 : header('Location: '. url('logout')); exit;
            case 422 :
            case 500 : return $res;
            default  : return abort($api->getStatusCode());
         }
      }*/
   }
   catch (GuzzleException $e) {
     return !empty($e->getResponse()->getStatusCode()) ? view()->exists('errors.'.$e->getResponse()->getStatusCode()) ? abort($e->getResponse()->getStatusCode()) : abort(404) : abort(500);
   }
}

public static function _api($api) {
   $base_url=env('API_URL');
	if(substr($base_url,-1)!='/') $base_url.='/';
	return $base_url.$api;
}
public static function xxx($api) {
	echo 'xxxx';
}

// DB function --------------------------
function DB_FieldSelect($fldSel) {
  global $request;
  $fld=[];
  for($a=0;$a<count($fldSel);$a++) {
      $type = substr($fldSel[$a], 0, 2);
      //$nm = substr($fldSel[$a], 2);
      $nm=$fldSel[$a];
      $fld[$nm] = $request->input($nm);
      if($type=='ck') { // checkbox
          // $fld[$nm] = ($request->input($nm)==null)?0:1;
          //$fld[$nm] = ($request->input($nm)==null)?0:1;
      }
  }
  return $fld;
}
function DB_select($sql) {
  $dat=DB::select($sql);
  $dat = json_decode(json_encode($dat), True);
  return $dat;
}

function DB_array($db, $fld='*', $where='') {
      if($where!='') $where="WHERE ".$where." ";
      $sql="SELECT $fld FROM $db ".$where;
      $dat=DB::select($sql);
      $dat = json_decode(json_encode($dat), True);
      return $dat;
    }
    
function DB_list($db, $fld, $where = '') { //for combolist
    if (!is_array($fld)) {
        $dat= $this->DB_array($db, $fld, $where);
        $arr=[];
        for($a=0;$a<count($dat);$a++) {
            $arr[]=$dat[$a][$fld];
        }
    } else {
        $flds = implode(',', $fld);
        $dat= $this->DB_array($db, $flds, $where);
        $arr=[];
        for($a=0;$a<count($dat);$a++) {
            $arr[$dat[$a][$fld[0]]]=$dat[$a][$fld[1]];
        }
    }
    return $arr;
}

function form_input_array($arr=[]){
  $r=[];
  for($a=0;$a<count($arr);$a++) {
      $f=$arr[$a];
      $r[$f]= Input::get($f);
  }
  return $r;
}

function table_generate($data, $header=[], $align=[]) {
  if(isset($data[0])) {
      $key=array_keys($data[0]);
  } else {
      $key=null;
  }
  if($header==[]) $header=$key;
  $r="<thead>";
  $r.="<tr>";
  for($b=0;$b<count($header);$b++) {
      $r.="<th>".$header[$b]."</th>";
      if(!isset($align[$b])) $align[$b]='left';
  }
  $r.="</tr>";
  $r.="</thead>";

  $r.="<tbody>";
  for($a=0;$a<count($data);$a++) {
      $r.="<tr>";
      for($b=0;$b<count($key);$b++) { //test
          //$al = ($align[$b]!='left')?"align='$align[$b]'":"";
          $al=''; //debug
          $r.="<td $al>".$data[$a][$key[$b]]."</td>";
      }
      $r.="</tr>";
  }
  $r.="</tbody>";
  return $r;
}

function profile_image($img) {
  $blank_image = 'assets/images/avatar_2x.png';
  $image = 'assets/images/profile-img/'.$img;
  return (file_exists($image))? $image : $blank_image;
  //$data['profile_image'] = image_profile('profile'.$user->id.'.jpg');
}

function fdate($dts) {
  //  yyyy-mm-dd -> dd/mm/yyyy 
  $dt = strtotime($dts);
  return date('d/m/Y', $dt);
}

function unixdate($dts) {
  // dd/mm/yyyy -> yyyy-mm-dd
  $dt = explode('/', $dts);
  return $dt[2].'-'.$dt[1].'-'.$dt[0];
}





}
