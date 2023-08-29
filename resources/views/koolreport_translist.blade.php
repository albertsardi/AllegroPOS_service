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
    @php use \koolreport\widgets\koolphp\Table;@endphp
    @php use \koolreport\datagrid\DataTables;@endphp
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
											<button id="cmNew" type="button" class="btn btn-primary btn-sm btn-submit">New</button>
											<button id="cmPrint" type="button" class="btn btn-primary btn-sm btn-submit">Print</button>
                                            <a href='{{ url('translist/'.$jr.'/excel') }}' class="btn btn-primary btn-sm btn-submit">to Excel</a>
										</div>
									</h3>
								</div>
								<div class="card-body">
                                    {{-- @php Table::create($table);@endphp --}}
                                    @php DataTables::create($table);@endphp
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
		<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
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
			    var jr='{{$jr}}';
            });
		</script>
		<!-- END Java Script for this page -->
</body>

</html>
