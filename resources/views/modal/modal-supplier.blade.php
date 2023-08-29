<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="listSupplier" class="table table-bordered table-hover display mx-10 w-100">
			<thead>
				<th>Supplier #</th><th>Name</th><th>Category</th>
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
    $('#listSupplier').DataTable({
        paging: true,
        pageLength: 10,
        pagingType: "full_numbers",
        data: {!! $mSupplier !!},
        columns: [
            {
                data: null,
                render: function (data, type, row, sett) {
                    var drow = `${row.AccCode}|${row.AccName}|${row.Category}`;
                    return `<a href='' class='lookup-item' rowIdx=${sett.row} data-drow='${drow}'>`+ data['AccCode'] +`</a>`;
                }
            },
            { data: 'AccName' },
            { data: 'Category' }
        ]
    }); 
</script>







