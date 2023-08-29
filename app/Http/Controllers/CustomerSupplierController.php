<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Http\Model\User;
// use App\Http\Model\Product;
// use App\Http\Model\AccountAddr;
use App\Http\Model\CustomerSupplier;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Profile;
// use App\Http\Model\Account;
// use App\Http\Model\Bank;
use App\Http\Model\Order;
use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
use Session;

class CustomerSupplierController extends MainController {

    // form edit
    function dataedit($id) {
        if(str_contains($_SERVER['REQUEST_URI'], 'customer/edit')) $jr='customer';
	    if(str_contains($_SERVER['REQUEST_URI'], 'supplier/edit')) $jr='supplier';
    
        $data = [
            'jr' => $jr, 'id' => $id,
            'caption' => $this->makeCaption($jr, $id),
            //'user' => ['Code'=>'123']
        ];

        $data = array_merge($data,[
            //'mAddr'         => $this->DB_list('masteraccountaddr', 'Code', "AccCode='$id' "),
            //'mCat'          => $this->DB_list('masteraccountcategory', 'Category'),
            //'mSalesman'     => $this->DB_list('mastersalesman', 'Name'),
            //'mPriceChannel' => ['Channel1', 'Channel2', 'Channel3', 'Channel4', 'Channel5' ],
            //'mAccount'      => json_encode(DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get()),
            //'mAccount'      => DB::table('mastercoa')->selectRaw('id, AccNo, AccName, CatName')->where('CatName','Accounts Receivable (A/R)')->get(),
            'mAccount'      => ($jr=='AR')? $this->modalData(['modAccount-AR']):$this->modalData(['modAccount-AP']),
            'mAddress'      => DB::table('masteraccountaddr')->where('AccountId',$id)->get(),
            //'AdrressCount'  => (DB::table('masteraccountaddr')->where('AccountId',$id)->count()),
            'mOrder'        => '[]',
            //'data'        => $res->data,

            'select' => $this->selectData(['selCustomerSupplierCategory', 'selSalesman', 'selPriceLevel']),
        ]);

        // get data
        $data['data']=[];
        $res = CustomerSupplier::getdata($jr, $id);
        if(!empty($res->data)) {
            $data['data'] = $res->data;
            $data['mOrder'] = Order::where('AccCode',$res->data->AccCode)->get();
        }

        return view("form-$jr-v2", $data);
    }

    // save data
    function datasave_customersupplier(Request $req) {
        $err='';
        $save = $req->all();
        dump($save);
        DB::beginTransaction();
        try {
            // $save = [
            //     'AccCode'       => (string)$req->AccCode,
            //     'AccName'       => (string)$req->AccName,
            //     'Category'      => (string)$req->Category,
            //     'Salesman'      => (string)$req->Salesman,
            //     'CreditLimit'   => (string)$req->CreditLimit,
            //     'CreditActive'  => (string)$req->CreditActive,
            //     'PriceChannel'  => (string)$req->PriceChannel,
            //     'Memo'          => (string)$req->Memo,
            //     'AccNo'         => (string)$req->AccNo,
            //     'TaxNo'         => (string)$req->TaxNo,
            //     'TaxName'       => (string)$req->TaxName,
            //     'TaxAddr'       => (string)$req->TaxAddr,
            //     'AccType'       => ($req->jr=='customer')?'C':'S',
            //     'Active'        => (string)$req->Active,
            //     'CreatedBy'     => Session::get('user')->LoginName,
            // ];
            // $saveAddrData = [
            //     'Code'          => (string)$req->TaxAddr,
            //     'Address'       => (string)$req->TaxAddr,
            //     'Zip'           => (string)$req->TaxAddr,
            //     'ContachPerson' => (string)$req->TaxAddr,
            //     'Phone'         => (string)$req->TaxAddr,
            //     'Fax'           => (string)$req->TaxAddr,
            //     'AccCode'       => (string)$req->AccCode,
            // ];
            // $data = CustomerSupplier::where("AccCode", $req->AccCode)->first();
            // if (empty($data)) {
            //     //add new
            //     CustomerSupplier::save($save);
            // } else {
            //     //update
            //     CustomerSupplier::where("id", $data->id)->update($save);
            //     //CustomerSupplierAddr::where("id", $data->id)->update($addrData);
            // }
            $accType = ($req->jr=='customer')? 'C':'S';
            $data = CustomerSupplier::updateOrCreate(
                ['Token'=>session('token'), 'AccType'=>$accType ],
                $save);

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

    // function
    function getAccBalance($AccCode, $jr) {
    $row = $this->DB_select("SELECT AccCode,(Total-IFNULL(AmountPaid,0))as Bal
                                FROM transhead
                                LEFT JOIN transpaymentarap ON transpaymentarap.InvNo=transhead.TransNo
                                WHERE LEFT(transhead.transno,2)='$jr' AND AccCode='$AccCode' ");
    if($row==[]) return 0;
    return $row[0]['Bal'];
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

}
