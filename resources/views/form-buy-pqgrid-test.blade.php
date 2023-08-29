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
                <h3><i class="fa fa-check-square-o"></i> TEST - General data</h3>
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
                { title: "", editable: false, minWidth: 165, sortable: false,
                        render: function (ui) {
                            return "<button type='button' line="+ui.rowIndx+" class='edit_btn'>Edit</button>\
                                <button type='button' line="+ui.rowIndx+" class='delete_btn'>Delete</button>";
                        },
                        /*postRender: function (ui) {
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
                        }*/
                }
            ];
            var option = {
                colModel: colModel,
                dataModel: { data: {!!$griddata!!} },
                bootstrap: { on: true },
                showHeader: true,
                showBottom: true,
                rowBorders: true,
                columnBorders: true,
                title: 'Grid Order',
                width: '100%', //width of grid
                height: 400, //height of grid
                xxxcellSave: function (e,ui) {
                    var amount = (ui.rowData.Qty * ui.rowData.Price)
                    this.updateRow({
                        rowIndx: ui.rowIndx,
                        newRow: {
                            Amount : amount, 
                            ProductCode : ui.rowData.ProductCode,
                            ProductName : ui.rowData.ProductName,
                            Qty : ui.rowData.Qty,
                            Price : ui.rowData.Price,
                        },
                        checkEditable:false
                    })
                },
            }
            var xgd = $('#xgrid').pqGrid(option);

            //add new line
            $("button#cmAddrow").click(function(e){
                //alert('add new line');
                var newLine = {ProductCode: 'newcode', ProductName: 'Colgate',Qty:123 };
                xgd.pqGrid( "addRow", { newRow: newLine } ); 
            });
            //del line
            //$(".delete_btn").click(function(e){
            var ln=1;
            $(".delete_btn").on("click", function(){
                var ln = $(this).attr('line');
                alert('delte row '+ln);
                $('#xgrid').pqGrid( "deleteRow", { rowIndx: ln } );
                //$('#xgrid').pqGrid( "removeData", {rowIndx: ln});
                ln=ln+1;

                //xgd.pqGrid("commit");
                //xgd.pqGrid("refreshDataAndView");
                //$( "#xgrid" ).pqGrid( "refreshRow", {rowIndx:ln} );
                //$( "#xgrid" ).pqGrid( "refresh" );
                //$( "#xgrid" ).pqGrid( "commit" );
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
            
        });

        
    </script>
@stop

