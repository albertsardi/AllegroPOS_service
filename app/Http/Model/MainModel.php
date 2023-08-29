<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Session;

class MainModel extends Model
{
  	// save data with token
	public static function replace($key, $data) {
		//if(empty($data['Token'])) return (object)['status'=>'Error', 'message'=>'no Token'];
		$res = self::where($key, $data[$key])->where('Token',$data['Token'])
						->first();
		if (empty($res)) {
			//add new
			self::create($data);
		} else {
			//update
			$id = $res->id;
			self::where('id', $id)->update($data);
		}
	}

}


