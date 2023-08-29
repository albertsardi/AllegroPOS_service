<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Http\Model\User;
use App\Http\Model\Product;
// use App\Http\Model\AccountAddr;
// use App\Http\Model\CustomerSupplier;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Profile;
// use App\Http\Model\Account;
// use App\Http\Model\Bank;
// use App\Http\Model\Order;
use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
use Session;

class ProductController extends MainController {

    //form edit
    function dataedit($id) {
        $jr = 'product';
        $data = [
            'jr' => $jr, 'id' => $id,
            'caption' => $this->makeCaption($jr, $id),
            'token' => session('token'),

            //'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
            //'mSubCat' => [],
            //'mBrand' => [],
            //'mType'  => ['Raw material','Finish good'],
            //'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
            //'mHpp'   => ['Average'],
            'mAccount'  => DB::table('mastercoa')->selectRaw('id, AccNo, AccName, CatName')->get(),
            //'mCoa'  => DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get(),

            'select' => $this->selectData(['selProductCategory','selProductType','selHPP']),
        ];

        // get data
        $data['data']=[];
        $res = Product::getdata($id);
        if ($res->status=='OK') {
            $data['data'] = $res->data;
        }
        //return view('form-product', $data);
        return view('form-product-v2', $data);
    }

    // dataSave data
    // function datasave_product(Request $req) {
    //     $err='';
    //     $save = $req->all();
    //     try {
    //         // $save = [
    //         //     'Code'              => (string)$req->Code,
    //         //     'Name'              => (string)$req->Name,
    //         //     'Category'          => (string)$req->Category,
    //         //     'Type'              => (string)$req->Type,
    //         //     'Brand'             => (string)$req->Brand,
    //         //     'MinOrder'          => (string)$req->MinOrder,
    //         //     'StockProduct'      => (string)$req->StockProduct,
    //         //     'ActiveProduct'     => (string)$req->ActiveProduct,
    //         //     'canBuy'            => (string)$req->canBuy,
    //         //     'canSell'           => (string)$req->canSell,
    //         //     'BuyPrice'          => (string)$req->BuyPrice,
    //         //     'SellPrice'         => (string)$req->SellPrice,
    //         //     'HppBy'             => (string)$req->HppBy,
    //         //     'Department'        => (string)$req->Department,
    //         //     'Memo'              => (string)$req->Memo,
    //         //     'Barcode'           => (string)$req->Barcode,
    //         //     'UOM'               => (string)$req->UOM,
    //         //     'ProductionUnit'    => (string)$req->ProductionUnit,
    //         //     'MinStock'          => (string)$req->MinStock,
    //         //     'MaxStock'          => (string)$req->MaxStock,
    //         //     'SellPrice'         => (string)$req->SellPrice,
    //         //     'AccHppNo'          => (string)$req->AccHppNo,
    //         //     'AccSellNo'         => (string)$req->AccSellNo,
    //         //     'AccInventoryNo'    => (string)$req->AccInvetoryNo,
    //         // ];
    //         $data = Product::updateOrCreate(
    //             ['Token'=>session('token')],
    //             $save);
    //         //return response()->json(['success'=>'data saved', 'input'=>$input]);
    //         $message='Sukses!!';
    //         return redirect(url( $req->path() ))->with('success', $message);
    //     }
    //     catch (Exception $e) {
    //         // exception is raised and it'll be handled here
    //         // $e->getMessage() contains the error message
    //         //return response()->json(['error'=>$e->getMessage()]);
    //         return redirect(url( $req->path() ))->with('error', $e->getMessage());
    //     }
    // }
    // function datasave_product(Request $req) {
    //     $err='';
    //     //$save = $req->all();
    //     try {
    //         $save = [
    //             'Code'              => (string)$req->Code,
    //             'Name'              => (string)$req->Name,
    //             'Category'          => (string)$req->Category,
    //             'Type'              => (string)$req->Type,
    //             'Brand'             => (string)$req->Brand,
    //             'MinOrder'          => (string)$req->MinOrder,
    //             'StockProduct'      => (string)$req->StockProduct,
    //             'ActiveProduct'     => (string)$req->ActiveProduct,
    //             'canBuy'            => (string)$req->canBuy,
    //             'canSell'           => (string)$req->canSell,
    //             'BuyPrice'          => (string)$req->BuyPrice,
    //             'SellPrice'         => (string)$req->SellPrice,
    //             'HppBy'             => (string)$req->HppBy,
    //             'Department'        => (string)$req->Department,
    //             'Memo'              => (string)$req->Memo,
    //             'Barcode'           => (string)$req->Barcode,
    //             'UOM'               => (string)$req->UOM,
    //             'ProductionUnit'    => (string)$req->ProductionUnit,
    //             'MinStock'          => (string)$req->MinStock,
    //             'MaxStock'          => (string)$req->MaxStock,
    //             'SellPrice'         => (string)$req->SellPrice,
    //             'AccHppNo'          => (string)$req->AccHppNo,
    //             'AccSellNo'         => (string)$req->AccSellNo,
    //             'AccInventoryNo'    => (string)$req->AccInvetoryNo,
    //         ];
    //         //dd($save);
    //         $data = Product::where('Code', $req->Code)->first();
    //         if (empty($data)) {
    //             //add new
    //             Product::save($save);
    //         } else {
    //             //update
    //             Product::where("id", $data->id)->update($save);
    //         }
    //         //return response()->json(['success'=>'data saved', 'input'=>$input]);
    //         $message='Sukses!!';
    // 	    return redirect(url( $req->path() ))->with('success', $message);
    //     }
    //     catch (Exception $e) {
    //         // exception is raised and it'll be handled here
    //         // $e->getMessage() contains the error message
    //         //return response()->json(['error'=>$e->getMessage()]);
    //         return redirect(url( $req->path() ))->with('error', $e->getMessage());
    //     }
    // }

}
