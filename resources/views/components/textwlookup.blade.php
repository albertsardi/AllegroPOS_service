<?php
    /* function _attributes_to_string($attributes)
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
	} */

    //$attr = _attributes_to_string($other);
	$attr = '';
    //$value= Form::getValue($name, $other);
	if (!empty($other2)) $other = $other2;
    $tb =  "<input name='$name' id='$name' value='$value' type='text' class='form-control form-control-sm' autocomplete='off' ".
					$attr.
				">";
?>

<div class='form-row align-items-top'>
    <div class='col-sm-4 my-3'>
        <label>{{$label}}</label>
    </div>
    <div class='col-sm-8 my-1'>
        <div class='form-row'>
            <div class='input-group'>
                
				<div class="dropdown">
					<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
						Dropdown button
					</button>
					<div class="dropdown-menu" style="width:300px;">
						<div class='row'>
							<div class='col m-1'><input id='searchBox' value='' class='w-100' onkeyup="onPress();" /></div>
						</div>
						<div class='row' text='1|label text1'>
							<div class='col'><a class="dropdown-item" href="#">1</a></div><div class='col'>label text1</div>
						</div>
						<div class='row' text='2|label text2'>
							<div class='col'><a class="dropdown-item" href="#">2</a></div><div class='col'>label text2</div>
						</div>
						<div class='row' text='3|label text3'>
							<div class='col'><a class="dropdown-item" href="#">3</a></div><div class='col'>label text3</div>
						</div>
					</div>
				</div>
				
				{{-- <div class='input-group-prepend'>
                    <button id='{{$name}}-lookup' type='button' data-toggle='modal' data-target='#{{$modal}}' class='btn btn-outline-secondary btn-sm btnlookup'><i class='fa fa-search'></i></button>
                </div> --}}
            </div>
        </div>   
        <div class='form-row'>
            <label id='{{$name}}-val2' class='form-label' for='autoSizingCheck2'> <i>blank</i> </label>
        </div>   
    </div>   
</div>

<style>
	.dropdown-menu { border: 1px solid lightgray;}
</style>

<script>
	/*jQuery('#searchBox').keypress(function() {
		let v = jQuery(this).val();
		console.log(v);
	})*/
	function onPress() {
		let f = document.getElementById("searchBox").value;
		//let f = jQuery("#searchBox").val()
		//let f = this.value;
		//console.log(f);
		
		//let div = document.getElementsByClassName('dropdown-menu')
		let div = $('.dropdown-menu')
		console.log(div)
		div.each(function(r) {
			//console.log(r)
			//var row = $(this).find(".row");
			var r = r.find(".row")
			console.log(r)
			//var txt = row.attr('text')
			//console.log(txt)
		})
	}
</script>
