<?php
//MyReport.php
namespace App\Report;

require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php";
use koolreport\KoolReport;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable; //use to export to excel
    use \koolreport\cloudexport\Exportable;

    public $rdata;

    protected function bladeInit()
    {
        $viewsFolder = __DIR__."/../views";
        $cacheFolder = __DIR__."/../cache";
        $blade = new \Jenssegers\Blade\Blade($viewsFolder, $cacheFolder);
        return $blade;
    }


    ///--------------------
    function settings() {
        return array(
            "dataSources" => array(
                // "dollarsales"=>array(
                //     //'filePath' => '../../../databases/customer_product_dollarsales2.csv',
                //     'filePath' => dirname(__FILE__).'\customer_product_dollarsales2.csv',
                //     'fieldSeparator' => ',',
                //     'class' => "\koolreport\datasources\CSVDataSource"      
                // ), 
                "data" => array(
                    "class" => '\koolreport\datasources\ArrayDataSource',
                    //"data" => $this->params["report"],
                    //"data" => [ ['aa'=>11,'bb'=>11], ['aa'=>22,'bb'=>22] ],
                    "data" => $this->rdata,
                    
                    "dataFormat" => "associate",
                )
            )
        );
    }    

}
