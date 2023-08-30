@extends('temp-master')

@section('css')
  <link href="{{ asset('assets/css/profile.css') }}" rel="stylesheet" type="text/css" >
@stop


@section('content')
	<form id='formData' method='POST'>
    {{ Form::hidden('_token', csrf_token() ) }}  
    {{ Form::hidden('jr', 'profile') }}  
	<div class="container">
	<div class="row">
	<div class="col-12">
		<h1>{{ $data->nama }}</h1>
	</div>
	</div>
	<div class="row">
	<div class="col-3">
		<div class="text-center">
				<input name="foto" type="hidden" value="{{$data->foto}}">
				<img src="{{ $profile_image }}" class="avatar img-circle img-thumbnail" alt="avatar">
				<button type="button" data-toggle="modal" data-target="#modal-fileupload" class="btn btn-secondary btn-sm">Upload a different photo...</button>
		</div>
		<hr /><br>

		<ul class="list-group">
			<li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti diambil</strong></span> 125</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Tersisa</strong></span> 13</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Sakit</strong></span> 37</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Ijin</strong></span> 78</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Alpha</strong></span> 78</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Haji</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Nikah</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Sunat</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti KK Meninggal</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Tugas Negara</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Hamil</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Melahirkan</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Benjana</strong></span> 1/3</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Cuti Keguguran</strong></span> 1/3</li>
		</ul>

	</div>
	<div class="col-9">
		{{-- <form  method='POST'> --}}
			<ul id='tabs' class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#personal-tab">Personal</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#work-tab">Work</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#family-tab">Family</a>
				</li>
				{{-- <li class="nav-item">
					<a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
				</li> --}}
			</ul>

			<div class="tab-content" style="height:auto;">
				<div class="tab-pane active" id="personal-tab" role="tabpanel" aria-labelledby="home-tab">
					<hr>
						<div class="form-group">
							<div class="col-xs-6">
								<label for="nik">
									<h4>NIK #</h4>
								</label>
								<input type="text" value="{{ $data->nik }}" class="form-control" name="nik" id="nik" placeholder="first name" title="enter your first name if any." />
								<label class='errors'>Error: xxxxxxx</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12">
								<label for="first_name">
									<h4>Name</h4>
								</label>
								<input type="text" value=" {{$data->nama}}" class="form-control" name="nama" id="nama" placeholder="first name" title="enter your first name if any.">
								<label class='errors'>Error:  errors[0].msg </label>
							</div>
						</div>


						<div class='row'>
							{{-- col1 --}}
							<div class='col'>
								<div class="form-group">
									<div class="col-xs-6">
										<label for="phone">
											<h4>Phone</h4>
										</label>
										<input type="text" value="{{$data->no_rumah}}" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any.">
									</div>
								</div>
								<div class="form-group">
							<div class="col-xs-6">
								<label for="email">
									<h4>Email</h4>
								</label>
								<input type="email" value="{{$data->email_pribadi}}" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
							</div>
						</div>
							</div>

							{{--  col2 --}}
							<div class='col'>
								<div class="form-group">
									<div class="col-xs-6">
										<label for="mobile">
											<h4>Mobile Phone</h4>
										</label>
										<input type="text" value="{{$data->no_hp}}" class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any.">
									</div>
								</div>
							</div>
						</div>
						<div class='form-group'>
							<div class='col-xs-6'>
								<label for='alamat'><h4>Alamat</h4></label>
								<input type='text' value="{{$data->alamat}}" class='form-control' name='alamat' id='alamat' ng-model='alamat'  placeholder='input alamat' title=''>
							</div>
						</div>  
						<div class='row'>
							<div class='col'>
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='rtrw'><h4>RT/RW</h4></label>
										<input type='text' value="{{$data->no_rumah}}" class='form-control' name='rtrw' id='rtrw' ng-model='rtrw'  placeholder='input RT/RW' title=''>
									</div>
								</div> 
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='kotakab'><h4>Kota</h4></label>
										{{-- <input type='text' value="{{$data->kotakab}}" class='form-control' name='kotakab' id='kotakab' ng-model='kotakab'  placeholder='input RT/RW' title=''> --}}
										<select name='kotakab' id='kotakab' class='select2 form-control'>
											@foreach($mCity as $r)
												@php $sel = ($r==$data->kotakab)? 'selected':''; @endphp
												<option value={{$r['id']}} {{$sel}}>{{$r['nama']}}</option>
											@endforeach
										</select>
									</div>
								</div>  
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='status_tempat_tinggal'><h4>Status tempat tinggal</h4></label>
										<input type='text' value="{{$data->status_tempat_tinggal}}" class='form-control' name='status_tempat_tinggal' id='status_tempat_tinggal' ng-model='status_tempat_tinggal'  placeholder='input RT/RW' title=''>
									</div>
								</div> 
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='tempat_lahir'><h4>Tempat Lahir</h4></label>
										<input type='text' value="{{$data->tempat_lahir}}" class='form-control' name='tempat_lahir' id='tempat_lahir' ng-model='tempat_lahir'  placeholder='input RT/RW' title=''>
									</div>
								</div>         
							</div>           
							<div class='col'>         
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='kelurahan'><h4>Kelurahan</h4></label>
										<input type='text' value="{{$data->kelurahan}}" class='form-control' name='kelurahan' id='kelurahan' ng-model='kelurahan'  placeholder='input RT/RW' title=''>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='kodepos'><h4>Kode pos</h4></label>
										<input type='text' value="{{$data->kodepos}}" class='form-control' name='kodepos' id='kodepos' ng-model='kodepos'  placeholder='input RT/RW' title=''>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='kecamatan'><h4>Kecamatan</h4></label>
										<input type='text' value="{{$data->kecamatan}}" class='form-control' name='kecamatan' id='kecamatan' ng-model='kecamatan'  placeholder='input RT/RW' title=''>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-xs-6'>
										<label for='tgl_lahir'><h4>Tanggal Lahir</h4></label>
										<input type='date' value="{{$data->tgl_lahir}}" class='form-control' name='tgl_lahir' id='tgl_lahir' ng-model='tgl_lahir'  placeholder='input RT/RW' title=''>
									</div>
								</div>   
							</div>
						</div>
						<div class='form-group'>
							<div class='col-xs-6'>
								<label for='agama'><h4>Agama</h4></label>
								{{-- <input type='text' value="{{$data->agama}}" class='form-control' name='agama' id='agama' ng-model='agama'  placeholder='input RT/RW' title=''> --}}
								<select name='agama' id='agama' class='select2 form-control'>
									@foreach(['Islam','Kristen Katolik','Kristen Protestan','Hindu','Budha','Kong Hu Cu'] as $r)
									@php $sel = ($r==$data->agama)? 'selected':''; @endphp
										<option value='{{$r}}' {{$sel}}>{{$r}}</option>
									@endforeach
								</select>
							</div>
						</div>                    
					<hr>
				</div>

				<!-- tab work -->
				<div class="tab-pane" id="work-tab" aria-labelledby="work-tab">
					<hr>
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='section'><h4>Section</h4></label>
							<input type='text' value="{{$data->section}}" class='form-control' name='section' id='section' ng-model='section'  placeholder='Section' title='input your working section'>
						</div>
					</div>                    
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='jabatan'><h4>Jabatan</h4></label>
							<input type='text' value="{{$data->jabatan}}" class='form-control' name='jabatan' id='jabatan' ng-model='jabatan'  placeholder='' title=''>
						</div>
					</div>                    
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='tgl_kerja'><h4>Tanggal Kerja</h4></label>
							<input type='date' value="{{$data->tgl_kerja}}" class='form-control' name='tgl_kerja' id='tgl_kerja' ng-model='tgl_kerja'  placeholder='dd/mm/yyyy' title=''>
						</div>
					</div>                    
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='status_karyawan'><h4>Status Karyawan</h4></label>
							{{-- <input type='text' value="{{$data->status_karyawan}}" class='form-control' name='status_karyawan' id='status_karyawan' ng-model='status_karyawan'  placeholder='Tetap/Kontrak' title=''> --}}
							<select name='status_karyawan' id='status_karyawan' class='select2 form-control'>
								@foreach(['Permanent','Kontrak','Parttime'] as $r)
									@php $sel = ($r==$data->status_karyawan)? 'selected':''; @endphp
									<option value='{{$r}}' {{$sel}}>{{$r}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='no_sk'><h4>SK #</h4></label>
							<input type='text' value="{{$data->no_sk}}" class='form-control' name='no_sk' id='no_sk' ng-model='no_sk'  placeholder='' title=''>
						</div>
					</div>                    
					{{-- <div class="form-group">
						<div class="col-xs-12">
							<br>
							<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
							<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
						</div>
					</div> --}}
				</div>
				
				<!-- tab family -->
				<div class="tab-pane" id="family-tab">
					<hr>
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='nama_ibu_kandung'><h4>Nama Ibu Kandung</h4></label>
							<input type='text' value="{{$data->nama_ibu_kandung}}" class='form-control' name='nama_ibu_kandung' id='nama_ibu_kandung' ng-model='nama_ibu_kandung'  placeholder='' title=''>
						</div>
					</div>
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='no_telp_keluarga'><h4>Telp Keluarga</h4></label>
							<input type='text' value="{{$data->no_rumah}}" class='form-control' name='no_telp_keluarga' id='no_telp_keluarga' ng-model='no_telp_keluarga'  placeholder='' title=''>
						</div>
					</div>                    
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='nama_pasangan'><h4>Nama Pasangan</h4></label>
							<input type='text' value="{{$data->no_rumah}}" class='form-control' name='nama_pasangan' id='nama_pasangan' ng-model='nama_pasangan'  placeholder='' title=''>
						</div>
					</div>                    
					<div class='form-group'>
						<div class='col-xs-6'>
							<label for='tgl_pasangan'><h4>Tanggal Lahir Pasangan</h4></label>
							<input type='date' value="{{$data->tgl_pasangan}}" class='form-control' name='tgl_pasangan' id='tgl_pasangan' ng-model='tgl_pasangan'  placeholder='' title=''>
						</div>
					</div>                    
					<div class='row'>
						<div class='col'>
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='anak1'><h4>Nama Anak ke-1</h4></label>
									<input type='text' value="{{$data->anak1}}" class='form-control' name='anak1' id='anak1' ng-model='anak1'  placeholder='Input Nama' title=''>
								</div>
							</div>   
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='anak2'><h4>Nama Anak ke-2</h4></label>
									<input type='text' value="{{$data->anak2}}" class='form-control' name='anak2' id='anak2' ng-model='anak2'  placeholder='Input Nama' title=''>
								</div>
							</div>  
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='anak3'><h4>Nama Anak ke-3</h4></label>
									<input type='text' value="{{$data->anak3}}" class='form-control' name='anak3' id='anak3' ng-model='anak3'  placeholder='Input Nama' title=''>
								</div>
							</div>  
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='anak4'><h4>Nama Anak ke-4</h4></label>
									<input type='text' value="{{$data->anak4}}" class='form-control' name='anak4' id='anak4' ng-model='anak4'  placeholder='Input Nama' title=''>
								</div>
							</div>                                     
						</div>
						<div class='col'>
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='tgl_anak1'><h4>Tanggal Lahir Anak ke-1</h4></label>
									<input type='date' value="{{$data->tgl_anak1}}" class='form-control' name='tgl_anak1' id='tgl_anak1' ng-model='tgl_anak1'  placeholder='dd/mm/yyyy' title=''>
								</div>
							</div>                    
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='tgl_anak2'><h4>Tanggal Lahir Anak ke-2</h4></label>
									<input type='date' value="{{$data->tgl_anak2}}" class='form-control' name='tgl_anak2' id='tgl_anak2' ng-model='tgl_anak2'  placeholder='dd/mm/yyyy' title=''>
								</div>
							</div>                    
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='tgl_anak3'><h4>Tanggal Lahir Anak ke-3</h4></label>
									<input type='date' value="{{$data->anak3}}" class='form-control' name='tgl_anak3' id='tgl_anak3' ng-model='tgl_anak3'  placeholder='dd/mm/yyyy' title=''>
								</div>
							</div>                    
							<div class='form-group'>
								<div class='col-xs-6'>
									<label for='tgl_anak4'><h4>Tanggal Lahir Anak ke-4</h4></label>
									<input type='date' value="{{$data->anak4}}" class='form-control' name='tgl_anak4' id='tgl_anak4' ng-model='tgl_anak4'  placeholder='dd/mm/yyyy' title=''>
								</div>
							</div>
						</div>
					</div>
					{{-- <div class="form-group">
						<div class="col-xs-12">
							<br>
							<button class="btn btn-lg btn-success pull-right" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
						</div>
					</div> --}}
				</div>

			</div>
		{{-- </form> --}}
		</div>
	</div>
	</div>
	</form>
@stop

@section('modal')
   @include('modal.modal-fileupload') 
@stop

@section('js')
<!-- BEGIN Java Script for this page -->
<!-- Tab Event -->
<script type="text/javascript">  
   	$(document).ready(function(){
        //init form
        $('select.select2').select2({ theme: "bootstrap" });

      	//tab
      	$('#tabs a').click(function (e) {
         	e.preventDefault()
         	$(this).tab('show')
      	});
      
	  	$("button#cmSave2").click(function(e){
            e.preventDefault();
            var formdata=$('form').serialize();
            $('#formData').submit();
        });
        $("button#cmPrint").click(function(e){
            e.preventDefault();
            alert('print');
        });
   });
</script>

<!-- END Java Script for this page -->
@stop
