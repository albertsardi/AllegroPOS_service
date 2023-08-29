@extends('temp-master')

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css">
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css">
   
@stop

@section('content')
    <form id='formData'>
     <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', $jr) }}      
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3> 
            </div>
            <div class="card-body">
               {{ Form::text('TransNo', 'Payment #') }}
			   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Launch demo modal</button>
			   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-account">Launch demo modal2</button>
               {{ Form::date('TransDate', 'Payment Date') }}
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
               {{ Form::text('Code', 'Reff #') }}
               {{ Form::number('Amount', 'Pay Amount') }}
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

@section('grid')
<div class="col-12">
    <div class="card mb-3">
        <div class="card-body">
            <div id='grid'></div>
        </div>
    </div>
</div>
@stop

@section('modal')
   @include('modal.modal-account') 
   @include('modal.modal-customer') 
   @include('modal.modal-invoice-unpaid') 
@stop

@section('js')
<script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
<script>
   $(document).ready(function() {
      $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
      $('select.select2').select2({ theme: "bootstrap" });
      $.ajaxSetup({
            async: false
      });

      //load data
            var mydata = {!!$griddata!!}

            //init ag-grid
            var colModel = [
                { field: "InvNo", headerName: 'Invoice #', editable:true, edittype:'text', width: 150, 
                    cellRenderer:function(row)  {
                        return row.value+'  <button type="button" line='+row.rowIndex+'">...</button>';
                    },
                    cellRendererParams: {
                        clicked: function(field) {
                            alert(`${field} was clicked`);
                        }
                    }
                },
                { field: "InvDate", headerName: 'Date', width: 100 },
                { field: 'InvAmount', headerName: 'Amount', valueGetter: '"Rp. "+data.InvTotal' },
                { headerName: 'AmountPaid', valueGetter: '"Rp. "+data.AmountPaid' },
                { field: "Memo", headerName: 'Memo', width: 270 }
            ];
        
        var gridOptions = {
            columnDefs: setColModel(colModel),
            rowData: mydata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
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
        var xgd =  document.querySelector('#xgrid');
        new agGrid.Grid(xgd, gridOptions);

         // EVENT
        //add new line
        $("button#cmAddrow").click(function(e){
            //demo: https://www.ag-grid.com/examples/infinite-scrolling/insert-remove/packages/vanilla/index.html
            //demo: https://www.ag-grid.com/javascript-data-grid/data-update-transactions/
            //alert('add new line');
            var newLine = { ProductCode:'new product', ProductName:'new product name', Qty:123, Price:1000 }
            
            //mydata.push(newLine)
            //gridOptions.api.setRowData(mydata);

            gridOptions.api.applyTransaction({ add: [newLine] });
        });
        //del line
        $(".delete_btn").click(function(e){
            var ln = $(this).attr('line');
            //lert('delte row '+ln[0]);
        });
        //delete row selected 
        $("button#cmDelrow").click(function(e){
            //alert('del selected row');
            var selRows = gridOptions.api.getSelectedRows() 
            gridOptions.api.applyTransaction({ remove: selRows });
            //gridOptions.api.refreshInfiniteCache();
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
                    //console.log(res.data);
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

        $('.modal').on('show.bs.modal', function (e) {
            lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
        })
   });

   {{-- function afterModalClose(sel){
       //console.log(sel)
      if (Array.isArray(lookup_target)) { //grid lookup
         if (lookup_target[1]=='ProductCode') { //form lookup
            cellset(lookup_target[0], lookup_target[1], lookup_select.Code)
            cellset(lookup_target[0], 'ProductName', lookup_select.Name)
         }
      } else {
         if (lookup_target=='toAccNo') { //form lookup
            $("input[name='"+lookup_target+"']").val(lookup_select.AccNo);
            $("label[name='"+lookup_target+"-val2']").text(lookup_select.AccName);
         }
      }
      
   } --}}
    function afterModalClose(sel) {
        //console.log(sel)
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
                ////console.log(data)
                mydata[selRowIdx].Price = parseInt(data.Channel1);
                gridOptions.api.setRowData(mydata);
            });
            gridOptions.api.setRowData(mydata);
        }
    };
</script>
@stop






