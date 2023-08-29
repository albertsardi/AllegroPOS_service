<?php

namespace App\View\Components;

use Illuminate\View\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Textwselect extends Component
{
    public $name;
    public $label;
    public $api;
    public $slist;

    function fetch($url) {
        try {
            $type = 'GET';
            $client = new Client();
            $api = $client->request($type, $url, ['auth'=>null]); 
            if ( $api->getStatusCode()==200) {
                $res    = json_decode($api->getBody());
                return $res;
            }
            /*if ( $res->success ) {
                //return $res->data;
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
    
    public function __construct($name, $label, $api='', $value='', $label2='')
    {
        $this->name = $name;
        $this->label = $label;
        $this->api = $api;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.textwselect');
    }
}
