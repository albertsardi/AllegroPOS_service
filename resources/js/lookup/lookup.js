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
        $("a.lookup-item").click(function (e) {
            // https://stackblitz.com/edit/angular-ag-grid-button-renderer?file=src/app/app.component.ts
            e.preventDefault();
            var itm = $(this).text();
            mydata[selRowIdx].InvNo = itm;
            mydata[selRowIdx].AmountPaid = 1234567;
            gridOptions.api.setRowData(mydata);
            //$('#modal-invoice-unpaid').modal('hide')
            $('div.modal').modal('hide')
        });
    });
});