// modal account populate
$(document).ready(function() {
    $('#listCoa').DataTable({
        paging: true,
        pageLength: 10,
        pagingType: "full_numbers",
        //data: {!! $mCoa !!},
        data: mCoa,
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return "<a href=''>" + data['AccNo'] + "</a>";
                }
            },
            { data: 'AccNo' },
            { data: 'CatName' }
        ]
    });
});