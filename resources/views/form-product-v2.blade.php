@extends('temp-master')

@section('css')
    {{-- <link href="/assets/css/style-other.css" rel="stylesheet" type="text/css"/> {% endblock %} --}}
@stop

@section('content')
    @php Form::setBindData($data);@endphp
    <form id='formData' method='POST'>
    {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}" /> --}}
    {{ Form::hidden('jr', 'product') }}
    {{ Form::hidden('Token', session('token')) }}

    <!-- PANEL1 -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-fw fa-user"></i> {{$data->Name??''}} [{{$data->Code??''}}] -
            <a href="/dataedit/product/'{{$data->id??''}}'"
            class="tooltip-link float-right edit-link"
            data-toggle="pstooltip"
            title=""
            data-placement="top"
            data-original-title="Edit" >
            <i class="fa fa-fw fa-pencil"></i>
            </a>
        </div>
        <div class="card-body">
            @if($id=='new')
                {{ Form2::text('Code', 'Code / SKU' ) }}
            @else
                {{ Form2::text('Code', 'Code / SKU', ['readonly'=>true] ) }}
            @endif
            {{ Form2::text('Name', 'Name') }}
            {{ Form2::select('Category', 'Category', $select->selProductCategory ) }}
            {{ Form2::select('Type', 'Type', $select->selProductType ) }}
            {{-- {{ Form2::select('SubCategory', 'Sub Category', $mSubCat) }} --}}
            {{ Form2::text('Brand', 'Brand') }}
            {{ Form2::text('Barcode', 'Barcode', ['size'=>5,'maxlength'=>10]) }}
            {{ Form2::check('StockProduct', 'Have Stock') }}
            {{ Form2::number('MinStock', 'Stock Minimum') }}
            {{ Form2::number('MaxStock', 'Stock Maximum') }}
            {{ Form2::number('MinOrder', 'Stock Minimum Order') }}
            {{ Form2::check('canBuy', 'Can Buy') }}
            {{ Form2::check('canSell', 'Can Sell') }}
            {{ Form2::text('UOM', 'Unit') }}
            {{-- {{ Form2::number('BuyPrice', 'Buy Price') }} --}}
            {{ Form2::number('SellPrice', 'Sell Price') }}
            {{ Form2::select('HppBy', 'HPP By', $select->selHPP ) }}
            <hr/>
            {{-- <div class="row mb-1">
                <div class="col-4 text-right"> HPP Account # </div>
                <div class="col-8"> {{ $data->AccHppNo ?? '-' }} </div>
            </div> --}}
            {{ Form2::textwlookup('AccHppNo', 'HPP Account #', 'modal-account') }}
            {{-- <div class="row mb-1">
                <div class="col-4 text-right"> Purchase Account # </div>
                <div class="col-8"> {{ $data->AccPurchaseNo ?? '-' }} </div>
            </div> --}}
            {{ Form2::textwlookup('AccPurchaseNo', 'Purchase Account #', 'modal-account') }}
            {{-- <div class="row mb-1">
                <div class="col-4 text-right"> Sell Account # </div>
                <div class="col-8"> {{ $data->AccSellNo ?? '-' }} </div>
            </div> --}}
            {{ Form2::textwlookup('AccSellNo', 'Sell Account #', 'modal-account') }}
            {{-- <div class="row mb-1">
                <div class="col-4 text-right"> Inventory Account# </div>
                <div class="col-8"> {{ $data->AccInventoryNo ?? '-' }} </div>
            </div> --}}
            {{ Form2::textwlookup('AccInventoryNo', 'Inventory Account #', 'modal-account') }}
            <hr/>

            @if($id!='new')
            <hr/>
            <div class="row mb-1">
                <div class="col-4 text-right"> Created </div>
                <div class="col-8"> {{ $data->CreatedDate ?? '-' }} By {{ $data->CreatedBy ?? '-' }}</div>
            </div>
            <div class="row mb-1">
                <div class="col-4 text-right"> Latest Update </div>
                <div class="col-8"> {{ $data->UpdatedDate??'' }} </div>
            </div>
            <div class="row mb-1">
                <div class="col-4 text-right"> Status </div>
                <div class="col-8">
                    <span class="badge badge-success rounded">
                        <i class="fa fa-check" aria-hidden="true"></i> Active
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div><!-- end card-->

    <div class="card mb-3">
        <div class="card-header">
            <i class="fa fa-fw fa-eye"></i> Add a private note
            {{-- {{ alert_info('', 'This note will be displayed to all employees but not to customers.') }} --}}
        </div>
        <div class="card-body">
            <form name="private_note" method="post" action="presta_1.7.8.5/admin396tciuup/index.php/sell/customers/2/set-private-note?_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ" class="form-horizontal">
                <textarea name="memo" placeholder="Add a note on this customer. It will only be visible to you." class="form-control"></textarea>
                <button class="btn btn-primary float-right mt-3" id="save-private-note" type="submit"> Save </button>
                <div class="form-group row type-hidden " >
                    <label for="private_note__token" class="form-control-label "> </label>
                    <div class="col-sm">
                        <input type="hidden" id="private_note__token" name="private_note[_token]" class="form-control" value="LvbtE4jdzutkw6LXbaIt9w6a5aBGGNGgjgTrefANiW0" />
                    </div>
                </div>
            </form>
        </div>
    </div><!-- end card-->

    <div class="card mb-3">
        <div class="card-header">
        </div>
        <div class="card-body">
        </div>
    </div><!-- end card-->
    </form>
@stop

@section('modal')
   @include('modal.modal-account')
@stop


@section('js')
<script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/helper_form.js') }}" type="text/javascript"></script>
<script type="text/javascript">
   $(document).ready(function() {
		//init page

        //init datagrid
        var fcur = $.fn.dataTable.render.number(',', '.', 0, 'Rp ');
        var fnum = $.fn.dataTable.render.number(',', '.', 0, '');
        var fdate = function(v) { return moment(v).format('DD/MM/YYYY') }
        $('#table-order').DataTable({
            paging: true,
            pagingType: "full_numbers",
            pageLength: 5,
            data: [],
            columnDefs: [{width:20, targets:6}],
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                    return "#" + data['TransNo'];
                    }
                },
                { data: 'TransDate', render: fdate },
                {
                    data: null,
                    render: function (data, type, row) {
                        return "Transfer";
                    }
                },
                { data: 'Status' },
                { data: 'ProductCount', "className": 'col-number', render: fnum  },
                { data: 'Total', "className": 'col-number', render: fcur },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a href="presta_1.7.8.5/admin396tciuup/index.php/sell/orders/carts/000005/view?_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
                                class="btn tooltip-link dropdown-item grid-view-row-link"
                                data-toggle="pstooltip"
                                data-placement="top"
                                data-original-title="View">
                                        <i class="fa fa-fw fa-search-plus"></i>
                            </a>`;
                    }
                },
            ]
        });

        $('#table-cart').DataTable({
            paging: true,
            pagingType: "full_numbers",
            pageLength: 5,
            //ajax: _api("SI"),
            data: [],
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                    return "#" + data['TransNo'];
                    }
                },
                { data: 'TransDate', render: fdate },
                { data: 'DeliveryCode' },
                { data: 'Status' },
                { data: 'Total', "className": 'col-number', render: fcur },
                {
                    data: null,
                    render: function (data, type, row) {
                    return `<a href="presta_1.7.8.5/admin396tciuup/index.php/sell/orders/carts/000005/view?_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
                            class="btn tooltip-link dropdown-item grid-view-row-link"
                            data-toggle="pstooltip"
                            data-placement="top"
                            data-original-title="View">
                                    <i class="fa fa-fw fa-search-plus"></i>
                        </a>`;
                    }
                },
            ]
        });

        $('#table-address').DataTable({
            paging: true,
            pagingType: "full_numbers",
            pageLength: 5,
            data: [],
            columnDefs: [{width:20, targets:7}],
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                    return "#" + row.id;
                    }
                },
                { data: 'Code' },
                { data: 'ContachPerson' },
                { data: 'Address' },
                { data: 'Area' },
                { data: 'Phone' },
                { data: 'Fax' },
                {
                    data: null,
                    render: function (data, type, row) {
                    return `<a href="presta_1.7.8.5/admin396tciuup/index.php/sell/addresses/5/edit?back=http%3A%2F%2Flocalhost%2Fprestashop_1.7.8.5%2Fadmin396tciuup%2Findex.php%2Fsell%2Fcustomers%2F2%2Fview%3F_token%3D3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ&amp;_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
                                data-confirm-message=""
                                data-toggle="pstooltip"
                                data-placement="top"
                                data-original-title="Edit"
                                data-clickable-row="1">
                                <i class="fa fa-fw fa-pencil"></i>
                            </a>

                            <a class=""
                            href="#"
                            data-confirm-message="Are you sure you want to delete the selected item(s)?"
                            data-url="presta_1.7.8.5/admin396tciuup/index.php/sell/addresses/5/delete?back=http%3A%2F%2Flocalhost%2Fprestashop_1.7.8.5%2Fadmin396tciuup%2Findex.php%2Fsell%2Fcustomers%2F2%2Fview%3F_token%3D3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ&amp;_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
                            data-method="POST"
                            data-title="Delete selection"
                            data-confirm-button-label="Delete"
                            data-confirm-button-class="btn-danger"
                            data-close-button-label="Cancel">
                            <i class="fa fa-fw fa-trash"></i>
                            </a>`;
                    }
                },
            ]
        });

        $("button#cmSave2").click(function(e){
            e.preventDefault();
            var formdata=$('form').serialize();
            $('#formData').submit();
        });
        $("button#cmSave").click(function(e){ //using ajax
            e.preventDefault();
            var formdata=$('form').serialize();
            /*$.post('http://localhost/lav7_PikeAdmin_multi/api/product/save/{{$id}}', formdata, function(result) {
                //alert(JSON.stringify(result));
                if (result.status=='OK') {
                    alert('data saved.')
                    @if($id=='new')
                        //window.location.href = "http://localhost/lav7_PikeAdmin_multi/product/edit/"+result.data.id;
                        window.location.href = "{{ url('/product/edit') }}/"+result.data.id;
                    @endif
                } else {
                    alert(JSON.stringify(result));
                }
            }); */
            var result = $('form').formsave('product', '{{$id}}' );
            console.log(result)
        });
        

        $("button#cmPrint").click(function(e){
            e.preventDefault();
            alert('print');
        });

        $('.modal').on('show.bs.modal', function (e) {
            lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
        })

	});

    function afterModalClose(sel) {
        ////console.log(sel)
        if (sel.selModal == 'modal-account') {
            var btn = lookup_target_button;
            $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[1])
        }
    };
</script>
@stop
