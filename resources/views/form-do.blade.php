{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    {{-- <?php dump($data);?> --}}
    <form id='formData' action='transsave' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    @php define('NOW', date('d/m/Y'));@endphp
    {{ Form::setBindData($data) }}
    {{ Form::setDateFormat('d/m/Y') }}
    {{ Form::hidden('jr', 'DO') }}   
    {{ Form::hidden('id', $data->id??'') }}   
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="ribbon-wrapper ribbon-lg">
                <div class="ribbon bg-danger text-white text-lg">{{$data->Status??'DRAFT'}}</div>
            </div>
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Delivery Order
                <div class='float-right text-muted'></div>
            </div>
            <div class="card-body">
                {{-- new design --}}
                <?php
                    $temp = '<label for="input{{name}}">{{label}}</label> 
                             {{input}}';
                    Form::setFormTemplate($temp);
                ?>
                <div class="form-group">
                    {{-- {{ Form::select('AccNo', 'Customer' ) }} --}}
                    <label for="input">Customer</label>
                    {!! Form::_mselect('AccCode', $select->selCustomer, $data->AccCode) !!}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('TransNo', 'Transaction #' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="input">Delivery from Warehouse</label>
                        {!! Form::_mselect('Warehouse', $select->selWarehouse, $data->Warehouse) !!}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('ReffNo', 'Order #',['readonly'=>true] ) }}
                        {{-- {!! Form::_mselect('ReffNo', $select->selSalesOrder, $data->ReffNo??'') !!} --}}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::text('SODate', 'Order Date', ['readonly'=>true] ) }}
                    </div>
                </div>
                <div class="form-group">
                    {{-- {{ Form::select('DeliveryTo', 'DeliveryTo' ) }} --}}
                    <label for="inputAddress">Delivery to</label>
                    <select type="text" class="form-control form-control-sm select2-address" id="DeliveryCode" name="DeliveryCode" placeholder="input address"></select>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('CarNo', 'Car Delivery #' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::text('Driver', 'Car Driver' ) }}
                    </div>
                </div>
                
                <hr/>
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   
                {{-- <button id='cmAddrow' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button> --}}

                @php Form::setFormTemplate('layout-inline');@endphp
                <div class='row px-3'>
                    <div class='col'>
                        {{ Form::number('TotalQty', 'Qty Total', 0, ['disabled'=>true]) }}
                    </div>
                    <div class='col float-right'>
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
   @include('modal.modal-salesorder') 
@stop
                    
@section('js')
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_grid.js') }}" type="text/javascript"></script>
    
    <script>
        var selRowIdx = null;
        var selRow = null;
        var selModal = null;
        var lookup_target_button = null;
        
        //load data
        var mydata = {!! json_encode($griddata) !!}

        //init ag-grid
        var colModel = [
            //{ headerName:'', width:10, valueGetter:'node.rowIndex + 1'},
            /*{ field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 150, 
                cellRenderer:function(row)  {
                    {{-- return  "<button class='btn btn-sm btn-secondary cmLookup' type='button' data-idx=11 data-toggle='modal' data-target='#modal-product'><i class='fa fa-ellipsis-h'></i></button>"+"   "+row.value; --}}
                    return setLookupButton(row, 'modal-product')
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },*/
            { field: "ProductCode", headerName: 'Product #', width: 150 },
            { field: "ProductName", headerName: 'Product Name', width: 270 },
            { field: "UOM", headerName: 'Unit', width: 100 },
            { field: "OrderQty", headerName: 'Order Qty', width: 100 },
            { field: "TotSentQty", headerName: 'Total Sent Qty', width: 120 },
            { field: "SentQty", headerName: 'Qty', editable:true, edittype:'number', width: 100 },
            { field: "Price", headerName: 'Price', width: 120 },
            { field: "Amount", headerName: 'Amount', valueGetter: '"Rp. "+data.SentQty*data.Price' },
            /*{ headerName: '',  cellRenderer: function(row)  {
                    return `<button type='button' class='btn btn-sm btn-danger cmDelrow my-2'><i class='fa fa-close'></i></button>`;
                }, 
            },*/
        ];
        
        var gridOptions = {
            //defaultColDef: {
                //resizable: true,
                //initialWidth: 50,
                //wrapHeaderText: true,
                //autoHeaderHeight: true,
            //},
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
                calcAll();
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
            $('select.select2').select2({ theme: "bootstrap" });

            //init select box
            var selData = [
                ['AccCode', 'Customer'],
                ['Warehouse', 'Warehouse'],
            ];
            for(let dt of selData) {
                $('select#'+dt[0]).select2({
                    placeholder: `Choose a ${dt[1]}`,
                    templateResult: function(data) {
                        var result = jQuery(
                        '<div class="row">' +
                            '<div class="col-md-3">' + data.id + '</div>' +
                            '<div class="col-md-9">' + data.text + '</div>' +
                        '</div>'
                        );
                        return result;
                    },
                });
            }
            $('select#Address').select2({
                //data: data
            });
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
            calcAll();

            // EVENT
            //add new line
            $("button#cmAddrow").click(function(e){
                var newLine = { ProductCode:'', ProductName:'', SentQty:0, Price:0 }
                mydata.push(newLine)
                gridOptions.api.setRowData(mydata);
            });
            //delete row 
            $(document).on('click','button.cmDelrow',function(e){
                const selRow = gridOptions.api.getSelectedRows();
                gridOptions.rowData.splice(selRow, 1);
                gridOptions.api.setRowData(gridOptions.rowData);
            });

            // select2 customer change
            $('#AccCode').on('select2:select', function (e) {
                var sel = e.params.data.id;
                refreshDelivery(sel);
            });

            //save data
            $("button#cmSave2").click(function(e){
                e.preventDefault();
                alert('save');
                //submit grid data to variable
                const rowData = [];
                gridOptions.api.forEachNode(function (node) {
                    console.log(mydata)
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
            if (sel.selModal == 'modal-salesorder') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], '')
                //$('input#OrderDate').val(sel.selRow[1])
                //$('input#AccCode').val(sel.selRow[3])
                //$('input#AccName').val(sel.selRow[2])
                //$('input#DeliveryTo').val(sel.selRow[4])
                refreshSO();
            }
        };

        async function refreshSO() {
            var SOno = $('input#OrderNo').val();
            let resp = await fetch(' {{ url('getrow/orderhead') }}?TransNo='+SOno);
            if (resp.statusText=='OK') {
                let dat = await resp.json();
                $('input#OrderDate').val(moment(dat.TransDate).format('DD/MM/YYYY'))
                $('input#AccCode').val(dat.AccCode)
                $('input#AccName').val(dat.AccName)
                $('input#DeliveryTo').val(dat.DeliveryTo)
            }

            //populate detail
            resp = await fetch(' {{ url('getdata/orderdetail') }}?TransNo='+SOno);
            if (resp.statusText=='OK') {
                let dat = await resp.json();
                //console.log(dat)
                if (dat.length>0) {
                    //mydata = []; //clear data grid
                    for(let dt of dat) {
                        //console.log(dt);
                        let idx = findIdx(mydata, {ProductCode:dt.ProductCode} )
                        //console.log(row);
                        if (idx==-1) {
                            // if not found insert
                            mydata.push( { 
                                ProductCode:    dt.ProductCode,
                                ProductName:    dt.ProductName,
                                UOM:            dt.UOM, 
                                OrderQty:       Math.abs(dt.Qty),
                                Price:          dt.Price,
                            });
                        } else {
                            // if found, then update
                            mydata[idx].UOM         = dt.UOM; 
                            mydata[idx].OrderQty    = Math.abs(dt.Qty);
                            mydata[idx].Price       = dt.Price;
                        } 
                    }
                    gridOptions.api.setRowData(mydata);
                    calcAll();
                } else {
                    alert('no data from Order #'+SOno);
                }
                
            }
        }

        async function refreshDelivery(sel) {
            let resp = await fetch("{{ url('api/select/address') }}?AccCode="+sel);
            if (resp.statusText=='OK') {
                let data = await resp.json();
                let dat = await data.results.map(r => { return {id:r.id, text:r.text} }) 
                $('#DeliveryCode').empty().select2({data: dat});
            }
        }

        function findIdx(arr, find) {
            let key = Object.keys(find)[0]; 
            let v = find[key]; 
            for(let idx=0;idx<arr.length;idx++) {
                if (arr[idx][key] == v) return idx;
            }
            return -1;
        }

        function calcAll() {
            var tot = 0; var totQty = 0;
            for(let r of mydata) {
                //console.log(r)
                r.Amount = parseInt(r.SentQty) * parseInt(r.Price);
                tot+= r.Amount;
                totQty+= parseInt(r.SentQty);
            }
            gridOptions.api.setRowData(mydata);
            $('#SubTotal').val(tot);
            $('#Total').val( tot - $('#DiscAmountH').val() + $('#TaxAmount').val() );
            $('#TotalQty').val( totQty );
        }

        
    </script>
    
@stop

