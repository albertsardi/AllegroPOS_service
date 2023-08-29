@extends('temp-master')

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData'>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'coa') }}
    {{ Form::hidden('id', 'id') }}
    {{ Form::hidden('Token', session('token')) }}
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3>
            </div>
            <div class="card-body">
                {{-- {{ Form::text('AccNo', 'Account #', $data->AccNo,['placeholder'=>'ID']) }} --}}
                @if($id=='new')
                    {{ Form2::text('AccNo', 'Account #' ) }}
                @else
                    {{ Form2::text('AccNo', 'Account #', ['readonly'=>true] ) }}
                @endif
                {{ Form2::text('AccName', 'Account Name', $data->AccName??'') }}
                {{ Form2::select('CatName', 'Category', $select->selAccountCategory??[] ) }}
                {{-- {{ Form2::select('Level', 'Level', $select->selAccountLevel??[] ) }} --}}
                {{-- {{ Form2::text('Posting', 'Posting', $data->Posting??'') }} --}}
                {{ Form2::number('OpenAmount', 'Open Amount') }}
                {{ Form2::text('Memo', 'Memo', $data->Memo??'') }}
            </div>
        </div><!-- end card-->
    </div>

    <!-- PANEL2 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-none">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> Other data</h3>
            </div>
            <div class="card-body">
                {{-- {{ Form::textwlookup('AccLInk', 'Account link #', 'modal-account') }} --}}
                {{ Form::textwlookup('AccLink', 'Account link #', 'modal-account') }}
                {{ Form::text('Memo', 'Memo', $data->Memo??'') }}
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
    <script src="{{ asset('asset/js/modal-account.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_form.js') }}" type="text/javascript"></script>
    <script>
        // modal global variable
        {{-- var mCoa = {!! $mCoa !!} //init modal data --}}

        $(document).ready(function() {
            //init page
            //$(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            //$('select.select2').select2({ theme: "bootstrap" });
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
                    ////console.log(res);
                    $.each(res, function( f, v ) {
                        $("input[name='"+f+"']").val(v);
                    })
                }
            });*/

            //save data
            $("button#cmSave").click(async function(e){ //using ajax
                e.preventDefault();
                var formdata=$('form').serialize();
                //var result = await $('form').formsave('{{$jr}}', '{{$id}}' );

                var id = '{{$id}}';
                var resp = await axios.post(window.API_URL+"/api/{{$jr}}/save/"+id, formdata);
                console.log(resp)
                if (resp.status==200) {
                    console.log(resp.data)
                    if (resp.data.status=='Error') {
                        alert('Error:: '+resp.data.message);
                    } else {
                        alert('datasave.');
                        if (id=='new' || id=='') window.location.href = window.location.href.replace("/new", "/"+resp.data.data.id);
                    }
                } else {
                    alert('Error')
                    console.log(resp)
                    
                }
            });

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
        })

        function afterLookupClose(e) {
            //console.log(e.lookup_id)
            if (e.lookup_id == 'modal-customer') {
                //alert('afterLookupClose inside')
                ////console.log(selRow)
                var btn = modal_target_button;
                ////console.log(btn.attr('id'))
                $('#'+btn.attr('id')).textwlookup(selRow.AccCode, selRow.AccName)
            }
            if (e.lookup_id == 'modal-product') {
                //console.log(e)
                // alert('afterLookupClose inside')
                ////console.log(selRow)
                ////console.log(selRowIdx)
                //selRowIdx=0;
                //mydata[selRowIdx].InvNo = selRow.TransNo;
                //mydata[selRowIdx].InvDate = selRow.TransDate;
                //mydata[selRowIdx].InvTotal = selRow.Total;
                //mydata[selRowIdx].AmountPaid = selRow.InvPaid;
                mydata[selRowIdx].ProductCode = selRow.Code
                //mydata[selRowIdx] = selRow;
                gridOptions.api.setRowData(mydata);
            }
        };
    </script>
@stop

