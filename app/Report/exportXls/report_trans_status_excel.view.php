<?php
use \koolreport\excel\Table;

$sheet1 = "Simple report";

//$rdata = $this->report->rdata;
//dd($rdata);
// $res = json_decode(json_encode($this->rdata),true);
$res = json_decode(json_encode($this->rdata['data']),true);
$res = new \koolreport\core\DataStore($res);

$tableSum = $this->rdata['tableSum'];
//dd($tableSum);


// https://www.koolreport.com/examples/reports/excel/table/

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
    

    {{-- Header --}}
    <div class='row'>
        <div class='col-3 text-left'><img src="{{ cpm_img('logo/'.$report->logo) }}" height="35px" alt="" /></div>
        <div class='col-6 text-center'><b><h2>Transaction Status Report</h2></b></div>
        <div class='col-3 text-right'>
            <p>PRAISINDO TEKNOLOGI</p>
            <p></p>
            <p>Jalan Taman Empu Sendok no. 53
            Jakarta Selatan, 21220
            Phone No. +62 21 251 352 11</p>
        </div>
    </div>

    <div class='row'>
        <div class='col-3 text-left'>
            <p>Period Date
            <br> <small class="text-muted">Tanggal Period</small></p>
            <p></p>
        </div>
        <div class='col-9 text-left'>
            <p>: {{ $head['fromDate'] }} - {{ $head['toDate'] }}</p>
            <p></p>
        </div>
    </div>

    <!-- 
    excel docs
    https://www.koolreport.com/docs/excel/export_to_excel/
    -->
    <!-- http://localhost:84/report/report_trans_status_xls?fromDate=2021-01-03&toDate=2021-12-30&search= -->
    <div range='A1:M3'>DETAIL</div>
    <div range='A5:A5'>
        <?php
        //table Detail
        $tableDetail = [
            'name' =>'EXCEL NAME',
            //'dataSource' => $this->dataStore('result'),
            'dataSource' => $res,
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
        ?> 
    </div>

    <div>xxx</div>
    <div>    </div>

    <div>SUMMARY</div>
    <div>
        <?php
        //dd($tableSum['dataSource']);
        $dat = json_decode(json_encode($tableSum['dataSource']),true);
        $dat = new \koolreport\core\DataStore($dat);
        $tableSum['dataSource'] = $dat;
        //table Sum
        Table::create( $tableSum );
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