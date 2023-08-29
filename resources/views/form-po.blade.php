{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css"> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-balham.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-bootstrap.css') }}"> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-balham.css"/> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-bootstrap.css"/> --}}
@stop

@section('content')
    @php Form::setBindData($data);@endphp {{-- this is important --}}
    <form id='formData' action='transsave' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'PO') }}   
    {{ Form::hidden('id', $id) }}   
    {{-- <?php dump($data->toArray());?> --}}
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="ribbon-wrapper ribbon-lg">
                <div class="ribbon bg-danger text-white text-lg">{{$data->Status??'DRAFT'}}</div>
            </div>
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Purchase Order
                <div class='float-right text-muted'></div>
            </div>
            <div class="card-body">
                {{-- new design --}}
                @php
                    $temp = '<label for="input{{name}}">{{label}}</label>
                            {{input}}';
                    Form::setFormTemplate($temp);
                @endphp
                <div class="form-group">
                    <label for="input">Supplier</label>
                    {{-- <select type="text" class="form-control form-control-sm select2-supplier" id="Supplier" name="Supplier" placeholder="input supplier"></select> --}}
                    {!! Form::_mselect('AccCode', $select->selSupplier, $data->AccCode) !!}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('TransNo', 'Transaction #' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="input">Delivery to / Warehouse</label>
                        {!! Form::_mselect('Warehouse', $select->selWarehouse, $data->Warehouse) !!}
                    </div>
                </div>
                <hr/>





                <!--
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
                        @php Form::setFormTemplate('layout-inline');@endphp
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
   @include('modal.modal-supplier') 
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
        function BtnCellRenderer() {}
        BtnCellRenderer.prototype.init = function(params) {
            this.params = params;
            this.eGui = document.createElement('button');
            this.eGui.innerHTML = 'Click me!';
            console.log(this.eGui)
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
        var mydata = {!! json_encode($griddata) !!}
       // var mSupplier = {!! $mSupplier !!};
        //var mProduct= {!! $mProduct !!};

        //init ag-grid
        var colModel = [
            { field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 270, 
                cellRenderer:function(row)  {
                    console.log(row);
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
            { field: "Qty", headerName: 'Qty', editable:true, edittype:'text', width: 80 },
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
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);

            // EVENT
            //add new line
            $("button#cmAddrow").click(function(e){
                var newLine = { ProductCode:'', ProductName:'', Qty:0, Price:0 }
                mydata.push(newLine)
                gridOptions.api.setRowData(mydata);
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
                console.log(formdata);
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
                        console.log(res.data);
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
                //console.log(lookup_target_button)
            })
        });

        function afterModalClose(sel) {
            //console.log(sel)
            console.log(lookup_target_button)
            if (sel.selModal == 'modal-supplier') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
            }
            if (sel.selModal == 'modal-product') {
                var selRow = gridOptions.api.getSelectedRows();
                var nodes = gridOptions.api.getSelectedNodes();
                selRowIdx = nodes[0].rowIndex;
                mydata[selRowIdx].ProductCode = sel.selRow[0]
                mydata[selRowIdx].ProductName = sel.selRow[1]
                mydata[selRowIdx].Qty = (mydata[selRowIdx].Qty!=0)? mydata[selRowIdx].Qty : 1;
                mydata[selRowIdx].Price = 0;
                //get product price
                $.get(`http://localhost/lav7_PikeAdmin/getrow/masterproductprice/Code=${sel.selRow[0]}`, function(data, status){
                    //console.log(data)
                    mydata[selRowIdx].Price = parseInt(data.Channel1);
                    gridOptions.api.setRowData(mydata);
                });
                gridOptions.api.setRowData(mydata);
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

