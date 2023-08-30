<div class="modal fade" id="modal-salesorder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="listSO" class="table table-bordered table-hover display mx-10 w-100">
			<thead>
				<th>Sales Order #</th><th>Date</th><th>Customer</th><th>Amount</th>
			</thead>
			<tbody></tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $('#listSO').DataTable({
    paging: true,
    pageLength: 10,
    pagingType: "full_numbers",
    data: {!! json_encode($mSO) !!},
    columns: [
        {
            data: null,
            render: function (data, type, row, sett) {
                var drow = `${row.TransNo}|${row.TransDate}|${row.AccName}|${row.AccCode}|${row.DeliveryTo}`;
                return `<a href='' class='lookup-item' rowIdx=${sett.row} data-drow='${drow}'>`+ data['TransNo'] +`</a>`;
            }
        },
        { data: 'TransDate' },
        { data: 'AccName' },
        { data: 'Total' },
    ]
});
</script>






