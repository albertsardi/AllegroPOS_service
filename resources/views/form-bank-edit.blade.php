@extends('temp-master')

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'bank') }}   
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3>
            </div>
            <div class="card-body">
                {{ Form::text('AccNo', 'Account #', $data->AccNo,['placeholder'=>'ID']) }}
                {{ Form::text('BankAccNo', 'Bank Account #', $data->BankAccNo,['placeholder'=>'ID']) }}
                {{ Form::text('BankAccName', 'Bank Account Name', $data->BankAccName) }}
                {{ Form::text('BankId', 'Bank Name', $data->BankId) }}
                {{ Form::select('BankType', 'Bank Type', $mCat, $data->BankTypey) }}
                {{-- {{ Form::select('Level', 'Level', $mLevel, $data->Level) }} --}}
                {{-- {{ Form::text('Posting', 'Posting', $data->Posting) }} --}}
                {{-- {{ Form::number('OpenAmount', 'Open Amount') }} --}}
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
                {{-- {{ Form::textwlookup('AccLInk', 'Account link #', 'modal-account') }} --}}
                {{ Form::text('Amount', 'Amount', $data->Amount) }}
                {{ Form::text('Memo', 'Memo', $data->Memo) }}
            </div>
        </div><!-- end card-->
    </div>
    </form>
@stop

@section('modal')
   @include('modal.modal-account') 
@stop
                    
@section('js')
    <script>
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
            $('#listCoa').DataTable({
               //processing: true,
               //serverSide: true,
               paging: true,
               pageLength: 10,
               //pagingType: "full_numbers",
               ajax: "{{env('API_URL')}}/api/coa",
               columns: [
                  {
                     data: null,
                     render: function (data, type, row) {
                        return "<a href=''>" + data['AccNo'] + "</a>";
                     }
                  },
                  { data: 'AccName' }
               ]
            });
                
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

