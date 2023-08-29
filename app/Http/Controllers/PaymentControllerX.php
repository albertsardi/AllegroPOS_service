<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Model\Payment;
use App\Http\Model\CustomerSupplier;
use App\Http\Model\Transaction;

class PaymentController extends MainController {
	
    public function __contruct()
    {
        $this->middleware("Auth");
        //session_start();
        //$user = Session::get('user');
        //if (empty($user)) return redirect('/login');
    }

    function transedit($jr, $id)
	{
		$data = [
			'modal' => '',
			'jsmodal' => '',
			'jr' => $jr,
      	    'id' => $id,
            'caption' => $this->makeCaption($jr, $id)
        ];

		if ($jr == 'AP') {
			$modal = $this->modalData(['mAccount','mInvUnpaid']);
			$select = $this->selectData(['selSupplier','selBankAccount']);
			$view = 'form-ARAP';
			$res = Payment::Get($jr, $id);
		}
		if ($jr == 'AR') {
			$modal = $this->modalData(['mAccount']);
			$select = $this->selectData(['selCustomer','selBankAccount']);
			$view = 'form-ARAP';
			$res = Payment::Get($jr, $id);
		}

		// get modal data
		if (isset($modal)) $data = array_merge($data, $modal);
		
		// get select data
		$data['select'] = isset($select)? $select:[];
		
		// get data
		if ($res->status=='OK') {
			if(!empty($res->data)) {
				$res->data->status 	= $this->gettransstatus($id, $jr);
				$data['data']		= $res->data;
				$data['griddata'] 	= $res->data->detail;
				$data['data']->Status = $this->getTransStatus($jr, $data['data']->Status);
			} else {
				$data['data'] =(object)['status' => 'DRAFT'];
				$data['griddata'] = [];
				$resp = $data;
			}
			return view($view, $data); // using agGrid
		} else {
			dd('Error '.$res->status);
		}








		if ($jr == 'AR' || $jr == 'AP') {
			// form initial
			// /$dat = Transaction::GetListInvoiceUnpaid();
			//return ($dat->data);
			//$dat = DB::table('transhead')->get();
			//return $dat;
			$data = array_merge($data, [
                'mCat'   => $this->DB_list('masterproductcategory', 'Category'),
                //'mType'  => ['Raw material','Finish good'],
                // 'mType'  => ['RAW'=>'Raw material','FINISH'=>'Finish good'],
                // 'mHpp'   => ['Average'],
				'mCustomer'  => json_encode(DB::table('masteraccount')->where('AccType', 'C')->get() ),
				'mSupplier'  => json_encode(DB::table('masteraccount')->where('AccType', 'S')->get() ),
                'mAccount'  => json_encode(DB::table('mastercoa')->selectRaw('AccNo, AccName, CatName')->get()),
                'mInvUnpaid'  => ($jr=='AR')? json_encode(Transaction::GetListSalesInvoiceUnpaid()->data) : json_encode(Transaction::GetListPurchaseInvoiceUnpaid()->data),
                'data'   => [],
            ]);
            //return $data;
		
            // get data
			$res = Payment::Get($id);
			if ($res->status=='OK') {
				if(!empty($res->data)) {
					$res->data->status 	= $this->gettransstatus($id, $jr);
					$data['data']		= $res->data;
					$data['griddata'] 	= $res->data->detail;
					$data['data']->Status = $this->getTransStatus($jr, $data['data']->Status);
				} else {
					$data['data'] =(object)['status' => 'DRAFT'];
					$data['griddata'] = [];
					$resp = $data;
				}
				return view('form-arap', $data); // using agGrid
			} else {
				dd('Error '.$res->status);
			}
        }
    }

	function paymentsave(Request $req) {
		$err= '';
		DB::beginTransaction();
		try {
			// save cara 1
			// payment HEAD update
			if($req->TransNo=='') {
				$data = new Payment();
				//$data->TransNo = $req->TransNo; //generate new transaction
			} else {
				$data = Payment::where('TransNo', $req->TransNo)->first();
			}
			$data->TransDate	= $req->TransDate;
			$data->AccCode 		= $req->AccCode;
			$data->AccName 		= $req->AccName;
			$data->Payment 		= $req->Payment;
			$data->toAccNo 		= $data->toAccNo;
			$data->Memo 		= $req->Memo;
			$data->Total 		= $req->Total;
			$data->CreatedBy 	= 'admin';
			$data->CreatedDate 	= date('Y-m-d H:m:s');
			$data->save();
			
			//get id
			$id = Payment::where('TransNo',$req->TransNo)->first()->id;

			// payment ARAP update
			$detail = json_decode($req->detail);
			$dbDetail = DB::table('transpaymentarap');
			$dbDetail->where("TransNo", $req->TransNo)->delete();
			foreach($detail as $r) {
				$save = [
					'InvNo' 		=> $r->InvNo,
					'AmountPaid'	=> $r->AmountPaid,
					'AmountAdj' 	=> $r->AmountAdj,
					'TransNo' 		=> $r->TransNo,
					'Memo' 			=> $r->Memo,
				];
				$dbDetail->insert($save);
			}
			DB::commit();
			//return response()->json(['success'=>'data saved', 'result'=>$result]);
			$message='Sukses!!';
			return redirect(url( $req->path() ))->with('success', $message);
		} 
		catch (Exception $e) {
			DB::rollback();
			// exception is raised and it'll be handled here
			// $e->getMessage() contains the error message
			//return response()->json(['error'=>$e->getMessage()]);
			//return redirect('http://localhost/lav7_PikeAdmin/payment/edit/AR.1800002')->with('error', $e->getMessage());
			return redirect(url( $req->path() ))->with('error', $e->getMessage());
	  	}
	}

	function getTransStatus($jr, $status)
	{
		/*switch ($jr) {
			case 'AP':
				$dat = $this->DB_array('transpaymentarap', "sum(AmountPaid) as paid", "InvNo='$transno' ");
				$dat = (int) ($dat[0]['paid']);
				if ($dat > 0) return "CLOSED";
				return "";
				break;
			case 'AR':
				$dat = $this->DB_array('transdetail', "InvNo", "TransNo='$transno' ");
				if (count($dat) > 0) return "CLOSED";
				return "";
				break;
			default:
				return "none";
		}*/
		if ($status==0) return DRAFT;
		if ($status==1) return APPROVED;
	}

	function createModal($type)
	{
		switch ($type) {
			case 'supplier':
				$dat = (array) DB::table('masteraccount')->where('AccType', 'S')->get();
				$caption = ['Supplier #', 'Supplier Name'];
				return $this->MetroCreateLookupTable("lookup-$type", $dat, $caption);
				break;
			case 'product':
				$dat = $this->DB_array('masterproduct');
				$caption = ['Product #', 'Product Name'];
				return $this->MetroCreateLookupTable("lookup-$type", $dat, $caption);
				break;
		}
	}

	function field_array($fld) {
		$arr = [];
		for($a=0;$a<count($fld);$a++) {
			$nm= $fld[$a];
			// $arr[$nm]= isset(Input::get($nm))?Input::get($nm):'';
			$arr[$nm]= Input::get($nm);
		}
		return $arr;
	}



	function CreateModalGrid($id, $dat, $caption)
	{
		if ($dat != []) {
			$key = array_keys($dat[0]);
			$grid = '';
			//caption
			$caption = "<tr><th>" . implode('</th><th>', $caption) . "</th></tr>";
			//detail
			for ($a = 0; $a < count($dat); $a++) {
				$link = $dat[$a][$key[0]];
				$grid .= "<tr>" .
					// "<td><a href='#' onclick='lookupSel($link)'>$link</a></td>".
					"<td><a href='#' class='dialog-link' js-dialog-close>$link</a></td>" .
					"<td>" . $dat[$a][$key[1]] . "</td>" .
					"</tr>";
			}
			$grid .= '</table>';
			return "<table id='gridLookup-$id' class='gridLookup' border='1' class='xtable xtable-bordered xtable-hoer xdisplay' style='font-size:small;border:1px solid black;'>
						<thead>$caption</thead>
						<tbody>$grid</tbody>
						</table>";
		}
	}

	
	function cell($row, $nm) {
		return Input::get("grid-$nm")[$row];
	}
	//----------------------------------------------
	//function Form to update query
	//----------------------------------------------
	function form_query($db, $fld, $otherfld=[]) {
		$dat = [];
		for($a=0;$a<count($fld);$a++) {
			$nm= $fld[$a];
			// $arr[$nm]= isset(Input::get($nm))?Input::get($nm):'';
			$dat[$nm]= Input::get($nm);
		}
		//keys
		$keys=array_keys($dat); $keys=implode(',' ,$keys);
		//values
		$dat=implode("','" ,$dat); $dat="'".$dat."'";
		return "replace into $db($keys) values($dat)";
	}
	//----------------------------------------------
	//function Edit grid to update query
	//----------------------------------------------
	function egrid_query($db, $fld, $key='', $otherfld=[]) {
		$post=$_POST;
		if($key=='') $key=$fld[0];
		$key= 'grid-'.$key;
		$totrow= count($post[$key]);
		$dat='';
		for($a=0;$a<$totrow;$a++) {
			if(!isset($post[$key][$a])) continue; //jika key tidak ada
			if($post[$key][$a]=='') continue; //jika baris kosong
			$r=[];
			foreach($fld as $f) {
				$nm= "grid-$f";
				$v= isset($post[$nm][$a])?$post[$nm][$a]:'';
				$r[$f]=$v;
			}
			$r= array_merge($r, $otherfld);
			$flds= implode(',', array_keys($r));
			$r= array_values($r);
			$dat.= "('".implode("','", $r)."'),";
		}
		$dat= substr($dat,0,-1);
		$dat= "replace into $db($flds) values".$dat;
		return $dat;
	}


	public function allPosts(Request $request)
    {

        $columns = array(
                        0 => "TransNo",
						1 => "TransDate",
						2 => "AccName",
						3 => "Total",
						4 => "Status",
						5 => "CreatedBy"
					);

        $totalData = Post::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = Post::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  Post::where('id','LIKE',"%{$search}%")
                            ->orWhere('title', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Post::where('id','LIKE',"%{$search}%")
                             ->orWhere('title', 'LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
                $show =  route('posts.show',$post->id);
                $edit =  route('posts.edit',$post->id);

				//$nestedData['id'] = $post->id;
				$nestedData["TransNo"] = $post->TransNo;
				$nestedData["TransDate"] = $post->TransDate;
				$nestedData["AccName"] = $post->AccName;
				$nestedData["Total"] = $post->Total;
				$nestedData["Status"] = $post->Status;
				$nestedData["CreatedBy"] = $post->CreatedBy;
                //$nestedData['title'] = $post->title;
                //$nestedData['body'] = substr(strip_tags($post->body),0,50)."...";
                //$nestedData['created_at'] = date('j M Y h:i a',strtotime($post->created_at));
                //$nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                          &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        //echo json_encode($json_data);
        return json_encode($json_data);

	}

	public function ajax()
    {
		return view('customers.ajax');
	}











}
