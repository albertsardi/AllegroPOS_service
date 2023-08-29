@extends('temp-master')

@section('content')
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <div class="card mb-3">
            <div class="card-header">
                <h3><i class="fa fa-check-square-o"></i> General data</h3> 
            </div>
            <div class="card-body">
                {{ Form::text('AccCode', 'ID Account',['placeholder'=>'ID']) }}
                {{ Form::text('AccName', 'Name') }}
                {{ Form::combo('Category', 'Category') }}
                {{ Form::combo('Salesman', 'Salesman') }}
                {{ Form::text('Memo', 'Memo') }}
                {{ Form::checkbox('Active', 'Active Customer') }}
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
                {{-- Form::combo('Code', 'Address Code', $mAddr) --}}
                {{ Form::checkbox('DefAddr', 'Default Address') }}
                {{ Form::text('Address', 'Address') }}
                {{ Form::text('Zip', 'Postal Code') }}
                {{ Form::text('ContachPerson', 'Contach Person') }}
                {{ Form::text('Phone', 'Phone') }}
                {{ Form::text('Fax', 'Fax') }}
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
                    {{ Form::textwlookup('AccNo', 'Account No.', ['modal' => 'modal-account', 'width' => '3']) }}
                </div>
                <!-- tab 2-->
                <div class="tab-pane fade" id="nav-tax" role="tabpanel" aria-labelledby="nav-tax-tab">
                    {{ Form::text('Taxno', 'Tax No#') }}
                    {{ Form::text('TaxName', 'Tax Name') }}
                    {{ Form::text('TaxAddr', 'Tax Address') }}
                </div>
            </div>
        </div><!-- end card-->
    </div>
</div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            $.ajaxSetup({
                async: false
            });
            
            //load data
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
            });
            
            //cmSave click
            $('button#cmSave').click(function() {
                //alert('Save');
                var data = $('form').serialize();
                $.post( 'datasave_customer', data, function(res) {
                    console.log(res);
                    $('#result').text( res );
                    /*bootbox.alert({
                        message: data, backdrop:true
                    });*/
                    //$('#pop').show();
                    //if(data!='') $('.alert').visible();
                    //$('#info2').text( data );
                    //$(this).text('Save');
                    // save dialog
                    //dialog.modal('hide');
                });
                //dialog.modal('hide');
            })

            //tbLookup Event
            $('input[type=lookup]').change(function() {
                var nm=$(this).attr('name');
                var find=$(this).val();
                var row='';
                for(var a=0;a<mcoa.length;a++) {
                    row=mcoa[a];
                    if(row.AccNo==find) break;
                }
                $('#label-'+nm).text(row.AccName); 
                //alert(row.AccName);
            }) 
        });
    </script>
@stop






