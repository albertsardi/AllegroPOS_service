{{-- get from https://www.youtube.com/watch?v=lDCs_Ksn-nM --}}

@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    @php Form::setBindData($data);@endphp {{-- this is important --}}
    {{-- <form id='formData' action='{{ url('ajax_getProduct') }}' method='POST'> --}}
    <form id='formData' method='POST'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', $jr) }}   
    {{ Form::hidden('id', $id) }}   
    {{-- <?php dump($data);?> --}}
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3 border-info">
            <div class="card-header text-white bg-info py-1 h5">
                <i class="fa fa-check-square-o"></i> @php echo ($jr=='AR')? 'Receive Payment' : 'Paid Bill'; @endphp
            </div>
            <div class="card-body">
                {{-- new design --}}
                @php
                    $temp = '<label for="input{{name}}">{{label}}</label>
                            {{input}}';
                    Form::setFormTemplate($temp);
                @endphp
                <div class="form-group">
                    @if($jr=='AR')
                        {{-- {{ Form::select('AccCode', 'Receive payment from customer' ) }} --}}
                        <label for="input">Receive payment from customer</label>
                        {{-- {!! Form::_mselect('AccCode', $select->selCustomer??[], $data->AccCode??'-') !!} --}}
                    @else
                        {{-- {{ Form::select('AccCode', 'Pay bill to supplier',  ) }} --}}
                        <label for="input">Pay bill to supplier</label>
                        {{-- {!! Form::_mselect('AccCode', $select->selSupplier??[], $data->AccCode??'-') !!} --}}
                    @endif
                    <select name='AccCode' id='AccCode' class='form-control form-control-sm w-100' autocomplete='off'></select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        {{ Form::text('TransNo', 'Payment #' ) }}
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::date('TransDate', 'Date', date('d/m/Y')) }}
                    </div>
                    <div class="form-group col-md-4">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        {{-- {{ Form::select('toAccNo', 'to Bank Account', $select->selBankAccount ) }} --}}
                        <label for="input">to Bank Account1</label>
                        {{-- {!! Form::_mselect('toAccNo', $select->selBankAccount??[] ) !!} --}}
                        <select name='AccNo' id='AccNo' class='form-control form-control-sm w-100' autocomplete='off'></select>
                    </div>
                    <div class="form-group col-md-4">
                        {{ Form::number('Total', 'Payment Amount' ) }}
                    </div>
                </div>
                <hr/>


                
                
                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                {{ Form::hidden('detail', '') }}   
                <button id='cmAddrow' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>
                {{-- <button id='cmDelrow' class='btn btn-info' type='button'>Del selected line</button> --}}

                <div class='row px-3 float-right'>
                    @php Form::setFormTemplate('layout-inline');@endphp
                    <div class='col'>
                        {{ Form::number('TotalInv', 'Total Invoice', ['disabled'=>true]) }}
                        {{ Form::number('TotalPaid', 'Total Payment', ['disabled'=>true]) }}
                    </div>
                </div>
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   {{-- @include('modal.modal-customer')  --}}
   {{-- @include('modal.modal-supplier')  --}}
   @include('modal.modal-account') 
   {{-- @include('modal.modal-invoice-unpaid')  --}}
@stop
                    
@section('js')
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_form.js') }}" type="text/javascript"></script>
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
            { field: "InvNo", headerName: 'Invoice #', editable:false, edittype:'text', width: 150, 
                cellRenderer:function(row)  {
                    return setLookupButton(row, 'modal-invoice-unpaid')
                },
            },
            { field: "InvDate", headerName: 'Date', width: 100 },
            { field: 'InvAmount', headerName: 'Amount', type:'numericColumn' },
            { field: 'AmountUnpaid', headerName: 'Unpaid Amount' },
            { field: 'AmountPaid', headerName: 'AmountPaid', editable:true, edittype:'number', type:'numericColumn' },
            { field: "Memo", headerName: 'Memo', editable:true, edittype:'text', width: 270 },
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
            {{-- $('select.select2').select2({ theme: "bootstrap" }); --}}

            //init select box
            /*
            var selData = [
                ['AccCode', 'Customer/Supplier'],
                ['toAccNo', 'Bank Account #'],
            ];
            selData[0]['AccCode'] = ('{{$jr}}'=='AR')? 'Customer':'Supplier';
            for(let dt of selData) {
                $('select#'+dt[0]).select2({
                    placeholder: `Choose a ${dt[1]}`,
                    templateResult: function(data) {
                        var str = data.text.split('|')
                        //if str[0]=='-' str[1]='-';
                        var result = jQuery(
                        '<div class="row">' +
                            '<div class="col-md-3">' + str[0] + '</div>' +
                            '<div class="col-md-9">' + str[1] + '</div>' +
                        '</div>'
                        );
                        return result;
                    },
                });
            }
            */

            /*@if($jr=='AP')
                //$('select#AccCode').mselect2("{{ env('API_URL') }}/api/select/supplier", 'Supplier');
                $('select#AccCode').mselect2("supplier", 'Supplier');
            @else
                //$('select#AccCode').mselect2("{{ env('API_URL') }}/api/select/customer", 'Customer');
                $('select#AccCode').mselect2("customer", 'Customer');
            @endif
            */
            //$('select#AccNo').mselect2("{{ env('API_URL') }}/api/select/coa?catname={{ urlencode('Cash & Bank') }}", 'Cash & Bank' );
            //$('select#AccNo').mselect2("coa?catname={{ urlencode('Cash & Bank') }}", 'Cash & Bank' );
            $('select#AccNo').mselect2("coa", 'Cash & Bank' );
            //$('select#AccNo').select2('val','11100').trigger('change');
            //$('select#AccNo').select2('val',11200);
            //$('select#AccNo').val('11200');
            $('select#AccNo').data('select2').trigger('select', {
                data: {"id": 11200, "text": 'test11200' }
            });
            
            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
            calcTotal();

            // EVENT
            //add new line
            $("button#cmAddrow").click(function(e){
                var newLine = { InvNo:'', InvDate:'', InvAmount:0, AmountUnpaid:0, AmountPaid:0, Memo:'' }
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
                //submit grid data to variable
                const rowData = [];
                gridOptions.api.forEachNode(function (node) {
                    rowData.push(node.data);
                });
                $("input[name='detail']").val(JSON.stringify(rowData));
                
                var formdata=$('form').serialize();
                $('#formData').submit();
            });
            $("button#cmSave").click(async function(e){ //using ajax
                e.preventDefault();
                var formdata=$('form').serialize();
                //var result = await $('form').formsave('{{$jr}}', '{{$id}}' );

                var id = '{{$id}}';
                var resp = await axios.post(window.API_URL+"/api/{{$jr}}/save/"+id, formdata);
                console.log(resp)
                if (resp.status==200) {
                    alert('datasave.')
                    if (id=='new' || id=='') window.location.href = window.location.href.replace("/new", "/"+resp.data.data.id);
                } else {
                    console.log(resp)
                }
            });

            $('.modal').on('show.bs.modal', function (e) {
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
            })
        });

        function afterModalClose(sel) {
            if (sel.selModal=='modal-customer' || sel.selModal=='modal-supplier') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
            }
            if (sel.selModal == 'modal-account') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
            }
            if (sel.selModal == 'modal-invoice-unpaid') {
                var selRow = gridOptions.api.getSelectedRows();
                var nodes = gridOptions.api.getSelectedNodes();
                selRowIdx = nodes[0].rowIndex;
                mydata[selRowIdx].InvNo = sel.selRow[0]
                mydata[selRowIdx].InvDate = sel.selRow[1]
                mydata[selRowIdx].InvAmount = sel.selRow[2]
                mydata[selRowIdx].AmountUnpaid = sel.selRow[3]
                gridOptions.api.setRowData(mydata);
                calcTotal();
            }
        };

        function calcTotal() {
            var totInv = 0; var totPaid = 0;
            gridOptions.api.forEachNode(function (node) {
                totPaid += parseInt(node.data.AmountPaid ?? 0)
                totInv += parseInt(node.data.InvAmount ?? 0)
            });
            $('input#TotalInv').val(totInv)
            $('input#TotalPaid').val(totPaid)
        }
    </script>
    
@stop

