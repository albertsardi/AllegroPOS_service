@extends('temp-master')

@section('content')
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'supplier') }}   
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3> 
            </div>
            <div class="card-body">
                {{ Form::text('AccCode', 'ID Account', $data->AccCode, ['placeholder'=>'ID']) }}
                {{ Form::text('AccName', 'Name', $data->AccName ) }}
                {{ Form::select('Category', 'Category', $mCat, $data->Category ) }}
                {{ Form::select('Salesman', 'Salesman', $mSalesman, $data->Salesman ) }}
                {{ Form::text('Memo', 'Memo', $data->Memo ) }}
                {{ Form::checkbox('Active', 'Active Customer', $data->Active ) }}
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
                {{ Form::combo('Code', 'Address Code', $mAddr) }}
                {{ Form::checkbox('DefAddr', 'Default Address') }}
                {{ Form::text('Address', 'Address', $data->Address->Address ) }}
                {{ Form::text('Zip', 'Postal Code', $data->Address->Zip ) }}
                {{ Form::text('ContachPerson', 'Contach Person', $data->Address->ContachPerson ) }}
                {{ Form::text('Phone', 'Phone', $data->Address->Phone ) }}
                {{ Form::text('Fax', 'Fax', $data->Address->Fax ) }}
            </div>
        </div><!-- end card-->
    </div>
@stop

@section('tab')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa fa-square-o"></i> Account Data</h3>
        </div>
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-acc-tab" data-toggle="tab" href="#nav-acc" role="tab" aria-controls="nav-acc" aria-selected="false">Account</a>
                    <a class="nav-item nav-link" id="nav-tax-tab" data-toggle="tab" href="#nav-tax" role="tab" aria-controls="nav-tax" aria-selected="false">Tax</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- tab 1-->
                <div class="tab-pane fade show active" id="nav-acc" role="tabpanel" aria-labelledby="nav-acc-tab">
                    {{ Form::textwlookup('AccNo', 'Account No.', 'modal-account', ['width' => '3']) }}
                </div>
                <!-- tab 2-->
                <div class="tab-pane fade" id="nav-tax" role="tabpanel" aria-labelledby="nav-tax-tab">
                    {{ Form::text('Taxno', 'Tax No#', $data->Taxno ) }}
                    {{ Form::text('TaxName', 'Tax Name', $data->TaxName ) }}
                    {{ Form::text('TaxAddr', 'Tax Address', $data->TaxAddr ) }}
                </div>
            </div>
        </div><!-- end card-->
    </div>
</div>
@stop

@section('modal')
   @include('modal.modal-account') 
@stop

@section('js')
    {{-- <script src="{{ asset('resources/js/modal-account.js') }}" type="text/javascript"></script> --}}
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-account.js') }}" type="text/javascript"></script>
    <script>
        // modal global variable
        var mCoa = {!! $mCoa !!} //init modal data

        $(document).ready(function() {
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
				$('select.select2').select2({ theme: "bootstrap" });
            $.ajaxSetup({
                async: false
            });
            
            //load data
            /*
            $.ajax({url: "{{ url('ajax_getCustomer') }}/{{$id}}", 
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
            
            //cmSave click
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
                //dialog.modal('hide');
            });


            //tbLookup Event
            /* $('input[type=lookup]').change(function() {
                var nm=$(this).attr('name');
                var find=$(this).val();
                var row='';
                for(var a=0;a<mcoa.length;a++) {
                    row=mcoa[a];
                    if(row.AccNo==find) break;
                }
                $('#label-'+nm).text(row.AccName); 
                //alert(row.AccName);
            })  */

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
        });

        function afterLookupClose(e) {
            console.log(e.lookup_id)
            if (e.lookup_id == 'modal-account') {
                var btn = modal_target_button;
                $('#'+btn.attr('id')).textwlookup(selRow.AccNo, selRow.AccName)
            }
        };
    </script>
@stop






