{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    @php Form::setBindData($data);@endphp {{-- this is important --}}
    <form id='formData' action='transsave' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'PI') }}   
    {{ Form::hidden('id', $id) }}   
    {{-- <?php dump($data);?> --}}
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Purchase Invoice
            </div>
            <div class="card-body">
                {{-- new design --}}
                @php
                    $temp = '<label for="input{{name}}">{{label}}</label>
                            {{input}}';
                    Form::setFormTemplate($temp);
                @endphp
                <div class="form-group">
                    {{-- {{ Form::select('AccNo', 'Customer', $mSupplier, $data->AccNo ?? '' ) }} --}}
                    <label for="input">Supplier</label>
                    {!! Form::_mselect('AccCode', $select->selSupplier, $data->AccCode??'') !!}
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
                        {{-- {{ Form::select('Salesman', 'Salesman', $mSalesman, $data->Salesman??'' ) }} --}}
                        {{-- <label for="inputEmail4">Salesman</label> --}}
                        {{-- {!! Form::_mselect('Salesman', $select->selSalesman, $data->selSalesman??'') !!} --}}
                    </div>
                    <div class="form-group col-md-4">
                    <label for="inputEmail4">Delivery to / Warehouse</label>
                        {!! Form::_mselect('Warehouse', $select->selWarehouse, $data->selWarehouse??'') !!}
                        {{-- {{ Form::select('Warehouse', 'Delivery to / Warehouse', $mWarehouse, $data->Warehouse ?? '' ) }} --}}
                    </div>
                </div>
                <hr/>


                <!--
                model lama
                <div class='row  xxjustify-content-md-center'>    
                    {{-- leff side --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-left">
                        {{ Form::textwlookup('AccCode', 'Supplier', 'modal-supplier' ) }}
                        {{ Form::text('Currency', 'Currency') }}
                        {{-- {{ Form::select('Type', 'Type', $mType, $data->Type) }} --}}
                        {{-- {{ Form::select('HppBy', 'HPP', $mHpp, $data->HppBy) }} --}}
                        {{-- {{ Form::checkbox('ActiveProduct', 'Active Product') }} --}}
                        {{-- {{ Form::checkbox('StockProduct', 'Have Stock', $data->StockProduct) }} --}}
                        {{-- {{ Form::checkbox('canBuy', 'Product can buy', $data->canBuy) }} --}}
                        {{-- {{ Form::checkbox('canSell', 'Product can sell', $data->canSell) }} --}}
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-3"> </div>
                    {{-- righsideside --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-right">
                        {{ Form::text('TransNo', 'Transaction #', ['placeholder'=>'Transaction #', 'disabled'=>true]) }}
                        {{ Form::date('TransDate', 'Date') }}
                        {{-- {{ Form::select('ReffNo', 'Purchase Quotation #', $mPurchaseQuotation) }} --}}
                        {{-- {{ Form::text('TaxNo', 'Tax #', $data->TaxNo) }} --}}
                    </div>
                </div>
                -->
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   
                <button id='cmAddrow' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>
                {{-- <button id='cmDelrow' class='btn btn-info' type='button'>Del selected line</button> --}}

                <div class='row px-3 float-right'>
                    <div class='col'>
                        {{ Form::number('SubTotal', 'Sub total', ['disabled'=>true]) }}
                        {{ Form::number('DiscAmountH', 'Discount', ['disabled'=>true]) }}
                        {{ Form::number('TaxAmount', 'Tax', ['disabled'=>true]) }}
                        {{ Form::number('Total', 'Grand Total', ['disabled'=>true]) }}
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop



@section('modal')
   {{-- @include('modal.modal-supplier')  --}}
   @include('modal.modal-product') 
@stop
                    
@section('js')
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_mselect.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_grid.js') }}" type="text/javascript"></script>
    {{-- <script src="{{ asset('resource/js/lookup/lookup.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-customer.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-product.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('resource/js/lookup/lookup_modal-invoice-unpaid.js') }}" type="text/javascript"></script> --}}
    <script>
        function BtnCellRenderer() {}
        BtnCellRenderer.prototype.init = function(params) {
            this.params = params;
            this.eGui = document.createElement('button');
            this.eGui.innerHTML = 'Click me!';
            this.btnClickedHandler = this.btnClickedHandler.bind(this);
            this.eGui.addEventListener('click', this.btnClickedHandler);
        }
        BtnCellRenderer.prototype.getGui = function() {
            return this.eGui;
        }
        BtnCellRenderer.prototype.destroy = function() {
            this.eGui.removeEventListener('click', this.btnClickedHandler);
        }
        BtnCellRenderer.prototype.btnClickedHandler = function(event) {
            event.preventDefault();
            this.params.clicked(this.params.value);
        }           
    </script>
    
    <script>
        var selRowIdx = null;
        var selRow = null;
        var selModal = null;
        var lookup_target_button = null;

        //load data
        var griddata = {!! json_encode($griddata) !!}

        //init ag-grid
        var colModel = [
            { field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 270, 
                cellRenderer:function(row)  {
                    //return row.value+'  <button type="button" line='+row.rowIndex+'">...</button>';
                    //return row.value + "   <button class='cmLookup' type='button' data-toggle='modal' data-target='#modal-product'>...</button>";
                    return  "<button class='btn btn-sm btn-secondary cmLookup' type='button' data-idx=11 data-toggle='modal' data-target='#modal-product'><i class='fa fa-ellipsis-h'></i></button>"+
                            "   "+row.value;
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },
            { field: "ProductName", headerName: 'Product Name', width: 270 },
            { field: "Qty", headerName: 'Qty', editable:true, width: 100 },
            { field: "Price", headerName: 'Price', editable:true, width: 120 },
            { headerName: 'Amount', valueGetter: '"Rp. "+data.Qty*data.Price' },
        ];
        
        var gridOptions = {
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
                calcTotal(); //calc all + subtotal
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
                //['Salesman', 'Salesman'],
                ['Warehouse', 'Warehouse'],
            ];
            for(let dt of selData) {
                var obj = $('select#'+dt[0]);
                init_mselect(obj, dt);
            }
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);

            // EVENT
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
            $("button#cmSave").click(function(e){
                e.preventDefault();
                var formdata=$('form').serialize();
                var name = $("input[name=Code]").val();
                var password = $("input[name=Name]").val();
                var email = $("input[name=Barcode]").val();
                exit;
                //alert(name);
                $.ajax({
                    type:'POST',
                    //url:'/datasave_product', //using local
                    url:'{{env('API_URL')}}/api/datasave',
                    //data:{name:name, password:password, email:email},
                    data: formdata,
                    success:function(res){
                        alert(res.success);
                    }
                });
            });
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

            $('.modal').on('show.bs.modal', function (e) {
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
            })
        });

        function afterModalClose(sel) {
            if (sel.selModal == 'modal-supplier') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
            }
            if (sel.selModal == 'modal-product') {
                var selRow = gridOptions.api.getSelectedRows();
                var nodes = gridOptions.api.getSelectedNodes();
                selRowIdx = nodes[0].rowIndex;
                griddata[selRowIdx].ProductCode = sel.selRow[0]
                griddata[selRowIdx].ProductName = sel.selRow[1]
                griddata[selRowIdx].Qty = (griddata[selRowIdx].Qty!=0)? griddata[selRowIdx].Qty : 1;
                griddata[selRowIdx].Price = 0;
                //get product price
                $.get("{{ url('getrow/masterproductprice') }}?Code="+sel.selRow[0], function(data, status){
                    griddata[selRowIdx].Price = parseInt(data.Channel1);
                    gridOptions.api.setRowData(griddata);
                });
                gridOptions.api.setRowData(griddata);
                calcTotal();
            }
        };

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

