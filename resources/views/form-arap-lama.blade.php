@extends('temp-master')

@section('css')
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css"> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
@stop

@section('content')
    <form id='formData'>
     <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'AR') }}      
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3> 
            </div>
            <div class="card-body">
               {{ Form::text('TransNo', 'Payment #', $data->TransNo) }}
               {{ Form::date('TransDate', 'Payment Date', $data->TransDate) }}
               {{ Form::textwlookup('toAccNo', 'to Bank Account', 'modal-account') }}
               {{ Form::textwlookup('AccCode', 'Payment From', 'modal-account') }}
            </div>
        </div><!-- end card-->
    </div>

    <!-- PANEL2 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> Other data</h3>
            </div>
            <div class="card-body">
               {{ Form::checkbox('Code', 'Reff #') }}
               {{ Form::text('ReffNo', 'Reff #', $data->ReffNo) }}
               {{ Form::number('Amount', 'Pay Amount', $data->Amount) }}
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
@stop

@section('modal')
   @include('modal.modal-account') 
   @include('modal.modal-invoice-unpaid') 
@stop

@section('js')
    {{-- <script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script> --}}
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-invoice-unpaid.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-account.js') }}" type="text/javascript"></script>
    <script>
        var selRowIdx = null;
        var selRow = null;
        var modal_target_button = null;
        var mydata = {!!$griddata!!}
        var mInvUnpaid = {!! $mInvUnpaid !!};
        var mCoa = {!! $mCoa !!};
        
        //init ag-grid
        var colModel = [
            { field:'checkboxBtn',headerName:'', checkboxSelection:true,headerCheckboxSelection:true,pinned:'left',width:50},
            { field: "InvNo", headerName: 'Invoice #', editable:false, edittype:'text', width: 150, 
                cellRenderer:function(row)  {
                    return row.value + "   <button class='cmLookup' type='button' data-toggle='modal' data-target='#modal-invoice-unpaid'>...</button>";
                },
            },
            { field: "InvDate", headerName: 'Date', width: 100 },
            { field: 'InvAmount', headerName: 'Amount', valueGetter: '"Rp. "+data.InvTotal' },
            { field: 'AmountPaid', headerName: 'AmountPaid', valueGetter: '"Rp. "+data.AmountPaid' },
            { field: "Memo", headerName: 'Memo', width: 270 }
        ];
        var gridOptions = {
            columnDefs: colModel,
            rowData: mydata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            rowSelection: 'single',
        }

        // jQuery 
        $(document).ready(function() {
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $('select.select2').select2({ theme: "bootstrap" });
            $.ajaxSetup({ async: false });

            //load data
            //var mydata = {!!$griddata!!}
            //var selRow = null;

            //init ag-grid
            /*var colModel = [
                { field:'checkboxBtn',headerName:'', checkboxSelection:true,headerCheckboxSelection:true,pinned:'left',width:50},
                { field: "InvNo", headerName: 'Invoice #', editable:false, edittype:'text', width: 150, 
                    cellRenderer:function(row)  {
                        return row.value + "   <button class='cmLookup' type='button' data-toggle='modal' data-target='#modal-invoice-unpaid'>...</button>";
                    },
                },
                { field: "InvDate", headerName: 'Date', width: 100 },
                { field: 'InvAmount', headerName: 'Amount', valueGetter: '"Rp. "+data.InvTotal' },
                { headerName: 'AmountPaid', valueGetter: '"Rp. "+data.AmountPaid' },
                { field: "Memo", headerName: 'Memo', width: 270 }
            ];*/
            
            /*var gridOptions = {
                columnDefs: colModel,
                rowData: mydata,
                caption: 'Grid Order',
                enableCellChangeFlash: true,
                editType: 'fullRow',
            }*/
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
           
            // EVENT
            //add new line
            $("button#cmAddrow").click(function(e){
                var newLine = {InvNo:'', InvDate:'', InvTotal:0, AmountPaid:0, Memo:''};
                
                mydata.push(newLine)
                gridOptions.api.setRowData(mydata);

                //gridOptions.api.applyTransaction({ add: [newLine] });
                //console.log( mydata );
                //console.log( gridOptions.api.data );
                //console.log( gridOptions.api.getSelectedRows() );
            });
            //del line
            $(".delete_btn").click(function(e){
                var ln = $(this).attr('line');
            });
            //delete row selected 
            $("button#cmDelrow").click(function(e){
                //alert('del selected row');
                var selRows = gridOptions.api.getSelectedRows() 
                gridOptions.api.applyTransaction({ remove: selRows });
            });
            $(".cellLookup").click(function(e){
                alert('del selected row');
                //var selRows = gridOptions.api.getSelectedRows() 
                //gridOptions.api.applyTransaction({ remove: selRows });
                //gridOptions.api.refreshInfiniteCache();
            });
            $(document).on('click','button.cmLookup',function(e){
                e.preventDefault();
                var key = $(this).parent().text().replace('...','').trim()
                selRowIdx = agGrid_getIndex(gridOptions, key);
                //selRow = gridOptions.api.getSelectedRows();
                //console.log(selRow)
            });
           
            //save data
            /*$("button#cmSave").click(function(e){
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
            }); */
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
            $('#listCoa').DataTable({
                paging: true,
                pageLength: 10,
                pagingType: "full_numbers",
                data: {!! $mCoa !!},
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, sett) {
                            return "<a href='' class='lookup-item' rowIdx="+sett.row+">" + data['AccNo'] + "</a>";
                        }
                    },
                    { data: 'AccNo' },
                    { data: 'CatName' }
                ]
            });
            $('#listInvUnpaid').DataTable({
                paging: true,
                pageLength: 10,
                pagingType: "full_numbers",
                data: {!! $mInvUnpaid !!},
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, sett) {
                            return "<a href='' class='lookup-item' rowIdx="+sett.row+">" + data['TransNo'] + "</a>";
                        }
                    },
                    { data: 'TransDate' },
                    { data: 'Total' },
                    { data: 'InvUnpaid' }
                ]
            });
            /*$("a.lookup-item").click(function(e){
                // https://stackblitz.com/edit/angular-ag-grid-button-renderer?file=src/app/app.component.ts
                e.preventDefault();
                var itm = $(this).text();
                mydata[selRow].InvNo = itm;
                mydata[selRow].AmountPaid = 1234567;
                gridOptions.api.setRowData(mydata);
                //$('#modal-invoice-unpaid').modal('hide')
                $('div.modal').modal('hide')
            });*/

            $('#modal-account').on('show.bs.modal', function (e) {
            //$('.modal').on('show.bs.modal', function (e) {
                //alert('show modal')
                modal_target_button = $(e.relatedTarget) // Button that triggered the modal
                console.log(modal_target_button)
            })
        });

         function grid_afterLookupClose(e) {
            console.log(e.lookup_id)
            if (e.lookup_id == 'modal-invoice-unpaid') {
                // alert('afterLookupClose inside')
                mydata[selRowIdx].InvNo = selRow.TransNo;
                mydata[selRowIdx].InvDate = selRow.TransDate;
                mydata[selRowIdx].InvTotal = selRow.Total;
                mydata[selRowIdx].AmountPaid = selRow.InvPaid;
                //mydata[selRowIdx] = selRow;
                gridOptions.api.setRowData(mydata);
            }
            if (e.lookup_id == 'modal-account') {
                //alert('afterLookupClose inside')
                console.log(selRow)
                var btn = modal_target_button;
                console.log(btn.attr('id'))
                $('#'+btn.attr('id')).textwlookup(selRow.AccNo, selRow.AccName)
                //mydata[selRowIdx].InvNo = selRow.TransNo;
                //mydata[selRowIdx].InvDate = selRow.TransDate;
                //mydata[selRowIdx].InvTotal = selRow.Total;
                //mydata[selRowIdx].AmountPaid = selRow.InvPaid;
                //mydata[selRowIdx] = selRow;
                //gridOptions.api.setRowData(mydata);
            }
        };
    </script>
    
    
    
@stop






