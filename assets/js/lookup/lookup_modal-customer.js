//========================
// Lookup function for FOrm and AG_Grid
// using jQuery
//========================

function agGrid_getIndex(gridOptions, find) {
    var idx = 0;
    var rowData = gridOptions.rowData
    for (let row of rowData) {
        if (row.InvNo == find) return idx;
        idx++;
    }
    return -1;
}



$(function () {
    $(document).ready(function () {
        $("#listCustomer a.lookup-item").click(function (e) {
            e.preventDefault();
            e.lookup_id = $(this).closest('div.modal').attr('id')
            var itm = $(this).text(); //get modal id

            //search lookup data
            selRow = mCustomer[ $(this).attr('rowIdx') ];
            
            $('div.modal').modal('hide')
            grid_afterLookupClose(e);
        });

        $('.modal').on('show.bs.modal', function (e) {
            modal_target_button = $(e.relatedTarget) // Button that triggered the modal
        })
    });
});

