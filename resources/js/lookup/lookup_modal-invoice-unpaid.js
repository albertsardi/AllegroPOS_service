//========================
// Lookup function for FOrm and AG_Grid
// using jQuery
//========================

function agGrid_getIndex(gridOptions, key, find) {
    var idx = 0;
    var rowData = gridOptions.rowData
    for (let row of rowData) {
        // if (row.InvNo == find) return idx;
        if (row[key] == find) return idx;
        idx++;
    }
    return -1;
}

$(function () {
    $(document).ready(function () {
        $("#listInvUnpaid a.lookup-item").click(function (e) {
            //var lookupRow = [];
            // https://stackblitz.com/edit/angular-ag-grid-button-renderer?file=src/app/app.component.ts
            e.preventDefault();
            e.lookup_id = $(this).closest('div.modal').attr('id')
            var itm = $(this).text();
            //mydata[selRowIdx].InvNo = itm;
            //mydata[selRowIdx].AmountPaid = 1234567;

            // lookupRow = $(this).attr('rowIdx');
            selRow = mInvUnpaid[ $(this).attr('rowIdx') ];
            // console.log(selRow)
            //console.log(selRowIdx)

            //gridOptions.api.setRowData(mydata);
            //$('#modal-invoice-unpaid').modal('hide')
            $('div.modal').modal('hide')
            
            // alert('afterLookupClose')
            grid_afterLookupClose(e);
        });
    });
    
    $('.modal').on('show.bs.modal', function (e) {
        modal_target_button = $(e.relatedTarget) // Button that triggered the modal
    })
});

// function grid_afterLookupClose() {
//     // alert('afterLookupClose inside')
//     alert(selRowIdx)
//     var row = mydata[selRowIdx]
//     mydata[selRowIdx].InvNo = selRow.TransNo;
//     mydata[selRowIdx].InvDate = selRow.TransDate;
//     mydata[selRowIdx].InvTotal = selRow.Total;
//     mydata[selRowIdx].AmountPaid = selRow.InvPaid;
//     //mydata[selRowIdx].InvNo = '12345';
//     //mydata[selRowIdx].InvDate = '2020-01-22';
//     gridOptions.api.setRowData(mydata);
// };