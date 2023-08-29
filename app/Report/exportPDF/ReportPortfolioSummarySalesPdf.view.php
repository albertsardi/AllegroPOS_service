<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;

//$sheet1 = "Simple report";

$rdata = $this->report->rdata;
//dd($rdata);

// dd($rdata);
$data = $rdata['data'];
return dd($data);
$res = json_decode(json_encode($data),true);
//dd($res);
$res = new \koolreport\core\DataStore($res);
//return dd($res);


?>

<meta charset="UTF-8">
<meta name="description" content="Very simple report for testing">
<meta name="keywords" content="Excel,HTML,CSS,XML,JavaScript">
<meta name="creator" content="Koolreport">
<meta name="subject" content="">
<meta name="title" content="Simple report">
<meta name="category" content="Report">
<meta name="company" content="Advanced Applications GmbH">

<div sheet-name="Simple Report">
    <div>Simple report test</div>

    <div>
        <?php
            Table::create(array(
                //"dataSource" => $this->dataStore('orders'),
                "dataSource" => $res,

                // tuk Document export xls dan xls chart 
                // ini bisa di lihar di
                // https://www.koolreport.com/docs/excel/excel_widgets/
                /*'filtering'=>function ($row,$idx) {
                    if (stripos($row['Code'], "BENANG") !== false) return true;
                    return false;
                }*/
            ));
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