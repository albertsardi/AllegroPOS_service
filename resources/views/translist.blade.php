<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Allegro - ERP System Administrator</title>
<meta name="description" content="Allegro - ERP System Administrtor">
<meta name="author" content="Albert - (c)ASAfoodenesia">

<html lang="en">

<head>
	<!-- BEGIN CSS for this page -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/fontawesome/font-awesome.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/plugin/select2/select2.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/plugin/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" >
	<style>
		.col-date { color: darkgreen; }
	</style>
	<!-- END CSS for this page -->
</head>

<body class="adminbody">

	<div id="main">

		<!-- top bar navigation -->
		@extends('topmenu')
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
								<h1 class="main-title float-left">{{ $caption }}</h1>
								<ol class="breadcrumb float-right">
									<li class="breadcrumb-item">Home</li>
									<li class="breadcrumb-item active">Data Tables</li>
								</ol>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<!-- end row -->

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div class="card mb-3">
								<div class="card-header">
									<h3>
										<i class="fa fa-table"></i> Trans list
										<div class='float-right'>
											<?php
												$link = '/trans-edit/';
												if (in_array($jr, ['AR','AP'])) $link = '/payment/edit/';
											?>
											{{-- <button id="cmNew" type="button" class="btn btn-primary btn-sm btn-submit">New</button> --}}
											<a href='{{ url($link.$jr.'/NEW') }}' class="btn btn-primary btn-sm">New</a>
											<a href='{{ url('translist/'.$jr.'/pdf_usingTPDF') }}' class="btn btn-primary btn-sm">Print</a>
                                            <a href='{{ url('translist/'.$jr.'/excel') }}' class="btn btn-primary btn-sm btn-submit">to Excel</a>
                                            <a href='{{ url('translist/'.$jr.'/pdf') }}' class="btn btn-primary btn-sm btn-submit">to PDF</a>
										</div>
									</h3>
								</div>
								<div class="card-body">
                            <table id="example1" class="table table-bordered table-hover display w-100">
					                  {!! $grid !!}
                            </table>
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
				<?php #require 'footer.php'	?>
			</footer>

		</div>
		<!-- END main -->

		<!-- BEGIN Java Script for this page -->
		<script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/popper.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugin/dataTables/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugin/dataTables/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    	<script src="{{ asset('assets/plugin/dataTables/jszip.min.js') }}" type="text/javascript"></script>
    	<script src="{{ asset('assets/plugin/dataTables/pdfmake.min.js') }}" type="text/javascript"></script>
    	<script src="{{ asset('assets/plugin/dataTables/vfs_fonts.js') }}" type="text/javascript"></script>
    	<script src="{{ asset('assets/plugin/dataTables/buttons.html5.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/bootbox.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/fastclick.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/plugin/select2/select2.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/js/pikeadmin.js') }}" type="text/javascript"></script>
		<script type="text/javascript">
			// START CODE FOR BASIC DATA TABLE
			$(document).ready(function() {
				var fnum    = $.fn.dataTable.render.number(',','.',0,'')
				var fcur    = $.fn.dataTable.render.number(',','.',0,'Rp ')
                var fcurB   = function(data, type) {
                                var cur = 'Rp ';
                                var number = $.fn.dataTable.render.number( ',', '.', 2, ''). display(Math.abs(data));
                                if (data==0) {
                                    return '<span style="color:black">' +cur + number + '</span>';
                                } else if (data<0) {
                                    return '<span style="color:red">' +cur + ' (' + number + ')</span>';
                                } else {
                                    return '<span style="color:darkgreen">'+ cur + number + '</span>';
                                }
                            }    
            var fdate       = function (data, type, row) { 
                                return moment(data).format('DD/MM/YYYY'); 
                            }
			var jr='{{$jr}}';
            var opt={
               //processing: true,
               //serverSide: true,
               paging: true,
               pagingType: "full_numbers",
               pageLength: 10,
					deferRender: true,
            	}
				switch (jr) {
					case 'DO':
						$('#example1').DataTable({
                     		data: {!! json_encode($data) !!},
							columns: [
								{ data: null, 
									render: function(data, type, row) {
										//return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['DONo']+">"+data['DONo']+"</a>";
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200, render: fdate },
             					{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['OrderNo']+"'>"+data['OrderNo']+"</a>";
									}
								},
								{ data: 'OrderDate', width:200, render: fdate },
								{ data: 'AccName'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate', width:200, render: fdate },
							]
                  		});
					   break;
					case 'PI':
					case 'SI':
					case 'IN':
						$('#example1').DataTable({
                        	data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200,title:'tanggal',render: fdate },
								{ data: 'AccName'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate', width:200, render: fdate },
								//{ data: 'UpdatedDate'},
							],
							dom: 'Bfrtip',
							buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
						});
						break;
					case 'PO':
						$('#example1').DataTable({
							data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200,title:'tanggal',
									render: fdate
								},
								{ data: 'AccName'},
								{ data: 'Note'},
								{ data: 'Note'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate'},
								{ data: 'UpdatedDate'},
							],
							dom: 'Bfrtip',
							buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
						});
						break;
					case 'SO':
						$('#example1').DataTable({
                        	data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200,title:'tanggal',
									render: fdate
								},
								{ data: 'AccName'},
								{ data: 'Note'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate', width:200, render: fdate },
								{ data: 'UpdatedDate', width:200, render: fdate },
							],
							dom: 'Bfrtip',
							buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
						});
						break;
					case 'accountdetail':
						$('#example1').DataTable({
            				//'ajax':"{{ route('ajax_translist', 'EX' ) }}",
                            //ajax:"{{$_url}}",
                            data: {!! json_encode($data) !!},
                            //scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
								//ajax:"http://localhost:500/api/trans/accdetail/1-1200",
								columns: [
									{ data: 'JRdate', width:200,
										render: fdate
									},
									{ data: 'AccNo'},
									{ data: 'AccName'},
									{ data: 'ReffNo'},
									{ data: 'JRdesc'},
									{ data: 'Debet', className:'col-number', render: fcurB },
									{ data: 'Credit', className:'col-number', render: fcurB },
									{ data: 'Bal', className:'col-number', render: fcurB },
									//{ data: 'CreatedDate'}
								]
						});
						break; 
					case 'AR':
					case 'AP':
						$('#example1').DataTable({
                        	data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/payment/edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								//{ data: 'TransNo' },
								{ data: 'TransDate', width:200, render: fdate },
								{ data: 'AccName'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate', width:200, render: fdate },
							]
							//buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
						});
						break;
					case 'EX':
						$('#example1').DataTable({
                        	//ajax:"{{$_url}}",
                        	//return json_encode($data);
							data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							/*columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit') }}/"+data['ReffNo']+"'>"+data['ReffNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200,
									render: fdate
								},
								{ data: 'AccName'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate, width:200, render: fdate },
							] */
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/trans-edit/'.$jr) }}/"+data['TransNo']+"'>"+data['TransNo']+"</a>";
									}
								},
								{ data: 'TransDate', width:200, render: fdate },
								{ data: 'Receiver'},
								{ data: 'PaymentBy'},
								{ data: 'Total', className:'col-number', render: fcur },
								{ data: 'Status'},
								{ data: 'CreatedBy'},
								{ data: 'CreatedDate', width:200, render: fdate },
							]
							//buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
						});
						break; 
					case 'CR':
					case 'CD':
						$('#example1').DataTable({
                        	//ajax:"{{$_url}}",
                        	data: {!! json_encode($data) !!},
							//scrollY: 400,
							//deferRender: true,
							//scroller: { loadingIndicator: true 	},
							columns: [
								{ data: null,
									render: function(data, type, row) {
										return "<a href='{{ url('/cash-edit/'.$jr) }}/"+data['ReffNo']+"'>"+data['ReffNo']+"</a>";
									}
								},
								{ data: 'JRdate', width:200,
									render: fdate
								},
								{ data: 'AccNo'},
								{ data: 'JRdesc'},
								{ data: 'Total', className:'col-number', render: fcurB },
								{ data: 'Status'},
								{ data: 'Created By'},
								{ data: 'Created Date', width:200, render: fdate },
							]
							//buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
						});
						break;
					default:
						return "no data list from $jr";
					
				}
			});
		</script>
		<!-- END Java Script for this page -->

</body>

</html>
