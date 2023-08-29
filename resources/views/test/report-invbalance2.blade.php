@extends('layouts/app-cpm')
@section('header_elements')
    @php 
        use \koolreport\widgets\koolphp\Table;
        use \koolreport\widgets\google\PieChart;
        use \koolreport\excel\ExcelExportable;
        use \koolreport\export\Exportable;
    @endphp
@endsection

@section('header_contents')
    <div class="page-header-content header-elements-md-inline">
    </div>
@endsection
@section('content')
    <div class="xcard">
        <div class="xcard-body">     
            <div class='row'>
                    <div class="card" style="width: auto">
                        <div class="card-body" style="font-size:12px;">
                            <div class='row'>
                                <div class='col-3 text-left'></div>
                                <div class='col-6 text-center'>
                                    <h2 class='py-0 my-0'><b>LAPORAN KONSOLIDASI</b></h2>
                                    <p class='text-muted'>(Consolidated Statement)</p>
                                </div>
                                 <div class='col-3 text-right' style='vertical-align:text-bottom;'>
                                    {{-- <p class="text-uppercase"><b>{{ $report->company_name ?? '-' }}</b></p>
                                    <p></p>
                                    <p>{{ $report->company_address1 ?? '-' }}</br>
                                    {{ $report->company_address2 ?? '-' }}</p> --}}
                                    <img src="{{ cpm_img('logo/'.$report->logo) }}" height="45px" alt="">
                                    {{-- <img src="https://cpm-ext.praisindo.com/img/logo/42_40e055cdf4f866ade934a8e3eed8db26.png" height="45px" alt="">  <!-- color logo --> --}}
                                    <!-- <img src="https://cpm-ext.praisindo.com/img/logo/42_aaabf62a5f66a8e729c7fec453797e34.png" height="45px" alt="">  --> <!--  white logo -->
                                </div>
                            </div>
                            <br/><br/>
                            <div class='row'>
                                <div class='col-2 text-left'>
                                    <p><b>CIF</b><br/>CIF</p>
                                    <p><b>Nama</b><br/>Name</p>
                                    <p></p>
                                    <p><b>Jumlah Asset ({{ $head['cur'] }})</b><br/>Total Asset Value ({{ $head['cur'] }})</p>
                                    <p></p>
                                </div>
                                <div class='col-4 text-left'>
                                    <p>: {{ $head['cif'] ?? '-' }}<br/>&nbsp;</p>
                                    <p>: {{ $head['fullname'] ?? '-'  }}<br/>&nbsp;</p>
                                    <p></p>
                                    <p>: {{ $head['totasset'] ?? '0' }}<br/>&nbsp;</p>
                                    <p></p>
                                </div>
                                <div class='col-4 text-center'></div>
                                <div class='col-2 text-left'>
                                </div>
                                <div class='col-2 text-left'>
                                </div>
                            </div>

                            <div class='row table-caption'><b>Aset/Asset</b></div>
                            <div class='row' style='font-weight: bold;font-size:10px;'>
                                <div class='w-100'>
                                @php Table::create($table1); @endphp
                            </div>
                            </div>
                            {{-- <br><br> --}}
                            <div class='row' style='font-weight: bold;font-size:10px;'>
                                <div class='w-100'>
                                @php Table::create($table2); @endphp
                                </div>
                            </div>
                            <br><br>
                            
                            <div class='row table-caption'><b>Liabilitas/Liabilities</b></div>
                            <div class='row' style='font-weight: bold;font-size:10px;'>
                                <div class='w-100'>
                                @php Table::create($table4); @endphp
                                </div>
                            </div>
                            <br><br>
                            
                            <div class='row table-caption'><b>Proteksi/Protection</b></div>
                            <div class='row' style='font-weight: bold;font-size:10px;'>
                                <div class='w-100'>
                                @php Table::create($table3); @endphp
                                </div>
                            </div>
                            <hr/>
                            <!--<div class='row'>
                                <div class='col-12' style='font-size:8px;font-style:italic;'>
                                    Report Generated at	System, <br>Generated_Date {{ date('d/m/Y h:i:s') }}
                                </div>
                            </div>-->
                            <div class='row'>
                                <div class='col-12' style='font-size:10px;font-style:italic;'>
                                    <p>Disclaimer: Laporan bulanan nasabah terkait investment value yang diterbitkan oleh PT Bank Syariah Indonesia Tbk hanya memuat laporan investasi Reksa Dana dengan harga Nett Asset Value (NAV) yang tercatat pada sistem internal PT Bank Syariah Indonesia Tbk. NAV yang tercantum merupakan Harga NAV pada hari sebelum Laporan Keuangan Ini diterbitkan. Laporan bulanan terkait investment value belum termasuk sukuk pemerintah serta banccassurance.</p>
                                    <p>The customer's monthly report regarding investment value issued by PT Bank Syariah Indonesia Tbk only contains a report on Mutual Fund investment at the Nett Asset Value (NAV) price recorded in the internal system of PT Bank Syariah Indonesia Tbk. The NAV listed is the NAV Price on the day before this Financial Statement is issued. Monthly reports related to investment value do not include government sukuk and banccassurance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ url('report/inv-balance?invid='.$invid.'&mod=pdf') }}" target="_blank" class='btn btn-primary'>Export PDF</a>
                    {{-- <button type='button' class='btn btn-primary'>Export Excel</button> --}}
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
<style>
    h2 {color:rgb(192,143,0);padding-bottom:5px;}
    table {font-size:11px;font-weight:normal;border:none;}
    table th {font-size:12px;font-weight:bold;background-color:lightgray;}
    table td {border-style:none !important;}
    table tfoot tr {font-size:12px;height:50px;color:white;background-color:#56585B;}
    table tfoot td{font-weight:bold;}
    table tfoot td small{font-weight:bold;}
    .row-group td{background-color:#F0F0F0;}
    .table-caption {font-size:14px;color:rgb(0,162,156);}
</style>
@endsection

@section('plugin_js')
@endsection
@section('script_js')
@endsection