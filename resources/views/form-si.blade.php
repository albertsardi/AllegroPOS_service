{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    {{-- @php define('NOW', date('m/d/Y'));@endphp --}}
    @php define('NOW', date('Y-m-d'));@endphp
    <form id='formData' action='transsave' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'SI') }}   
    {{ Form::hidden('id', $data->id ?? null) }}   
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
        <div class="ribbon-wrapper ribbon">
                <div class="ribbon bg-danger text-white text-lg">{{$data->Status??'DRAFT'}}</div>
            </div>
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Sale Invoice
                <div class="h6 text-muted float-right"></div>
            </div>
            <div class="card-body">
                {{-- new design --}}
                @php
                    $temp = '<label for="input{{name}}">{{label}}</label>
                            {{input}}';
                    Form::setFormTemplate($temp);
                @endphp
                <div class="form-group">
                    {{-- {{ Form::select('AccNo', 'Customer', $mCustomer, $data->AccNo ?? '' ) }} --}}
                    <label for="input">Customer</label>
                    {!! Form::_mselect('AccCode', $select->selCustomer, $data->AccCode??'') !!}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('TransNo', 'Transaction #', $data->TransNo ?? '' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date', $data->TransDate ?? '') }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::text('DueDate', 'Due Date', $data->DueDate ?? '', ['readonly'=>true]) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{-- {{ Form::select('PaymentType', 'Payment Type', $mPayType, $data->PaymentType??'' ) }} --}}
                        <label for="input">Payment Type</label>
                        {!! Form::_mselect('PaymentType', $select->selPayment, $data->PaymentType??'') !!}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Salesman</label>
                        {!! Form::_mselect('Salesman', $select->selSalesman, $data->selSalesman??'') !!}
                        {{-- {{ Form2::select('Salesman', 'Salesman2', $select->selSalesman??[] ) }} --}}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Warehouse</label>
                        {!! Form::_mselect('Warehouse', $select->selWarehouse, $data->selWarehouse??'') !!}
                        {{-- {{ Form::select('Warehouse', 'Delivery to / Warehouse', $mWarehouse, $data->Warehouse ?? '' ) }} --}}
                    </div>
                </div>
                <hr/>



                <!--
                desaign lama
                <div class='row  xxjustify-content-md-center'>    
                    {{-- leff side --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-left">
                        {{ Form::text('TransNo', 'Transaction #', $data->TransNo??'', ['placeholder'=>'Transaction #', 'readonly'=>true]) }}
                        {{ Form::date('TransDate', 'Date', $data->TransDate??NOW ) }}
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-3"> </div>
                    {{-- righsideside --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-right">
                        {{ Form::textwlookup('OrderNo', 'Order #', 'modal-salesorder', $data->OrderNo??'') }}
                        <div class='form-group form-row'>
						    <label for='inputOrderNo' class='col-sm-4 col-form-label px-0'>From DO #</label>
						    <div class='col-sm-8'>
							    <select name="OrderNo[]" id="OrderNo" class="form-control form-control-sm select2"  multiple="multiple">
                                </select>
						    </div>
					    </div>
                        {{-- {{ Form::select('PaymentType', 'Payment Type', $mPayType, $data->PaymentType??'' ) }} --}}
                        {{-- {{ Form::text('DueDate', 'Due Date', $data->DueDate??NOW, ['readonly'=>true]) }} --}}
                        {{-- {{ Form::select('Salesman', 'Salesman', $mSalesman, $data->Salesman??'' ) }} --}}
                    </div>
                </div>
                -->
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   

                @php Form::setFormTemplate('layout-inline');@endphp
                <div class='row'>
                    <div class='col'></div>
                    <div class='col align-self-end text-right'>
                        {{ Form::number('SubTotal', 'Sub total', 0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                        {{ Form::number('DiscAmountH', 'Discount', $data->DiscAmountH??0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                        {{ Form::number('TaxAmount', 'Tax', $data->TaxAmount??0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                        {{ Form::number('Total', 'Grand Total', $data->Total??0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                        <hr/>
                        @if(isset($data->TransNo) && $data->TransNo!='')
                            <div class='form-group form-row my-1'>
                                <label for='inputFirstPayment' class='col-sm-4 col-form-label'>Uang Muka</label>
                                <div class='col-sm-8'>
                                    <div class='form-row'>
                                        <div class='input-group'>
                                            {!! Form::_numericbox('FirstPayment', $data->FirstPaymentAmount??0, ['readonly'=>true]) !!}
                                            <div class='input-group-prepend'>
                                                <button id='FirstPayment-lookup' type='button' data-toggle='modal' data-target='#modal-addpayment' class='btn btn-outline-secondary btn-sm btnlookup'><i class='fa fa-search'></i></button>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        @else
                            {{ Form::number('FirstPayment', 'Uang Muka', $data->Total??0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                        @endif
                        {{ Form::number('UnpaidAmount', 'Unpaid Amount', 0, ['disabled'=>true, 'style'=>'width:90%;']) }}
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   @include('modal.modal-salesorder') 
   @include('modal.modal-addpayment') 
@stop
                    
@section('js')
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_mselect.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_grid.js') }}" type="text/javascript"></script>
    
    <script>
        var selRowIdx = null;
        var selRow = null;
        var selModal = null;
        var lookup_target_button = null;

        //load grid data
        var griddata = {!! json_encode($griddata) !!}

        //init ag-grid
        var colModel = [
            { field: "ProductCode", headerName: 'Product #', width: 150 },
            { field: "ProductName", headerName: 'Product Name', width: 270 },
            { field: "Qty", headerName: 'Qty', width: 80 },
            { field: "Price", headerName: 'Price', width: 120 },
            { field: "Amount", headerName: 'Amount', width: 120 },
            { field: "Memo", headerName: 'Memo', editable:true, edittype:'text', width: 150 },
        ];
        
        var gridOptions = {
            //columnDefs: colModel,
            columnDefs: setColModel(colModel),
            rowData: griddata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            rowSelection: 'single',
            onRowEditingStarted: (event) => {
                //console.log('never called - not doing row editing');
            },
            onRowEditingStopped: (event) => {
                //console.log('never called - not doing row editing');
            },
            onCellEditingStarted: (event) => {
                //console.log('cellEditingStarted');
            },
            onCellEditingStopped: (event) => {
                //console.log('cellEditingStopped');
            },
            onGridReady: function (params) {
                sequenceId = 1;
                allOfTheData = [];
                for (var i = 0; i < 4; i++) {
                    //allOfTheData.push(createRowData(sequenceId++));
                    allOfTheData.push(griddata[i]);
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

            //init select box
            var selData = [
                ['AccCode', 'Customer'],
                ['PaymentType', 'Payment'],
                ['Salesman', 'Salesman'],
                ['Warehouse', 'Warehouse'],
            ];
            for(let dt of selData) {
                var obj = $('select#'+dt[0]);
                init_mselect(obj, dt);
            }
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
            calcAll();

            // EVENT
             //Payment Type 
            $('#PaymentType').on('select2:select', function (e) {
                var id = e.params.data.id;
                var find = mPayType.find(row=>{
                    if( row.catid==id) return row 
                });
                let day = find.name2;
                var tdate = $('input#TransDate').val()
                var duedate = moment(tdate, 'YYYY-MM-DD')
                    .add(day,'days') 
                    .format('YYYY-MM-DD');
                $('#DueDate').val(duedate);
            });
            //add new line
            $("button#cmAddrow").click(function(e){
                var newLine = { ProductCode:'', ProductName:'', Qty:0, Price:0 }
                griddata.push(newLine)
                gridOptions.api.setRowData(griddata);
            });
            //delete row 
            $(document).on('click','button.cmDelrow',function(e){
                const selRow = gridOptions.api.getSelectedRows();
                gridOptions.rowData.splice(selRow, 1);
                gridOptions.api.setRowData(gridOptions.rowData);
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
            //save payment data
            $("button#cmPaySave").click(function(e){
                e.preventDefault();
                //alert('payment save');
                ajaxindicatorstart('Payment Save');
                var formdata=$('form').serialize();
                $('#formPaymentData').submit();
               ajaxindicatorstop(); 
            });
            
            $('#FirstPayment').change(function() {
                calcAll();
            });

            $('.modal').on('show.bs.modal', function (e) {
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
            })
        });

        function afterModalClose(sel) {
            if (sel.selModal == 'modal-salesorder') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[2]+','+sel.selRow[1]);
                refreshDO();
            }
        };

        async function refreshDO() {
            var SOno = $('input#OrderNo').val();
            try {
                let resp = await fetch(' {{ url('getdata/orderhead') }}?TransNo='+SOno);
                if (resp.statusText=='OK') {
                    let dat = await resp.json();
                    let opt = '';
                    //console.log(dat)
                    for(let dt of dat) opt += `<option value="${dt.DONo}">${dt.DONo}</option>`;
                    $('select#OrderNo').html(opt); 

                    //populate detail
                    resp = await fetch(' {{ url('getdata/transdetail') }}?OrderNo='+SOno);
                    if (resp.statusText=='OK') {
                        let dat = await resp.json();
                        for(let dt of dat ) {
                            griddata.push( { 
                                ProductCode: dt.ProductCode,
                                ProductName: dt.ProductName,
                                Memo: dt.Memo, 
                                Qty: dt.Qty,
                                Price: dt.Price,
                            }); 
                        }
                        gridOptions.api.setRowData(griddata);
                    }
                }
            } catch(error) {
                //console.log(error)
            }
        }

        function calcAll() {
            var tot = 0;
            for(let r of griddata) {
                r.Amount = parseInt(r.SentQty) * parseInt(r.Price);
                tot+= r.Amount;
            }
            gridOptions.api.setRowData(griddata);
            var gtot    = tot - $('#DiscAmountH').val() + $('#TaxAmount').val();
            var unpaid  = gtot - $('#FirstPayment').val(); 
            
            $('#SubTotal').val(tot);
            $('#Total').val(gtot);
            $('#UnpaidAmount').val(unpaid);
        }
    </script>
    
@stop

