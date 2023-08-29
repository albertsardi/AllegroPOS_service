<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Allegro - ERP System Administrator</title>
<meta name="description" content="Allegro - ERP System Administrator">
<meta name="author" content="Albert - (c)ASAfoodenesia">

<html lang="en">
<head>
   <meta name="csrf-token" content="9FfOwNmxTo3GQxAvVdIOX3NiyW5QO7jjRPknhKxy" />
    <!-- BEGIN CSS for this page -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/fontawesome/font-awesome.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/plugin/DatePicker/bootstrap-datepicker.standalone.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/plugin/select2/select2.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/plugin/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/ribbon.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" >
    <style>
        .modal table td{margin-top:2px;margin-bottom:2px;padding:2px;}
    </style>
     <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-grid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/ag-grid/styles/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />
    <!-- END CSS for this page -->
</head>

<!--<body class="adminbody widescreen" ng-controller="formCtrl">-->
<body >
<div id="main">
	<!-- top bar navigation -->
    <div class="headerbar">

  <!-- LOGO -->
  <div class="headerbar-left">
    <a href="index.php" class="logo"><img src='http://localhost/lav7_PikeAdmin_multi/assets/images/logo.png' alt='AllegroLogo'/><span>Allegro</span></a>
      </div>

      <nav class="navbar-custom">

                  <ul class="list-inline float-right mb-0">

          <li class="list-inline-item dropdown notif">
                          <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              <i class="fa fa-fw fa-question-circle"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-arrow-success dropdown-lg">
                              <!-- item-->
                              <div class="dropdown-item noti-title">
                                  <h5><small>Help and Support</small></h5>
                              </div>

                              <!-- item-->
                              

                              <!-- item-->
                              <a target="_blank" href="https://www.pikeadmin.com/pike-admin-pro" class="dropdown-item notify-item">
                                  <p class="notify-details ml-0">
                                      <b>This ERP system still on development</b> some menu can ot be access yet.
                                      <span>(c) InterSoft Media</span>
                                      <span>Develop by Albert</span>
                                  </p>
                              </a>

                          </div>
                      </li>

                      <li class="list-inline-item dropdown notif">
                          <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              <i class="fa fa-fw fa-envelope-o"></i><span class="notif-bullet"></span>
                          </a>
                          
                      </li>

          <li class="list-inline-item dropdown notif">
                          <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              <i class="fa fa-fw fa-bell-o"></i><span class="notif-bullet"></span>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg">
              <!-- item-->
                              <div class="dropdown-item noti-title">
                                  <h5><small><span class="label label-danger pull-xs-right">5</span>Allerts</small></h5>
                              </div>

                              <!-- item-->
                              <a href="#" class="dropdown-item notify-item">
                                  <div class="notify-icon bg-faded">
                                      <img src="http://localhost/lav7_PikeAdmin_multi/assets/images/avatars/avatar2.png" alt="img" class="rounded-circle img-fluid">
                                  </div>
                                  <p class="notify-details">
                                      <b>John Doe</b>
                                      <span>User registration</span>
                                      <small class="text-muted">3 minutes ago</small>
                                  </p>
                              </a>

                              <!-- item-->
                              <a href="#" class="dropdown-item notify-item">
                                  <div class="notify-icon bg-faded">
                                      <img src="http://localhost/lav7_PikeAdmin_multi/assets/images/avatars/avatar3.png" alt="img" class="rounded-circle img-fluid">
                                  </div>
                                  <p class="notify-details">
                                      <b>Michael Cox</b>
                                      <span>Task 2 completed</span>
                                      <small class="text-muted">12 minutes ago</small>
                                  </p>
                              </a>

                              <!-- item-->
                              <a href="#" class="dropdown-item notify-item">
                                  <div class="notify-icon bg-faded">
                                      <img src="http://localhost/lav7_PikeAdmin_multi/assets/images/avatars/avatar4.png" alt="img" class="rounded-circle img-fluid">
                                  </div>
                                  <p class="notify-details">
                                      <b>Michelle Dolores</b>
                                      <span>New job completed</span>
                                      <small class="text-muted">35 minutes ago</small>
                                  </p>
                              </a>

                              <!-- All-->
                              <a href="#" class="dropdown-item notify-item notify-all">
                                  View All Allerts
                              </a>

                          </div>
                      </li>

                      <li class="list-inline-item dropdown notif">
                          <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                              <img src="http://localhost/lav7_PikeAdmin_multi/assets/images/avatars/admin.png" alt="Profile image" class="avatar-rounded">
                          </a>
                          <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                              <!-- item-->
                              <div class="dropdown-item noti-title">
                                    
                                                                      <h5 class="text-overflow"><small>Hello, admin</small> </h5>
                              </div>

                              <!-- item-->
                              <a href="http://localhost/lav7_PikeAdmin_multi/profile" class="dropdown-item notify-item">
                                  <i class="fa fa-user"></i> <span>Profile</span>
                              </a>

                              <!-- item-->
                              <a href="http://localhost/lav7_PikeAdmin_multi/logout" class="dropdown-item notify-item">
                                  <i class="fa fa-power-off"></i> <span>Logout</span>
                              </a>
                          </div>
                      </li>

                  </ul>

                  <ul class="list-inline menu-left mb-0">
                      <li class="float-left">
                          <button class="button-menu-mobile open-left">
              <i class="fa fa-fw fa-bars"></i>
                          </button>
                      </li>
                  </ul>

      </nav>

</div>
    <!-- End Navigation -->

    <!-- Left Sidebar -->
        @extends('menu')
    <!-- End Sidebar -->


    <div class="content-page">

		<!-- Start content -->
        <div class="content">

			<div class="container-fluid">
			    <div class="row">
					<div class="col-xl-12">
                        <div class="breadcrumb-holder">
                            <h1 class="main-title float-left">{{$caption}}</h1>
                            <ol class="breadcrumb float-right">
                                <li class="breadcrumb-item">Home</li>
                                <li class="breadcrumb-item active">Forms</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
					</div>
			    </div>

                <!-- alert info -->
			    <div id='result'></div>
                                <!-- End alert info -->
            
            
                <!-- panel button -->
                
                <div class="row">
                    <div class="card card-body mb-2">
                        <div class="form-group row">
                            <div class="col">
                                                                                                                                <button id='cmSave2' type='submit'  class="btn btn-primary btn-sm btn-submit">Save</button>

                                <button id='cmSave' type='button'  class="btn btn-primary btn-sm btn-submit">Save use ajax</button>
                                <button id='cmPrint' type="submit" disabled  class="btn btn-primary btn-sm">Print</button>
                            </div>
                            <div class="col text-right">
                                                                                                                                                                                            </div>
                        </div>
                    </div>
			    </div>

                <div class='xrow'>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="card mb-3 border-info">
                            <div class="card-header text-white bg-info py-1 h5">
                                <i class="fa fa-check-square-o"></i>{{$caption}}
                                                <div class="h6 text-muted float-right"></div>
                            </div>
                            <div class="card-body">
                                
                                <h3>using ag-grid</h3>
                                <div id="xgrid" class="ag-theme-alpine w-100 my-2" style="height: 300px;"></div>
                                <input type='hidden' name='detail' value=''>   
                                <button id='cmAddrow-aggrid' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>


                                <h3>using handsontable</h3>
                                <div id="hsgrid" class="w-100 my-2" style="height: 300px;"></div>
                                <input type='hidden' name='detail' value=''>   
                                <button id='cmAddrow-hsgrid' class='btn btn-secondary' type='button'><i class="fa fa-plus"></i> Add line</button>
                            
                            
                            
                            
                            </div>
                        </div><!-- end card-->
                    </div>
                </div>
            </div>
			<!-- END container-fluid -->
		</div>
		<!-- END content -->

    </div>
	<!-- END content-page -->

	<footer class="footer">
		<? #require 'footer.php' ?>
	</footer>

</div>
<!-- END main -->

<!-- BEGIN Java Script for this page -->
<script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugin/DatePicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>

<script src="{{ asset('assets/js/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootbox.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/fastclick.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugin/select2/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugin/axios/axios.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pikeadmin.js') }}" type="text/javascript"></script>

<script type="text/javascript">
   $(document).ready(function() {
        //init api url
        window.API_URL = "http://localhost/lav7_PikeAdmin_multi";
        //init datepicker
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            clearBtn:true,
        });
        //init select2
        $('.select2').select2({
            theme: "bootstrap",
            placeholder: 'Select an option'
        });
	})
</script>
    <script src="{{ asset('assets/plugin/ag-grid/ag-grid-community.min.noStyle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/textwlookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/lookup/lookup.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_mselect.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_form.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/helper_grid.js') }}" type="text/javascript"></script>
    
    
    <script>
        var selRowIdx = null;
        var selRow = null;
        var selModal = null;
        var lookup_target_button = null;

        //ag-grid
        windows.grid.type=='ag-grid'; //uncomment jika menggunakan ag-grid
        //load grid data
        var griddata = []
        calcAll();
        //init ag-grid
        var colModel = [
            { field: "ProductCode", headerName: 'Product #', editable:false, edittype:'text', width: 150, 
                cellRenderer:function(row)  {
                    return setLookupButton(row, 'modal-product')
                },
                cellRendererParams: {
                    clicked: function(field) {
                        alert(`${field} was clicked`);
                    }
                }
            },
            { field: "ProductName", headerName: 'Product Name', width: 270 },
            { field: "Qty", headerName: 'Qty', width: 80, editable:true },
            { field: "Price", headerName: 'Price', width: 120, editable:true },
            { field: "Amount", headerName: 'Amount', width: 120 },
            { field: "Memo", headerName: 'Memo', editable:true, edittype:'text', width: 150 },
        ];
        var gridOptions = {
            //columnDefs: colModel,
            columnDefs: setColModel(colModel),
            rowData: griddata,
            caption: 'Grid Order',
            enableCellChangeFlash: true,
            editType: 'fullRow',
            rowSelection: 'single',
            onRowEditingStarted: (event) => {
                //console.log('on event onRowEditingStarted');
            },
            onRowEditingStopped: (event) => {
                //console.log('on event onRowEditingStopped');
                calcAll();
            },
            onCellEditingStarted: (event) => {
                //console.log('cellEditingStarted');
            },
            onCellEditingStopped: (event) => {
                //console.log('cellEditingStopped');
            },
            onGridReady: function (params) {
                sequenceId = 1;
                allOfTheData = [];
                for (var i = 0; i < 4; i++) {
                    //allOfTheData.push(createRowData(sequenceId++));
                    allOfTheData.push(griddata[i]);
                }
            },
            components: {
                //btnCellRenderer: BtnCellRenderer,
                //numericCellEditor: NumericEditor,
                //moodCellRenderer: MoodRenderer,
                //moodEditor: MoodEditor,
            },
        }

        
        //using handsontable
        windows.grid.type=='handsontable';
        const container = document.querySelector('#hsgrid');
        const hot = new Handsontable(container, {
            data: [
                ['', 'Tesla', 'Volvo', 'Toyota', 'Ford'],
                ['2019', 10, 11, 12, 13],
                ['2020', 20, 11, 14, 13],
                ['2021', 30, 15, 12, 13]
            ],
            rowHeaders: true,
            colHeaders: true,
            height: 'auto',
            width: 'auto',
            licenseKey: 'non-commercial-and-evaluation', // for non-commercial use only
            afterOnCellMouseDown: function(event, coords, td) {
                                    var now = new Date().getTime();
                                    // check if dbl-clicked within 1/5th of a second. change 200 (milliseconds) to other value if you want
                                    if(!(td.lastClick && now - td.lastClick < 200)) {
                                        td.lastClick = now;
                                        return; // no double-click detected
                                    }
                                    // double-click code goes here
                                    //console.log('double clicked');
                                    if(coords.col==0) { 'click first col'
                                        alert('double clicked');
                                        //console.log(coords)
                                        selRowIdx = coords.row
                                        $('#modal-product').modal()
                                    }
                                }
        });


        $(document).ready(function() {
            //init page
            //$(':input[type=number]').on('mousewheel',function(e){ $(this).blur();  });
            //$('select.select2').select2({ theme: "bootstrap" });

            var selRow =[];
            var xgd =  document.querySelector('#xgrid');
            new agGrid.Grid(xgd, gridOptions);
            calcAll();

            // EVENT
            //add new line
            $("button#cmAddrow-aggrid").click(function(e){
                var newLine = { ProductCode:'', ProductName:'', Qty:0, Price:0, Amount:0 }
                griddata.push(newLine)
                gridOptions.api.setRowData(griddata);
            });
            //delete row 
            $(document).on('click','button.cmDelrow',function(e){
                const selRow = gridOptions.api.getSelectedRows();
                gridOptions.rowData.splice(selRow, 1);
                gridOptions.api.setRowData(gridOptions.rowData);
            });
            
            //save data
            $("button#cmSave2").click(function(e){
                e.preventDefault();
                alert('save');
                //submit grid data to variable
                const rowData = [];
                gridOptions.api.forEachNode(function (node) {
                    rowData.push(node.data);
                });
                $("input[name='detail']").val(JSON.stringify(rowData));
                
                var formdata=$('form').serialize();
                $('#formData').submit();
                
            });
            
            $('.modal').on('show.bs.modal', function (e) {
                lookup_target_button = $(e.relatedTarget) // Button that triggered the modal
            })
        });

        async function afterModalClose(sel) {
            if (sel.selModal == 'modal-salesorder') {
                var btn = lookup_target_button;
                $('#'+btn.attr('id')).textwlookup(sel.selRow[0], sel.selRow[2]+','+sel.selRow[1]);
                refreshDO();
            }
            if (sel.selModal == 'modal-product') {
                if(windows.grid.type=='handsontable') {
                    //handsontable
                    hot.setDataAtCell(selRowIdx,0,sel.selRow[0]);
                    hot.setDataAtCell(selRowIdx,1,sel.selRow[1]);
                } else {
                    //ag-grid
                    var selRow = gridOptions.api.getSelectedRows();
                    var nodes = gridOptions.api.getSelectedNodes();
                    selRowIdx = nodes[0].rowIndex;
                    griddata[selRowIdx].ProductCode = sel.selRow[0]
                    griddata[selRowIdx].ProductName = sel.selRow[1]
                }
                
                


                /*let resp = await fetch('http://localhost/lav7_PikeAdmin_multi/getbalanceproduct/'+ sel.selRow[0]);
                if (resp.statusText=='OK') {
                    let dat = await resp.json();
                    griddata[selRowIdx].StockQty = dat.Qty;
                } else {
                    griddata[selRowIdx].StockQty = 0;
                }*/

                //get sell price
                //http://localhost/lav7_PikeAdmin_multi/api/masterproductprice?code=BENANG-KARET
                let resp = await fetch('http://localhost/lav7_PikeAdmin_multi/api/masterproductprice?code='+ sel.selRow[0]);
                if (resp.statusText=='OK') {
                    let dat = await resp.json();
                    griddata[selRowIdx].Price = Number(dat[0].Level1??0);
                } else {
                    griddata[selRowIdx].Price = 0;
                }

                gridOptions.api.setRowData(griddata);
                calcAll();
            }
        };

        function calcAll() {
            var tot = 0;
            for(let r of griddata) {
                r.Amount = Number(r.Qty) * Number(r.Price);
                tot+= r.Amount;
            }
            if (gridOptions) gridOptions.api.setRowData(griddata); //save to grid buffer
            
            var gtot    = tot - $('#DiscAmountH').val() + $('#TaxAmount').val();
            var unpaid  = gtot - $('#FirstPayment').val(); 
            $('#SubTotal').val(tot);
            $('#Total').val(gtot);
            $('#UnpaidAmount').val(unpaid);
        }
    </script>
    
<!-- END Java Script for this page -->

</body>
</html>

<!-- Modal -->
   <div class="modal fade" id="modal-product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table id="listProduct" class="table table-bordered table-hover display mx-10 w-100">
			<thead>
				<th>Product #</th><th>Name</th><th>Category</th>
			</thead>
			<tbody></tbody>
		</table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<script>
    $('#listProduct').DataTable({
                paging: true,
                pageLength: 10,
                pagingType: "full_numbers",
                data: [{"Code":"1","Name":"234-test","Category":"-"},{"Code":"10","Name":"Mild 12","Category":"ROKOK"},{"Code":"11","Name":"Star Mild","Category":"ROKOK"},{"Code":"12","Name":"Marlboro Mrh","Category":"ROKOK"},{"Code":"123","Name":"456","Category":""},{"Code":"13","Name":"Marlboro Pth","Category":"ROKOK"},{"Code":"14","Name":"Marlboro Mtl","Category":"ROKOK"},{"Code":"15","Name":"LA Bold","Category":"ROKOK"},{"Code":"16","Name":"K Tebu","Category":"ROKOK"},{"Code":"17","Name":"Gudang Garam Merah","Category":"ROKOK"},{"Code":"176","Name":"Ds Kopikap","Category":"KOPI"},{"Code":"18","Name":"Surya 16","Category":"ROKOK"},{"Code":"19","Name":"Surya Pro","Category":"ROKOK"},{"Code":"2","Name":"Filter","Category":"ROKOK"},{"Code":"20","Name":"Envio","Category":"ROKOK"},{"Code":"21","Name":"MLD Black","Category":"ROKOK"},{"Code":"22","Name":"LA","Category":"ROKOK"},{"Code":"23","Name":"Mustang","Category":"ROKOK"},{"Code":"24","Name":"Jinggo","Category":"ROKOK"},{"Code":"25","Name":"234 16 test","Category":"-"},{"Code":"26","Name":"Marl Ice Blast","Category":"ROKOK"},{"Code":"27","Name":"234 Refil","Category":"ROKOK"},{"Code":"28","Name":"234 Magnum","Category":"ROKOK"},{"Code":"29","Name":"Bentoel Biru","Category":"ROKOK"},{"Code":"3","Name":"Super","Category":"ROKOK"},{"Code":"30","Name":"Laksa","Category":"ROKOK"},{"Code":"31","Name":"U mild","Category":"ROKOK"},{"Code":"32","Name":"Class Mild","Category":"ROKOK"},{"Code":"33","Name":"Filter KLG","Category":"ROKOK"},{"Code":"330","Name":"Nu tea pc","Category":"SUSU"},{"Code":"34","Name":"Wismilak","Category":"ROKOK"},{"Code":"343","Name":"Susu Zee","Category":"SUSU"},{"Code":"35","Name":"Pro Mild","Category":"ROKOK"},{"Code":"351","Name":"Ds ABC Moka","Category":"KOPI"},{"Code":"352","Name":"Ds ABC Susu","Category":"KOPI"},{"Code":"353","Name":"Top Gula Aren","Category":"KOPI"},{"Code":"355","Name":"Ls Ben Cok Sak","Category":"SUSU"},{"Code":"357","Name":"Ls Ben Put Sak","Category":"SUSU"},{"Code":"36","Name":"Surya 12","Category":"ROKOK"},{"Code":"362","Name":"Ls Enak Sak","Category":"SUSU"},{"Code":"364","Name":"Bl Good Day","Category":"KOPI"},{"Code":"366","Name":"Milkuat botol","Category":"SUSU"},{"Code":"368","Name":"Ds Kapalapi Mix","Category":"KOPI"},{"Code":"37","Name":"Dunhill Mild","Category":"ROKOK"},{"Code":"370","Name":"Bl Medali","Category":"KOPI"},{"Code":"371","Name":"Ds Ultra 200ml","Category":"SUSU"},{"Code":"375","Name":"Mlkuat bantal","Category":"SUSU"},{"Code":"376","Name":"Milkymoo","Category":"SUSU"},{"Code":"379","Name":"Ds Susu Beruang","Category":"SUSU"},{"Code":"38","Name":"Signature","Category":"ROKOK"},{"Code":"381","Name":"Bl Nikmat","Category":"KOPI"},{"Code":"382","Name":"Bl Piala Gula","Category":"KOPI"},{"Code":"39","Name":"Marl Fil Black","Category":"ROKOK"},{"Code":"390","Name":"Ds Kopi Luwak","Category":"KOPI"},{"Code":"391","Name":"Ds Tora Moka","Category":"KOPI"},{"Code":"392","Name":"Ds Tora Susu","Category":"KOPI"},{"Code":"396","Name":"Ds Indomilk Kid","Category":"SUSU"},{"Code":"397","Name":"Ds Ultra 250ml","Category":"SUSU"},{"Code":"398","Name":"Ds Indomilk btl","Category":"SUSU"},{"Code":"399","Name":"Ds Ultra 125ml","Category":"SUSU"},{"Code":"4","Name":"Super 16","Category":"ROKOK"},{"Code":"40","Name":"Magnum Mild","Category":"ROKOK"},{"Code":"401","Name":"Pk 234","Category":"ROKOK"},{"Code":"402","Name":"Pk Filter","Category":"ROKOK"},{"Code":"403","Name":"Pk Super","Category":"ROKOK"},{"Code":"404","Name":"Pk Super 16","Category":"ROKOK"},{"Code":"405","Name":"Pk Coklat","Category":"ROKOK"},{"Code":"406","Name":"Pk SK","Category":"ROKOK"},{"Code":"407","Name":"Pk Sejati","Category":"ROKOK"},{"Code":"408","Name":"Pk Mild 16","Category":"ROKOK"},{"Code":"409","Name":"Pk Mild Mtl","Category":"ROKOK"},{"Code":"41","Name":"Toppas","Category":"ROKOK"},{"Code":"410","Name":"Pk Mild 12","Category":"ROKOK"},{"Code":"411","Name":"Pk Star 16","Category":"ROKOK"},{"Code":"412","Name":"Pk Marlboro Merah","Category":"ROKOK"},{"Code":"413","Name":"Pk Marlboro Putih","Category":"ROKOK"},{"Code":"414","Name":"Pk Marlboro Mentol","Category":"ROKOK"},{"Code":"415","Name":"Pk LA Bold","Category":"ROKOK"},{"Code":"416","Name":"Pk K Tebu","Category":"ROKOK"},{"Code":"417","Name":"Pk G merah","Category":"ROKOK"},{"Code":"418","Name":"Pk Surya 16","Category":"ROKOK"},{"Code":"419","Name":"Pk Surya Pro","Category":"ROKOK"},{"Code":"42","Name":"Neslite","Category":"ROKOK"},{"Code":"420","Name":"Pk Envio","Category":"ROKOK"},{"Code":"421","Name":"Pk MLD Black","Category":"ROKOK"},{"Code":"422","Name":"Pk LA","Category":"ROKOK"},{"Code":"423","Name":"Pk Mustang","Category":"ROKOK"},{"Code":"424","Name":"Pk Jinggo","Category":"ROKOK"},{"Code":"425","Name":"Pk 234 16","Category":"ROKOK"},{"Code":"426","Name":"Pk Marlboro Ice Blast","Category":"ROKOK"},{"Code":"427","Name":"Pk 234 Refil","Category":"ROKOK"},{"Code":"428","Name":"Pk Magnum","Category":"ROKOK"},{"Code":"43","Name":"L Strike Bold 12","Category":"ROKOK"},{"Code":"431","Name":"Pk U mild","Category":"ROKOK"},{"Code":"432","Name":"Pk Clas Mild","Category":"ROKOK"},{"Code":"434","Name":"Pk Wismilak","Category":"ROKOK"},{"Code":"435","Name":"Pk Pro Mild","Category":"ROKOK"},{"Code":"436","Name":"Pk surya 12","Category":"ROKOK"},{"Code":"437","Name":"Pk Dunhill Mild","Category":"ROKOK"},{"Code":"439","Name":"Pk Marlboro FilBlack","Category":"ROKOK"},{"Code":"44","Name":"Wismilak Slim","Category":"ROKOK"},{"Code":"440","Name":"Pk Magnum Mild","Category":"ROKOK"},{"Code":"441","Name":"Pk Toppas","Category":"ROKOK"},{"Code":"442","Name":"Pk Neslite","Category":"ROKOK"},{"Code":"443","Name":"Pk L Strike Bold","Category":"ROKOK"},{"Code":"444","Name":"Pk Wismilak Slim","Category":"ROKOK"},{"Code":"445","Name":"Pk U Bold","Category":"ROKOK"},{"Code":"446","Name":"Pk Jrm MLD","Category":"ROKOK"},{"Code":"447","Name":"Pk Luckystrk Mil","Category":"ROKOK"},{"Code":"448","Name":"Pk U Mild Cool","Category":"ROKOK"},{"Code":"449","Name":"Pk Dunhill htm","Category":"ROKOK"},{"Code":"45","Name":"U Bold","Category":"ROKOK"},{"Code":"46","Name":"Jrm MLD","Category":"ROKOK"},{"Code":"47","Name":"Luckystrike Mild","Category":"ROKOK"},{"Code":"48","Name":"U Mild Cool","Category":"ROKOK"},{"Code":"49","Name":"Dunhill htm","Category":"ROKOK"},{"Code":"5","Name":"Coklat","Category":"ROKOK"},{"Code":"50","Name":"Bendera Kaleng Kremer","Category":"SUSU"},{"Code":"51","Name":"ABC Moca","Category":"KOPI"},{"Code":"52","Name":"ABC Susu","Category":"KOPI"},{"Code":"53","Name":"ABC White","Category":"KOPI"},{"Code":"54","Name":"Ben Cok Klg","Category":"SUSU"},{"Code":"55","Name":"Ben Cok Sak","Category":"SUSU"},{"Code":"56","Name":"Ben Klg Gold","Category":"SUSU"},{"Code":"57","Name":"Ben Put Sak","Category":"SUSU"},{"Code":"58","Name":"Coffemix","Category":"KOPI"},{"Code":"59","Name":"Dancow Coklat","Category":"SUSU"},{"Code":"6","Name":"Samp.kretek","Category":"ROKOK"},{"Code":"60","Name":"Dancow Putih","Category":"SUSU"},{"Code":"61","Name":"Enak klg","Category":"SUSU"},{"Code":"62","Name":"Enak Sak","Category":"SUSU"},{"Code":"63","Name":"Energen","Category":"SUSU"},{"Code":"64","Name":"Goodday","Category":"KOPI"},{"Code":"65","Name":"Top White Coffe","Category":"KOPI"},{"Code":"66","Name":"Milkuat Botol","Category":"SUSU"},{"Code":"67","Name":"Nikmat Sp","Category":"KOPI"},{"Code":"671","Name":"Ds Bendera sak","Category":"SUSU"},{"Code":"68","Name":"Kapalapi Mix","Category":"KOPI"},{"Code":"69","Name":"Ds Real Good","Category":"SUSU"},{"Code":"7","Name":"Sejati","Category":"ROKOK"},{"Code":"70","Name":"Medali","Category":"KOPI"},{"Code":"71","Name":"Ultra 200 ml","Category":"SUSU"},{"Code":"72","Name":"Kopi Liong 1\/4","Category":"KOPI"},{"Code":"73","Name":"Kopi Liong Gula","Category":"KOPI"},{"Code":"731","Name":"Bl Kopi Uhui","Category":"KOPI"},{"Code":"732","Name":"Goodday Capucinno","Category":"KOPI"},{"Code":"74","Name":"Kopi Liong Super","Category":"KOPI"},{"Code":"75","Name":"Mlkuat bantal","Category":"SUSU"},{"Code":"76","Name":"Milkymoo","Category":"SUSU"},{"Code":"77","Name":"Milo","Category":"SUSU"},{"Code":"78","Name":"Nikmat White","Category":"KOPI"},{"Code":"79","Name":"Susu Beruang","Category":"SUSU"},{"Code":"8","Name":"Mild 16","Category":"ROKOK"},{"Code":"80","Name":"Nestle","Category":"SUSU"},{"Code":"801","Name":"Bl 234","Category":"ROKOK"},{"Code":"802","Name":"Bl Filter","Category":"ROKOK"},{"Code":"803","Name":"Bl Super","Category":"ROKOK"},{"Code":"808","Name":"Bl Mild 16","Category":"ROKOK"},{"Code":"82","Name":"Piala Gula","Category":"KOPI"},{"Code":"823","Name":"Bl Mustang","Category":"ROKOK"},{"Code":"828","Name":"Bl 234 Magnum","Category":"ROKOK"},{"Code":"83","Name":"Kapal Api Gula Aren","Category":"KOPI"},{"Code":"831","Name":"Bl U Mild","Category":"ROKOK"},{"Code":"853","Name":"Ds ABC White","Category":"KOPI"},{"Code":"86","Name":"Kopi Uhui","Category":"KOPI"},{"Code":"860","Name":"Bl Liong gula","Category":"KOPI"},{"Code":"89","Name":"Tora Capucino\/Latte","Category":"KOPI"},{"Code":"9","Name":"Mild mtl","Category":"ROKOK"},{"Code":"90","Name":"Kopi Luak","Category":"KOPI"},{"Code":"91","Name":"Tora Moka","Category":"KOPI"},{"Code":"92","Name":"Tora Susu","Category":"KOPI"},{"Code":"925","Name":"Tora Double Up","Category":"KOPI"},{"Code":"93","Name":"Kopiko Brown","Category":"KOPI"},{"Code":"930","Name":"Tora Cafe","Category":"KOPI"},{"Code":"94","Name":"Tora Duo","Category":"KOPI"},{"Code":"95","Name":"Kopi Top Susu","Category":"KOPI"},{"Code":"97","Name":"Ultra 250 ml","Category":"SUSU"},{"Code":"99","Name":"Ultra 125 ml","Category":"SUSU"},{"Code":"test","Name":"test","Category":""},{"Code":"test","Name":"test2","Category":"-|-"},{"Code":"test2","Name":"test2","Category":""},{"Code":"test3","Name":"test2","Category":""},{"Code":"test4","Name":"test","Category":""}],
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, sett) {
                            var drow = `${row.Code}|${row.Name}|${row.Category}`;
                            return `<a href='' class='lookup-item' rowIdx=${sett.row} data-drow='${drow}'>`+ data['Code'] +`</a>`;
                        }
                    },
                    { data: 'Name' },
                    { data: 'Category' },
                ]
            }); 
</script>
<!-- End Modal -->


