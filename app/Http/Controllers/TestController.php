<?php
   
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Report\MyReport;
use App\Report\CPMExport;
use App\Http\Model\Common;
use App\Http\Model\Transaction;
use App\Http\Model\Order;
use App\Http\Model\Delivery;
use App\Http\Model\Invoice;
use App\Http\Model\Expense;
use App\Http\Model\Warehouse;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Journal;
use \koolreport\export\Exportable;
use \koolreport\excel\ExcelExportable;
use Session;

class TestController extends MainController {

	/* test make export PDF using koolreport */
	public function reportPDF(Request $req) {
        // http://localhost:84/report/inv-balance?invid=27
        // http://localhost:84/report/investor_list
        $sess = Session::get('user_auth');
        $mod = (!empty($req->input('mod')))? $req->input('mod') : 'view';
		$mod = 'pdf';
        
        // if ($mod=='pdf') {
        //     $this->printData = json_decode(session('printData'), true);
        //     return $this->report_export( $this->printData, 'report-invbalance2Pdf');
        // }

        $report = new MyReport;
        $report->run();
		$cur = 'Rp.';
		$tbl1 = DB::table('masterproduct')->limit(20)->get();
		foreach($tbl1 as $t) {
			$t->product = $t->Code.'<br/>'.$t->Name.'<br/>'.'type; Fresh Product';
		}
		// dd($tbl1);
        $table1 = [
            'name' => 'table1',
            'dataSource' => $tbl1,
            'columns' => [
                'id' => ['label' => '', "cssStyle" => "width:10%"],
                'product'=>['label' => 'Product Detail', 'footerText'=>'Cash/Deposits', "cssStyle" => "width:70%;"],
                'id' => ['label'=>"Market Value ($cur)", "type"=>"number", "cssStyle"=>"text-align:right;width:20%;", 'footer'=>'sum', 'footerText'=>"<span class='underline'><small>Market Value ($cur)</small></span><br><br><b>@value</b>",
                                'formatValue'=>function ($value) { if($value==0) return '-'; return ($value); }
                            ],
            ],
            // 'grouping' => [
            //     'asset_class_name' => array(
            //         "calculate"=>[
            //             "{sum_amount}" => ['sum', 'amount'],
            //         ],
            //         "top" => function ($row) {
            //                         $pct = ($this->tot1!=0)? $this->num($row['{sum_amount}'])/$this->tot1 : 0;
            //                         $percent = $this->percent($pct);
            //                         if ($row['{asset_class_name}']=='Saving') $row['{asset_class_name}']='Saving & Giro'; 
            //                         return "<tr class='row-subgroup'><td>$percent</td><td>".$row['{asset_class_name}']."</td>
            //                                 <td class='text-right'>".$row['{sum_amount}']."</td></tr>";
            //                     },
            //     ),
            // ],
            'showHeader' => false,
            'showFooter' => 'top',
            // 'sorting' => ['asset_class_name' => 'desc', 'product_name' => 'asc'],
            'cssClass' => ["table" => "table-bordered table-striped table-hover", "th" => "d-none"],
            'cssStyle' => ['table td' => 'font-size:10px;'],
        ];
        
        // $rpt = $this->report_header();
		$rpt = []; $head = [];
        $this->printData = ['head' => $head, 'table1'=>$table1, 'report' => $rpt ];
        //session(['printData' => json_encode($this->printData)]);
        if ($mod=='pdf') {
            return $this->report_export( $this->printData, 'test/report-invbalance2Pdf');
        } else {
            return view("test/report-invbalance2", $this->printData);
        }
    }

	public function koolreportchart() {
		//return 'koolreportchartwatermark';
        $data = [];
        $data = DB::table("journal")->selectRaw('AccNo,sum(abs(Amount))as Amount')->groupBy('AccNo')->limit(10)->get();
        $data = $data->toArray();
        $report = new MyReport;
        $report->run();
        return view('test/koolreport-chart', ['data'=>$data,'caption'=>'koolreport-chart','jr'=>'chart']);
	}

    public function koolreportchart_pdf() {
        $pdfPath = 'exportPdf/';
        $report = new MyReport;
        $data = DB::table("journal")->selectRaw('AccNo,sum(abs(Amount))as Amount')->groupBy('AccNo')->limit(10)->get();
        $data = $data->toArray();
        $report->rdata['report-header'] = 'Report Master Data List';
        $report->rdata['data'] = $data;
        // dd($data);
        return $report->run()->export($pdfPath.'koolreport-chart_pdf')
                    ->settings([
                        // "useLocalTempFolder" => true,
                    ])
                    ->pdf([
                        'format'=>'A4',
                        'orientation'=>'portrait',
                    ])->toBrowser("myreport.pdf");
}
	

	public function report_export($data, $pdfFile='examPdf', $useLocal=true) {
        //$PhantomLinuxPath = '/var/www/html/staging/asnb/web/CPM/vendor/koolreport/export/bin/phantomjs';
        //$PhantomWinPath = "C:/xampp2/htdocs/CPM/vendor/koolreport/export/bin/phantomjs"; 
        $PhantomWinPath = base_path()."/vendor/koolreport/export/bin/phantomjs";
        $PhantomLinuxPath = base_path().'/vendor/koolreport/export/bin/phantomjs';

        $exportDir = '../../resources/views/';
		$report = new CPMExport;
        $report->data = $data;
        return $report->run()->export($pdfPath.$pdfFile)
                    ->settings([
                        // "useLocalTempFolder" => true,
                    ])
                    ->pdf([
                        'format'=>'A4',
                        'orientation'=>'portrait',
                    ])->toBrowser("myreport.pdf");



        /*$report->run()
            ->export($exportDir.$pdfFile)
            ->settings([
                // sudah di coba not use local but fail
                // sekarang coba use local
                "useLocalTempFolder" => true,
                // "useLocalTempFolder" => $useLocal=='yes'? true : false, //not use local
                "phantomjs" => "phantomjs", //enable jika menggunakan BRANCH MASTER
                // "phantomjs" => (strtolower(env('EXPORT_SERVER'))=='win')? $PhantomWinPath : $PhantomLinuxPath,
                //"phantomjs" => (strtolower(env('EXPORT_SERVER', config('environment.EXPORT_SERVER')))=='win')? $PhantomWinPath : $PhantomLinuxPath,
                //"resourceWaiting"=>4000,
                //"resourceWaiting"=>6000
                // "resourceWaiting"=>60000,
            ])

            ->pdf(array(
                "format"        => "A4",
                "orientation"   => "portrait",
                "margin"        => "0.2in",
                "zoom"        => .9 
                //"zoom"        =>  (strtolower(env('EXPORT_SERVER'))=='win')? 0.9 : 0.7 //was .9
            ))
            ->toBrowser("export.pdf", true); */
    }

	/* test make docraptor */
	public function test_docraptor() {
		//return 'docraptor';
		// doc raptor Your API Key: 60AHGxlcsR-nH6XtjUtS
		/* install
			composer require guzzlehttp/guzzle:^7.0
		*/
		$apiKey = '60AHGxlcsR-nH6XtjUtS';
		$docraptor = new DocRaptor\DocApi();
		$docraptor->getConfig()->setUsername("YOUR_API_KEY_HERE");
		// $docraptor->getConfig()->setDebug(true);
		$doc = new DocRaptor\Doc();
		$doc->setTest(true);                                                   // test documents are free but watermarked
		//$doc->setDocumentContent("<html><body>Hello World</body></html>");     // supply content directly
		// $doc->setDocumentUrl("http://docraptor.com/examples/invoice.html"); // or use a url
		$doc->setDocumentUrl("https://money.kompas.com/worksmart"); // or use a url
		$doc->setName("docraptor-php.pdf");                                    // help you find a document later
		$doc->setDocumentType("pdf");                                          // pdf or xls or xlsx
		// $doc->setJavascript(true);                                          // enable JavaScript processing
		// $prince_options = new DocRaptor\PrinceOptions();                    // pdf-specific options
		// $doc->setPrinceOptions($prince_options);
		// $prince_options->setMedia("screen");                                // use screen styles instead of print styles
		// $prince_options->setBaseurl("http://hello.com");                    // pretend URL when using document_content
		$create_response = $docraptor->createDoc('d:/'.$doc);
	}

}
