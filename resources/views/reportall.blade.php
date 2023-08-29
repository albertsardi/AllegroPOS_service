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
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" >
    {{-- {{ HTML::style("assets/css/style.css") }} --}}
    
    <style type="text/css">
		.nav-link  {font-size: 20px;}
		.card {
        	margin: 0 auto;
        	float: none;
        	margin-bottom: 10px;
		}
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
							<h1 class="main-title float-left">Report</h1>
							<ol class="breadcrumb float-right">
								<li class="breadcrumb-item">Home</li>
								<li class="breadcrumb-item active">Blank Page</li>
							</ol>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<!-- end row -->

				<div class="row">
					<div class="col-xl-12">
						<ul id="tabs" class="nav nav-tabs">
							<li><a class="nav-link active" href="#tabs-1">Sekilas Bisnis</a></li>
						  	<li><a class="nav-link" href="#tabs-2">Pembelian</a></li>
						  	<li><a class="nav-link" href="#tabs-3">Penjualan</a></li>
						  	<li><a class="nav-link" href="#tabs-4">Product</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content" style="height:650px;">
							<div id="tabs-1" class="tab-pane active" >
						  		<div style="width: 48%; float: left;">
					                <br />
					                <h3>Neraca</h3>
					                <p>Menampilkan apa yang anda miliki (asset), apa yang anda hutang (libilitas, dan apa yang anda sudah investasikan pada perusahaan anda (ekuitas).</p>
					               	<button name="report10" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Laporan Laba-Rugi</h3>
					                <p>Menampilkan setiap tipe transaksi dan jumlah total untuk pendapatan dan pengeluaran anda.</p>
					                <button name="report11" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Arus Kas</h3>
					                <p>Laporan ini mengukur kas yuang telah dihasilkan atau di gunakan oleh suatu perusahaan dan menunjukan detail pergerakannya dalam suatu periode.</p>
					                <button name="report12" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
				            	</div>
					            <div style="width: 48%; float: left;">
					                <br />
					                <h3>Laporan Jurnal</h3>
					                <p>Laporan ini menampilkan daftar semua jurnal per transaksi yang terjadi dalam periode tertenu. Hal ini berguna untuk melacak di mana transaksi Anda masuk ke masing-masing rekening.</p>
					                <button name="report13" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Laporan Buku Besar</h3>
					                <p>Laporan ini menampilkan semua transaksi per akun yang telah dilakukan untuk suatu periode, sehingga Anda dapat melihat catatan perubahan-perubahan yang terjadi pada masing-masing akun.</p>
					                <button name="report14" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					            </div>
					  		</div>
					  		<div id="tabs-2" class="tab-pane" >
					  			<div style="width: 48%; float: left;">
				                	<br />
					                <h3>Daftar Pembelian</h3>
					                <p>Menampilkan daftar kronologis untuk semua pembelian Anda untuk suatu periode.</p>
					                <button name="report20" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Laporan Hutang Supplier</h3>
					                <p>Menampilkan jumlah total yang Anda berhutang pada setiap Supplier.</p>
					                <button name="report21" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					            </div>
					            <div style="width: 48%; float: left;">
					                <br />
					                <h3>Pembelian per Supplier</h3>
					                <p>Menampilkan setiap pembelian dan jumlah untuk setiap Supplier.</p>
					                <button name="report22" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Usia Hutang</h3>
					                <p>Laporan ini memberikan ringkasan hutang Anda, menunjukan setiap vendor Anda secara bulanan, serta jumlah total dari waktu ke waktu. Hal ini praktis untuk membantu melacak hutang Anda.</p>
					                <button name="report23" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					            </div>
					  		</div>
					  		<div id="tabs-3" class="tab-pane" >
					  			<div style="width: 48%; float: left;">
				                	<br />
					                <h3>Daftar Penjualan</h3>
					                <p>Menampilkan daftar kronologis untuk semua faktur penjualan Anda untuk suatu periode.</p>
					                <button name="report30" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Laporan Piutang Pelanggan</h3>
					                <p>Menampilkan tagihan yang belum dibayar untuk setiap pelanggan, termasuk nomor & tanggal faktur, tanggalk jatuh tempo, jumlah nilai, dan sisa tagihan yang terhutang pada Anda.</p>
					                <button name="report31" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
			            		</div>
				            	<div style="width: 48%; float: left;">
				                	<br />
				                	<h3>Penjualan per Pelanggan</h3>
				                	<p>Menampilkan setiap transaksi penjualan untuk setiap pelanggan, termasuk tanggal, tipe, jumlah dan total.</p>
					                <button name="report32" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Usia Piutang</h3>
					                <p>Laporan ini memberikan ringkasan piutang Anda, yang menunjukan setiap pelanggan Anda secara bulanan, serta jumlah total dari waktu ke waktu. Hal ini praktis untuk membantu melacak piutang Anda.</p>
					                <button name="report33" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					            </div>
					  		</div>
					  		<div id="tabs-4" class="tab-pane" >
						  		<div style="width: 48%; float: left;">
					                <br />
					                <h3>Ringkasan Persediaan Barang</h3>
					                <p>Menampilkan daftar kuntitas dan nilai seluruh arang persediaan per tanggal yang ditentukan.</p>
					                <button name="report40" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					                <h3>Rincian Persediaan Barang</h3>
					                <p>Menampilkan daftar transaksi yang terkait dengan setiap Barang dan Jasa, dan menjelaskan bagaimana transaksi tersebut mempengaruhi jumlah stok barag, nilai, dan harga biaya nya.</p>
					                <button name="report41" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
			            		</div>
			            		<div style="width: 48%; float: left;">
			                		<br />
					                <h3>Nilai Persediaan Barang</h3>
					                <p>Rangkuman informasi penting seperti sisa stok yang tersedia, nilai, dan biaya rata-rata, untuk setiap barang persediaan.</p>
					                <button name="report42" type="button"  class="btn btn-primary reportbutton">Show Report</button>
					                <br /><br /><br />
					            </div>
		          			</div>
						</div>
					</div>
				</div>

            </div>
			<!-- END container-fluid -->

		</div>
		<!-- END content -->

    </div>
	<!-- END content-page -->

	<footer class="footer">
		
	</footer>
    
    <!-- Filter Form -->
    <br/><br/><br/><br/><br/><br/>
    <!--<div id='panel' class='xmodal xfade'>
    	<div class='row'>
            <div class="col-md-4 col-md-offset-4">
    			<div id="filter" class="panel panel-primary">
	    			<div class="panel-heading">
				        <div class="row">
				            <div class="col-xs-3">
				                <i class="fa fa-edit fa-2x"></i>
				            </div>
				        </div>
	    			</div>
        			<div class="panel-body">
		               <?php 
		            		/*
		            		$_POST['date1']=date('Y-m-d'); $_POST['date2']=date('Y-m-d');
		            		$_POST['date1']='01/01/2018'; $_POST['date2']='31/12/2018'; //debug 2018
		            		echo form_text('date1','From :');
		            		echo form_text('date2','To :');
		            		*/
		                ?>
                	</div>
		        	<div class="panel-footer">
		        		<button name='cmsubmit' value='Show Report' id='cmsave' class='btn btn-primary'/>
		        	</div>
	        	</div>
    		</div>
    	</div>
    </div>-->
    <div class="row">
        <!-- PANEL1 -->
        <!--<div class="col-md-6 xxxcol-md-offset-3">-->
            <div class="card mb-3 col-md-6 invisible">
                <div class="card-header">
                    header
                </div>
                <div class="card-body">
                    {{-- <?= form_text('date1','From :');?> --}}
		            {{-- <?= form_text('date2','To :');?> --}}
                    <input name='date1' type='text' value='' class='form-control' placeholder='input date here'>
                    <input name='date2' type='text' value='' class='form-control' placeholder='input date here'>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-secondary reportbutton">Report</button>
				        </div>
                    </div>
                </div>
            </div>
        <!--</div>-->
    </div>

</div>
<!-- END main -->

{{-- @extends('loadjs') --}}

<!-- BEGIN Java Script for this page -->
<script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<!-- Tab Event -->
    <script type="text/javascript">  
    	$(document).ready(function(){
	    	$('#tabs a').click(function (e) {
	  			e.preventDefault()
	  			$(this).tab('show')
			});
		});
	</script>
    <!-- Button Event -->
	<script type="text/javascript">  
		$(document).ready(function(){
			$('#cmprint').click(function(event){
	    		//alert('PrintPreview');
	    		event.preventDefault();
	    		$('#dataTables-list_filter').hide();
	    		var header = $('#PanelHeader').html();
	    		PrintPreview(header) ;
	    		$('#dataTables-list_filter').show();
			});
			$('.reportbutton').click(function(event){
                $(this).attr('data-toggle','modal');
	    		$(this).attr('data-target','#panel');
	    		var btn=$(this).attr('name');
				//alert("report/"+btn);
	    		window.open("report/"+btn);
			});
	    });
	</script>
<!-- END Java Script for this page -->


</body></html>

 
