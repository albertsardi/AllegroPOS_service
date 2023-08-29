<div class="modal fade" id="modal-setting-unit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id='list'>
            <button type="button" class="btn btn-sm btn-outline-primary float-right">+ Add New</button>
            <table id="listProductUnit" class="table table-bordered table-hover display mx-10 w-100">
                <thead>
                    <th>Name</th><th>Quantity</th><th> </th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="form-input" class="d-none">
            {{-- {{ Form::text('Code', 'Product #', '$data->Code', ['placeholder'=>'input']) }} --}}
            {{ Form::text('input-unit-name', 'Name', '', ['placeholder'=>'input']) }}
            {{ Form::text('input-unit-id', 'ID', '', ['readonly'=>true]) }}
        </div>
      </div>
      <div class="modal-footer">
        <button id="input-save" type="button" class="btn btn-primary d-none">Save changes</button>
        <button id="input-close" type="button" class="btn btn-secondary d-none">Close changes</button>
        <button id="btn-close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>












