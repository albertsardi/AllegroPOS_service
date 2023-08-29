<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Model\User;
use App\Http\Model\Product;
use App\Http\Model\AccountAddr;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\CustomerSupplierCategory;
use App\Http\Model\Profile;
use App\Http\Model\Account;
use App\Http\Model\Bank;
use App\Http\Model\Order;
use App\report\MyReport;
use \koolreport\widgets\koolphp\Table;
use \koolreport\export\Exportable;
//use \koolreport\cloudexport\Exportable;
//use HTML;
use Session;

class MasterController extends MainController {

    function datalist($jr) {
        $today = date('Y-m-d');
        //dump(session('user')->Token);
        //show view
        switch($jr) {
        case 'product':
            //$res = Product::selectRaw('Code,Name,UOM,Category,ActiveProduct,id')->where('Token', session('user')->Token)->get();
            $res = Product::selectRaw('Code,Name,UOM,Category,ActiveProduct,id')->where('Token', session('token'))->get();
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
            $accType = ($jr=='supplier')? 'S':'C';
            $res =CustomerSupplier::selectRaw('AccName,masteraccount.AccCode,Phone,Email,Address,Active,masteraccount.id')
                    ->leftJoin('masteraccountaddr as ma', 'masteraccount.id', '=', 'ma.AccountId')
                    ->where('AccType', $accType)
            		->whereRaw('(DefAddr=1 or DefAddr is null)')
                    ->where('masteraccount.Token', session('token'))
                    ->whereRaw("(ma.Token='".session('token')."' or ma.Token is null)")
                    ->get();
            foreach($res as $r) {
                $r->Bal = 12345679;
                $r->Bal = rand(1000,2000)*1000; //TODO
            }
            $data = [
                'jr'        => $jr,
                'grid'      => ['Display Name','Code','Phone','Email','Address', 'Balance (Rp)', 'Status'],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                'data'      => $res,
                // 'datacol'   => []
            ];
            break;
        case 'coa':
            //$res = DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName, 123456 as Bal,id')->get();
            $res= Account::Get()->data;
            foreach($res as $r) {
                $r->Bal = DB::select(" CALL getAccountAmount('".Session::get('Token')."','$r->id','$today') ")[0]->Total ?? 0;;
            }
            $data = [
                'jr'        => $jr,
                'grid'      => ['Account #','Account Name','Category','Amount (Rp)',' '],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                'data'      => $res
            ];
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
            ];
            break;
        }

        $data['grid'] = $this->makeTableList($data['grid']);
        return view('datalist', $data);
    }

    // function db_query($db, $fld='*') {
    //     return DB::table($db)->get();
    // }

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
            //   $dat= $this->db_select("SELECT mastercoa.AccNo as AccNo,AccName,CatName,ifnull(SUM(Amount),0)
            //                               FROM mastercoa
            //                               LEFT JOIN journal ON journal.AccNo=mastercoa.AccNo
            //                               GROUP BY AccNo");
            $dat= Account::Get()->data;
            for($a=0;$a<count($dat);$a++) {
                $dat[$a]['AccNo']= link_to("accountdetail/".$dat[$a]['AccNo'], $dat[$a]['AccNo']);
            }
            dd($dat);
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

}