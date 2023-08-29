<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

//https://www.malasngoding.com/membuat-upload-file-laravel/

class FileUploadController extends Controller {

    public function upload(){
		return view('fileupload');
	}
 
	public function proses_upload(Request $request){
		@session_start();

		$this->validate($request, [
			'file' => 'required',
		 	//'keterangan' => 'required',
		]);
		//$newFile='profile.jpg';
		//$tujuan_upload = 'd://Temp';
		$user = Session::get('user');
		$newFile = 'profile'.$user->id.'.jpg';
		$tujuan_upload = 'assets/images/profile-img/';
		// menyimpan data file yang diupload ke variabel $file
		$file = $request->file('file');
      	// nama file
		echo 'File Name: '.$file->getClientOriginalName();
		echo '<br>';
      	// ekstensi file
		echo 'File Extension: '.$file->getClientOriginalExtension();
		echo '<br>';
      	// real path
		echo 'File Real Path: '.$file->getRealPath();
		echo '<br>';
        // ukuran file
		echo 'File Size: '.$file->getSize();
		echo '<br>';
      	// tipe mime
		echo 'File Mime Type: '.$file->getMimeType();
      	// isi dengan nama folder tempat kemana file diupload
		echo 'move to '.$tujuan_upload.$file->getClientOriginalName();
		echo '<br>';
        // upload file
		//$file->move($tujuan_upload.$file->getClientOriginalName());
		$file->move($tujuan_upload, $newFile);

		return redirect('/profile');
	}

}
