{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    <form id='formData' action='transsave' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'SO') }}   
    {{ Form::hidden('id', $data->id??'') }}   
    {{ Form::hidden('cmd', '') }}   
    <?php Form::setFormTemplate('layout-normal'); ?>
    <?php dump($data->toArray());?>
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="ribbon-wrapper ribbon-lg">
                <div class="ribbon bg-danger text-white text-lg">{{$data->Status??'DRAFT'}}</div>
            </div>
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Sale Order
                <div class="h6 text-muted float-right"></div>
            </div>
            <div class="card-body">
                {{-- new design --}}
                <div class="form-group">
                    <label for="input">Customer</label>
                    {!! Form::_mselect('AccCode', $select->selCustomer, $data->AccCode) !!}
                </div>
                <div class="form-group">
                    <label for="inputAddress">Delivery to</label>
                    <select type="text" class="form-control form-control-sm select2-address" id="DeliveryCode" name="DeliveryCode" placeholder="input address"></select>
                    {{-- {!! Form::_mselect('Address', $select->selAddress, $data->Address) !!} --}}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Transaction #</label>
                        {!! Form::_textbox('TransNo', $data->TransNo??'', ['placeholder'=>'Transaction #', 'readonly'=>true]) !!}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date', date('d/m/Y')) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Sales Quotation #</label>
                        {!! Form::_textbox('ReffNo', $data->ReffNo??'') !!}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Payment</label>
                        {!! Form::_mselect('Payment', $select->selPayment, $data->Payment) !!}
                    </div>
                     <div class="form-group col-md-4">
                        <!-- blank -->                    
                    </div>
                     <div class="form-group col-md-4">
                        <label for="inputEmail4">Salesman</label>
                        {!! Form::_mselect('Salesman', $select->selSalesman, $data->Salesman) !!}
                    </div>
                    
                </div>
                <hr/>



                {{-- old design --}}
                <!--
                <div class='row  xxjustify-content-md-center'>    
                    {{-- leff side --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-left">
                        <input name='name' value=''/>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                Dropdown button
                            </button>
                            <div class="dropdown-menu">
                                <div class='row'>
                                    <div class='col'><a class="dropdown-item" href="#">Action</a></div><div class='col'>text1</div>
                                </div>
                                <div class='row'>
                                    <div class='col'><a class="dropdown-item" href="#">Another action</a></div><div class='col'>text2</div>
                                </div>
                                <div class='row'>
                                    <div class='col'><a class="dropdown-item" href="#">Something else here</a></div><div class='col'>text3</div>
                                </div>
                            </div>
                        </div>

                        {{-- {{ Form::textwlookup('AccCode', 'Customer', 'modal-customer', $data->AccCode??'' ) }} --}}
                        <x-Textwselect name='AccCode' label='Customer' api='select/customer' value='{{$data->AccCode ?? ''}}' />
                        {{ Form::select('Salesman', 'Salesman', $mSalesman, $data->Salesman ?? '') }}
                        {{ Form::select('DeliveryCode', 'Delivery to', $mAddr, $data->DeliveryCode ?? '') }}
                        {{ Form::text('Deliveryto', '', $data->Deliveryto ?? '') }}
                        {{ Form::select('Payment', 'Payment', $mPayType, $data->Payment ?? '') }}
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-3"> </div>
                    <div class="col-sm-5 col-md-5 col-lg-3 float-right">
                        {{ Form::text('TransNo', 'Transaction #', $data->TransNo??'', ['placeholder'=>'Transaction #', 'readonly'=>true]) }}
                        {{ Form::date('TransDate', 'Date', date('d/m/Y')) }}
                        {{ Form::text('ReffNo', 'Sale Quotation #', $data->ReffNo??'') }}
                        {{-- {{ Form::select('Warehouse', 'Warehouse', $mWarehouse, $data->Warehouse) }} --}}
                    </div>
                </div>
                -->
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   
                <button id='cmAddrow' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>
                {{-- <button id='cmDelrow' class='btn btn-info' type='button'>Del selected line</button> --}}

                <div class='row px-3 float-right'>
                    <div class='col'>
                        <?php Form::setFormTemplate('layout-inline'); ?>
                        {{ Form::number('SubTotal', 'Sub total', 0, ['disabled'=>true]) }}
                        {{ Form::number('DiscAmountH', 'Discount', $data->DiscAmountH??0, ['disabled'=>true]) }}
                        {{ Form::number('TaxAmount', 'Tax', $data->TaxAmount??0, ['disabled'=>true]) }}
                        {{ Form::number('Total', 'Grand Total', $data->Total??0, ['disabled'=>true]) }}
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   @include('modal.modal-customer') 
   @include('modal.modal-product') 
@stop
                    
@section('js')
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_grid.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('resource/js/lookup/lookup.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-customer.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-product.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-invoice-unpaid.js') }}" type="text/javascript"></script> --}}
    
    <script>
        var selRowIdx = null;
        var selRow = null;
        var selModal = null;
        var lookup_target_button = null;
        
        //load data
        var mydata = {!! json_encode($griddata) !!}

        //init select box
        var selData = [
            ['AccCode', 'Customer'],
            ['Payment', 'Payment'],
            ['Salesman', 'Salesman'],
        ];
        for(let dt of selData) {
            $('select#'+dt[0]).select2({
                placeholder: `Choose a ${dt[1]}`,
                templateResult: function(data) {
                    //console.log(data)
                    var result = jQuery(
                    '<div class="row">' +
                        '<div class="col-md-3">' + data.id + '</div>' +
                        '<div class="col-md-9">' + data.text + '</div>' +
                    '</div>'
                    );
                    return result;
                },
                xxxtemplateSelection: function(dat) {
                    //console.log(dat)
                    //var r=[dat.id, dat.text];
                    var result = $(
                        '<div class="row">' +
                            '<div class="col-md-3">' + data.id + '</div>' +
                            '<div class="col-md-9">' + 'data.text' + '</div>' +
                        '</div>'
                    );
                    //return result;
                },
            });
        }
        $('select#Address').select2({
             //data: data
        });


        //init ag-grid
        var colModel = [
            { field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 150, 
                cellRenderer:function(row)  {
                    return setLookupButton(row, 'modal-product')
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },
            { field: "ProductName", headerName: 'Product Name', width: 250 },
            { field: "UOM", headerName: 'Unit', width: 80 },
            { field: "StockQty", headerName: 'Stock Qty', width: 120 },
            { field: "Qty", headerName: 'Order Qty', editable:true, edittype:'text', width: 120 },
            { field: "SentQty", headerName: 'Sent Qty', width: 120 },
            { field: "Price", headerName: 'Price', editable:true, edittype:'text', width: 120 },
            { headerName: 'Amount', valueGetter: '"Rp. "+data.Qty*data.Price' },
        ];
        
        var gridOptions = {
            columnDefs: setColModel(colModel),
            rowData: mydata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            rowSelection: 'single',
            onRowEditingStarted: (event) => {
                console.log('never called - not doing row editing');
            },
            onRowEditingStopped: (event) => {
                console.log('never called - not doing row editing');
            },
            onCellEditingStarted: (event) => {
                console.log('cellEditingStarted');
            },
            onCellEditingStopped: (event) => {
                console.log('cellEditingStopped');
            },
            onGridReady: function (params) {
                sequenceId = 1;
                allOfTheData = [];
                for (var i = 0; i < 4; i++) {
                    //allOfTheData.push(createRowData(sequenceId++));
                    allOfTheData.push(mydata[i]);
                }
            },
            components: {
                //btnCellRenderer: BtnCellRenderer,
                //numericCellEditor: NumericEditor,
                //moodCellRenderer: MoodRenderer,
                //moodEditor: MoodEditor,
            },
        }

        $(document).ready(function() {
            //init page
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            //$('select.select2').select2({ theme: "bootstrap" });
            // https://stackoverflow.com/questions/35540576/ajax-filled-select2-not-clickable
            /*$('#AccCodeX').select2({
                ajax: {
                    url: 'http://localhost/lav7_PikeAdmin/select/customer',
                    dataType: 'json',
                },
                templateResult: function(dat) {
                    //var r = dat.toString().split('|');
                    var r=[dat.id, dat.text];
                    var $result = $(
                        '<div class="row">' +
                            '<div class="col-md-3">' + r[0] + '</div>' +
                            '<div class="col-md-1">-</div>' +
                            '<div class="col-md-8">' + r[1] + '</div>' +
                        '</div>'
                    );
                    return $result;
                },
                templateSelection: function(dat) {
                    var r=[dat.id, dat.text];
                    var result = $(
                        '<div class="row">' +
                            '<div class="col-md-3">' + r[0] + '</div>' +
                            '<div class="col-md-9">' + r[1] + '</div>' +
                        '</div>'
                    );
                    return result;
                },
                matcher: function(term, text) {
                    // TODO: search nya mash belum jalamconsole.log([term, text]);
                    //return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                    if (text.toUpperCase().indexOf(term.toUpperCase())==0) return true;
                    return false;
                }
            }); */
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
            calcTotal();
            refreshDelivery();
            $('#DeliveryCode').trigger('change');

            // EVENT
            //grid - add new line
            $("button#cmAddrow").click(function(e){
                var newLine = { ProductCode:'', ProductName:'', Qty:0, Price:0 }
                mydata.push(newLine)
                gridOptions.api.setRowData(mydata);
            });
            //grid - delete row 
            $(document).on('click','button.cmDelrow',function(e){
                const selRow = gridOptions.api.getSelectedRows();
                gridOptions.rowData.splice(selRow, 1);
                gridOptions.api.setRowData(gridOptions.rowData);
            });
            
            // select2 customer change
            $('#AccCode').on('select2:select', function (e) {
                var sel = e.params.data.id;
                //alert(sel)
                refreshDelivery(sel);
            });
            //delivery select2 change
            $('#DeliveryCode').on('select2:select', function (e) {
                var sel = e.params.data;
                //alert('sel.text')
                $('#Deliveryto').val(sel.text)
            });

            //save data
            $("button#cmSave2").click(function(e){
                e.preventDefault();
                alert('save');
                //submit grid data to variable
                const rowData = [];
                gridOptions.api.forEachNode(function (node) {
                    rowData.push(node.data);
                });
                $("input[name='detail']").val(JSON.stringify(rowData));
                
                var formdata=$('form').serialize();
                $('#formData').submit();
                
            });
            //print data
            $("button#cmPrint").click(function(e){
                e.preventDefault();
                alert('print');
            });
            // approve
            $("button#cmApprove").click(async function(e){
                e.preventDefault();
                //alert('Approve');
                $("input[name='cmd']").val('confirm')
                $('#formData').submit();
            });
            // create invoice
            $("button#cmCreateInv").click(async function(e){
                e.preventDefault();
                var OrderNo = $('#TransNo').val();
                //alert('create invoice '+OrderNo);
                let resp = await fetch(' {{ url('order/createinvoice') }}/'+OrderNo );
                //console.log(resp);
                if (resp.statusText=='OK') {
                    let dat = await resp.json();
                    //console.log(dat);
                } else {
                    console.log(['ERROR',resp.status,resp.statusText])
                }

            });

            $('.modal').on('show.bs.modal', function (e) {
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
                //console.log(lookup_target_button)
            })
        });

        async function loadData() {
            //let resp = await fetch(' {{ url('select/customer') }} ');
            let resp = await fetch('select/customer');
            if (resp.statusText=='OK') {
                let dat = await resp.json();
                let data = await data.map(r => { return { id: r.AccCode, text: r.AccName } });
                console.log(data)
                return data;
            }
        }

        async function afterModalClose(sel) {
            //console.log(sel)
            //console.log(lookup_target_button)
            if (sel.selModal == 'modal-customer') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
                refreshDelivery(sel);
            }
            if (sel.selModal == 'modal-product') {
                var selRow = gridOptions.api.getSelectedRows();
                var nodes = gridOptions.api.getSelectedNodes();
                selRowIdx = nodes[0].rowIndex;
                mydata[selRowIdx].ProductCode = sel.selRow[0]
                mydata[selRowIdx].ProductName = sel.selRow[1]
                let resp = await fetch('{{ url('getbalanceproduct') }}/'+ sel.selRow[0]);
                if (resp.statusText=='OK') {
                    let dat = await resp.json();
                    mydata[selRowIdx].StockQty = dat.Qty;
                } else {
                    mydata[selRowIdx].StockQty = 0;
                }
                gridOptions.api.setRowData(mydata);
                calcTotal();
            }
        };

        async function refreshDelivery(sel) {
            let resp = await fetch("{{ url('api/select/address') }}?AccCode="+sel);
            if (resp.statusText=='OK') {
                let data = await resp.json();
                let dat = await data.results.map(r => { return {id:r.id, text:r.text} }) 
                $('#DeliveryCode').empty().select2({data: dat});
            }
        }

        /*async function refreshCustomer() {
            let resp = await fetch(' {{ url('select/customer') }} ');
            if (resp.statusText=='OK') {
                let dat = await resp.json();
                //let dat = await data.map(r => {return {id:r.AccCode, text:r.AccName} }) 
                console.log(dat)
                $('#AccCodeX').empty().select2({data: dat});
            }
        }*/

        function calcTotal() {
            var subtot = 0;
            gridOptions.api.forEachNode(function (node) {
                subtot += parseInt(node.data.Qty) * parseInt(node.data.Price)
            });
            var total = subtot - parseInt($('input#DiscAmountH').val()) + parseInt($('input#TaxAmount').val())
            $('input#SubTotal').val(subtot)
            $('input#Total').val(total)
        }
    </script>
    
@stop

