<?php


function cimage($image, $alt='') {
    $xx=asset($image);
    return "<img src='$xx' alt='$alt'>";
}

class HTML
{
    public static function image($image, $alt='') {
        $xx=asset($image);
        return "<img src='$xx' alt='$alt'>";
    }
}

class Image
{
    public static function get($image) {
		$no_img = asset('assets/images/no_image.png');
        $img = asset('assets/images/'.$image);
		return $img;
		if (file_exists($img)) {
		  return $img;
	  	} else {
		  return $no_img;
	  	}
	}
}




#------------------------------------------------
#- Form Helper Functon
#- (c) 2019 Albert (albertsardi@gmail.com)
#------------------------------------------------
class Form
{
	public static $form_data = null;
	public static $form_date_format = 'Y-m-d';
	public static $form_template = null;
	private static $form_template_default = "<div class='form-group form-row my-1'>
												<label for='input{{name}}' class='col-sm-4 col-form-label mx-0'>{{label}}</label>
												<div class='col-sm-8 mx-0'>{{input}}</div>
												</div>";

	public static function setBindData($dat) {
		self::$form_data = $dat;
	}

	public static function setFormTemplate($dat) {
		self::$form_template = $dat;
		if ($dat=='layout-inline') self::$form_template = self::$form_template_default;
		if ($dat=='layout-normal') self::$form_template = "<div class='mb-3'>
								<label class='form-label'>{{label}}</label>{{input}}
								</div>";
	}

	public static function setDateFormat($dat) {
		self::$form_date_format = $dat;
	}

	public static function getValue($name, $other, $defvalue='') {
		if(!isset(self::$form_data)) { //not binding
			$value = $other;
			if (is_array($other)) $value = $other['value']??$defvalue;
		} else { //use binding
			$value = isset(self::$form_data->$name)? self::$form_data->$name : $defvalue;
		}

		// if(is_array($other)) {
		// 	$value = isset($other->value)? $other->value:$defvalue;
		// 	if (isset(Form::$form_data)) $value = Form::$form_data->$name;
		// } else {
		// 	$value= $other;
		// 	if (isset(Form::$form_data)) $value = Form::$form_data->$name;
		// }
		return $value;
	}

	public static function hidden($name, $value) {
        echo "<input type='hidden' name='$name' value='$value'>";
	}

	//public static function text($name, $label, $value, $other=null) {
	public static function text($name, $label, $other=[], $other2=[]) {
		$value = Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;
		//if (is_array($other)&&is_array($other2)) $other = array_merge($other,$other2);
		//$other = array_merge($other,$other2);
		$input = Form::_textbox($name, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
	}

	public static function label($name, $label, $other=[], $other2=[]) {
		$value = Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;

		echo "<div class='form-group form-row my-1'>
                <label for='input$name' class='col-sm-4 col-form-label mx-0'>$label</label>
                <div class='col-sm-8 mx-0'>".
					Form::_label($name, $value, $other).
                "</div>
				</div>";
	}

	public static function number($name, $label, $other=[], $other2=[]) {
		$value = Form::getValue($name, $other, 0);
		if (!empty($other2)) $other = $other2;
		$input = self::_numericbox($name, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;

		// echo "<div class='form-group form-row my-1'>
        //         <label for='input$name' class='col-sm-4 col-form-label px-0'>$label</label>
        //         <div class='col-sm-8'>".
		// 			self::_numericbox($name, $value, $other).
        //         "</div>
		// 		</div>";
	}
	public static function combo($name, $label, $list = [], $other = [], $other2 = []) {
		$value = Form::getValue($name, $value, 0);
		if (!empty($other2)) $other = $other2;

		$v= '';
		$slist = "<option> - </option>";
		if (count($list) > 0) {
			for ($a = 0; $a < count($list); $a++) {
				$v1 = $list[$a]; //dd($v1);
				$v2 = $list[$a]; //dd($v2);
				if ($v == $v1) {
					$slist .= "<option value='$v1' selected>" . $v2 . "</option>";
				} else {
					$slist .= "<option value='$v1'>" . $v2 . "</option>";
				}
			}
		}
		$input = "<select name='$name' class='form-control'>
					$slist
				</select>";

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
		// echo "<div class='form-group row'>
		// 				<label for='input$name' class='col-sm-3 col-form-label'>$label</label>
		// 				<div class='col-sm-9'>
		// 					<select name='$name' class='form-control'>
		// 					$slist
		// 					</select>
		// 				</div>
		// 			</div>";
	}
	public static function select($name, $label, $list = [], $other = [], $other2 = []) {
		$value= Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;
		$input = self::_select($name, $list, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
		// echo "<div class='form-group form-row'>
		// 				<label for='input$name' class='col-sm-4 col-form-label px-0'>$label</label>
		// 				<div class='col-sm-8'>".
		// 					self::_select($name, $list, $value, $other).
		// 				"</div>
		// 			</div>";
	}
	public static function date($name, $label, $other=[], $other2=[]) {
		$now = date(self::$form_date_format);
		$value= Form::getValue($name, $other, $now );
		if (!empty($other2)) $other = $other2;
		$input = self::_datebox($name, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
		// echo "<div class='form-group form-row my-1'>
		// 	<label for='input$name' class='col-sm-4 col-form-label px-0'>$label</label>
		// 	<div class='col-sm-8'>".
		// 		self::_datebox($name, $value, $other).
		// 	"</div>
		// 	</div>";
	}
	public static function checkbox($name, $label, $other=[], $other2=[]) {
      	$value= Form::getValue($name, $other, 0);
		if (!empty($other2)) $other = $other2;
	  	$ck = ($value==1 or $value)? 'checked' : '';
		$input = self::_check($name, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
		// echo "<div class='form-group row'>
		// 			<div class='col-sm-9 offset-sm-3'>
		// 				<div class='form-check'>".
		// 				self::_check($name, $value, $other).
        //           "<label class='form-check-label' for='ck$name'>$label</label>
		// 				</div>
        //           </div>
        //           </div>";
	}
	public static function textwlookup($name, $label, $modal, $other=[], $other2=[]) {
		$value= Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;

		echo "<div class='form-row align-items-top'>
               	<div class='col-sm-4 my-3'>
               		<label>$label</label>
               	</div>
               	<div class='col-sm-8 my-1'>
					<div class='form-row'>
						<div class='input-group'>".
							self::_textbox($name, $value).
							"<div class='input-group-prepend'>
								<button id='$name-lookup' type='button' data-toggle='modal' data-target='#$modal' class='btn btn-outline-secondary btn-sm btnlookup'><i class='fa fa-search'></i></button>
							</div>
				   		</div>
					</div>
					<div class='form-row'>".
						// <label id='$name-val2' class='form-label' for='autoSizingCheck2'> <i>blank</i> </label>
						self::_textbox($name."Label", '')."
					</div>
				</div>
            </div>";
	}
	public static function textwlookup2($dat=[]) {
		$name = $dat['name'];
		$label = $dat['label'];
		$name2 = $dat['name2'] ?? '';
		$modal = $dat['modal'] ?? '';
		$other = $dat['other'] ?? '';
		$other2 = $dat['other2'] ?? '';
		$value= Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;

		echo "<div class='form-row align-items-top'>
               	<div class='col-sm-4 my-3'>
               		<label>$label</label>
               	</div>
               	<div class='col-sm-8 my-1'>
					<div class='form-row'>
						<div class='input-group'>".
							self::_textbox($name, $value).
							"<div class='input-group-prepend'>
								<button id='$name-lookup' type='button' data-toggle='modal' data-target='#$modal' class='btn btn-outline-secondary btn-sm btnlookup'><i class='fa fa-search'></i></button>
							</div>
				   		</div>
					</div>
					<div class='form-row'>".
						self::_textbox($name2, $value)."
					</div>
				</div>
            </div>";
	}
	public static function textwselect($name, $label, $list = [], $other = [], $other2 = []) {
		$value= Form::getValue($name, $other);
		if (!empty($other2)) $other = $other2;
		$other['mselect'] = true;
		$input = self::_select($name, $list, $value, $other);

		$temp = self::$form_template;
		if($temp == null) $temp = self::$form_template_default;
		$temp = str_replace("{{name}}", $name, $temp);
		$temp = str_replace("{{label}}", $label, $temp);
		$temp = str_replace("{{input}}", $input, $temp);

		echo $temp;
		// echo "<div class='form-group form-row'>
		// 				<label for='input$name' class='col-sm-4 col-form-label px-0'>$label</label>
		// 				<div class='col-sm-8'>".
		// 					self::_select($name, $list, $value, $other).
		// 				"</div>
		// 			</div>";
	}

	public static function _textbox($name, $value='', $other=[]) {
		return "<input name='$name' id='$name' value='$value' type='text' class='form-control form-control-sm' autocomplete='off' ".
					self::_attributes_to_string($other).
				">";
	}

	public static function _numericbox($name, $value='', $other=[]) {
		return "<input name='$name' id='$name' value='$value' type='numeric' class='form-control form-control-sm' autocomplete='off' ".
					self::_attributes_to_string($other).
				">";
	}

	public static function _label($name, $value='', $other=[]) {
		return "<input name='$name' id='$name' value='$value' type='text' class='form-control-plaintext form-control-sm' autocomplete='off' readonly ".
					self::_attributes_to_string($other).
				">";
	}

	public static function _datebox($name, $value='', $other=[]) {
		return "<div class='input-group mb-3'>
  				<input id='$name' name='$name' value='$value' data-date='$value' type='text' class='form-control form-control-sm datepicker' placeholder='dd/mm/yyyy' ".
				  self::_attributes_to_string($other). ">
  				<div class='input-group-append'>
    				<button class='btn btn-sm btn-outline-secondary' type='button'><i class='fa fa-calendar'></i></button>
  				</div>
			</div>";
	}
	public static function _check($name, $value, $other=[]) {
		$ck = ($value==1 or $value)? 'checked' : '';
		return "<input id='$name' name='$name' class='form-check-input' type='checkbox' $ck value='$value' >";
	}
	public static function _select($name, $list, $value='', $other=[]) {
		$slist = "<option> -|- </option>";
		$slist = "";
		if (count($list) > 0) {
			//single array
			// foreach($list as $ls) {
			// 	$v1 = $ls; $v2 = $ls;
			// 	$slist .= "<option value='$v1'>" . $v2 . "</option>";
			// }
			// multiplae array
			foreach($list as $ls) {
				$ls = (array)$ls;
				$key = array_keys($ls);
				if (!isset($key[1])) $key[1]=$key[0]; //cek if single column
				$v1 = $ls[$key[0]]; $v2 = $ls[$key[1]];
                $s = (strtolower($v1)==strtolower($value))? 'selected':'';
				$slist .= "<option value='$v1' $s>" . $v2 . "</option>";
			}
		}
		if ($other==[]) $sel2 = 'select2';
		$sel2 = (isset($other['mselect']))? 'mselect2':'select2';
		return "<select name='$name' id='$name' class='form-control form-control-sm $sel2' autocomplete='off'>
					$slist
				</select>";
	}

	public static function _mselect($name, $list, $value='', $other=[]) {
		$slist = "<option> -|- </option>";
		$slist = "";
		if (count($list) > 0) {
			//single array
			// foreach($list as $ls) {
			// 	$v1 = $ls; $v2 = $ls;
			// 	$slist .= "<option value='$v1'>" . $v2 . "</option>";
			// }
			// multiplae array
			foreach($list as $ls) {
				//$ls = (array)$ls;
				$v1 = $ls['id'];
				$v2 = $ls['text'];
				// $key = array_keys($ls);
				// if (!isset($key[1])) $key[1]=$key[0]; //cek if single column
				// $v1 = $ls[$key[0]]; $v2 = $ls[$key[1]];
                $s = (strtolower($v1)==strtolower($value))? 'selected':'';
				$slist .= "<option value='$v1' $s>" . $v2 . "</option>";
			}
		}
		return "<select name='$name' id='$name' class='form-control form-control-sm xmselect2 w-100' autocomplete='off'>
					$slist
				</select>";
	}



	public static function _attributes_to_string($attributes)
	{
		if (empty($attributes))
		{
			return '';
		}
		if (is_object($attributes))
		{
			$attributes = (array) $attributes;
		}
		if (is_array($attributes))
		{
			$atts = '';
			foreach ($attributes as $key => $val)
			{
				$atts .= ' '.$key.'="'.$val.'"';
			}
			return $atts;
		}
		if (is_string($attributes))
		{
			return ' '.$attributes;
		}
		return FALSE;
	}
	// public static function _get_value($name, $defValue='') {
	// 	if(isset(self::$form_data->$name)) {
	// 		return (string)self::$form_data->TransNo;
	// 	} else {
	// 		return $defValue;
	// 	}
	// }

}

#------------------------------------------------
#- Form 2 Helper Functon
#- (c) 2019 Albert (albertsardi@gmail.com)
#------------------------------------------------
class Form2
{
	//public static function text($name, $label, $value, $other=null) {
	public static function text($name, $label, $other=null) {
		// if (!is_array($other)) {$value = $other;} else {$value = isset($other['value'])? $other['value']:'';}
		// if (!is_null(Form::$form_data)) $value= Form::_get_value($name, 0);

		// if ($other==null) $value = Form::$form_data->$name;
		// if(is_array($other)) {
		// 	$value = isset($other->value)? $other->value:'';
		// 	if (isset(Form::$form_data)) $value = Form::$form_data->$name;
		// } else {
		// 	$value = Form::$form_data->$name;
		// }
		$value = Form::getValue($name, $other);

		echo "<div class='row mb-1'>
				<div class='col-4 text-right'> $label </div>
				<div class='col-8'>". Form::_textbox($name, $value, $other) ."</div>
			</div>";
	}
	public static function number($name, $label, $other=null) {
		$value = Form::getValue($name, $other, 0);

		echo "<div class='row mb-1'>
				<div class='col-4 text-right'> $label </div>
				<div class='col-8'>". Form::_numericbox($name, $value, $other) ."</div>
			</div>";
	}
	public static function check($name, $label, $other=null) {
		$value = Form::getValue($name, $other, 0);
		$ck = ($value==1 or $value)? 'checked' : '';

		$ck = "<div class='form-check'>".
				Form::_check($name, $value, $other).
				"<label class='form-check-label' for='defaultCheck1'> $label </label>
			  </div>";
		echo "<div class='row mb-1'>
				<div class='col-4 text-right'></div>
				<div class='col-8'> $ck </div>
			</div>";
	}
	public static function select($name, $label, $list = [], $other = null) {
		$value = Form::getValue($name, $other);

		echo "<div class='row mb-1'>
				<div class='col-4 text-right'> $label </div>
				<div class='col-8'>". Form::_select($name, $list, $value, $other) ."</div>
			</div>";
	}
	public static function textwlookup($name, $label, $modal, $other=[]) {
		$value = Form::getValue($name, $other);

		$tbw = "<div class='form-row'>
					<div class='input-group'>".
						Form::_textbox($name, $value).
						"<div class='input-group-prepend'>
							<button id='$name-lookup' type='button' data-toggle='modal' data-target='#$modal' class='btn btn-outline-secondary btn-sm btnlookup'><i class='fa fa-search'></i></button>
						</div>
					</div>
				</div>".
				"<div class='form-row'>
					<label id='".$name."-label' class='form-label' for='autoSizingCheck2'> <i>blank</i> </label>
				</div>  ";
		echo "<div class='row mb-1'>
				<div class='col-4 text-right'> $label </div>
				<div class='col-8'>". $tbw ."</div>
			</div>";
	}
}

#------------------------------------------------
#- Modal Functon
#- (c) Albert
#------------------------------------------------
class Modal
{
   public static function open($name, $label, $size="modal-lg", $other = "") {
      if($other!='') $other=`style='$other'`;
      echo "<div class='modal fade' id='modal-$name' >
               <div class='modal-dialog $size'>
                  <div class='modal-content' $other>
                     <div class='modal-header'>
                     <h5 class='modal-title'>$label</h5>
                     <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                     </button>
                     </div>
                     <div class='modal-body'>";
   }
   public static function close($other= "") {
      echo "</div>
                  <div class='modal-footer'>
                  <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                  <button type='button' class='btn btn-primary'>Save changes</button>
                  </div>
               </div>
            </div>
            </div>";
   }
}

#------------------------------------------------
#- Card Functon
#- (c) Albert
#------------------------------------------------
class Card
{
	public static function open($name='', $label='', $opt='') {
		if ($opt != '') $opt = "style='$opt'";
		$h = "<div class='card mb-3' id='$name'>";
		if ($label != '') $h.= "<div class='card-header'>
									<h3><i class='fa fa-check-square-o'></i> $label </h3>
								</div>";
		$h.="<div class='card-body' $opt>";
		return $h;
	}
	public static function close($other = "") {
		$footer = '';
		if($other!='') $footer="<div class='card-footer'>$other</div>";
		return "</div>$footer</div>";
	}
}

?>