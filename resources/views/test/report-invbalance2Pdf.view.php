<?php
    use \koolreport\bootstrap4\Theme;
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\export\Exportable;
    
    $data = $report->data;
    $head = $data['head'];
    $logo = $data['report']->logo ?? '';
?>

<meta charset="UTF-8">
<html>
    <header>
        <style>
            @media print {
                * { -webkit-print-color-adjust: exact !important; }
                /*table, tr, td, th, tbody, thead, tfoot { 
                    page-break-inside: avoid !important;
                }*/
                #table1 td{page-break-after: always !important;}
            }
            h2 b{font-size:17px;color:rgb(192,143,0) !important;padding-bottom:5px;}
            .gap {padding-bottom:30px;}
            .w-100 {width:100%;}
            .text-small {font-size:10px;}
            .text-left {text-align:left;}
            .text-right {text-align:right;}
            .text-center {text-align:center;}
            .text-danger { color:red !important; }
            .text-success { color:green !important; }
            .italic {font-style:italic;}
            .py-0 {padding-top:0px;padding-bottom:0px;}
            .my-0 {margin-top:0px;margin-bottom:0px;}

            .table-head {border:none;} 
            .table-caption b{font-size:14px;color:rgb(0,162,156) !important;}
            small{color:white !important;}
            /* table th {height:1px !important;visibility:hidden} */
            tr.row-subgroup td{background-color:#F0F0F0 !important;}
            #table1 td{border-width:1px 0px 1px 0px !important;font-size:11px;}
            #table1 tfoot {height:50px;border-color:black !important;background-color:#56585B !important;}
            #table1 tfoot td{font-size:12px;color:white !important;background-color:#56585B !important;font-weight:bold;}
            #table1 tfoot td b{color:white !important;}
            #table1 td {color:red !important;}

        </style>
    </header>
    
    <body>
        <!-- <div class="page-header text-small" style="text-align:right"><i>CPM Report</i><hr/></div> -->
        <div class="page-header text-small" style="font-size:9px;"><b>Tanggal Laporan <?=date('d M Y h:i');?></b><br><i>Reporting Date</i></div>
        <div class="page-footer text-small" style="font-size:9px;"><i>{pageNum}</i></div>
        
        <div class='row'>
            <table class="table-head" style="width:100%;height:90px;">
                <tr>
                    <td style="width:20%"></td>
                    <td style="width:60%" class="text-center">
                        <h2 class='py-0 my-0'><b>LAPORAN KONSOLIDASI</b></h2>
                        <p class='text-muted'>(Consolidated Statement)</p>
                    </td>
                    <td style="width:20%;vertical-align:bottom;text-align:right;">[IMAGE]</td>
                </tr>
            </table>
            <br/>
        </div>

        <div class='row'>
            <table class="table-head" style="width:100%;font-size:12px;">
                <tr>
                    <td style="width:20%"><p><b>CIF</b><br/>CIF</p></td>
                    <td style="width:80%">: <?=$head['cif'] ?? '-';?><br/>&nbsp;</td>
                </tr>
                <tr>
                    <td><p><b>Nama</b><br/>Name</p></td>
                    <td><p>: <?=$head['fullname'] ?? '-';?><br/>&nbsp;</p></td>
                </tr>
                <tr>
                    <td><p><b>Jumlah Asset (<?=$head['cur'] ?? '-';?>)</b><br/>Total Asset Value (<?=$head['cur']??'-';?>)</p></td>
                    <td>: <?=$head['totasset'] ?? '0';?><br/>&nbsp;</td>
                </tr>
            </table>
        </div>
        
        <div class='row table-caption py-0 my-0'><b>Aset/Assets</b></div>
        <div class='row py-0 my-0' style='font-weight:bold;font-size:10px;'>
            <div class='w-100 py-0 my-0'>    
            <?php Table::create($data['table1']);?>
            </div>
        </div>
        
        <hr/>
        <div class='row'>
            <div class='col-12' style='font-size:10px;font-style:italic;'>
            <p>Disclaimer: Laporan bulanan nasabah terkait investment value yang diterbitkan oleh PT Bank Syariah Indonesia Tbk hanya memuat laporan investasi Reksa Dana dengan harga Nett Asset Value (NAV) yang tercatat pada sistem internal PT Bank Syariah Indonesia Tbk. NAV yang tercantum merupakan Harga NAV pada hari sebelum Laporan Keuangan Ini diterbitkan. Laporan bulanan terkait investment value belum termasuk sukuk pemerintah serta banccassurance.</p>
            <p>The customer's monthly report regarding investment value issued by PT Bank Syariah Indonesia Tbk only contains a report on Mutual Fund investment at the Nett Asset Value (NAV) price recorded in the internal system of PT Bank Syariah Indonesia Tbk. The NAV listed is the NAV Price on the day before this Financial Statement is issued. Monthly reports related to investment value do not include government sukuk and banccassurance.</p>
            </div>
        </div>

    </body>
</html>