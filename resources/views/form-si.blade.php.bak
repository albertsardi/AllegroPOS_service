                    @extends('temp-trans')
                    @section('header')
                    <div class="row no-gap grid-container" style="width:1000px">
                        <!-- Left Panel -->
                        <div class="stub float-left" style="width:40%">
                            <div class="card">
                                <div class="card-content p-4">
                                    <div class='row mb-2'>
                                        {{ Form::text('TransNo','Transaction #') }}
                                    </div>
                                    <div class='row mb-2'>
                                        {{ Form::date('TransDate','Date') }}
                                    </div>
                                    <div class='row mb-2'>
                                        {{ Form::text('TaxNo','Tax No.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right Panel -->
                        <div class="stub float-left" style="width:60%">
                            <div class="card">
                                <div class="card-content p-4">
                                    <div class="row mb-2">
                                        {{-- {{ Form::form_textwlookup('AccCode', 'Order To', ['modal'=>'modal-supplier']); }} --}}
                                        {{ Form::combo('AccCode', 'Order To', $mAcc) }}
                                    </div>
                                    <div class="row mb-2">
                                        {{ Form::combo('Warehouse', 'Warehouse', $mWarehouse) }}
                                        <!-- <label class="plabel">Warehouse</label>
                                        <div class="xxcell-sm-10" style="display:flex;">
                                            <input type="text">
                                        </div> -->
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </div>
                    </div>
                    @stop

                    <!-- GRID -->
                    @section('grid')
                    <div class="card mb-2">
                        <div class="card-header">
                            <i class="fa fa-check-square-o"></i> Order Detail
                        </div>
                        <div class="card-content p-2" >
                            {{-- <?= makeeditgrid();?> --}}
                        </div>
                        <div class="card-footer">
                            <div class='row'></div>
                        </div>
                    </div>
                    @stop

                    @section('footer')
                    <!-- Footer Baru -->
                    <div class="card mb-2">
                        <div class="card-content p-2"  style="white-space:nowrap;display:inline-block;">
                            <div class="row float-left">
                                <div class="cell-3 float-left">
                                    {{ Form::number('SubTotal','Sub Total', 'readonly') }}
                                </div>
                                <div class="cell-3 float-left">
                                    {{ Form::number('DiscAmountH','Disc', 'readonly') }}
                                </div>
                                <div class="cell-3">
                                    {{ Form::number('TaxAnount','Tax', 'readonly') }}
                                </div>
                                <div class="cell-3">
                                    {{ Form::number('Total','Total', 'readonly') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @stop

                    @section('js')
                    <!-- PHP Function -->
                    <?php
                        function makeeditgrid()
                        {
                            //$f = [
                            //    ['name' => 'ProductCode', 'caption' => 'Product#', 'type' => 'select', 'list' => $mProduct, 'width' => '60'],
                            //    ['name' => 'Qty', 'caption' => 'Quantity', 'width' => '10'],
                            //    ['name' => 'Price', 'caption' => 'Price', 'width' => '20'],
                            //    ['name' => 'Amount', 'caption' => 'Amount', 'width' => '20', 'other' => 'num disabled']
                            //];
                            $f = [
                                ['name' => 'ProductCode', 'caption' => 'Product#', 'width' => '20'],
                                ['name' => 'cm', 'type' => 'button', 'modal' => 'modal-product', 'target' => 'ProductCode', 'target2' => 'ProductName'],
                                ['name' => 'ProductName', 'caption' => 'Product Name', 'width' => '40', 'other' => 'disabled'],
                                ['name' => 'Qty', 'caption' => 'Quantity', 'width' => '10'],
                                ['name' => 'Price', 'caption' => 'Price', 'width' => '20'],
                                ['name' => 'Amount', 'caption' => 'Amount', 'width' => '20', 'other' => 'num disabled']
                            ];
                            return editGridNew('grid', $f);
                        }
                    ?>
                    <!-- End PHP Function -->

                    <!-- JS Function -->
                    <script>
                        function calcAll() {
                            var tot = 0;
                            var line = 0;
                            $.each($("div.grow"), function() {
                                if ($(this).is(':visible')) {
                                    var qty = cnum(cell(line, 'Qty'));
                                    var price = cnum(cell(line, 'Price'));
                                    var amount = qty * price;
                                    setcell(line, 'Amount', amount);
                                    tot = tot + amount;
                                }
                                line++;
                            });
                            //tot=fnum(tot);
                            $("input[name='SubTotal']").val(tot);
                            // alert( $("input[name='SubTotal']").val() );
                            //alert('xx');
                        }
                        function showLookup(lookupType, target) {
                            alert(lookupType + ' - ' + target);
                        }
                    </script>

                    <!-- JQuery SCRIPT -->
                    <script>
                        $(document).ready(function() {
                            //Edit grid
                            $('input.cell').change(function() {
                                var row = target = $(this).parent().attr('line');
                                var col = $(this).attr('name');
                                switch (col) {
                                    case 'grid-ProductCode[]':
                                        //alert(col);
                                        /*
                                        var id = cell(row, 'ProductCode');
                                        var dataSource = '_loaddatarow/product/' + id;
                                        $.getJSON(dataSource, function(data, status) {
                                                setcell(row, 'ProductName', data.Name);
                                            })
                                            .error(function(jqXHR, textStatus, errorThrown) {
                                                console.log("error " + textStatus);
                                                console.log("incoming Text " + jqXHR.responseText);
                                            });
                                        */
                                        break;
                                    case 'grid-Qty[]':
                                    case 'grid-Price[]':
                                        calcAll();
                                        break;
                                }
                            });
                            calcAll();
                        });
                    </script>
                @stop
