<?php
//MyReport.php
namespace App\Report;

require_once dirname(__FILE__)."/../../vendor/koolreport/core/autoload.php";
use koolreport\KoolReport;
use \koolreport\bootstrap4\Theme;
use koolreport\export\Exportable;
use koolreport\laravel\Friendship;
use koolreport\excel\ExcelExportable;

//use test
use \koolreport\processes\Map;
use \koolreport\processes\Limit;
use \koolreport\cube\processes\Cube;
use \koolreport\processes\Filter;
use \koolreport\pivot\processes\Pivot;

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\export\Exportable;
    //use \koolreport\blade\Engine;
    use \koolreport\excel\ExcelExportable;

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
    function setup() {
        // dd($this);
        //dd($this->dataStore("result"));
        $this->src('data')
            ->pipe($this->dataStore("result"));
    }
    // function setup() {
    //     $node = $this->src('dollarsales')
    //     //->query('select *, dollar_sales as dollar_sales2 from customer_product_dollarsales2')
    //     ->pipe(new Map([
    //         '{value}' => function($row, $meta) {
    //             //dd($row);
    //             //dd($row['orderQuarter']);
    //             $row['orderQuarter'] = 'Q' . $row['orderQuarter'];
    //             return $row;
    //         },
    //         '{meta}' => function($meta) {
    //             //dd($meta);
    //             $meta['columns']['orderDate']['type'] = 'datetime';
    //             $meta['columns']['orderQuarter']['type'] = 'string';
    //             return $meta;
    //         }
    //     ]))
    //     ;

    //     $node
    //     ->pipe(new Limit(array(
    //         50, 0
    //     )))
    //     ->pipe(new Map([
    //         "{meta}" => function($meta) {
    //             $cMeta = & $meta["columns"]["dollar_sales"];
    //             $cMeta["footer"] = "sum";
    //             $cMeta["type"] = "number";
    //             // print_r($meta); exit;
    //             return $meta;
    //         }
    //     ]))
    //     ->pipe($this->dataStore('orders'));

    //     $node->pipe(new Cube(array(
    //         "rows" => "customerName",
    //         "column" => "orderQuarter",
    //         "sum" => "dollar_sales",
    //     )))
    //     ->pipe(new Limit(array(
    //         5, 0
    //     )))
    //     ->pipe($this->dataStore('salesQuarterCustomer'));

    //     $node->pipe(new Cube(array(
    //         "rows" => "productName",
    //         "column" => "orderQuarter",
    //         "sum" => "dollar_sales",
    //     )))
    //     ->pipe(new Limit(array(
    //         5, 0
    //     )))
    //     ->pipe($this->dataStore('salesQuarterProduct'));

    //     $node
    //     ->pipe(new Filter(array(
    //         array('customerName', '<', 'Au'),
    //         array('orderYear', '>', 2003),
    //     )))
    //     ->pipe(new Pivot(array(
    //         "dimensions" => array(
    //             "column" => "orderYear, orderQuarter",
    //             "row" => "customerName, productLine",
    //         ),
    //         "aggregates" => array(
    //             "sum" => "dollar_sales",
    //         ),
    //     )))
    //     ->pipe($this->dataStore('salesPivot'));
    // }

}
