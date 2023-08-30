<?php
namespace App\Http\Controllers;
//use GuzzleHttp\Client;
// use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
//use \koolreport\cloudexport\Exportable;
// use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\Http;
//use HTML;
// use Validator;
// use SSP;
// use Illuminate\Support\Facades\Input;
use App\Http\Model\User;
use App\Http\Model\Product;
use App\Http\Model\AccountAddr;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Profile;
use App\Http\Model\Account;
use App\Http\Model\Bank;
use App\Http\Model\Order;
use Session;

class MasterController extends MainController {

function datalist($jr) {
    $today = date('Y-m-d');
   //show view
   switch($jr) {
      case 'product':
         $res = DB::table('masterproduct')->selectRaw('Code,Name,UOM,Category,ActiveProduct,id')->get();
         foreach($res as $r) {
             $r->Qty = DB::select(" CALL getProductQty('$r->Code','$today') ")[0]->Total ?? 0;
         }
         $data = [
            'jr'        => $jr,
            'grid'      => ['Product #','Product Name','Unit','Category','Quantity','Status'],
            'caption'   => $this->makeCaption($jr),
            '_url'      => env('API_URL').'/api/'.$jr,
            // 'data'      => $this->db_query('masterproduct','Code,Name,UOM,Category,12345 as Qty'),
            'data'      => $res,
            'xxdatacol'   => json_encode([
                  [ 'data' => 'Code'],
                  [ 'data' => 'Name'],
                  [ 'data' => 'UOM'],
                  [ 'data' => 'Category']
            ])
         ];
         break;
      case 'supplier':
      case 'customer':
         $dat = DB::table('masteraccount as m')->selectRaw('AccName,m.AccCode,Phone,Email,Address,Active,m.id')
                  ->leftJoin('masteraccountaddr as ma', 'm.id', '=', 'ma.AccountId')->where('DefAddr',1);
         $dat = ($jr=='supplier')? $dat->where('AccType','S')->get() : $dat->where('AccType','C')->get();
         foreach($dat as $r) {
            $r->Bal = 12345679;
        }
         // return $dat;
         $data = [
            'jr'        => $jr,
            'grid'      => ['Display Name','Code','Phone','Email','Address', 'Balance (Rp)', 'Status'],
            'caption'   => $this->makeCaption($jr),
            '_url'      => env('API_URL').'/api/'.$jr,
            'data'      => $dat,
            // 'datacol'   => []
         ];
         break;
      case 'coa':
        $res = DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName, 123456 as Bal,id')->get();
        foreach($res as $r) {
            //$r->Bal = Account::getAmount($r->id);
            $r->Bal = DB::select(" CALL getAccountAmount('$r->AccNo','$today') ")[0]->Total ?? 0;;
        }
        $data = [
            'jr'        => $jr,
            'grid'      => ['Account #','Account Name','Category','Amount (Rp)',' '],
            'caption'   => $this->makeCaption($jr),
            '_url'      => env('API_URL').'/api/'.$jr,
            'data'      => $res
         ];
         // return $data['data'];
         break;
      case 'bank':
         $dat =  DB::table('masterbank')->selectRaw('BankAccName, BankAccNo, AccNo, BankType, 1234567 as Bal,id')->get();
         foreach($dat as $dt) {
            $dt->Bal = Account::getAmount($dt->id);
            }
         $data = [
            'jr'        => $jr,
            'grid'      => ['Bank Name', 'Bank Account#', 'Account#','Bank Type','Amount (Rp)', ' '],
            'caption'   => $this->makeCaption($jr),
            '_url'      => env('API_URL').'/api/'.$jr,
            'data'      => $dat
            // 'datacol'   => []
         ];
         // return $data['data'];
         break;
   }

   $data['grid'] = $this->makeTableList($data['grid']);
   return view('datalist', $data);
}
function db_query($db, $fld='*') {
   return DB::table($db)->get();
}

function makeList($jr='') {
  switch($jr) {
      case 'product':
          $dat= DB::table('masterproduct')->select('Code','Name','UOM','Category')->get();
          $dat= json_decode(json_encode($dat), true);
          for($a=0;$a<count($dat);$a++) {
              $link=$dat[$a]['Code'];
              $dat[$a]['Code']= link_to("product-edit/$link", $link);
              $dat[$a]['Qty']= 1234; //$this->getProdBalance($link); //1234;
          }
          return $this->table_generate($dat,['Product #','Product Name','Unit','Category','Quantity']);
          break;

      // case 'product':
      //     $dat= $this->db_array('masterproduct', 'Code,Name,UOM,Category,0 as Qty');
      //     for($a=0;$a<count($dat);$a++) {
      //         $link=$dat[$a]['Code'];
      //         $dat[$a]['Code']= link_to("product-edit/$link", $link);
      //         $dat[$a]['Qty']= $this->getProdBalance($link); //1234;
      //     }
      //     return $this->table_generate($dat,['Product #','Product Name','Unit','Category','Quantity']);
      //     break;

      case 'customer':
          // $dat= $this->db_select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
          //                             FROM masteraccount
          //                             LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
          //                             WHERE AccType='C' ");
          // $dat= DB::table('masteraccount')->select("concat(masteraccount.AccCode,'|',AccName)","Phone","Email","Address","AccName")
          //                                     ->leftJoin('masteraccountaddr', 'masteraccountaddr.AccCode', '=', 'masteraccount.AccCode')->get();
          $dat= DB::select("SELECT concat(masteraccount.AccCode,'|',AccName)as Acc,Phone,Email,Address,0 as Bal
                                       FROM masteraccount
                                       LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                                       WHERE AccType='C' ");
          //$dat= DB::table('listaccount')->get();
          $dat= DB::table('masteraccount')->get();
          $dat= json_decode(json_encode($dat), true);
          for($a=0;$a<count($dat);$a++) {
              //$acc=explode('|', $dat[$a]['Acc']);
              //$dat[$a]['Acc']= link_to("customer-edit/".$acc[0], $acc[1]);
              //$dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'IN' ); //1234567890;
              $dat[$a]['Bal']= 1234567890;
          }
          return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
          break;

      case 'supplier':
          $dat= $this->DB_select("SELECT CONCAT(masteraccount.AccCode,'|',AccName)AS Acc,Phone,Email,Address,0 AS Bal
                              FROM masteraccount
                              LEFT JOIN masteraccountaddr ON masteraccountaddr.AccCode=masteraccount.AccCode
                              WHERE AccType='S' AND DefAddr=1");
          for($a=0;$a<count($dat);$a++) {
              $acc=explode('|', $dat[$a]['Acc']);
              //$dat[$a]['Acc']= "<a href='supplier-edit.php?id=$acc[0]'>".$acc[1]."</a>";
              $dat[$a]['Acc']= link_to("supplier-edit/$acc[0]", $acc[1]);
              $dat[$a]['Bal']= $this->getAccBalance( $acc[0], 'PI' ); //1234567890;
          }
          return $this->table_generate($dat,['Display Name','Phone','Email','Address', 'Balance (Rp)']);
          break;

      case 'coa':
          $dat= $this->db_select("SELECT mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0)
                                      FROM mastercoa
                                      LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
                                      GROUP BY AccNo");
          for($a=0;$a<count($dat);$a++) {
              $dat[$a]['AccNo']= link_to("accountdetail/".$dat[$a]['AccNo'], $dat[$a]['AccNo']);
          }
          return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
          break;

      case 'bank':
          $dat= $this->db_select("SELECT mastercoa.AccNo,AccName,CatName,ifnull(SUM(Amount),0)
                                      FROM mastercoa
                                      LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
                                      WHERE CatName='Cash & Bank'
                                      GROUP BY AccNo");
          for($a=0;$a<count($dat);$a++) {
              $dat[$a]['AccName']= "<a href='accountdetail.php?id=".$dat[$a]['AccNo']."'>".$dat[$a]['AccName']."</a>";
          }
          return $this->table_generate($dat,['Account #','Account Name','Category','Amount (Rp)']);
          break;

      case 'bom':
          $dat= DB::select("SELECT pcode,(select Name from masterproduct where masterproduct_bomhead.PCode=masterproduct.Code)as pname,pcat,ptype
                              FROM masterproduct_bomhead
                              ORDER BY pcode,pname ");
          $dat= json_decode(json_encode($dat), true);
          for($a=0;$a<count($dat);$a++) {
              $dat[$a]['pcode']= link_to("bom-edit/".$dat[$a]['pcode'], $dat[$a]['pcode']);
          }
          return $this->table_generate($dat,['Product #','Product Name','Category','Type']);
          break;
  }
}

//form VIEW
function dataview($id) {
	if(str_contains($_SERVER['REQUEST_URI'], 'product/view')) $jr='product';
	if(str_contains($_SERVER['REQUEST_URI'], 'customer/view')) $jr='customer';
	if(str_contains($_SERVER['REQUEST_URI'], 'supplier/view')) $jr='supplier';
	if(str_contains($_SERVER['REQUEST_URI'], 'account/view')) $jr='account';
	if(str_contains($_SERVER['REQUEST_URI'], 'bank/view')) $jr='bank';
	$data = [
      'jr' => $jr, 'id' => $id,
	   'caption' => $this->makeCaption($jr, $id),
      'user' => ['Code'=>'123']
   ];
 
	switch($jr) {
		case 'product':
            // form initial
			$data = array_merge($data, [
                'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
                //'mType'  => ['Raw material','Finish good'],
                'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
                'mHpp'   => ['Average'],
                'mAccount'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
                'mCoa'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
                'data'   => [],
            ]);
		
            // get data
            $data['data']=[];
            //$res = $this->api('GET', 'api/product/'.$id);
            $res = Product::Get($id);
			if ($res->status=='OK') {
                $data['data'] = $res->data;
            }
            //return $data;
			return view('view-product', $data);
		break;
      
	} 
}

// Profile
function profile() {
    // return 'profile';
    @session_start();

    $data = [
        'caption' => 'Profile',
        'jr' => 'profile',
        'mCity' => $this->getIndoCity(),
        'data' => [] 
    ];
    $user = Session::get('user');
    if (empty($user)) return redirect('/login');
    $res = User::GetProfile( $user->id );
    if ($res->status=='OK') {
        $data['data'] = $res->data;
        $data['profile_image'] = $this->profile_image('profile'.$user->id.'.jpg');
    }
    //return $data;
    return view('form-profile', $data);
}
function profile_save(Request $req) {
    @session_start();
    $err='';
    try {
        $user = Session::get('user');
        $data = Profile::find($user->id);
        if (empty($data)) $data = new Profile(); 
        $data->nik                      = (string)$req->nik;
        $data->foto                     = (string)$req->foto;
        $data->nama                     = (string)$req->nama;
        $data->section                  = (string)$req->section;
        $data->jabatan                  = (string)$req->jabatan;
        $data->tgl_kerja                = $req->tgl_kerja ?? '0000-00-00';
        $data->status_karyawan          = (string)$req->status_karyawan;
        $data->no_sk                    = (string)$req->no_sk;
        $data->alamat                   = (string)$req->alamat;
        $data->rt                       = (string)$req->rt;
        $data->rw                       = (string)$req->rw;
        $data->kelurahan                = (string)$req->kelurahan;
        $data->kecamatan                = (string)$req->kecamatan;
        $data->kotakab                  = (string)$req->kotakab;
        $data->kodepos                  = (string)$req->kodepos;
        $data->status_tempat_tinggal    = (string)$req->status_tempat_tinggal;
        $data->no_hp                    = (string)$req->no_hp;
        $data->no_rumah                 = (string)$req->no_rumah;
        $data->email_pribadi            = (string)$req->email_pribadi;
        $data->email_kantor             = (string)$req->email_kantor;
        $data->tempat_lahir             = (string)$req->tempat_lahir;
        $data->tgl_lahir                = $req->tgl_lahir;
        $data->agama                    = (string)$req->agama;
        $data->nama_ibu_kandung         = (string)$req->nama_ibu_kandung;
        $data->no_ktp                   = (string)$req->no_ktp;
        $data->bank                     = (string)$req->bank;
        $data->no_rek                   = (string)$req->no_rek;
        $data->no_bpjs_kesehatan        = (string)$req->no_bpjs_kesehatan;
        $data->faskes                   = (string)$req->faskes;
        $data->no_bpjs_tenagakerja      = (string)$req->no_bpjs_tenagakerja;
        $data->jenjang                  = (string)$req->jenjang;
        $data->jurusan                  = (string)$req->jurusan;
        $data->nama_sekolah             = (string)$req->nama_sekolah;
        $data->riwayat_pengalaman_kerja = (string)$req->riwayat_pengalaman_kerja;
        $data->no_npwp                  = (string)$req->no_npwp;
        $data->status_pajak             = (string)$req->status_pajak;
        $data->no_tlp_keluarga          = (string)$req->no_tlp_keluarga;
        $data->nama_pasangan            = (string)$req->nama_pasangan;
        $data->tgl_pasangan             = $req->tgl_pasangan ?? '0000-00-00';
        $data->anak1                    = (string)$req->anak1;
        $data->anak2                    = (string)$req->anak2;
        $data->anak3                    = (string)$req->anak3;
        $data->anak4                    = (string)$req->anak4;
        $data->tgl_anak1                = $req->tgl_anak1 ?? '0000-00-00';
        $data->tgl_anak2                = $req->tgl_anak2 ?? '0000-00-00';
        $data->tgl_anak3                = $req->tgl_anak3 ?? '0000-00-00';
        $data->tgl_anak4                = $req->tgl_anak4 ?? '0000-00-00';
        $data->status_aktif             = (string)$req->status_aktif;
        $data->save();
        $message='Sukses!!';
	    return redirect(url( $req->path() ))->with('success', $message);
    } 
    catch (Exception $e) {
        // exception is raised and it'll be handled here
        // $e->getMessage() contains the error message
        //return response()->json(['error'=>$e->getMessage()]);
        return redirect(url( $req->path() ))->with('error', $e->getMessage());
    }
}

//form edit
function dataedit($id) {
	$data['modal']= ''; $data['jsmodal']= '';
	//require 'helper_database.php';
	//require 'helper_table.php';
	//require 'helper_formjq.php';
	//require 'helper_lookup.php';
	//$cn=db_connect();
	//$id=isset($_GET['id'])?$_GET['id']:'BENANG-KARET';
	//dd($_SERVER);
	if(str_contains($_SERVER['REQUEST_URI'], 'product/edit')) $jr='product';
	if(str_contains($_SERVER['REQUEST_URI'], 'customer/edit')) $jr='customer';
	if(str_contains($_SERVER['REQUEST_URI'], 'supplier/edit')) $jr='supplier';
	if(str_contains($_SERVER['REQUEST_URI'], 'account/edit')) $jr='account';
	if(str_contains($_SERVER['REQUEST_URI'], 'bank/edit')) $jr='bank';
	$data = [
      'jr' => $jr, 'id' => $id,
	   'caption' => $this->makeCaption($jr, $id),
      'user' => ['Code'=>'123']
   ];
 
	switch($jr) {
		case 'product':
			$data = array_merge($data, [
                'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
                'mSubCat' => [],
                'mBrand' => [],
                //'mType'  => ['Raw material','Finish good'],
                'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
                'mHpp'   => ['Average'],
                'mAccount'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
                //'mCoa'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
                'data'   => [],
            ]);
		
            // get data
            $data['data']=[];
            //$res = $this->api('GET', 'api/product/'.$id);
            $res = Product::Get($id);
			if ($res->status=='OK') {
                $data['data'] = $res->data;
            }
            //return view('form-product', $data);
			return view('form-product-v2', $data);
		break;
      case 'customer':
      case 'supplier':
            $res = CustomerSupplier::Get($id);
            if ($res->status=='OK') {
                //calc order
                $mOrder = Order::where('AccCode', $res->data->AccCode); 
                $order = $mOrder->get();
                foreach($order as $or) { $or->ProductCount = Order::GetProductCount($or->TransNo); }
                $res->data->orderTotAmount = $mOrder->sum('Total'); 
                $res->data->orderCount = $mOrder->count('Total'); 
                
                $data = array_merge($data, [
                    'mAddr'         => $this->DB_list('masteraccountaddr', 'Code', "AccCode='$id' "),
                    'mCat'          => $this->DB_list('masteraccountcategory', 'Category'),
                    'mSalesman'     => $this->DB_list('mastersalesman', 'Name'),
                    'mPriceChannel' => ['Channel1', 'Channel2', 'Channel3', 'Channel4', 'Channel5' ],
                    'mAccount'      => json_encode(DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get()),
                    'mAdrress'      => (DB::table('masteraccountaddr')->where('AccountId',$id)->get()),
                    'mOrder'        => json_encode($order),
                    'data'          => $res->data,
                ]);
            }
		    return view("form-$jr-v2", $data);
            break;
    case 'account':
        // form initial
        $data = array_merge($data, [
            'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
            'mLevel'  => ['0'=>'Level 0','1'=>'Level 1','2'=>'Level 2','3'=>'Level 3'],
            // 'mHpp'   => ['Average'],
            'mAccount'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
            'data'   => [],
        ]);
        
        //$res = $this->api('GET', 'api/product/'.$id);
        $res = Account::Get($id);
            if ($res->status=='OK') {
            $data['data'] = $res->data;
        }
        //return $data;
        return view('form-account', $data);
        break;
    case 'bank':
        // form initial
        $data = array_merge($data, [
            'mBankType'   => $this->DB_list('masterbanktype', ['id','BankType']),
            //'mBankType'   => DB::table('masterbanktype')->selectRaw('id, BankType')->get(),
            //'mType'  => ['Raw material','Finish good'],
            // 'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
            // 'mHpp'   => ['Average'],
            'mAccount'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
            'data'   => [],
        ]);
        
        //$res = $this->api('GET', 'api/product/'.$id);
        $res = Bank::Get($id);
        if ($res->status=='OK') {
            $res->data->Amount = Account::getAmount($id);
            $data['data'] = $res->data;
        }
        return view('form-bank', $data);
        break;
		// case 'supplier':
		// 	$data['user']=['Code'=>'123'];
		// 	//for combolist
		// 	$data['mAddr']= $this->DB_list('masteraccountaddr', 'Code', "AccCode='$id' ");
		// 	$data['mCat']= [];
		// 	$data['mSalesman']= [];
		// 	$data['mCoa']= [];
			
		// 	$data['data']=[];
		// 	$res = $this->api('GET', 'api/customer/'.$id);
		// 	if ($res->status=='OK') {
		// 		$data['data']=$res->data;
		// 	}
		// 	return view('supplier-edit', $data);
		// break;
	} 
 }
 

// Master save data


// dataSave data
function datasave(Request $req) {
    if ($req->jr == 'profile') return $this->datasave_profile($req);
    if ($req->jr == 'product') return $this->datasave_product($req);
    if ($req->jr == 'customer') return $this->datasave_customersupplier($req);
    if ($req->jr == 'supplier') return $this->datasave_customersupplier($req);
    if ($req->jr == 'coa') return $this->datasave_coa($req);
    if ($req->jr == 'bank') return $this->datasave_bank($req);
    return dd('no save form '.$req->jr);
}
function datasave_product(Request $req) {
    $err='';
    //$input = $req->all();
    try {
        $save = [
            'Code'              => (string)$req->Code,
            'Name'              => (string)$req->Name,
            'Category'          => (string)$req->Category,
            'Type'              => (string)$req->Type,
            'Brand'             => (string)$req->Brand,
            'MinOrder'          => (string)$req->MinOrder,
            'StockProduct'      => (string)$req->StockProduct,
            'ActiveProduct'     => (string)$req->ActiveProduct,
            'canBuy'            => (string)$req->canBuy,
            'canSell'           => (string)$req->canSell,
            'BuyPrice'          => (string)$req->BuyPrice,
            'SellPrice'         => (string)$req->SellPrice,
            'HppBy'             => (string)$req->HppBy,
            'Department'        => (string)$req->Department,
            'Memo'              => (string)$req->Memo,
            'Barcode'           => (string)$req->Barcode,
            'UOM'               => (string)$req->UOM,
            'ProductionUnit'    => (string)$req->ProductionUnit,
            'MinStock'          => (string)$req->MinStock,
            'MaxStock'          => (string)$req->MaxStock,
            'SellPrice'         => (string)$req->SellPrice,
            'AccHppNo'          => (string)$req->AccHppNo,
            'AccSellNo'         => (string)$req->AccSellNo,
            'AccInventoryNo'    => (string)$req->AccInvetoryNo,
        ];
        //dd($save);
        $data = Product::where('Code', $req->Code)->first();
        if (empty($data)) {
            //add new
            Product::save($save);
        } else {
            //update
            Product::where("id", $data->id)->update($save);
        }
        //return response()->json(['success'=>'data saved', 'input'=>$input]);
        $message='Sukses!!';
	    return redirect(url( $req->path() ))->with('success', $message);
    } 
    catch (Exception $e) {
        // exception is raised and it'll be handled here
        // $e->getMessage() contains the error message
        //return response()->json(['error'=>$e->getMessage()]);
        return redirect(url( $req->path() ))->with('error', $e->getMessage());
    }
}
function datasave_customersupplier(Request $req) {
    $err='';
    $input = $req->all();
    //dd($input);
    DB::beginTransaction();
    try {
        $save = [
            'AccCode'       => (string)$req->AccCode,
            'AccName'       => (string)$req->AccName,
            'Category'      => (string)$req->Category,
            'Salesman'      => (string)$req->Salesman,
            'CreditLimit'   => (string)$req->CreditLimit,
            'CreditActive'  => (string)$req->CreditActive,
            'PriceChannel'  => (string)$req->PriceChannel,
            'Memo'          => (string)$req->Memo,
            'AccNo'         => (string)$req->AccNo,
            'TaxNo'         => (string)$req->TaxNo,
            'TaxName'       => (string)$req->TaxName,
            'TaxAddr'       => (string)$req->TaxAddr,
            'AccType'       => ($req->jr=='customer')?'C':'S',
            'Active'        => (string)$req->Active,
            'CreatedBy'     => Session::get('user')->LoginName,
        ];
        $saveAddrData = [    
            'Code'          => (string)$req->TaxAddr,
            'Address'       => (string)$req->TaxAddr,
            'Zip'           => (string)$req->TaxAddr,
            'ContachPerson' => (string)$req->TaxAddr,
            'Phone'         => (string)$req->TaxAddr,
            'Fax'           => (string)$req->TaxAddr,
            'AccCode'       => (string)$req->AccCode,
        ];
        $data = CustomerSupplier::where("AccCode", $req->AccCode)->first();
        if (empty($data)) {
            //add new
            CustomerSupplier::save($save);
        } else {
            //update
            CustomerSupplier::where("id", $data->id)->update($save);
            //CustomerSupplierAddr::where("id", $data->id)->update($addrData);
        }

        DB::commit();
        //return response()->json(['success'=>'data saved', 'input'=>$input]);
        $message='Sukses!!';
	    return redirect(url( $req->path() ))->with('success', $message);
    } 
    catch (Exception $e) {
        DB::rollback();
        // exception is raised and it'll be handled here
        // $e->getMessage() contains the error message
        //return response()->json(['error'=>$e->getMessage()]);
        return redirect(url( $req->path() ))->with('error', $e->getMessage());
    }




    //return $req->Address; //Jakarta

    //update 
    $data = [
        'AccCode'   => (string)$req->AccCode,
        'AccName'   => (string)$req->AccName,
        'Category'  => (string)$req->Category,
        'Salesman'  => (string)$req->Salesman,
        'Memo'      => (string)$req->Memo,
        'AccNo'     => (string)$req->AccNo,
        'TaxNo'     => (string)$req->TaxNo,
        'TaxName'   => (string)$req->TaxName,
        'TaxAddr'   => (string)$req->TaxAddr,
        'AccType'   => ($req->jr=='customer')?'C':'S',
    ];
    $addrData = [    
        'Code'          => (string)$req->TaxAddr,
        'Address'       => (string)$req->TaxAddr,
        'Zip'           => (string)$req->TaxAddr,
        'ContachPerson' => (string)$req->TaxAddr,
        'Phone'         => (string)$req->TaxAddr,
        'Fax'           => (string)$req->TaxAddr,
        'AccCode'       => (string)$req->AccCode,
    ];
    CustomerSupplier::where("id", $req->id)->update($data);
    CustomerSupplierAddr::where("id", $req->id)->update($addrData);
    return response()->json(['success'=>'data saved', 'input'=>$input]);
    // "input": {
    //     "formtype": "supplier",
    //     "_token": "lOHjT5fuwnXWsJalJhJ8UM6mWQesdEbqH3p4SfYd",
    //     "jr": "supplier",
    //     "AccCode": "B001",
    //     "AccName": "BERDIKARI, TOKO",
    //     "Category": "-",
    //     "Salesman": "-",
    //     "Memo": null,
    //     "Code": "-",
    //     "Address": "JAKARTA",
    //     "Zip": null,
    //     "ContachPerson": null,
    //     "Phone": null,
    //     "Fax": null,
    //     "AccNo": null,
    //     "Taxno": null,
    //     "TaxName": "BERDIKARI, TOKO",
    //     "TaxAddr": null
    //     }
}
function datasave_coa(Request $req) {
    //return 'datasave-product';
    $input = $req->all();
    // return $input;
    //update 
    $data = [
        'AccNo'         => (string)$req->AccNo,
        'AccName'       => (string)$req->AccName,
        'CatName'       => (string)$req->CatName,
        'Level'         => (string)$req->Level,
        'Posting'       => (string)$req->Posting,
        'OpenAmount'    => (float)$req->OpenAmount,
        'AccLink'       => (string)$req->AccLink,
        'Memo'          => (string)$req->Memo,
    ];
    Account::where("id", $req->id)->update($data);
    return response()->json(['success'=>'data saved', 'input'=>$data]);
}
function datasave_bank(Request $req) {
    //return 'datasave-product';
    $input = $req->all();
    // return $input;
    //update 
    $data = [
        'AccNo'         => (string)$req->AccNo,
        'BankAccNo'     => (string)$req->BankAccNo,
        'BankAccName'   => (string)$req->BankAccName,
        'BankId'        => (string)$req->BankId,
        'BankType'      => (string)$req->BankType,
        'Memo'          => (string)$req->Memo,
    ];
    Bank::where("id", $req->id)->update($data);
    return response()->json(['success'=>'data saved', 'input'=>$data]);
}

// Export to Excel using koolreport
function datalist_exportexcel(Request $req) {
    //return 'koolreport - export excel';
    $excelPath = 'exportXls/';
    $report = new MyReport;
    $prodData = DB::table('masterproduct')->selectRaw('Code,Name,UOM,Category,1234567 as Qty,id')->get();
    //$prodData = $this->db_query('transhead');
    //return dd($prodData);
    $report->rdata['data'] = $prodData;
    return $report->run()->exportToExcel($excelPath.'report_excel')->toBrowser("myreport.xlsx");
    /*$dat = [ "dataStores" => array(
                'report_product' => array(
                    "columns"=>array(
                        0, 1, 2, 'column3', 'column4' //if not specifying, all columns are exported
                    )
                )
            )
    ];
    $report->run()->exportToExcel($dat)->toBrowser("myreport.xlsx");   */
}

// Export to Pdf using koolreport
function datalist_exportpdf(Request $req) {
    //dd("D:\ProgProject\Web\lav7_PikeAdmin\vendor\prince\engine\bin\prince.exe "."D:\ProgProject\Web\lav7_PikeAdmin\public\638b00325f1251.tmp");
    //$cmd = shell_exec("D:\ProgProject\Web\lav7_PikeAdmin\vendor\prince\engine\bin\prince.exe "."D:\ProgProject\Web\lav7_PikeAdmin\public\638b00325f1251.tmp");
    //exec('D:\ProgProject\Web\lav7_PikeAdmin\vendor\prince\engine\bin\prince.exe '.'D:\ProgProject\Web\lav7_PikeAdmin\public\638b00325f1251.tmp', $output, $status);
    //dd([$output,$status]);

    //return 'koolreport - export pdf';
    $pdfPath = 'exportPdf/';
    $report = new MyReport;
    $prodData = DB::table('masterproduct')->selectRaw('Code,Name,UOM,Category,1234567 as Qty,id')->get();
    $report->rdata['report-header'] = 'Report Master Data List';
    $report->rdata['data'] = $prodData->toArray();
    return $report->run()->export($pdfPath.'report_master_pdf')
                    ->settings([
                        // "useLocalTempFolder" => true,
                    ])
                    ->pdf([
                        'format'=>'A4',
                        'orientation'=>'portrait',
                    ])->toBrowser("myreport.pdf");
}

// Export to Pdf using koolreport chromeHeadless.io
function datalist_exportpdf_usingChromeheadless(Request $req) {
    // return 'koolreport - export pdf using chromeHeadless';
    // https://www.koolreport.com/docs/cloudexport/chromeheadlessio/
    // get Token -> Register an account in https://chromeheadless.io/
    // registered token email:albertsardi@gmail.com pass:sardi2201 token: 597482c40db16dd62a5a0d7d9c0894f5edd63f1178ab6a8d87e9b802a33a4e8b
    $pdfPath = 'exportPdf/';
    $tokenKey = "597482c40db16dd62a5a0d7d9c0894f5edd63f1178ab6a8d87e9b802a33a4e8b";
    $report = new MyReport;
    $prodData = DB::table('masterproduct')->selectRaw('Code,Name,UOM,Category,1234567 as Qty,id')->get();
    $report->rdata['report-header'] = 'Report Master Data List';
    $report->rdata['data'] = $prodData->toArray();
    /*return $report->run()->export($pdfPath.'report_master_pdf')
                    ->settings([
                        // "useLocalTempFolder" => true,
                    ])
                    ->pdf([
                        'format'=>'A4',
                        'orientation'=>'portrait',
                    ])->toBrowser("myreport.pdf"); */
    return $report->run()
            ->cloudExport($pdfPath.'report_master_using_chromeheadless_pdf')
            ->chromeHeadlessio($tokenKey)
            ->pdf([
                "format"=>"A4",
                // "displayHeaderFooter" => true,
                // "headerTemplate" => 'this is header',
                // "footerTemplate" => 'this is footer',
            ])
            ->toBrowser("myreport.pdf");
}

function makeDetailList($id='') {
  $dat=db_get_array('journal','JRdate,ReffNo,JRdesc,Amount as Debet,Amount as Credit,Amount as Bal',"AccNo='$id' ",'JRdate');
  $bal=0;
  for($a=0;$a<count($dat);$a++) {
      $dat[$a]['Debet']= debet($dat[$a]['Debet']);
      $dat[$a]['Credit']= credit($dat[$a]['Credit']);
      $bal= $bal + $dat[$a]['Debet'] - $dat[$a]['Credit'];
      $dat[$a]['Bal']= $bal;
  }
  return table_generate($dat,['Journal Date','Reff #','Description','Debet (Rp)','Credit (Rp)','Balance (Rp)']);
}


// function


function getAccBalance($AccCode, $jr) {
  $row = $this->DB_select("SELECT AccCode,(Total-IFNULL(AmountPaid,0))as Bal
                              FROM transhead
                              LEFT JOIN transpaymentarap ON transpaymentarap.InvNo=transhead.TransNo
                              WHERE LEFT(transhead.transno,2)='$jr' AND AccCode='$AccCode' ");
  if($row==[]) return 0;
  return $row[0]['Bal'];
}

function getProdBalance($Pcode) {
  $row = $this->DB_select("SELECT -SUM(Qty) AS Qty
          FROM transdetail
          WHERE ProductCode='$Pcode' ");
  //return isset($row[0]['Qty'])?$row[0]['Qty']:0;
  return $row[0]['Qty'];
}

function getIndoCity() {
    //populate city
    $OpenApi =new OpenapiController();
    $city = [];
    foreach($OpenApi->indoProvince() as $res) {
        foreach($OpenApi->indoCity($res->id) as $dat) {
            $city[] = (array)$dat;
        }
    }
    return $city;
}







//SERVER SIDE
// function ajax_datalist($jr)
// {
//   $con=[
//       'user'=> env('DB_USERNAME'),
//       'pass'=> env('DB_PASSWORD'),
//       'db'=> env('DB_DATABASE'),
//       'host'=> env('DB_HOST')
//   ];
//
//   $w="";
//   switch($jr) {
//       case 'customer':
//           //$table="masteraccount";
//           //$table="listaccount";
//           //$table="listcustomersupplier";
//           $table="listcustomer";
//           $primaryKey = "AccCode";
//           $col = ['AccCode','AccName','Phone','Email','Address','Bal'];
//           $w= "AccType='C'";
//           break;
//       case 'supplier':
//           //$table="listcustomersupplier";
//           $table="listsupplier";
//           $primaryKey = "AccCode";
//           $col = ['AccCode','AccName','Phone','Email','Address','Bal'];
//           $w= "AccType='S'";
//           break;
//       case 'product':
//           // $table="masterproduct";
//           $table="listproduct";
//           $primaryKey = "Code";
//           /*$col = [
//               ['db'=>'Code', 'dt'=>0],
//               ['db'=>'Name', 'dt'=>1],
//               ['db'=>'UOM', 'dt'=>2],
//               ['db'=>'Category', 'dt'=>3],
//               ['db'=>'Bal', 'dt'=>4]
//           ];*/
//           $col = ['Code','Name','UOM','Category','Bal'];
//           break;
//       case 'coa':
//               $table="listcoa";
//               $primaryKey = "AccNo";
//               $col = ['AccNo','AccName','CatName','Amount'];
//               $w= "";
//               break;
//   }
//
//   //produt
//   //$col="Code,Name,UOM,Category,Bal";
//
//   //customer
//   //$col="AccCode,AccName,Phone,Email,Address,Bal";
//
//   //dd($_GET);
//   $res= json_encode(
//       //SSP::simple($_GET, $con, $table, $primaryKey, $col)
//       //SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w )AccName,AccCode,Phone,Email,Address,Bal
//
//       $this->SSP( $_GET, $table, $primaryKey, $col, $w, "" )
//   );
//
//   //dd(SSP::complex ( $_GET, $con, $table, $primaryKey, $col, null, $w ));
//
//   //$xxx=$_GET;
//   //$res=SSP::complex( $_GET, $con, $table, $primaryKey, $col, null, $w );
//
//   return $res;
// }

// function ssp ( $request, $table, $primaryKey, $columns,  $where='', $order='' )
// {
// 	$bindings = array();
// 	//$db = self::db( $conn );
// 	$localWhereResult = array();
//       $localWhereAll = array();
//       $whereAllSql="";
//       //if ($where!='') $where="WHERE ".$where;
// 	//dd($request);
//
// 	//$table="transhead";
// 	//$primaryKey="TransNo";
// 	//$fld="TransNo,TransDate,AccName,Total,CreatedBy";
// 	//$where="where left(TransNo,2)='DO'";
//       //$order="order by Code";
//
//
//
//       //where
//       $search=isset($_GET['search']['value'])?$_GET['search']['value']:'';
//       //$search='FRO'; //debug
//       if($search!='') {
//           //$where=$this->combine($where, "$primaryKey like '%$search%' ");
//           $where=$this->combine($where, "($columns[0] like '%$search%' or $columns[1] like '%$search%') ");
//       }
//       if ($where!='') $where="WHERE $where ";
//
//       //order by
//       $order="";
//       if ($order!='') $where="ORDER BY $order ";
//
//       //start
//       $start=isset($request['start'])?$request['start']:1;
//
//       //limit
//       $limit=isset($request['length'])?$request['length']:10;
//
//       //columns
//       $columns= implode(",", $columns);
//
//       $resFilterLength = DB::select("SELECT COUNT(`{$primaryKey}`) as TOT
// 	                                   FROM   `$table`
//                                       $where");
//       $recordsFiltered = $resFilterLength[0]->TOT;
//
//       $resTotalLength = DB::select("SELECT COUNT(`{$primaryKey}`)
// 	                                FROM   `$table` ".
//                                   $whereAllSql);
//       $recordsTotal = $limit;//$resTotalLength;
//
// 	   $sql="SELECT $columns
// 				FROM `$table`
// 				$where
// 				$order
// 				limit $start,$limit ";
//       $data = DB::select($sql);
//
// 	// Data set length after filtering
// 	/*$resFilterLength = self::sql_exec( $db, $bindings,
// 		"SELECT COUNT(`{$primaryKey}`)
// 		 FROM   `$table`
// 		 $where"
// 	);
// 	$recordsFiltered = $resFilterLength[0][0];*/
//
//
// 	// Total data set length
// 	/*$resTotalLength = self::sql_exec( $db, $bindings,
// 		"SELECT COUNT(`{$primaryKey}`)
// 		 FROM   `$table` ".
// 		$whereAllSql
// 	);
// 	$recordsTotal = $resTotalLength[0][0];*/
//
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
//                   if(empty($column['db'])){
//                       $row[ $column['dt'] ] = $column['formatter']( $data[$i] );
//                   }
//                   else{
//                       $row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
//                   }
// 			}
// 			else {
//                   if(!empty($column['db'])){
//                       $row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
//                   }
//                   else{
//                       $row[ $column['dt'] ] = "";
//                   }
// 			}
// 		}
// 		$out[] = $row;
// 	}*/
//       $out=[];
//       if($data!=[]) {
//           $data=json_decode(json_encode($data), true);
//           //dd($data);
//      		//for ( $a=0;$a<$limit;$a++) {
//          $a=0;
//          foreach($data as $x) {
//      			$row = [];
//      			$key=array_keys($data[0]);
//      			for ( $b=0;$b<count($key);$b++) {
//      				array_push($row, $data[$a][$key[$b]]);
//      			}
//      			array_push($out,$row);
//             $a++;
//      		}
//       }
// 	     //dd($out);
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
// // }

  // function combine($s1, $s2) {
  //     $s=$s1." ".$s2." ";
  //     if($s1<>"" and $s2<>"") {
  //         $s=$s1." and ".$s2." ";
  //     }
  //     return $s;
  // }


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



}
