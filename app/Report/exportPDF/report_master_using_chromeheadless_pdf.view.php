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
<meta name="company" content="Advanced Applications GmbH">

<style>
    @media print {
        * { 
            -webkit-print-color-adjust: exact !important;
            font-family:'PT Sans', sans-serif !important;
        }
        table {page-break-inside:auto !important; display:block;}
        tr, .no-break {page-break-inside:avoid !important; page-break-after:auto !important;}
    }
</style>

<div xxxsheet-name="Simple Report">
    <div>Simple report test</div>

    <div>
        <?php
            Table::create(array(
                //"dataSource" => $this->dataStore('orders'),
                "dataSource" => $res,

                'columns' => [
                    [
                        'label' => 'Product', 
                        'value' => function($row) {
                            //dd($row);
                            // return "<div class='xprod'>".
                            // $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                            // $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                            // $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                            //     "</div>";
                            return "<tr class='no-break'>
                                    <td><div class='xprod'>".
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        $row['Code'].'<br/>'.$row['Name'].'<br/>'.$row['Category'].
                                        "</div></td>
                                    <td>".$row['UOM']."</td>
                                    <td>".$row['Qty']."</td>
                                    </tr>";
                        }
                    ],
                    // 'UOM',
                    // 'Qty',
                ],

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

<header>
    <div id="header-template" 
        style="font-size:10px !important; color:#808080; padding-left:10px">
        <span>Header: </span>
        {date}
        {title}
        {url}
        {pageNumber}
        {totalPages}
        <span id='pageNum' class="pageNumber"></span>
    </div>
</header>
<footer>
    this is footer
</footer>

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