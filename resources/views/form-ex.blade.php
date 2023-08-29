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
    {{ Form::hidden('jr', 'EX') }}   
    {{ Form::hidden('id', $id) }}   
    {{-- <?php dump($data->toArray());?> --}}
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> Expense
            </div>
            <div class="card-body">
                <div class="card-body">
                {{-- new design --}}
                @php
                    $temp = '<label for="input{{name}}">{{label}}</label>
                            {{input}}';
                    Form::setFormTemplate($temp);
                @endphp
                <div class="form-group">
                    {{-- {{ Form::select('AccNo', 'Supplier' ) }} --}}
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        {{ Form::text('Recevier', 'Beneficiary' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::text('TransNo', 'Transaction #', ['placeholder'=>'Transaction #', 'readonly'=>true]) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date' ) }}
                    </div>
                </div>
                <hr/>
                

                <!--
                Design Lama
                <div class='row  xxjustify-content-md-center'>    
                    {{-- leff side --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-left">
                        {{ Form::textwlookup('AccCode', 'Supplier', 'modal-supplier') }}
                        {{-- {{ Form::select('Warehouse', 'Warehouse', $mCat) }} --}}
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-3"> </div>
                    {{-- righsideside --}}
                    <div class="col-sm-5 col-md-5 col-lg-3 float-right">
                        {{ Form::text('TransNo', 'Transaction #', ['placeholder'=>'Transaction #', 'readonly'=>true]) }}
                        {{ Form::date('TransDate', 'Date') }}
                        <input class="form-control" type="date" name="date">
				        <span class="form-text text-muted">Using <code>input type="date"</code></span>
                        {{ Form::text('TaxNo', 'Tax #') }}
                    </div>
                </div>
                -->
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   
                <button id='cmAddrow' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>
                {{-- <button id='cmDelrow' class='btn btn-info' type='button'>Del selected line</button> --}}

                <div class='row px-3 float-right'>
                    <div class='col'>
                        {{ Form::number('Amount', 'Balance', ['disabled'=>true]) }}
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   @include('modal.modal-supplier') 
   @include('modal.modal-account') 
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
            { field: "AccNo", headerName: 'Account #', width: 270, 
                cellRenderer:function(row)  {
                    return setLookupButton(row, 'modal-account')
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },
            { field: "AccName", headerName: 'Account Name', width: 270 },
            { field: "Debet", headerName: 'Debet', editable:true, edittype:'text', width: 80 },
            { field: "Credit", headerName: 'Credit', editable:true, edittype:'text', width: 120 },
        ];
        
        var gridOptions = {
            columnDefs: setColModel(colModel),
            rowData: mydata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            rowSelection: 'single',
            onRowEditingStarted: (event) => {
                //console.log('never called - not doing row editing');
            },
            onRowEditingStopped: (event) => {
                //console.log('never called - not doing row editing');
                calcTotal();
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
                    allOfTheData.push(mydata[i]);
                }
                calcTotal();
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
            //console.log(sel)
            if (sel.selModal == 'modal-supplier') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
            }
            if (sel.selModal == 'modal-account') {
                var selRow = gridOptions.api.getSelectedRows();
                var nodes = gridOptions.api.getSelectedNodes();
                selRowIdx = nodes[0].rowIndex;
                mydata[selRowIdx].AccNo = sel.selRow[0]
                mydata[selRowIdx].AccName = sel.selRow[1]
                mydata[selRowIdx].Debet = 0
                mydata[selRowIdx].Credit = 0
                gridOptions.api.setRowData(mydata);
                calcTotal();
            }
        };

        function calcTotal() {
            var total = 0;
            gridOptions.api.forEachNode(function (node) {
                total += parseInt(node.data.Debet) - parseInt(node.data.Credit)
            });
            $('input#Amount').val(total)
        }
    </script>
    
@stop

