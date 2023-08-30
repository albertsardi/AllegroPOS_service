<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Allegro - ERP System Administrator</title>
<meta name="description" content="Allegro - ERP System Administrator">
<meta name="author" content="Albert - (c)ASAfoodenesia">

<html lang="en" >
<head>
<!-- Favicon -->
<link rel="shortcut icon" href="assets/images/favicon.ico">
<!-- BEGIN CSS for this page -->
{{ HTML::style("assets/css/bootstrap.min.css") }}
{{ HTML::style("assets/css/fontawesome/font-awesome.min.css") }}
{{ HTML::style("assets/metro/css/metro-all.css") }}
{{ HTML::style("assets/css/style.css") }}
{{ HTML::style("assets/css/datepicker.css") }}
<!-- {{ HTML::style("assets/css/style2.css") }} -->
<style>
    .border {border-style: solid;}
    .plabel {width:120px; vertical-align:middle; margin:3px 8px 3px 8px; }
    .cell {margin:2px 2px 2px 2px; float:left;display: inline-block}
    input.w5 {width:50px;}
    input.w10 {width:100px;}
    input.w20 {width:200px;}
    input.w30 {width:300px;}
    input.w40 {width:400px;}
    .gridLookup, .gridLookup th, .gridLookup td {border: 1px solid gray;padding:5px;}
    .gridLookup {width:100%;}
    /* * {font-size:12px;} */
    /* input{font-size:12px;background-color:red;} */
    div.grow {
        border: 0px solid red;
        width:1300px;
        display: inline-block;
    }
    div.grid-container {
        overflow: hidden;
    }
</style>
<!-- END CSS for this page -->
</head>

<body>

<div id="main">

	<!-- top bar navigation -->
    @extends('topmenu')
    <!-- End Navigation -->

    <!-- Left Sidebar -->
    @extends('menu')
    <!-- End Sidebar -->


    <div class="content-page">
        <div class='result'>...</div>

        <!-- Start content -->
        <div class="content">

            <div class="container pull-left">

                <div class="breadcrumb-holder" style="height:70px; ">
                    <h1 class="main-title float-left">Forms Table</h1>
                    <span class="float-right">Trans / Form</span>
                </div>

                <form action="ajaxPost" method="post">
                    {{ Form::hidden('formtype',$jr) }}
			        <!-- panel button -->
			        <!-- @include('buttonpanel-trans',['jr'=>$jr]) -->
                    <div class="card card-body mb-2">
	                    <div class="form-group row" style="height:20px;">
                            <div class="col-sm-4">
                                <button id="cmSave" type="button" class="btn btn-primary">Save</button>
                                <button id="cmPrint" type="button" class="btn btn-primary">Print</button>
                            </div>
                            <div class="col-sm-8">
                                <div id='info' ></div>
                            </div>
                        </div>
                    </div>			

                    <div class="row no-gap grid-container" style="width:1000px">
                        <!-- Left Panel -->
                        <div class="stub float-left"  style="width:40%" >
                            <div class="card" >
                                <div class="card-content p-4" >
                                    <div class="row mb-2">
                                        <label class="plabel border">Transaction #</label>
                                        <div class="xcell-sm-9">
                                            <input type="text" name="TransNo">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="plabel">Date</label>
                                        <div class="xcell-sm-10 clear">
                                            <input type='text' name="TransDate" data-toggle='datepicker' class='d-inline' style="width:170px;">
                                            <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label class="plabel">TaxNo</label>
                                        <div class="xcell-sm-10">
                                            <input type="text" name="TaxNo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right Panel -->
                        <div class="stub float-left" style="width:60%">
                            <div class="card">
                                <div class="card-content p-4" >
                                    <!-- <div class="row mb-2">
                                        <label class="plabel">test Order To</label>
                                        <div class="xxcell-sm-10" style="display:flex;" >
                                        <input type="text" data-role="input" data-search-button="true" data-search-button-click="custom" data-on-search-button-click="showLookup('customer','AccName')">
                                            <label id="AccName" class="plabel">XXXXXX</label>
                                        </div>
                                    </div> -->
                                    <div class="row mb-2">
                                        <label class="plabel">Order To</label>
                                        <div class="xxcell-sm-10" style="display:flex;" >
                                            <input type="text" name="AccCode" class="W20">
                                            <button id="cm" type="button" class="button secondary cmLookup small" lookup-modal="lookup-supplier" lookup-target="AccCode" >...</button>
                                            <label id="AccName" class="plabel">XXXXXX</label>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row mb-2">
                                        <label class="plabel">Warehouse</label>
                                        <div class="xxcell-sm-10" style="display:flex;">
                                            <input type="text">
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GRID -->
                    <div class="card mb-2">
                        <div class="card-header">
                            <i class="fa fa-check-square-o"></i> Order Detail
                        </div>
                        <div class="card-content p-2" >
                            <?= makeeditgrid();?>
                        </div>
                        <div class="card-footer">
                            <div class='row'></div>
                        </div>
                    </div>

                    <!-- Footer Baru -->
                    <div class="card mb-2">
                        <div class="card-content p-2"  style="white-space:nowrap;display:inline-block;">
                            <div class="row ">
                                <div class="cell-3 float-left">
                                    <label class="plabel" >SubTotal</label>
                                    <div class="xcell-sm-10">
                                        <input type="numeric" name="SubTotal" style="readonly;">
                                    </div>
                                </div>
                                <div class="cell-3 float-left">
                                    <label class="plabel" >Disc</label>
                                    <div class="xcell-sm-10">
                                        <input type="text" name="DiscAmountH" style="readonly">
                                    </div>
                                </div>
                                <div class="cell-3">
                                    <label class="plabel" >Tax</label>
                                    <div class="xcell-sm-10">
                                        <input type="text" name="Tax">
                                    </div>
                                </div>
                                <div class="cell-3">
                                    <label class="plabel" >Total</label>
                                    <div class="xcell-sm-10">
                                        <input type="text" name="Total">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Footer -->
                    <div class="card mb-2 stub float-left" style="width:1000px;">
                        <div class="card-content p-2" >
                            <div class='row'>
                                <table>
                                <tr>
                                <td width='80%'></td>
                                <td width='200px'><label class="plabel">SubTotal</label></td>
                                <td width='200px'><input type="text" name="SubTotal"></td>
                                </tr>
                                </table>


                                <div class='cell-5 offset-7 place-right'>
                                    <div class="row mb-2">
                                        <label class="plabel">SubTotal</label>
                                        <div class="xcell-sm-10">
                                            <input type="text" name="">
                                        </div>
                                    </div>
                                    {!! form_text('SubTotal', 'Sub Total', "class='num' style='width:200px' readonly"); !!}
                                    {!! form_number('DiscAmountH', 'Disc', "class='num' style='width:200px' readonly"); !!}
                                    {!! form_number('Tax', 'Tax', "class='num' style='width:200px' readonly"); !!}
                                    {!! form_number('Total', 'Grand Total', "class='num' style='width:200px' readonly"); !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <!-- end Form -->

                <div class='row'>
                    test test test test test test 
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        Created 2018-09-03 00:00:00 by admin                
                    </div>
                </div>

            </div>

        </div>
        <!-- END content -->

    </div>
    <!-- END content-page -->

    <!-- Begin Footer -->
    @extends('footer')
    <!-- End Footer -->

</div>
<!-- END main -->

</body>
</html>


<!-- Modal -->
<!-- insert modal function HERE -->
<script>
{!! $jsmodal !!}
</script>
<!-- End Modal -->


    <!-- BEGIN Java Script for this page -->
    {{ HTML::script("assets/js/jquery.min.js") }}
    {{ HTML::script("assets/js/pikeadmin.js") }}
    {{ HTML::script("assets/js/bootbox.min.js") }}
    {{ HTML::script("assets/js/fastclick.js") }}
    {{ HTML::script("assets/js/datepicker.js") }}

    
    {{ HTML::script("assets/js/helper_metroform.js") }}
    <!-- {{ HTML::script("assets/js/helper_metroeditgrid.js") }} -->
<!-- <script src="assets/js/jquery.min.js"></script> -->
<!-- <script src="assets/js/modernizr.min.js"></script> -->
<!-- <script src="assets/js/moment.min.js"></script> -->
<!-- <script src="assets/js/popper.min.js"></script> -->
<!-- <script src="assets/js/detect.js"></script> -->
<!-- <script src="assets/js/fastclick.js"></script> -->
<!-- <script src="assets/js/jquery.blockUI.js"></script> -->
<!-- <script src="assets/js/jquery.nicescroll.js"></script> -->
<!-- <script src="assets/metro/js/metro.min.js"></script> -->
<!-- <script src="assets/js/numeral.min.js"></script> -->
<!-- <script src="assets/js/AutoNumeric.js"></script>
<script>
    $("input[type='num']").autoNumeric('init');
</script> -->
<!-- <script src="assets/js/easy-number-separator.js"></script> -->
<!-- <script src="assets/js/jquery.number.min.js"></script> -->
<!-- <script src="assets/js/datepicker.js"></script> -->
<!--use bootbox.js -->
<!-- <script src="assets/js/bootbox.min.js"></script> -->
<!-- popper js -->
<!-- <script src="assets/js/popper.min.js"></script> -->
<!-- App js -->
<!-- <script src="assets/js/pikeadmin.js"></script> -->
<script >
    function FormLoad2(TransNo) {
         var dataSource= '_loaddatarow.php?jr=transhead&id='+TransNo ;
        dataSource= "{{ action('DataController@loadTrans', 'PI.1800004') }}";
        //dataSource= "{{ action('DataController@loadTrans', '[id]') }}";
        //dataSource= dataSource.replace("[id]", TransNo);

        console.log(dataSource);
        $.getJSON(dataSource, function(data, status) {
            console.log(data);
            for (var nm in data) {
                //var result='';
                // obj.hasOwnProperty() is used to filter out properties from the object's prototype chain
                if (data.hasOwnProperty(nm)) {
                    //result += nm;
                    $("input[name='"+nm+"']").val(data[nm]);
                    $("input[name='"+nm+"']").css('background-color','red');
                    //$("input").val('xyz');
                    //alert(result);
                }
            }
        })
        .error(function(jqXHR, textStatus, errorThrown) {
            console.log("error " + textStatus);
            console.log("incoming Text " + jqXHR.responseText);
        });
    }
    $(document).ready(function() {
        var id='<?= $code;?>';
        FormLoad2(id);
        //GridLoad(id);
        calcAll();
        $("input[name='TransNo']").val( 'xyz' );

        //Form Event
        $('input[type=checkbox]').change(function() {
            //var name=$(this).attr('name').substr(3);
            var name=$(this).attr('target');
            var v=0;
            if($(this).prop('checked')) v=1;
            $('input[name='+name+']').val( v );
        })
    })
    
</script>


<!-- Form js -->
<script>
    //form format
    function cnum(v) {
        v = parseInt( v );
        if(isNaN(v)) v=0;
        return v;
    }
</script>


<script>
    //Form js
    $(document).ready(function() {
        $('input').change(function() {
            var nm=$(this).attr('name');
            if(nm=='AccCode') {
                var id= $(this).val();
                var dataSource= '_loaddatarow.php?jr=supplier&id='+id ;
                $.getJSON(dataSource, function(data, status) {
                    $('label#AccName').text(data.AccName);
                })
                .error(function(jqXHR, textStatus, errorThrown) {
                    console.log("error " + textStatus);
                    console.log("incoming Text " + jqXHR.responseText);
                });
            }
        });
    });

    // Edit Grid js
    $(document).ready(function() {
        //cell event
        $('input.cell').change(function() {
            var row=target=$(this).parent().attr('line');
            var col= $(this).attr('name');
            switch(col) {
                case 'grid-ProductCode[]':
                    //alert(col);
                    var id=cell(row, 'ProductCode');
                    var dataSource= '_loaddatarow.php?jr=product&id='+id ;
                    $.getJSON(dataSource, function(data, status) {
                        setcell(row, 'ProductName', data.Name);
                    })
                    .error(function(jqXHR, textStatus, errorThrown) {
                        console.log("error " + textStatus);
                        console.log("incoming Text " + jqXHR.responseText);
                    });
                    break;
                case 'grid-Qty[]':
                case 'grid-Price[]':
                    calcAll();
                    break;
            }
        });
    });

    function calcAll() {
        var tot = 0; var line=0;
        $.each( $("div.grow"), function() {
        	if($(this).is(':visible')) {
                var qty = cnum(cell(line, 'Qty'));
                var price = cnum(cell(line, 'Price'));
                var amount = qty*price;
                //setcell(line, 'Amount', fnum(amount));
                setcell(line, 'Amount', amount);
			    tot=tot+amount;
            }
            line++;
        });
        //tot=fnum(tot);
         $("input[name='SubTotal']").val(tot);
        // alert( $("input[name='SubTotal']").val() );
        //alert('xx');
    }

    function showLookup(lookupType, target) {
        alert(lookupType+' - '+target);
    }
</script>
<!-- END Java Script for this page -->

</body>
</html>

<!-- PHP Function -->
<?php
    function makeeditgrid()
    {
        // $ctr=cellLookup('ProductCode', '#modal-product', "placeholder='Product#' ").
        //     cell('ProductName', 'text', "placeholder='Product Name' disabled").
        //     cell('Qty', 'num', "placeholder='Quantity'").
        //     cell('Price', 'num', "placeholder='Price'").
        //     cell('Amount', 'num', "placeholder='Amount' disabled");
        // return editGrid('grid', $ctr);

        $ctr= "".
        "<input type='text' name='grid-ProductCode[]' data-role='cell' placeholder='Product#' class='cell w20'>".
        //cell('ProductCode', 'text').
        "<button id='cm' type='button' class='button secondary outline cell cmGridLookup' lookup-modal='lookup-product' lookup-target='grid-ProductCode[]'>...</button>
        <input type='text' name='grid-ProductName[]' data-role='cell' placeholder='Product Name' class='cell w40'>
        <input type='text' name='grid-Qty[]' data-role='cell' placeholder='Quantity' class='cell w10 num'>
        <input type='text' name='grid-Price[]' data-role='cell' placeholder='Price' class='cell w20 num'>
        <input type='text' name='grid-Amount[]' data-role='cell' disabled class='cell w20 num'>
        ";
        return editGrid('grid', $ctr);
    }

?>
<!-- End PHP Function -->
