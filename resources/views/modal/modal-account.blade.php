<div class="modal fade" id="modal-account" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="listCoa" class="table table-bordered table-hover display mx-10 w-100">
			<thead>
				<th>Account #</th><th>Account Name</th><th>Account Category</th>
			</thead>
			<tbody></tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>

<script>
  $('#listCoa').DataTable({
    paging: true,
    pageLength: 10,
    pagingType: "full_numbers",
    data: {!! $mAccount !!},
    columns: [
        {
            data: null,
            render: function (data, type, row, sett) {
                var drow = `${row.AccNo}|${row.AccName}|${row.CatName}`;
                return `<a href='' class='lookup-item' rowIdx=${sett.row} data-drow='${drow}'>`+ data['AccNo'] +`</a>`;
            }
        },
        { data: 'AccName' },
        { data: 'CatName' }
    ]
});
</script>

