<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Http\Model\User;
// use App\Http\Model\Product;
// use App\Http\Model\AccountAddr;
// use App\Http\Model\CustomerSupplier;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Profile;
use App\Http\Model\Account;
// use App\Http\Model\Bank;
// use App\Http\Model\Order;
use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
use Session;

class AccountController extends MainController {

    //form edit
    function dataedit($id) {

        // form initial
        $jr = 'coa';
        $data = [
            'jr' => $jr, 'id' => $id,
            'caption' => $this->makeCaption($jr, $id),

            //'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
            //'mLevel'  => ['0'=>'Level 0','1'=>'Level 1','2'=>'Level 2','3'=>'Level 3'],
            // 'mHpp'   => ['Average'],
            //'mAccount'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),
            'mAccount'  => DB::table('mastercoa')->selectRaw('id, AccNo, AccName, CatName')->get(),
            //'data'   => [],

            'select' => $this->selectData(['selAccountCategory']),
        ];

        //$res = $this->api('GET', 'api/product/'.$id);
        $res = Account::getdata($id);
            if ($res->status=='OK') {
            $data['data'] = $res->data;
        }
        //return $data;
        return view('form-account', $data);
    }


    // dataSave data
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

    // function
    function getAccBalance($AccCode, $jr) {
    $row = $this->DB_select("SELECT AccCode,(Total-IFNULL(AmountPaid,0))as Bal
                                FROM transhead
                                LEFT JOIN transpaymentarap ON transpaymentarap.InvNo=transhead.TransNo
                                WHERE LEFT(transhead.transno,2)='$jr' AND AccCode='$AccCode' ");
    if($row==[]) return 0;
    return $row[0]['Bal'];
    }

}
