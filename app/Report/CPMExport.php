<?php
//MyReport.php
namespace App\Report;

require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php";
use koolreport\KoolReport;
//use koolreport\export\Exportable;
use koolreport\laravel\Friendship;
use \koolreport\bootstrap4\Theme;
use \koolreport\processes\TimeBucket;
use \koolreport\processes\Group;

class CPMExport extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    //We leave this blank to demo only

    function exportToExcel() {
        return  '<html><h2>12345</h2></html>';
    }
    public function settings()
    {
        return array(
            // "dataSources"=>array(
            //     "sakila_rental"=>array(
            //         "class"=>'\koolreport\datasources\CSVDataSource',
            //         'filePath'=>dirname(__FILE__)."\sakila_rental.csv",
            //     )
            // )
            "dataSources"=>[['payment_date'=>'2021-01-01','amount'=>3333],
                            ['payment_date'=>'2021-01-02','amount'=>4444]]
        );
    }   
    protected function setup()
    {
        /*$this->src('sakila_rental')
        ->pipe(new TimeBucket(array(
            "payment_date"=>"month"
        )))
        ->pipe(new Group(array(
            "by"=>"payment_date",
            "sum"=>"amount"
        )))
        ->pipe($this->dataStore('sale_by_month'));
        */
    } 
    protected function bladeInit()
    {
        $viewsFolder = __DIR__."/../views";
        $cacheFolder = __DIR__."/../cache";
        $blade = new \Jenssegers\Blade\Blade($viewsFolder, $cacheFolder);
        // dd($blade);
        return $blade;
    }

}

