{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <style>
        .chartWithOverlay {
            position: relative;
            width: 700px;
        }
        .overlay {
            width: 200px;
            height: 200px;
            position: absolute;
            bottom: 40px;  /* chartArea top  */
            left: 180px; /* chartArea left */
            opacity: 0.1;
        }
    </style>
@stop

@section('content')
    @php
        //use \koolreport\widgets\koolphp\Table;
        use \koolreport\widgets\google\ColumnChart;
        //use Koolreport\widgets\google\LineChart;
        //use \koolreport\widgets\google\AreaChart;
        //use \koolreport\widgets\google\ScatterChart;
        //use \koolreport\widgets\google\DonutChart;
        //use \koolreport\excel\ExcelExportable;
        //use \koolreport\export\Exportable;
        use \koolreport\widgets\google\DonutChart;
        //require_once "koolreport\autoload.php";
    @endphp

    <h2>Chart</h2>

    <div class="chartWithOverlay">
        <div id="line-chart" style="width: 700px; height: 500px;">
        @php
            DonutChart::create(array(
                "title"=>"Sale Of Category",
                "dataSource"=>$data,
                /*"dataSource"=> [
                        ['AccNo'=>'aa','Amount'=>1000],
                        ['AccNo'=>'bb','Amount'=>2000],
                    ], */
                "columns"=>array(
                    "AccNo",
                    "Amount"=>array(
                        "type"=>"number",
                        "prefix"=>"$",
                    )
                )
            )); 
        @endphp
        </div>
        <div class="overlay">
            <div style="font-family:'Arial Black'; font-size: 40px;">(c)Praisindo</div>
            {{-- <div style="color: #b44; font-family:'Arial Black'; font-size: 32px; letter-spacing: .21em; margin-top:50px; margin-left:5px;">zombie</div> --}}
            {{-- <div style="color: #444; font-family:'Arial Black'; font-size: 32px; letter-spacing: .15em; margin-top:15px; margin-left:5px;">attacks</div> --}}
        </div>
    </div>

    <button id="exportPDF"type="button" class="btn btn-secondary">Print PDF</button>
   
@stop

                    
@section('js')
    
    <script>

        
    </script>
    
@stop

