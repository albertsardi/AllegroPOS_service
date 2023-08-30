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
    @page {
        size:A4; margin:0.5cm;
        @bottom-left { content: "Department of Strategy"; } 
        @bottom-right { content: counter(page) " of " counter(pages); }
    }
    @media print {
        * { 
            -webkit-print-color-adjust: exact !important;
            font-family:'PT Sans', sans-serif !important;
        }
        table {page-break-inside:auto !important; display:block;}
        tr, .prod, .no-break {page-break-inside:avoid !important; page-break-after:auto !important;}
        /* .table-row {page-break-inside:avoid !important; page-break-after:auto !important;} */
    }
    #header { 
  position: fixed; 
  width: 100%; 
  top: 0; 
  left: 0; 
  right: 0;
}
#footer { 
  position: fixed; 
  width: 100%; 
  bottom: 0; 
  left: 0;
  right: 0;
}
</style>

<div xxxsheet-name="Simple Report">
    <div>Simple report test</div>
        
    <div>
        <h3>123 <?=$this->rdata['report-header'];?></h3>
        <hr/>
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
                                        "</div></td>
                                    <td>".$row['UOM']."</td>
                                    <td>".$row['Qty']."</td>
                                    </tr>";
                        }
                    ],
                    // 'UOM',
                    // 'Qty',
                ],
                //'cssClass' => ["tr" => "table no-break"],
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