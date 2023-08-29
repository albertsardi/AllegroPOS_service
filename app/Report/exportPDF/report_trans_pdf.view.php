<?php
use \koolreport\widgets\koolphp\Table;
use \koolreport\widgets\google\ColumnChart;

$data = $this->rdata['data'];
$res = json_decode(json_encode($data),true);
$res = new \koolreport\core\DataStore($res);
?>

<meta charset="UTF-8">
<meta name="description" content="Very simple report for testing">
<meta name="keywords" content="Excel,HTML,CSS,XML,JavaScript">
<meta name="creator" content="Koolreport">
<meta name="subject" content="">
<meta name="title" content="Simple report">
<meta name="category" content="Report">
<meta name="company" content="AllegroSoft.id">

<div xxxsheet-name="Simple Report">
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

<div xxxsheet-name="Companies">
    <div>Simple report</div>

    <div>
        <?php
        // Table::create(array(
        //     "dataSource" => $this->dataStore('dataSet2')
        // ));
        ?>
    </div>

</div>