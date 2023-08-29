<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Session;


class OpenapiController extends MainController {

    public function indoProvince() {
        $res = $this->openApi('http://dev.farizdotid.com/api/daerahindonesia/provinsi');
        return $res->provinsi;
    }
    public function indoCity($provId) {
        $res = $this->openApi("http://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=$provId");
        return $res->kota_kabupaten;
    }

    function openApi($url) {
        //return 'ddd';
        try {
            $type = 'GET';
            $client = new Client();
            //return dd('dd');
            $api = $client->request($type, $url); 
            //return dd($api);
            if ( $api->getStatusCode()==200) {
                $res    = json_decode($api->getBody());
                return $res;
            }
            /*if ( $res->success ) {
                return $res->data;
            } else  {
            switch ( $res->errors->error_code )
            {
                case 401 : header('Location: '. url('logout')); exit;
                case 422 :
                case 500 : return $res;
                default  : return abort($api->getStatusCode());
            }
            }*/
        }
        catch (GuzzleException $e) {
            return !empty($e->getResponse()->getStatusCode()) ? view()->exists('errors.'.$e->getResponse()->getStatusCode()) ? abort($e->getResponse()->getStatusCode()) : abort(404) : abort(500);
        }
    }



    function datalist($jr) {
    //show view
    switch($jr) {
        case 'product':
            $data = [
                'jr'        => $jr,
                'grid'      => ['Product #','Product Name','Unit','Category','Quantity'],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                // 'data'      => $this->db_query('masterproduct','Code,Name,UOM,Category,12345 as Qty'),
                'data'      => DB::table('masterproduct')->selectRaw('Code,Name,UOM,Category,1234567 as Qty,id')->get(),
                'xxdatacol'   => json_encode([
                    [ 'data' => 'Code'],
                    [ 'data' => 'Name'],
                    [ 'data' => 'UOM'],
                    [ 'data' => 'Category']
                ])
            ];
            break;
        case 'supplier':
        case 'customer':
            $dat = DB::table('masteraccount as m')->selectRaw('AccName,m.AccCode,Phone,Email,Address,1234567 as Bal,id')
                    ->leftJoin('masteraccountaddr as ma', 'm.id', '=', 'ma.AccountId')->where('DefAddr',1);
            $dat = ($jr=='supplier')? $dat->where('AccType','S')->get() : $dat->where('AccType','C')->get();
            // return $dat;
            $data = [
                'jr'        => $jr,
                'grid'      => ['Display Name','Code','Phone','Email','Address', 'Balance (Rp)'],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                'data'      => $dat,
                // 'datacol'   => []
            ];
            break;
        case 'coa':
            $dat = DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName, 123456 as Bal,id')->get();
            foreach($dat as $dt) {
                $dt->Bal = Account::getAmount($dt->id);
            }
            $data = [
                'jr'        => $jr,
                'grid'      => ['Account #','Account Name','Category','Amount (Rp)',' '],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                'data'      => $dat
            ];
            // return $data['data'];
            break;
        case 'bank':
            $dat =  DB::table('masterbank')->selectRaw('BankAccName, BankAccNo, AccNo, BankType, 1234567 as Bal,id')->get();
            foreach($dat as $dt) {
                $dt->Bal = Account::getAmount($dt->id);
                }
            $data = [
                'jr'        => $jr,
                'grid'      => ['Bank Name', 'Bank Account#', 'Account#','Bank Type','Amount (Rp)', ' '],
                'caption'   => $this->makeCaption($jr),
                '_url'      => env('API_URL').'/api/'.$jr,
                'data'      => $dat
                // 'datacol'   => []
            ];
            // return $data['data'];
            break;
    }

    $data['grid'] = $this->makeTableList($data['grid']);
    return view('datalist', $data);
    }




}
