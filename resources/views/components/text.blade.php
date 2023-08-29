<?php
	$attr = '';
	if (!empty($other2)) $other = $other2;
	$value ='';
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
                {!! $tb !!}
            </div>
        </div>   
        {{-- <div class='form-row'>
            <label id='{{$name}}-val2' class='form-label' for='autoSizingCheck2'> <i>blank</i> </label>
        </div>    --}}
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
