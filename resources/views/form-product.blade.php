@extends('temp-master')

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'product') }}   
      <!-- PANEL1 -->
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3>
            </div>
            <div class="card-body">
                {{ Form::text('Code', 'Product #', ['placeholder'=>'ID']) }}
                {{ Form::text('Name', 'Product Name') }}
                {{ Form::text('Barcode', 'Barcode') }}
                {{ Form::select('Category', 'Category', $mCat) }}
                {{ Form::select('Type', 'Type', $mType) }}
                {{-- {{ Form::select('HppBy', 'HPP', $mHpp, $data->HppBy) }} --}}
                {{ Form::checkbox('ActiveProduct', 'Active Product') }}
                {{ Form::checkbox('StockProduct', 'Have Stock') }}
                {{ Form::checkbox('canBuy', 'Product can buy') }}
                {{ Form::checkbox('canSell', 'Product can sell') }}
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
                {{ Form::text('UOM', 'Main Unit', $data->UOM) }}
                {{ Form::text('ProductionUnit', 'Production Unit') }}
                {{ Form::number('MinStock', 'Minimal Stock') }}
                {{ Form::number('MaxStock', 'Maximal Stock') }}
                {{ Form::number('SellPrice', 'Sell Price') }}
                {{ Form::number('LastBuyPrice', 'Last Buy Price',['disabled'=>true]) }}
                <br/><br/><br/><br/>
                {{ Form::textwlookup('AccHppNo', 'HPP Account No', 'modal-account') }}
                {{ Form::textwlookup('AccSellNo', 'Income Account No', 'modal-account') }}
                {{ Form::textwlookup('AccInventoryNo', 'Inventory Account No', 'modal-account') }}
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   @include('modal.modal-account') 
@stop
                    
@section('js')
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-account.js') }}" type="text/javascript"></script>
    <script>
        // modal global variable
        //var mCoa = {!! $mCoa !!} //init modal data

        $(document).ready(function() {
            //init page
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $('select.select2').select2({ theme: "bootstrap" });
            /*$.ajaxSetup({
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            }); */
           
            //save data
            //original
            /*
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
	         }); */
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
            /*$('#listCoa').DataTable({
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
            });*/
                
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
            /*$('#modal-account').on('show.bs.modal', function (e) {
            //$('.modal').on('show.bs.modal', function (e) {
                //alert('show modal')
                modal_target_button = $(e.relatedTarget) // Button that triggered the modal
                console.log(modal_target_button)
            })*/
        });

        /*function afterLookupClose(e) {
            var btn = modal_target_button;
            $('#'+btn.attr('id')).textwlookup(selRow.AccNo, selRow.AccName)
        }; */

        
    </script>
    
    
@stop

