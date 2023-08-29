//========================
// Lookup function for FOrm and AG_Grid
// using jQuery
//========================

function agGrid_getIndex(gridOptions, find) {
    var idx = 0;
    var rowData = gridOptions.rowData
    for (let row of rowData) {
        if (row.ProductCode == find) return idx;
        idx++;
    }
    return -1;
}

$(function () {
    $(document).ready(function () {
        $("#listProduct a.lookup-item").click(function (e) {
            console.log('ddd')
            e.preventDefault();
            e.lookup_id = $(this).closest('div.modal').attr('id')
            //var itm = $(this).text(); //get modal id

            //search lookup data
            //selRow = mProduct[ $(this).attr('rowIdx') ];

            e.selRow = $(this).data('drow').toString().split('|')
            
            $('div.modal').modal('hide')
            grid_afterLookupClose(e);
        });
    });

    $('.modal').on('show.bs.modal', function (e) {
        lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
    })
});

