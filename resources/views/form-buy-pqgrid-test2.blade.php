@extends('temp-master')

@section('css')
    {{-- <link href="{{ asset('assets/plugin/jquery-ui/themes/smoothness/jquery-ui.css') }}" rel="stylesheet" type="text/css" > --}}
    <link href="{{ asset('assets/plugin/pqgrid/pqgrid.min.css') }}" rel="stylesheet" type="text/css" >
    {{-- <link href="{{ asset('assets/plugin/pqgrid/themes/bootstrap/pqgrid.css') }}" rel="stylesheet" type="text/css" > --}}
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
                <h3><i class="fa fa-check-square-o"></i> TEST2 - General data</h3>
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
                {{-- {{ Form::textwlookup('AccHppNo', 'HPP Account No', 'modal-account') }} --}}
                {{-- {{ Form::select('Category', 'Category', $mCat, $data->Category) }} --}}
                <div id="xgrid" style="xmargin:auto;"></div>
            </div>
            <div class="card-footer">
                <button id='cmAddrow' type='button' >Add new line</button>
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
    <script src="{{ asset('assets/plugin/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugin/pqgrid/pqgrid.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
           //init page
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $('select.select2').select2({ theme: "bootstrap" });
            
            //init pqgrid
            //demo: http://www.ok-soft-gmbh.com/jqGrid/ActionButtons.htm
            //demo: https://paramquery.com/demos
            //demo: http://jsfiddle.net/akasthuri/f75kxkx1/6/
            var colModel = [
                { dataIndx: "ProductCode", title: 'Product #', width: 270 },
                { dataIndx: "ProductName", title: 'Product Name', width: 270,  editable: false },
                { dataIndx: "Qty", dataType: 'integer', title: 'Quantity', width: 80 },
                { dataIndx: "Price", dataType: 'float', title: 'Price', width: 120 },
                { dataIndx: "Amount", dataType: 'float', title: 'Amount', width: 120, editable: true, format: 'Rp.##,###.00',
                /*formula: function(ui) {
                    return ui.rowData.Price * ui.rowData.Qty;
                } ,
                render: function(ui) {
                    console.log(ui.rowData.Price)
                    console.log(ui.rowData.Qty)
                    return (ui.rowData.Price * ui.rowData.Qty);
                } */
                },
                { title: "", editable: false, minWidth: 83, sortable: false,
                    render: function (ui) {
                        return "<button type='button' class='delete_btn'>Delete</button>";
                    },
                    postRender: function (ui) {
                        var rowIndx = ui.rowIndx,
                            grid = this,
                            $cell = grid.getCell(ui);

                        $cell.find("button").button({ icons: { primary: 'ui-icon-scissors'} })
                        .bind("click", function () {

                            grid.addClass({ rowIndx: ui.rowIndx, cls: 'pq-row-delete' });

                            var ans = window.confirm("Are you sure to delete row No " + (rowIndx + 1) + "?");
                            grid.removeClass({ rowIndx: rowIndx, cls: 'pq-row-delete' });
                            if (ans) {
                                grid.deleteRow({ rowIndx: rowIndx });
                            }
                        });
                    }
                }
            ];
            var option = {
                hwrap: false,     
                virtualX: true,       
                rowBorders: false,                        
                trackModel: { on: true }, //to turn on the track changes.            
                toolbar: {
                    items: [
                        { type: 'button', icon: 'ui-icon-plus', label: 'New Product', listener: function () {
                                //append empty row at the end.                            
                                var rowData = { ProductID: 34, UnitPrice: 0.2 }; //empty row
                                var rowIndx = grid.addRow( { rowData: rowData, checkEditable: true });
                                grid.goToPage({ rowIndx: rowIndx });
                                grid.editFirstCellInRow({ rowIndx: rowIndx });                        
                            }
                        },
                        { type: 'separator' },
                        { type: 'button', icon: 'ui-icon-disk', label: 'Save Changes', cls: 'changes', listener: function () {
                                saveChanges();
                            },
                            options: { disabled: true }
                        },
                        { type: 'button', icon: 'ui-icon-cancel', label: 'Reject Changes', cls: 'changes', listener: function () {
                                grid.rollback();
                                grid.history({ method: 'resetUndo' });                        
                            },
                            options: { disabled: true }
                        },
                        { type: 'button', icon: 'ui-icon-cart', label: 'Get Changes', cls: 'changes', listener: function () {
                                var changes = grid.getChanges({ format: 'byVal' });
                                if( console && console.log) {
                                    console.log(changes);
                                }                            
                                alert("Please see the log of changes in your browser console.");                        
                            },
                            options: { disabled: true }
                        },
                        { type: 'separator' },
                        { type: 'button', icon: 'ui-icon-arrowreturn-1-s', label: 'Undo', cls: 'changes', listener: function () {
                                grid.history({ method: 'undo' });
                            },
                            options: { disabled: true }
                        },
                        { type: 'button', icon: 'ui-icon-arrowrefresh-1-s', label: 'Redo', listener: function () {
                                grid.history({ method: 'redo' });                        
                            },
                            options: { disabled: true }
                        }
                    ]
                },
                scrollModel: {
                    autoFit: true
                },           
                swipeModel: { on: false },
                editor: {
                    select: true
                },
                title: "<b>Batch Editing</b>",
                history: function (evt, ui) {
                    var $tb = this.toolbar();
                    if (ui.canUndo != null) {
                        $("button.changes", $tb).button("option", { disabled: !ui.canUndo });
                    }
                    if (ui.canRedo != null) {
                        $("button:contains('Redo')", $tb).button("option", "disabled", !ui.canRedo);
                    }
                    $("button:contains('Undo')", $tb).button("option", { label: 'Undo (' + ui.num_undo + ')' });
                    $("button:contains('Redo')", $tb).button("option", { label: 'Redo (' + ui.num_redo + ')' });
                },
                colModel: colModel,
                dataModel: { data: {!!$griddata!!} },
                postRenderInterval: -1, //call postRender synchronously.
                pageModel: { type: "local", rPP: 20 },
                create: function(){
                    this.widget().pqTooltip();
                },
            };
            var grid = $('#xgrid').pqGrid(option);
            var xgd = grid;

            //add new line
            $("button#cmAddrow").click(function(e){
                //alert('add new line');
                var newLine = {ProductCode: 'newcode', ProductName: 'Colgate',Qty:123 };
                xgd.pqGrid( "addRow", { newRow: newLine } ); 
            });
            //del line
            
            function saveChanges() {            
                //attempt to save editing cell.
                alert('saving/...')
                //if (grid.saveEditCell() === false) { return false; }
                if (grid.pqGrid('saveEditCell') === false) { return false; }
                alert('save2')
                //if ( grid.isDirty() && grid.isValidChange({ focusInvalid: true }).valid ) {
                if ( grid.pqGrid('isDirty') && grid.pqGrid('isValidChange', { focusInvalid: true }).valid )  {
                    alert('save3')
                    //var changes = grid.getChanges({ format: "byVal" });
                    var changes = grid.pqGrid('getChanges',{ format: "byVal" });
                    console.log(changes)//post changes to server 
                    $.ajax({
                        dataType: "json",
                        //type: "POST",
                        type: "GET",
                        async: true,
                        beforeSend: function (jqXHR, settings) {
                            //grid.showLoading();
                            grid.pqGrid('showLoading');
                        },
                        url: "pro/products/batch", //for ASP.NET, java      
                        //url: "/pro/products.php", //for PHP                                          
                        data: { list: JSON.stringify(changes) },
                        success: function (changes) {
                            console.log (JSON.stringify(changes));
                            //debugger;
                            grid.pqGrid('commit', { type: 'add', rows: changes.addList });
                            grid.pqGrid('commit', { type: 'update', rows: changes.updateList });
                            grid.pqGrid('commit', { type: 'delete', rows: changes.deleteList });

                            grid.pqGrid('history', { method: 'reset' });
                        },
                        complete: function () {
                            grid.pqGrid('hideLoading');
                        }
                    });
                }
            }

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
            
        });

        
    </script>
@stop

