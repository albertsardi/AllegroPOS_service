<div class="modal fade" id="modal-addpayment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pay Invoice</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id='formPaymentData' action='{{ url('transpaymentsave') }}' method='POST'>
      <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <input type="hidden" name="jr" value="{{ $data->jr ?? ''}}" />
      <div class="modal-body">
        
        
        {{ Form::label('PayAccName', 'Customer', $mSO[0]->AccName ?? '') }}
        {{ Form::number('PayAmount', 'Pay Amount', 20000) }}
        {{ Form::label('PayAccNo', 'Bank Account', $data->Acc->AccNo??'') }}

        {{ Form::date('PayDate', 'Date', $data->PayDate??'') }}
        {{ Form::label('PayInvNo', 'Invoice #', $data->TransNo??'') }}
        
      
      </div>
      <div class="modal-footer">
        <button id="cmPaySave" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </form>    
    </div>
  </div>
</div>

<script>
</script>






