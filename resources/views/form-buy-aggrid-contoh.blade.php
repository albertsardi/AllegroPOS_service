@extends('temp-master')

@section('css')
    {{-- <link href="{{ asset('assets/plugin/jquery-ui/themes/smoothness/jquery-ui.css') }}" rel="stylesheet" type="text/css" > --}}
    {{-- <link href="{{ asset('assets/plugin/jqgrid/ui.jqgrid.css') }}" rel="stylesheet" type="text/css" > --}}
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-grid.css">
    <link rel="stylesheet" href="https://unpkg.com/ag-grid-community/dist/styles/ag-theme-alpine.css">

    <script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
    
@stop

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'buy') }}   
    <!-- Panel Left -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data -agGrid</h3>
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
                {{ Form::textwlookup('AccCode', 'Supplier', 'modal-account') }}
                {{ Form::select('Warehouse', 'Warehouse', $mCat, $data->Category) }}
                
                {{-- {{ Form::number('MinStock', 'Minimal Stock') }} --}}
                {{-- {{ Form::number('MaxStock', 'Maximal Stock') }} --}}
                {{-- {{ Form::number('SellPrice', 'Sell Price') }} --}}
                {{-- {{ Form::number('LastBuyPrice', 'Last Buy Price',['disabled'=>true]) }} --}}
                {{-- <br/><br/><br/><br/> --}}
                {{-- {{ Form::textwlookup('AccSellNo', 'Income Account No', 'modal-account') }} --}}
                {{-- {{ Form::textwlookup('AccInventoryNo', 'Inventory Account No', 'modal-account') }} --}}
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
            </div>
            <div class="card-footer">
                <button id='xcmAddrow' type='button' onclick='insertItemsAt2AndRefresh(2)'' >Add new line</button>
                <button id='cmAddrow' type='button'>Add new line2</button>
                <button id='cmDelrow' type='button'>Del selected line</button>
                <button id='cmSubmit' type='button'>Submit data</button>
            </div>
        </div><!-- end card-->
    </div>

    <!-- Panel Bottom -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-body">
                {{ Form::number('SubTotal', 'Minimal Stock', ['disabled'=>true]) }}
                {{ Form::number('DiscAmount', 'Discount', ['disabled'=>true]) }}
                {{ Form::number('TaxAmount', 'Tax', ['disabled'=>true]) }}
                {{ Form::number('Total', 'Grand Total', ['disabled'=>true]) }}
            </div>
        </div><!-- end card-->
    </div>
    
    
    </form>
@stop

@section('modal')
   {{ Modal::open('modal-account', 'Account List') }}
      <table id="listCoa" class="table table-bordered table-hover display mx-10">
			<thead>
				<th>Account #</th><th>Account Name</th>
			</thead>
			<tbody></tbody>
		</table>
   {{ Modal::close() }}
@stop
                    
@section('js')
    {{-- <script src="{{ asset('assets/plugin/jqgrid/reset-msie.js') }}" type="text/javascript"></script> //harus dijalankan agar menghapus msie bug --}}
    {{-- <script src="{{ asset('assets/plugin/jqgrid/grid.locale-en.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="{{ asset('assets/plugin/jqgrid/jquery.jqGrid.min.js') }}" type="text/javascript"></script> --}}
    {{-- <script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script> --}}
    {{-- <script src="{{ asset('assets/test/main.js') }}" type="text/javascript"></script> --}}

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
        //var sequenceId;
        //var allOfTheData;
        var grid_delrow = function (row) {
            //console.log(gridOptions.rowData)
            //gridOptions.api.applyTransaction({ add: gridOptions.rowData });

                alert('del row - '+row)
                gridOptions.api.applyTransaction({remove:2})
                console.log(gridOptions.rowData)
                gridOptions.rowData.splice(row, 2);
                gridOptions.api.refreshInfiniteCache();
            }     
        /*function createRowData(id) {
            var makes = ['Toyota', 'Ford', 'Porsche', 'Chevy', 'Honda', 'Nissan'];
            var models = [
                'Cruze',
                'Celica',
                'Mondeo',
                'Boxter',
                'Genesis',
                'Accord',
                'Taurus',
            ];
            return {
                id: id,
                make: makes[id % makes.length],
                model: models[id % models.length],
                price: 72000,
            };
        }
        function insertItemsAt2AndRefresh(count) {
            insertItemsAt2(count);
            var maxRowFound = gridOptions.api.isLastRowIndexKnown();
            if (maxRowFound) {
                var rowCount = gridOptions.api.getInfiniteRowCount();
                gridOptions.api.setRowCount(rowCount + count);
            }
            // get grid to refresh the data
            gridOptions.api.refreshInfiniteCache();
        }
        function insertItemsAt2(count) {
            var newDataItems = [];
            for (var i = 0; i < count; i++) {
                var newItem = createRowData(sequenceId++);
                allOfTheData.splice(2, 0, newItem);
                newDataItems.push(newItem);
            }
            return newDataItems;
        }
        onGridReady: function (params) {
            sequenceId = 1;
            allOfTheData = [];
            for (var i = 0; i < 1000; i++) {
                allOfTheData.push(createRowData(sequenceId++));
            }
        },
        */





        $(document).ready(function() {
            
           //init page
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $('select.select2').select2({ theme: "bootstrap" });
            /*$.ajaxSetup({
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            }); */
           
            
            //load data
            //loaddata(post);
            //$.ajax({url: "http://localhost:8000/ajax_getProduct/C-11", 
            /*$.ajax({url: "{{ url('ajax_getProduct') }}/{{$id}}", 
                success: function(resp){
                    var res=JSON.parse(resp); 
                    //alert(res.status);
                    res=res.data;
                    //console.log(res);
                    $.each(res, function( f, v ) {
                        $("input[name='"+f+"']").val(v);
                    })
                }
            });*/

            //var mydata = {!!$griddata!!}
            var mydata = [
                {"TransNo":"PI.1800001","InvNo":"","ProductCode":"DMO-22INC-NL-POLOS","ProductName":"DMO 22\" 51959 NL POLOS","Qty":"150.00","UOM":"","Price":"10455.00","DiscPercentD":0,"Cost":"10455.00","Memo":"","trans_id":1583,"id":145},
                {"TransNo":"PI.1800001","InvNo":"","ProductCode":"DMO-24INC-NL-POLOS","ProductName":"DMO 24\" 51959 NL POLOS","Qty":"3200.00","UOM":"","Price":"10909.00","DiscPercentD":0,"Cost":"10909.00","Memo":"","trans_id":1583,"id":146},
                {"TransNo":"PI.1800001","InvNo":"","ProductCode":"DMO-26INC-NL-POLOS","ProductName":"DMO 26\" 51959 NL POLOS","Qty":"150.00","UOM":"","Price":"11365.00","DiscPercentD":0,"Cost":"11365.00","Memo":"","trans_id":1583,"id":147},
                {"TransNo":"PI.1800001","InvNo":"","ProductCode":"DMO-30INC-NL-POLOS","ProductName":"DMO 30\" 51959 NL POLOS","Qty":"500.00","UOM":"","Price":"12273.00","DiscPercentD":0,"Cost":"12273.00","Memo":"","trans_id":1583,"id":148}
            ];
            

            //init jqgrid
            //demo: http://trirand.com/blog/jqgrid/jqgrid.html
            var colModel = [
                { field:'checkboxBtn',headerName:'', checkboxSelection:true,headerCheckboxSelection:true,pinned:'left',width:50},
                //{ field: "ProductCode", headerName: 'Product #', editable:true, edittype:'text', width: 270 },
                { field: "ProductCode", headerName: 'Product #', editable:true, edittype:'text', width: 270, 
                    //cellRenderer:'btnCellRenderer',
                    cellRenderer:function(row)  {
                        //console.log(row)
                        return row.value+'  <button type="button" line='+row.rowIndex+'">...</button>';
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
                //{ field: "Amount", headerName: 'Amount', width: 120, format: 'Rp.##,###.00' },
                //{ headerName: 'Amount', valueGetter: 'getValue("Qty")*getValue("Price")' },
                { headerName: 'Amount', valueGetter: '"Rp. "+data.Qty*data.Price' },
                //{ index: "cmd", name: "cmd", dataType: 'float', label: 'CMD', width: 120, editable: false },
                { headerName: '',  cellRenderer: function(row)  {
                        return `<button type='button' class='delete_btn' onclick='grid_delrow(${row.rowIndex})'>Delete</button>`;
                    }, 
                },
                /*{ title: "", editable: false, minWidth: 165, sortable: false,
                        render: function (ui) {
                            return "<button type='button' class='edit_btn'>Edit</button>\
                                <button type='button' class='delete_btn'>Delete</button>";
                        },
                        postRender: function (ui) {
                            var rowIndx = ui.rowIndx,
                                grid = this,
                                $cell = grid.getCell(ui);

                            //delete button
                            $cell.find(".delete_btn").button({ icons: { primary: 'ui-icon-close'} })
                            .bind("click", function (evt) {
                               alert('deelte row');
                                deleteRow(rowIndx, grid);
                            });

                            //edit button
                            $cell.find(".edit_btn").button({ icons: { primary: 'ui-icon-pencil'} })
                            .bind("click", function (evt) {
                                if (isEditing(grid)) {
                                    return false;
                                }
                                editRow(rowIndx, grid, true);
                            });

                            //if it has edit class, then edit the row.
                            if (grid.hasClass({ rowData: ui.rowData, cls: 'pq-row-edit' })) {
                                editRow(rowIndx, grid);
                            }
                        }
                    } */
        ];

        
        
        var gridOptions = {
            columnDefs: colModel,
            rowData: mydata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            //width: '100%', //width of grid
            //height: 400, //height of grid
            //onSelectRow: editRow,
            //editurl : 'clientarray', 
            //datatype: 'local',
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
                btnCellRenderer: BtnCellRenderer,
                //numericCellEditor: NumericEditor,
                //moodCellRenderer: MoodRenderer,
                //moodEditor: MoodEditor,
            },
        }
        var xgd =  document.querySelector('#xgrid');
        new agGrid.Grid(xgd, gridOptions);
        /*document.addEventListener('DOMContentLoaded', function () {
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
        });*/


        


        //add new line
        $("button#cmAddrow").click(function(e){
            //demo: https://www.ag-grid.com/examples/infinite-scrolling/insert-remove/packages/vanilla/index.html
            //demo: https://www.ag-grid.com/javascript-data-grid/data-update-transactions/
            alert('add new line');
            var newLine = { ProductCode:'new product', ProductName:'new product name', Qty:123, Price:1000 }
            
            //mydata.push(newLine)
            //gridOptions.api.setRowData(mydata);

            gridOptions.api.applyTransaction({ add: [newLine] });
        });
        //del line
        $(".delete_btn").click(function(e){
            var ln = $(this).attr('line');
            //alert('delte row '+ln[0]);
            //var gr = 1;
            //$("#xgrid").jqGrid('delGridRow',gr,{reloadAfterSubmit:false});
            //$("#xgrid").jqGrid('delRowData', ln);
            //mydata.splice(ln, 1);
            //option.api.refreshInfiniteCache();
        });
        //delete row selected 
        $("button#cmDelrow").click(function(e){
            alert('del selected row');
            var selRows = gridOptions.api.getSelectedRows() 
            gridOptions.api.applyTransaction({ remove: selRows });
            //gridOptions.api.refreshInfiniteCache();
        });
        $("button#cmSubmit").click(function(e){
            alert('submit all data');
            //console.log(mydata);
            const rowData = [];
            gridOptions.api.forEachNode(function (node) {
                rowData.push(node.data);
            });
            console.log(rowData);
        });

        


        //save data
        //original
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
            //using procedure, tapi tidak ada output
            /*$("button#cmSave").click(function(e){
               e.preventDefault();
               var url='http://localhost:500/api/datasave_product';
               Ajax_post(url, 'formData');
               console.log(res);
	         });*/

            
            /*var dataSource= "http://localhost:8000/ajax_getProduct/C-11";
            $.getJSON(dataSource, function(data, status) {
                for(var row=0;row<data.length;row++) {
                    console.log(data);
                }
            })  */
            
            //cmSave click
            //$('button#cmxSave').click(function() {
                //console.log('Saving ....');
                /*var dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i>Saving, Please wait ...</p>',
                    closeButton: false
                });*/
             //});


                
               
                //Ajax_post( '{{env('API_URL')}}/api/product/datasave', 'formData');
               //var url= '{{env('API_URL')}}/api/datasave/product';
               
               /*$('form').submit(function(ee) {
                  var url= '//datasave_product';
                  var formId= 'formData';
                  var formdata=$("#"+formId).serialize();
                  $.ajax({
                     url: 'http://localhost:8000/datasave_product', 
                     data: formdata, 
                     type: 'POST', 
                     dataType: 'json',
                     success: function (e) {
                        console.log(JSON.stringify(e));
                        app.locals='OK'
                        return 'OK'
                     },
                     error:function(e){
                        console.log(JSON.stringify(e));
                        //return JSON.stringify(e)
                        return 'ERROR'
                     }
                  })
                  ee.preventDefault();
               });*/
               
				// modal populate
            // inser modal here
                
            //tbLookup Event
            /*$('input[type=lookup]').change(function() {
                var nm=$(this).attr('name');
                var find=$(this).val();
                var row='';
                for(var a=0;a<mcoa.length;a++) {
                    row=mcoa[a];
                    if(row.AccNo==find) break;
                }
                $('#label-'+nm).text(row.AccName); 
                //alert(row.AccName);
            }) */
        });
    </script>

    
@stop

