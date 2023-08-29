<?php
	//dump($slist);
?>

<div class="form-group">
	<label for="Input{{$name}}">{{$label}}</label>
	<select name='{{$name}}' id='{{$name}}' api='{{$api}}' class='form-control  form-control-sm select2'>
	{!!$slist!!}
	</select>
    <small class="form-text text-muted">input {{$label}}</small>
  </div>

<style>
	.dropdown-menu { border: 1px solid lightgray;}
</style>
