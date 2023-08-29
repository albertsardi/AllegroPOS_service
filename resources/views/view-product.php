@extends('temp-master')

@section('css')
    <link href="/assets/css/style-other.css" rel="stylesheet" type="text/css"/> {% endblock %}
@stop

@section('content')
    @php Form::setData($data);@endphp
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    {{ Form::hidden('jr', 'product') }}   
    <!-- PANEL1 -->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-fw fa-user"></i> '+$data->Name+' ['+$data->Code+'] -
                <a href="/dataedit/product/'+$data->id+'"
                class="tooltip-link float-right edit-link"
                data-toggle="pstooltip"
                title=""
                data-placement="top"  
                data-original-title="Edit" >
                <i class="fa fa-fw fa-pencil"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-4 text-right"> Category </div>
                    <div class="col-8"> {{ $data->Category or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Sub Category </div>
                    <div class="col-8"> {{ $data->SubCategory or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Brand </div>
                    <div class="col-8"> {{ $data->Brand or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Barcode </div>
                    <div class="col-8"> {{ $data->Barcode or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Have Stock </div>
                    <div class="col-8"> {{ $data->StockProduct }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Stock Minimum </div>
                    <div class="col-8"> {{ $data->MinStock or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Stock Maximum </div>
                    <div class="col-8"> {{ $data->MaxStock or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Stock Minimum Order </div>
                    <div class="col-8"> {{ $data->MinOrder or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Can Buy </div>
                    <div class="col-8"> {{ $data->canBuy }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> can Sell </div>
                    <div class="col-8"> {{ $data->TaxAcanSellddr  }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Unit </div>
                    <div class="col-8"> {{ $data->UOM or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Buy Price </div>
                    <div class="col-8"> {{ $data->BuyPrice or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Sell Price </div>
                    <div class="col-8"> {{ $data->SellPrice or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> HPP By </div>
                    <div class="col-8"> {{ $data->HppBy or '-' }} </div>
                </div>
                <hr/>
                <div class="row mb-1">
                    <div class="col-4 text-right"> HPP Account # </div>
                    <div class="col-8"> {{ $data->AccHppNo or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Purchase Account # </div>
                    <div class="col-8"> {{ $data->AccPurchaseNo or '-' }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Sell Account # </div>
                    <div class="col-8"> {{ $data->AccSellNo or '-' }} </div>
                </div>
                </div><div class="row mb-1">
                    <div class="col-4 text-right"> Inventory Account# </div>
                    <div class="col-8"> {{ $data->AccInventoryNo or '-' }} </div>
                </div>
                <hr/>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Memo </div>
                    <div class="col-8"> {{ $data->Memo or '-' }} </div>
                </div>
                <hr/>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Created </div>
                    <div class="col-8"> {{ $data->CreatedDate or '-' }} By {{ $data->CreatedBy or '-' }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Latest Update </div>
                    <div class="col-8"> {{ $data->UpdatedDate }} </div>
                </div>
                <div class="row mb-1">
                    <div class="col-4 text-right"> Status </div>
                    <div class="col-8"> 
                        <span class="badge badge-success rounded">
                            <i class="fa fa-check" aria-hidden="true"></i> Active 
                        </span>
                    </div>
                </div>
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->

        <div class="card mb-3">
            <div class="card-header">
            </div>
            <div class="card-body">
            </div>
        </div><!-- end card-->
    </div>
@stop


{% extends "layout.njk" %}
{% block css %} <link href="/assets/css/style-other.css" rel="stylesheet" type="text/css"/> {% endblock %}

{% block content %}

<div class="card-header">
    <h3><i class="fa fa-check-square-o"></i> General data</h3>
</div>
<div class="card-body">
</div>

{{ pcard_open('', 
  	'<i class="fa fa-fw fa-user"></i> '+$data->Name+' ['+$data->Code+'] -
      <a href="/dataedit/product/'+$data->id+'"
       class="tooltip-link float-right edit-link"
       data-toggle="pstooltip"
       title=""
       data-placement="top"
       data-original-title="Edit" >
	  <i class="fa fa-fw fa-pencil"></i>
    </a>') }}
    <div class="row mb-1">
        <div class="col-4 text-right"> Category </div>
        <div class="col-8"> {{ $data->Category or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Sub Category </div>
        <div class="col-8"> {{ $data->SubCategory or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Brand </div>
        <div class="col-8"> {{ $data->Brand or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Barcode </div>
        <div class="col-8"> {{ $data->Barcode or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Have Stock </div>
        <div class="col-8"> {{ $data->StockProduct | check_mark }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Stock Minimum </div>
        <div class="col-8"> {{ $data->MinStock or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Stock Maximum </div>
        <div class="col-8"> {{ $data->MaxStock or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Stock Minimum Order </div>
        <div class="col-8"> {{ $data->MinOrder or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Can Buy </div>
        <div class="col-8"> {{ $data->canBuy | check_mark }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> can Sell </div>
        <div class="col-8"> {{ $data->TaxAcanSellddr | check_mark }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Unit </div>
        <div class="col-8"> {{ $data->UOM or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Buy Price </div>
        <div class="col-8"> {{ $data->BuyPrice or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Sell Price </div>
        <div class="col-8"> {{ $data->SellPrice or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> HPP By </div>
        <div class="col-8"> {{ $data->HppBy or '-' }} </div>
    </div>
    <hr/>
    <div class="row mb-1">
        <div class="col-4 text-right"> HPP Account # </div>
        <div class="col-8"> {{ $data->AccHppNo or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Purchase Account # </div>
        <div class="col-8"> {{ $data->AccPurchaseNo or '-' }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Sell Account # </div>
        <div class="col-8"> {{ $data->AccSellNo or '-' }} </div>
    </div>
    </div><div class="row mb-1">
        <div class="col-4 text-right"> Inventory Account# </div>
        <div class="col-8"> {{ $data->AccInventoryNo or '-' }} </div>
    </div>
    <hr/>
    <div class="row mb-1">
        <div class="col-4 text-right"> Memo </div>
        <div class="col-8"> {{ $data->Memo or '-' }} </div>
    </div>
    <hr/>
    <div class="row mb-1">
        <div class="col-4 text-right"> Created </div>
        <div class="col-8"> {{ $data->CreatedDate or '-' }} By {{ $data->CreatedBy or '-' }}</div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Latest Update </div>
        <div class="col-8"> {{ $data->UpdatedDate }} </div>
    </div>
    <div class="row mb-1">
        <div class="col-4 text-right"> Status </div>
        <div class="col-8"> 
            <span class="badge badge-success rounded">
                <i class="fa fa-check" aria-hidden="true"></i> Active 
            </span>
        </div>
    </div>
{{ pcard_close('') }}

{{ pcard_open('', 
  	'<i class="fa fa-fw fa-shopping-basket"></i> Orders
    <span class="badge badge-primary rounded">'+mOrder.length+'</span>') }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    Valid orders:
                    <span class="badge badge-success rounded">0</span>
                    for a total amount of <span id="total-order-amount">Rp0.00</span>
                </div>
                <div class="col">
                    Invalid orders:
                    <span class="badge badge-danger rounded">5</span>
                </div>
            </div>
        </div>
      </div>
  <table id='table-order' class="table w-100">
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Products</th>
        <th>Total spent</th>
        <th>Action</th>
      </tr>
    </thead>
    </table>
{{ pcard_close('') }}

{{ pcard_open('', 
  	  '<i class="fa fa-fw fa-shopping-cart"></i> Carts
      <span class="badge badge-primary rounded">5</span>') }}
  <table id='table-cart' class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Carrier</th>
        <th>Status</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
    </thead>
    </table>
{{ pcard_close('') }}

{{ pcard_open('customer-private-note-card', '<i class="fa fa-fw fa-eye"></i> Add a private note') }}
    {{ alert_info('', 'This note will be displayed to all employees but not to customers.') }}
   <form name="private_note" method="post" action="presta_1.7.8.5/admin396tciuup/index.php/sell/customers/2/set-private-note?_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ" class="form-horizontal">
      <textarea id="private_note_note" name="private_note[note]" placeholder="Add a note on this customer. It will only be visible to you." class="form-control"></textarea>
      <button class="btn btn-primary float-right mt-3" id="save-private-note" type="submit"> Save </button>
      <div class="form-group row type-hidden " >
         <label for="private_note__token" class="form-control-label "> </label>
         <div class="col-sm">
            <input type="hidden" id="private_note__token" name="private_note[_token]" class="form-control" value="LvbtE4jdzutkw6LXbaIt9w6a5aBGGNGgjgTrefANiW0" />
         </div>
      </div>
   </form>
{{ pcard_close() }}  

{{ pcard_open('customer-groups-card', 
   '<i class="fa fa-fw fa-users"></i> Groups  <span class="badge badge-primary rounded">1</span>'+
   '<a href="presta_1.7.8.5/admin396tciuup/index.php/sell/customers/2/edit?_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
       class="tooltip-link float-right"
       data-toggle="pstooltip"
       data-placement="top"
       data-original-title="Edit">
	  <i class="fa fa-fw fa-pencil"></i>
    </a>') }}
   <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
          </tr>
        </thead>
        <tbody>
            <tr class="customer-group">
            <td class="customer-group-id">#3</td>
            <td class="customer-group-name">
              <a href="http://localhost/presta_1.7.8.5/admin396tciuup/index.php?controller=AdminGroups&amp;id_group=3&amp;viewgroup=1&amp;token=23aed44929ae7e63d47020d7b1941e39">
                Customer
              </a>
            </td>
          </tr>
                </tbody>
      </table>
{{ pcard_close() }}  

{{ pcard_open('', 
   '<i class="fa fa-fw fa-map-marker"></i> Addresses <span class="badge badge-primary rounded">'+mAddr.length+'</span>'+
   '<a href="presta_1.7.8.5/admin396tciuup/index.php/sell/addresses/new?id_customer=2&amp;back=http%3A%2F%2Flocalhost%2Fprestashop_1.7.8.5%2Fadmin396tciuup%2Findex.php%2Fsell%2Fcustomers%2F2%2Fview%3F_token%3D3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ&amp;_token=3c3131zih1h5I1ftl2sC0vH3cXmZKgJYMDj3GmProbQ"
       class="tooltip-link float-right"
       data-toggle="pstooltip"
       title=""
       data-placement="top"
       data-original-title="Add">
	   <i class="fa fa-fw fa-plus-circle"></i>
    </a>') }}

    <form name="customer_address" method="post" action="" id="customer_address_filter_form" class="table-responsive form-horizontal">
      <table id="table-address" class="table w-100">
         <thead>
            <tr>
               <th>ID</th>
               <th>Company</th>
               <th>Name</th>
               <th>Address</th>
               <th>Area</th>
               <th>Phone number(s)</th>
               <th>Fax</th>
               <th>Action</th>
            </tr>
         </thead>
      </table>
    </form>
       {{ pcard_close() }}  
{% endblock %}

<!-- Modal -->
{% block modal %}
	{% include 'modal-account.njk' %}
   	{# {% include 'modal-address-edit.njk' %} #}
{% endblock %}

{% block js %}
<script src="/assets/textwlookup.js" type="text/javascript"></script>
<script type="text/javascript">
   $(document).ready(function() {
		//init page
		var mAccount = {{mAccount}}

        //init datagrid
        var fcur = $.fn.dataTable.render.number(',', '.', 0, 'Rp ');
        var fnum = $.fn.dataTable.render.number(',', '.', 0, '');
        var fdate = function(v) { return moment(v).format('DD/MM/YYYY') }
        $('#table-order').DataTable({
            paging: true,
            pagingType: "full_numbers",
            pageLength: 5,
            data: {{ mOrder | json_string }},
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
            ajax: _api("SI"),
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
            data: {{ mAddr | json_string }},
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
      
		//cmSave click
        $('button#cmSave').click(function() {
            //form_save('form', 'customer' );
            console.log(xx);
        });

	});
</script>
{% endblock %}
