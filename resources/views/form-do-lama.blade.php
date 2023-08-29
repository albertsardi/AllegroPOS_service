{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css"> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'PI') }}   
    {{ Form::hidden('id', $data->id) }}   
    <!-- Panel Left -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3>
            </div>
            <div class="card-body">
                {{ Form::text('TransNo', 'Transaction #', $data->TransNo, ['placeholder'=>'Transaction #']) }}
                {{ Form::date('TransDate', 'Date', $data->TranDate) }}
                {{ Form::text('TaxNo', 'Tax #', $data->TaxNo) }}
                {{-- {{ Form::select('Type', 'Type', $mType, $data->Type) }} --}}
                {{-- {{ Form::select('HppBy', 'HPP', $mHpp, $data->HppBy) }} --}}
                {{-- {{ Form::checkbox('ActiveProduct', 'Active Product') }} --}}
                {{-- {{ Form::checkbox('StockProduct', 'Have Stock', $data->StockProduct) }} --}}
                {{-- {{ Form::checkbox('canBuy', 'Product can buy', $data->canBuy) }} --}}
                {{-- {{ Form::checkbox('canSell', 'Product can sell', $data->canSell) }} --}}
            </div>
        </div><!-- end card-->
    </div>

    <!-- Panel Right -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> Other data</h3>
            </div>
            <div class="card-body">
                {{ Form::textwlookup('AccCode', 'Customer', 'modal-customer') }}
                {{ Form::select('Warehouse', 'Warehouse', $mCat, $data->Category) }}
            </div>
        </div><!-- end card-->
    </div>

    <!-- Panel Grid -->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> Detail data</h3>
            </div>
            <div class="card-body">
                <div id="xgrid" class="ag-theme-alpine" style="height: 300px; width:100%;"></div>
                {{ Form::hidden('detail', '') }}
                {{-- <input id='detail' name='detail'></input> --}}
            </div>
            <div class="card-footer">
                <button id='cmAddrow' type='button'>Add new line</button>
                <button id='cmDelrow' type='button'>Del selected line</button>
            </div>
        </div><!-- end card-->
    </div>

    <!-- Panel Bottom -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-body">
                {{ Form::number('SubTotal', 'Sub total', ['disabled'=>true]) }}
                {{ Form::number('DiscAmountH', 'Discount', ['disabled'=>true]) }}
                {{ Form::number('TaxAmount', 'Tax', ['disabled'=>true]) }}
                {{ Form::number('Total', 'Grand Total', ['disabled'=>true]) }}
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
{{-- <script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script> --}}
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-customer.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-product.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-invoice-unpaid.js') }}" type="text/javascript"></script>
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
        var lookup_target_button = null;
        
        //load data
        var mydata = {!!$griddata!!}
        var mCustomer = {!! $mCustomer !!};
        var mProduct= {!! $mProduct !!};

        //init ag-grid
        var colModel = [
            { field:'checkboxBtn',headerName:'', checkboxSelection:true,headerCheckboxSelection:true,pinned:'left',width:50},
            { field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 270, 
                cellRenderer:function(row)  {
                    //return row.value+'  <button type="button" line='+row.rowIndex+'">...</button>';
                    return row.value + "   <button class='cmLookup' type='button' data-toggle='modal' data-target='#modal-product'>...</button>";
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },
            { field: "ProductName", headerName: 'Product Name', width: 270 },
            { field: "Qty", headerName: 'Quantity', editable:true, edittype:'text', width: 80 },
            { field: "Price", headerName: 'Price', editable:true, edittype:'text', width: 120 },
            { headerName: 'Amount', valueGetter: '"Rp. "+data.Qty*data.Price' },
            { headerName: '',  cellRenderer: function(row)  {
                    return `<button type='button' class='delete_btn' onclick='grid_delrow(${row.rowIndex})'>Delete</button>`;
                }, 
            },
        ];
        
        var gridOptions = {
            columnDefs: colModel,
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

        var grid_delrow = function (row) {
            alert('del row - '+row)
            gridOptions.api.applyTransaction({remove:2})
            console.log(gridOptions.rowData)
            gridOptions.rowData.splice(row, 2);
            gridOptions.api.refreshInfiniteCache();
        }

        $(document).ready(function() {
           //init page
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $('select.select2').select2({ theme: "bootstrap" });
            
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);

            // EVENT
            //add new line
            $("button#cmAddrow").click(function(e){
                alert('add new line');
                var newLine = { ProductCode:'new product', ProductName:'new product name', Qty:123, Price:1000 }
                
                mydata.push(newLine)
                gridOptions.api.setRowData(mydata);

                //gridOptions.api.applyTransaction({ add: [newLine] });
            });
            //del line
            $(".delete_btn").click(function(e){
                var ln = $(this).attr('line');
                ////alert('delte row '+ln[0]);
            });
            //delete row selected 
            $("button#cmDelrow").click(function(e){
                alert('del selected row');
                var selRows = gridOptions.api.getSelectedRows() 
                gridOptions.api.applyTransaction({ remove: selRows });
                //gridOptions.api.refreshInfiniteCache();
            });
            /*$(document).on('click','button.cmLookup',function(e){
                e.preventDefault();
                var find = $(this).parent().text().replace('...','').trim()
                //console.log(find)
                selRowIdx = agGrid_getIndex(gridOptions, 'ProductCode', find);
                //selRow = gridOptions.api.getSelectedRows();
                //console.log(selRow)
            });*/
            //save data
            $("button#cmSave").click(function(e){
                e.preventDefault();
                var formdata=$('form').serialize();
                var name = $("input[name=Code]").val();
                var password = $("input[name=Name]").val();
                var email = $("input[name=Barcode]").val();
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
                //e.preventDefault();
                alert('save');
                //submit grid data to variable
                const rowData = [];
                gridOptions.api.forEachNode(function (node) {
                    rowData.push(node.data);
                });
                $("input[name='detail']").val(JSON.stringify(rowData));
            });

            // modal populate
            $('#listCustomer').DataTable({
                paging: true,
                pageLength: 10,
                pagingType: "full_numbers",
                data: {!! $mCustomer !!},
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, sett) {
                            return "<a href='' class='lookup-item' rowIdx="+sett.row+">" + data['AccCode'] + "</a>";
                        }
                    },
                    { data: 'AccName' },
                    { data: 'Category' }
                ]
            });
            $('#listProduct').DataTable({
                paging: true,
                pageLength: 10,
                pagingType: "full_numbers",
                data: {!! $mProduct !!},
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, sett) {
                            //return "<a href='' class='lookup-item' rowIdx="+sett.row+">" + data['Code'] + "</a>";
                            return "<a href='' class='lookup-item' rowIdx="+sett.row+">" + data['Code'] + "</a>";
                        }
                    },
                    { data: 'Name' },
                    { data: 'Category' },
                ]
            });

            /*$('.modal').on('show.bs.modal', function (e) {
                //alert('show modal')
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
                console.log(lookup_target_button)
            })*/
            
        });

        function grid_afterLookupClose(e) {
            console.log(e.lookup_id)
            if (e.lookup_id == 'modal-customer') {
                //alert('afterLookupClose inside')
                //console.log(selRow)
                var btn = lookup_target_button;
                //console.log(btn.attr('id'))
                $('#'+btn.attr('id')).textwlookup(selRow.AccCode, selRow.AccName)
            }
            if (e.lookup_id == 'modal-product') {
                //console.log(e)
                var btn = lookup_target_button
                var find = btn.parent().text().replace('...','').trim()
                selRowIdx = agGrid_getIndex(gridOptions, find);
                //console.log(selRowIdx)
                mydata[selRowIdx].ProductCode = selRow.Code
                mydata[selRowIdx].ProductName = selRow.Name
                gridOptions.api.setRowData(mydata);
            }
        };
    </script>

    
@stop

