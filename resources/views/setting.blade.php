@extends('temp-master')

@section('css')
  <link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet" type="text/css" >
@stop


@section('content')
    @php use App\Http\Model\Parameter; @endphp
    <div class="container-fluid">
    <div class="row">
        <div class="col-3">
        @php
            $menu = ['Perusahaan','Penjualan','Pembelian','Product','Template','User'];
        @endphp
        <ul class="list-group">
            <li class="list-group-item text-muted">Setting <i class="fa fa-dashboard fa-1x"></i></li>
            @foreach ($menu as $m)
                <li class="list-group-item text-right"><span class="pull-left"><a href=''>{{ $m }}</a></span> -</li>
            @endforeach
            {{-- <li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Tersisa</strong></span> 13</li> --}}
            {{-- <li class="list-group-item text-right"><span class="pull-left"><strong>Sakit</strong></span> 37</li> --}}
        </ul>
    </div>
    <div class="col-9">
        <form  method='POST'>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            {{-- <ul id='tabs' class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#personal-tab">Personal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#work-tab">Work</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#family-tab">Family</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul> --}}

            <?php #dd($comp->LogoPath);?>
            <div class="card" style="height:auto;">
                <div class="card-body" id="company-card" >
                    <h5>Pengaturan Perusahaan</h5>
                    <hr>
                    {{ Form::text('LogoPath', 'Logo', $comp->LogoPath ?? '') }}
                    {{ Form::checkbox('LogoShow', 'Tampilkan logo di Laporan', $comp->LogoShow ?? '') }}
                    {{ Form::text('Name', 'Nama Perusahaan', $comp->Name ?? '') }}
                    {{ Form::text('Address', 'Address', $comp->Address ?? '') }}
                    {{ Form::text('DeliveryAddress', 'Delivery Address', $comp->DeliveryAddress ?? '') }}
                    {{ Form::text('Phone', 'Phone', $comp->Phone ?? '') }}
                    {{ Form::text('Fax', 'Fax', $comp->Fax ?? '') }}
                    {{ Form::text('TaxNo', 'NPWP', $comp->TaxNo ?? '') }}
                    {{ Form::text('Website', 'Website', $comp->Website ?? '') }}
                    {{ Form::text('Email', 'Email', $comp->EMail ?? '') }}
                </div> 

                <div class="card-body" id="sale-card" >
                    <h4>Penjualan</h4>
                    <hr>
                    {{ Form::select('Sales.type', 'Syarat Pembayaran Penjualan Utama', $mPayCat, Parameter::getVal('sales.type')) }}
                    {{ Form::checkbox('Sales.delivery', 'Pengiriman', Parameter::getVal('sales.delivery')) }}
                    {{ Form::checkbox('Sales.showdisc', 'Diskon', Parameter::getVal('sales.showdisc')) }}
                    {{ Form::checkbox('Sales.showDiscLine', 'Diskon per Baris', Parameter::getVal('sales.showDiscLine')) }}
                    {{ Form::checkbox('Sales.haveDP', 'Uang Muka', Parameter::getVal('sales.haveDP')) }}
                    {{ Form::checkbox('Sales.noSalesStockEmpty', 'Tolak penjualan jika stock habis', Parameter::getVal('sales.noSalesStockEmpty')) }}
                    {{ Form::checkbox('Sales.lockPrice', 'Input Price Lock', Parameter::getVal('sales.lockPrice')) }}
                    {{ Form::text('Sales.memo', 'Pesan Penjualan Standard', Parameter::getVal('sales.memo')) }}
                    {{ Form::text('Sales.deliveryMemo', 'Pesan Surat Jalan Standard', Parameter::getVal('sales.deliveryMemo')) }}
                </div> 

                <div class="card-body" id="buy-card" >
                    <h4>Pembelian</h4>
                    <hr>
                    {{ Form::select('Purchase.type', 'Syarat Pembayaran Pembelian Utama', $mPayCat, Parameter::getVal('purchase.type')) }}
                    {{ Form::checkbox('Purchase.delivery', 'Pengiriman', Parameter::getVal('purchase.delivery')) }}
                    {{ Form::checkbox('Purchase.showDisc', 'Diskon', Parameter::getVal('purchase.showDisc')) }}
                    {{ Form::checkbox('Purchase.showDiscLine', 'Diskon per Baris', Parameter::getVal('purchase.showDiscLine')) }}
                    {{ Form::checkbox('Purchase.haveDP', 'Uang Muka', Parameter::getVal('purchase.haveDP')) }}
                    {{ Form::text('Purchase.memo', 'Pesan Pebelian Standard', Parameter::getVal('purchase.memo')) }}
                </div> 

                <div class="card-body" id="product-card" >
                    <h4>Product</h4>
                    <hr>
                    {{ Form::checkbox('Product.showImage', 'Show Product Image in Product List', Parameter::getVal('product.showImage')) }}
                    {{ Form::checkbox('Product.category', 'Product Category', Parameter::getVal('product.category')) }}
                    {{ Form::checkbox('Product.showStock', 'Show Product Stock Quantity', Parameter::getVal('product.showStock')) }}
                    {{ Form::select('Product.calcCost', 'Metode Perhitungan Biaya Inventory', ['Average','FIFO','LIFO'], Parameter::getVal('product.calcCost')) }}
                    {{-- <button type="button" class="btn btn-sm btn-info">Pengaturan Kategori</button> --}}
                    {{-- <button type="button" class="btn btn-sm btn-info">Pengaturan Satuan</button> --}}
                    <button id='setting-category-lookup' type='button' data-toggle='modal' data-target='#modal-setting-category' class='btn btn-sm btn-info btnlookup'>Pengaturan Kategori</button>
                    <button id='setting-unit-lookup' type='button' data-toggle='modal' data-target='#modal-setting-unit' class='btn btn-sm btn-info btnlookup'>Pengaturan Satuan</button>
                    {{-- <button id='AccHppNo-lookup' type='button' data-toggle='modal' data-target='#modal-account' class='btn btn-outline-secondary btnlookup'><i class='fa fa-search'></i></button> --}}
                </div> 

                <div class="card-body" id="mapping-card" >
                    @php
                        $data = [
                            ['Account.Sales.ProfitNo', 'Pendapatan Penjualan'],
                            ['Account.Sales.DiscNo', 'Diskon Penjualan'],
                            ['Account.Sales.ReturnNo', 'Return Penjualan'],
                            ['Account.Sales.DeliveryNo', 'Pengiriman Penjualan'],
                            ['Account.Sales.FirstPaymentNo', 'Pembayaran Dimuka'],
                            ['Account.Sales.ArOutstandingNo', 'Penjualan Belum Ditagih'],
                            ['Account.Sales.ApOutstandingNo', 'Piutang Belum Ditagih'],
                            ['Account.Sales.TaxNo', 'Pajak Penjualan'],

                            ['Account.Purchase.AccHppNo', 'Pembelian (COGS)'],
                            ['Account.Purchase.DeliveryNo', 'Pengiriman Pembelian'],
                            ['Account.Purchase.AccHppNo', 'Uang Muka Pembeliaan'],
                            ['Account.Purchase.AccHppNo', 'Hutang Belum Ditagih'],
                            ['Account.Purchase.AccHppNo', 'Pajak Pembelian'],

                            ['Account.ARAP.AccHppNo', 'Piutang Usaha'],
                            ['Account.ARAP.AccHppNo', 'Hutang Usaha'],
                            
                            ['Account.Stock.InventoryNo', 'Persediaan'],
                            ['Account.Stock.DamageNo', 'Persediaan Rusak'],
                            ['Account.Stock.ProductionNo', 'Persediaan Produksi'],
                            ['Account.Other.FirstQuityNo', 'Ekuitas Saldo Awal'],
                            ['Account.Other.AssetNo', 'Asset Tetap'],
                        ]
                    @endphp
                    <h4>Daftar Pemetaan Akun</h4>
                    <p class='text-muted'>Pilih akun default untuk setiap level untuk mambantu system membuat antry jurnal Anda secara otomatis dari transaksi, (Semua kolom wajib diisi)</p>
                    <hr>
                    <h5>Penjualan</h5>
                    @foreach ($data as $dt)
                        @if (str_starts_with($dt[0],'Account.Sales')) 
                            {{ Form::textwlookup($dt[0], $dt[1], 'modal-account', Parameter::getVal($dt[0])) }}
                        @endif
                    @endforeach
                    {{-- {{ Form::textwlookup('Account.ProfitNo', 'Pendapatan Penjualan', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.SalesDiscNo', 'Diskon Penjualan', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.SalesReturnNo', 'Return Penjualan', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.DeliveryNo', 'Pengiriman Penjualan', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.FirstPaymentNo', 'Pembayaran Dimuka', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.ArOutstandingNo', 'Penjualan Belum Ditagih', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.ApOutstandingNo', 'Piutang Belum Ditagih', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.SalesTaxNo', 'Pajak Penjualan', 'modal-account', '$data->AccHppNo') }} --}}
                    
                    <hr>
                    <h5>Pembelian</h5>
                    @foreach ($data as $dt)
                        @if (str_starts_with($dt[0],'Account.Purchase')) 
                            {{ Form::textwlookup($dt[0], $dt[1], 'modal-account', Parameter::getVal($dt[0])) }}
                        @endif
                    @endforeach
                    {{-- {{ Form::textwlookup('Account.AccHppNo', 'Pembelian (COGS)', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.DeliveryNo', 'Pengiriman Pembelian', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Uang Muka Pembelian', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Hutang Belum Ditagih', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Pajak Pembelian', 'modal-account', '$data->AccHppNo') }} --}}
                    
                    <hr>
                    <h5>AR/AP</h5>
                    @foreach ($data as $dt)
                        @if (str_starts_with($dt[0],'Account.ARAP')) 
                            {{ Form::textwlookup($dt[0], $dt[1], 'modal-account', Parameter::getVal($dt[0])) }}
                        @endif
                    @endforeach
                    {{-- {{ Form::textwlookup('Account.AccHppNo', 'Piutang Usaha', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Hutang Usaha', 'modal-account', '$data->AccHppNo') }} --}}

                    <hr>
                    <h5>Persediaan</h5>
                    @foreach ($data as $dt)
                        @if (str_starts_with($dt[0],'Account.Stock')) 
                            {{ Form::textwlookup($dt[0], $dt[1], 'modal-account', Parameter::getVal($dt[0])) }}
                        @endif
                    @endforeach
                    {{-- {{ Form::textwlookup('Account.AccHppNo', 'Persediaan', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Persediaan Umum', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Perediaan Rusak', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('Account.AccHppNo', 'Persediaan Produksi', 'modal-account', '$data->AccHppNo') }} --}}

                    <hr>
                    <h5>Lainnya</h5>
                    @foreach ($data as $dt)
                        @if (str_starts_with($dt[0],'Account.Other')) 
                            {{ Form::textwlookup($dt[0], $dt[1], 'modal-account', Parameter::getVal($dt[0])) }}
                        @endif
                    @endforeach
                    {{-- {{ Form::textwlookup('AccHppNo', 'Ekuitas Saldo Awal', 'modal-account', '$data->AccHppNo') }}
                    {{ Form::textwlookup('AccHppNo', 'Asset Tetap', 'modal-account', '$data->AccHppNo') }} --}}
                </div> 

                <div class="card-body" id="user-card" >
                    <h4>User</h4>
                    <hr>
                    {{ Form::text('User.Name', 'User', Parameter::getVal('User.Name')) }}
                    {{ Form::text('User.Name', 'Real Name', Parameter::getVal('User.RealName'), ['readonly'=>'readonly']) }}
                </div> 

                <div class="card-body" id="user-card" >
                    <h4>Date</h4>
                    <hr>
                    {{ Form::text('date.DateFormat', 'Date Format', Parameter::getVal('Date.date.DateFormat')) }}
                </div> 

            </div>
        </form>
        </div>
    </div>
    </div>
@stop

@section('modal')
   {{-- @include('modal.modal-fileupload')  --}}
   @include('modal.modal-account') 
   {{-- @include('modal.modal-setting-category')  --}}
   {{-- @include('modal.modal-setting-unit')  --}}
@stop

@section('js')
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('resource/js/lookup/lookup_modal-account.js') }}" type="text/javascript"></script>
    <script type="text/javascript">  
        //var selRowIdx = null;
        //var selRow = null;
        var modal_target_button = null;
        //var mCoa = {!! $mCoa !!};
        //var mUnit = {!! $mUnit !!};

        //init page
        $(document).ready(function(){
            //$(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            //$('select.select2').select2({ theme: "bootstrap" });

            //cmSave click
            $("button#cmSave2").click(function(e){
                //alert('save')
                e.preventDefault();
                var formdata=$('form').serialize();
                $.ajax({
                    type:'POST',
                    data: formdata,
                    success:function(res){
                        alert(res.success);
                        console.log(res.data);
                    }
                });
            });

            // edit item category
            // modal-setting-category
            $("#modal-setting-category a.edit-item").click(function (e) {
                e.preventDefault();
                var name = $(this).text();
                console.log('edit ---'+name);
                $("input[name='input-name']").val(name);
                
                $('#modal-setting-category #list').addClass('d-none'); //hide list
                $('#modal-setting-category #form-input').removeClass('d-none'); //show form

                $('#modal-setting-category #input-save,#input-close').removeClass('d-none');
                //$('#modal-setting-category #input-close').removeClass('d-none');
                $('#modal-setting-category #btn-close').addClass('d-none');

            });
            $("#modal-setting-category #input-close").click(function (e) {
                e.preventDefault();
                console.log('input-close')
                $('#modal-setting-category #list').removeClass('d-none'); //show form
                $('#modal-setting-category #form-input').addClass('d-none'); //hide list
                
                $('#modal-setting-category #input-save,#input-close').addClass('d-none');
                //$('#modal-setting-category #input-close').addClass('d-none');
                $('#modal-setting-category #btn-close').removeClass('d-none');
            });
            $("#modal-setting-category #input-save").click(function (e) {
                e.preventDefault();
                console.log('input-save')

                //save
                var name = $("input[name='input-name']").val();
                var data = { 
                    table: 'masterproductcategory', save: {Category: name, Active: 1 }
                }
                //console.log(data)
                //$.get("http://localhost/lav7_PikeAdmin/api/datasave/master?table=master&Category="+name, function(data, status){
                    //alert("Data: " + data + "\nStatus: " + status);
                    //console.log('ddd')
                    //console.log("Data: " + data + "\nStatus: " + status);
                //});
                //$.get("http://localhost/lav7_PikeAdmin/api/datasave/master", data, function(data, status){
                $.get( '{{ url('/ajax/datasave') }}', data, function(resp, status){
                    //alert("Data: " + data + "\nStatus: " + status);
                    //console.log('ddd')
                    console.log("Data: " + resp + "\nStatus: " + status);
                });
                $('#modal-setting-category #list').removeClass('d-none'); //show form
                $('#modal-setting-category #form-input').addClass('d-none'); //hide list
                
                $('#modal-setting-category #input-save,#input-close').addClass('d-none');
                //$('#modal-setting-category #input-close').addClass('d-none');
                $('#modal-setting-category #btn-close').removeClass('d-none');
            });
            


            
            // modal-setting-unit
            $("#modal-setting-unit a.edit-item").click(function (e) {
                e.preventDefault();
                var name = $(this).text();
                var id = $(this).attr('id');
                $("input[name='input-unit-name']").val(name);
                $("input[name='input-unit-id']").val(id);
                $('#modal-setting-unit #list').addClass('d-none'); //hide list
                $('#modal-setting-unit #form-input').removeClass('d-none'); //show form
                $('#modal-setting-unit #input-save,#input-close').removeClass('d-none');
                $('#modal-setting-unit #btn-close').addClass('d-none');
            });
            $("#modal-setting-unit #input-close").click(function (e) {
                e.preventDefault();
                $('#modal-setting-unit #list').removeClass('d-none'); //show form
                $('#modal-setting-unit #form-input').addClass('d-none'); //hide list
                $('#modal-setting-unit #input-save,#input-close').addClass('d-none');
                $('#modal-setting-unit #btn-close').removeClass('d-none');
            });
            $("#modal-setting-unit #input-save").click(function (e) {
                e.preventDefault();
                //save
                var name = $("input[name='input-unit-name']").val();
                var id = $("input[name='input-unit-id']").val();
                var data = { 
                    table: 'common', save: {category: 'Unit',catid: 1, name1: name, id: id }
                }
                console.log('{{ url('/ajax/datasave') }}');
                $.get( '{{ url('/ajax/datasave') }}', data, function(data, status){
                    console.log("Data: " + data+ "\nStatus: " + status);
                    $('#listProductUnit').DataTable().draw();
                });
                $('#modal-setting-unit #list').removeClass('d-none'); //show form
                $('#modal-setting-unit #form-input').addClass('d-none'); //hide list
                $('#modal-setting-unit #input-save,#input-close').addClass('d-none');
                $('#modal-setting-unit #btn-close').removeClass('d-none');
            });

           
        });

        function afterLookupClose(e) {
            console.log(e.lookup_id)
            if (e.lookup_id == 'modal-account') {
                var btn = modal_target_button;
                $('#'+btn.attr('id')).textwlookup(selRow.AccNo, selRow.AccName)
            }
        };
    </script>
@stop
