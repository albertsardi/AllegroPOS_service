<?php
use \koolreport\excel\Table;

$sheet1 = "Simple report";

//$rdata = $this->report->rdata;
//dd($rdata);



?>

<meta charset="UTF-8">
<meta name="description" content="Very simple report for testing">
<meta name="keywords" content="Excel,HTML,CSS,XML,JavaScript">
<meta name="creator" content="Koolreport">
<meta name="subject" content="">
<meta name="title" content="Simple report">
<meta name="category" content="Report">
<meta name="company" content="Advanced Applications GmbH">

<div sheet-name="<?php echo $sheet1; ?>">
    <div>Simple report test</div>

    <div>
        <?php
        // https://www.koolreport.com/examples/reports/excel/table/

        //dd( $this->rdata);
        //dd( $this->report_data);
        /*Table::create(array(
            //"dataSource" => $this->dataStore('array_example_datasource')
            $data = $this->report->data->tableDetail
        ));*/
        //$res = $this->__api(['url' => "report/trans_histories?fromDate=2021-01-03&toDate=2021-12-30"]);
        $res = json_decode(json_encode($this->rdata),true);
        //$res[0]=(array)$res[0];
        //$res[1]=(array)$res[1];
        //dd($res);
        
        
        $res = new \koolreport\core\DataStore($res);
        //$res->rows[0] = (array)$res->rows[0];
        //$res->rows[1] = (array)$res->rows[1];
        //dd($res);
        $tableDetail = [
            //'dataSource' => $this->dataStore('result'),
            'dataSource' => $res,
            //'data' => $res,
            "columns" => [
                '#' => ['label'=>"No", "start"=>1 ],
                'transaction_date' => ['label' => 'Transaction Date'],
                'reference_code' => ['label' => 'Transaction Type'], 
                'trans_reference_code' => ['label' => 'Transaction Status'], 
                'reference_no' => ['label' => 'Reference No'],
                'cif' => ['label' => 'CIF'],
                'fullname' => ['label' => 'Investor Name'],
                'product_name' => ['label' => 'Product'],
                'net_amount' => ['label' => 'Transaction Amount (IDR)', 'type' => 'number', 'cssStyle' => 'text-align:right',
                    'formatValue' => function($val)  {
                        if ($val == 0) return '-';
                        return number_format($val, 2);
                    },
                ],
                'unit' => ['label' => 'Transaction Unit', "cssStyle" => "text-align:right"],
                'approve_amount' => ['label' => 'Approve Amount (IDR)', 'type' => 'number', 'cssStyle' => 'text-align:right',
                    'formatValue' => function($val)  {
                        if ($val == 0) return '-';
                        return number_format($val, 2);
                    },
                ],
                'approve_unit' => ['label' => 'Approve Unit', "cssStyle" => "text-align:right"],
                'fee_amount' => ['label' => 'Fee (IDR)', 'type' => 'number', 'cssStyle' => 'text-align:right',
                    'formatValue' => function($val)  {
                        if ($val == 0) return '-';
                        return number_format($val, 2);
                    },
                    "footer"=>"sum"
                ],
                'tax_amount' => ['label' => 'Tax (IDR)', 'type' => 'number', 'cssStyle' => 'text-align:right',
                    'formatValue' => function($val)  {
                        if ($val == 0) return '-';
                        return number_format($val, 2);
                    },
                    "footer"=>"sum"
                ],
                // 'debt_status' => ['label' => 'Debt Status'],
            ],
            // 'grouping' => [
            //     "transaction_date" => array(
            //         "calculate" => array(
            //             "{sumAmount}" => array("sum", "net_amount"),
            //             "{avgAmount}" => array("avg", "net_amount"),
            //             "{maxAmount}" => array("max", "net_amount")
            //         ),
            //         "top" => "<b>Product ID  => {product_id}</b>",
            //         "bottom" => function ($calculated_results) {
            //             return "Sum amount :".$calculated_results["{sumAmount}"];
            //         },
            //     ),
            // ],
            // 'paging' => [
            //     'pageSize' => 10,
            //     'pageIndex' => 0,
            //     'align' => 'center'
            // ],
            
            // "showFooter"=>"bottom",
            // 'cssClass' => ["table" => "table-bordered table-striped table-hover"],
            // 'cssStyle' => ['table td' => 'font-size:10px;']
            "excelStyle" => [
                "header" => function($colName) { 
                    return [
                        'font' => [
                            'italic' => true,
                            'bold' => false,
                            'color' => [
                                'rgb' => '808080',
                            ]
                        ],
                    ]; 
                },
                "bottomHeader" => function($colName) { return []; },
                "cell" => function($colName, $value, $row) { 
                    return [
                        'font' => [
                            'italic' => true,
                            'color' => [
                                'rgb' => '808080',
                            ]
                        ],
                    ]; 
                 },
                "footer" => function($colName, $footerValue) { return []; },
            ],
        ];
        Table::create( $tableDetail );
        // Table::create( $rdata );
        // Table::create(array(
        //     "dataSource" => 'result',
        // ));
        ?> 
    </div>

</div>

<div sheet-name="Companies">
    <div>Simple report</div>

    <div>
        <?php
        // Table::create(array(
        //     "dataSource" => $this->dataStore('dataSet2')
        // ));
        ?>
    </div>

</div>