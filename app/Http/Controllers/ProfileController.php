<?php
namespace App\Http\Controllers;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Http\Model\User;
// use App\Http\Model\Product;
// use App\Http\Model\AccountAddr;
use App\Http\Model\Profile;
// use App\Http\Model\CustomerSupplierCategory;
// use App\Http\Model\Profile;
// use App\Http\Model\Account;
// use App\Http\Model\Bank;
// use App\Http\Model\Order;
// use App\report\MyReport;
// use \koolreport\widgets\koolphp\Table;
// use \koolreport\export\Exporta/\ble;
use Session;

class ProfileController extends MainController {

    // Profile
    function profile() {
        // return 'profile';
        @session_start();

        $data = [
            'caption' => 'Profile',
            'jr' => 'profile',
            'mCity' => $this->getIndoCity(),
            'data' => []
        ];
        $user = Session::get('user');
        if (empty($user)) return redirect('/login');
        //dd($user);
        $res = User::GetProfile( $user->UserId );
        if ($res->status=='OK') {
            $data['data'] = $res->data;
            $data['profile_image'] = $this->profile_image('profile'.$user->UserId.'.jpg');
        }
        //return $data;
        return view('form-profile', $data);
    }
    function profile_save(Request $req) {
        @session_start();
        $err='';
        try {
            $user = Session::get('user');
            $data = Profile::find($user->id);
            if (empty($data)) $data = new Profile();
            $data->nik                      = (string)$req->nik;
            $data->foto                     = (string)$req->foto;
            $data->nama                     = (string)$req->nama;
            $data->section                  = (string)$req->section;
            $data->jabatan                  = (string)$req->jabatan;
            $data->tgl_kerja                = $req->tgl_kerja ?? '0000-00-00';
            $data->status_karyawan          = (string)$req->status_karyawan;
            $data->no_sk                    = (string)$req->no_sk;
            $data->alamat                   = (string)$req->alamat;
            $data->rt                       = (string)$req->rt;
            $data->rw                       = (string)$req->rw;
            $data->kelurahan                = (string)$req->kelurahan;
            $data->kecamatan                = (string)$req->kecamatan;
            $data->kotakab                  = (string)$req->kotakab;
            $data->kodepos                  = (string)$req->kodepos;
            $data->status_tempat_tinggal    = (string)$req->status_tempat_tinggal;
            $data->no_hp                    = (string)$req->no_hp;
            $data->no_rumah                 = (string)$req->no_rumah;
            $data->email_pribadi            = (string)$req->email_pribadi;
            $data->email_kantor             = (string)$req->email_kantor;
            $data->tempat_lahir             = (string)$req->tempat_lahir;
            $data->tgl_lahir                = $req->tgl_lahir;
            $data->agama                    = (string)$req->agama;
            $data->nama_ibu_kandung         = (string)$req->nama_ibu_kandung;
            $data->no_ktp                   = (string)$req->no_ktp;
            $data->bank                     = (string)$req->bank;
            $data->no_rek                   = (string)$req->no_rek;
            $data->no_bpjs_kesehatan        = (string)$req->no_bpjs_kesehatan;
            $data->faskes                   = (string)$req->faskes;
            $data->no_bpjs_tenagakerja      = (string)$req->no_bpjs_tenagakerja;
            $data->jenjang                  = (string)$req->jenjang;
            $data->jurusan                  = (string)$req->jurusan;
            $data->nama_sekolah             = (string)$req->nama_sekolah;
            $data->riwayat_pengalaman_kerja = (string)$req->riwayat_pengalaman_kerja;
            $data->no_npwp                  = (string)$req->no_npwp;
            $data->status_pajak             = (string)$req->status_pajak;
            $data->no_tlp_keluarga          = (string)$req->no_tlp_keluarga;
            $data->nama_pasangan            = (string)$req->nama_pasangan;
            $data->tgl_pasangan             = $req->tgl_pasangan ?? '0000-00-00';
            $data->anak1                    = (string)$req->anak1;
            $data->anak2                    = (string)$req->anak2;
            $data->anak3                    = (string)$req->anak3;
            $data->anak4                    = (string)$req->anak4;
            $data->tgl_anak1                = $req->tgl_anak1 ?? '0000-00-00';
            $data->tgl_anak2                = $req->tgl_anak2 ?? '0000-00-00';
            $data->tgl_anak3                = $req->tgl_anak3 ?? '0000-00-00';
            $data->tgl_anak4                = $req->tgl_anak4 ?? '0000-00-00';
            $data->status_aktif             = (string)$req->status_aktif;
            $data->save();
            $message='Sukses!!';
            return redirect(url( $req->path() ))->with('success', $message);
        }
        catch (Exception $e) {
            // exception is raised and it'll be handled here
            // $e->getMessage() contains the error message
            //return response()->json(['error'=>$e->getMessage()]);
            return redirect(url( $req->path() ))->with('error', $e->getMessage());
        }
    }

    function getIndoCity() {
        //populate city
        $OpenApi =new OpenapiController();
        $city = [];
        foreach($OpenApi->indoProvince() as $res) {
            foreach($OpenApi->indoCity($res->id) as $dat) {
                $city[] = (array)$dat;
            }
        }
        return $city;
    }

}
