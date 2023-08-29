//========================
// Lookup function for FOrm and AG_Grid
// using jQuery
//========================


$(function () {
    $(document).ready(function () {
        // $("a.lookup-item").click(function (e) {
        //     e.preventDefault();
        //     selRow = $(this).data('drow').toString().split('|');
        //     console.log(selRow)
        //     //mydata[selRowIdx].InvNo = itm;
        //     //mydata[selRowIdx].AmountPaid = 1234567;
        //     //gridOptions.api.setRowData(mydata);
        //     //$('#modal-invoice-unpaid').modal('hide')
        //     //$('div.modal').modal('hide')
        //     grid_afterLookupClose()
        // });
        $("a.lookup-item").click(function (e) {
            e.preventDefault();
            var sel = {}
            sel.selRow = $(this).data('drow').toString().split('|');
            sel.selModal = $(this).closest('div.modal').attr('id')
            //sel.selRowIdx = $(this).data('idx').toString()
            $('div.modal').modal('hide')
            afterModalClose(sel)
        });
    });
});