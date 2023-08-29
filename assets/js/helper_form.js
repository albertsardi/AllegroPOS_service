//datagrid cell format
// var datatable = {
// 	fcur : $.fn.dataTable.render.number(',', '.', 0, 'Rp '),
// 	fnum : $.fn.dataTable.render.number(',', '.', 0, ''),
//     fdate : function(v) { return moment(v).format('DD/MM/YYYY') },
// 	xtest:'dddddXXXX'
// }
// alert(datagrid.xtest)

(function( $ ){

	//function save form
   	$.fn.formsave = function(jr, id) {
		console.log('saving ...')
		//alert(_api);
		var formdata=this.serialize();
		//$.post("{{ env('API_URL') }}/api/"+jr+'/save/'+id, formdata, function(result) {
		$.post(window.API_URL+"/api/"+jr+'/save/'+id, formdata, function(result) {
			//console.log(result)
			return JSON.stringify(result);
			//alert(JSON.stringify(result));
			if (result.status=='OK') {
				if(id=='new' || id=='') {
					//window.location.href = "http://localhost/lav7_PikeAdmin_multi/product/edit/"+result.data.id;
					window.location.href = window.location.href.replace("/new", "/"+result.data.id);
				}
				return result.data
			} else {
				return result;
			}
			
        }); 
   	}; 

	//function select2 multi column
	$.fn.mselect2 = function(jr, txt='') {
		//var p = api.lastIndexOf("/");
		//var jr = api.substring(p+1);
		if (txt=='') txt = jr.charAt(0).toUpperCase() + jr.slice(1);
	 	this.select2({
			placeholder: `Choose a ` + txt,
			ajax: {
				//url: "<?php echo env('API_URL');?>/api/select/" + jr,
				url: window.API_URL+"/api/select/" + jr,
				//url: 'http://localhost/lav7_PikeAdmin_multi/api/select/customer',
				//url: api,
				dataType: 'json'
			},
			templateResult: function(data) {
				if (data.text.includes('|')) {
					var str = data.text.split('|')
					var result = jQuery(
					'<div class="row">' +
						'<div class="col-md-3">' + str[0] + '</div>' +
						'<div class="col-md-9">' + str[1] + '</div>' +
					'</div>'
					);
				} else {
					var result = data.text;
				}
				return result;
			},
			templateSelection: function(data) {
				if (data.text.includes('|')) {
					var str = data.text.split('|')
					var result = jQuery(
						'<div class="row">' +
							'<div class="col-md-3">' + str[0] + '</div>' +
							'<div class="col-md-9">' + str[1] + '</div>' +
						'</div>'
					);
				} else {
					var result = data.text;
				}
				return result;
			}
		});
	}

})( jQuery );



// func lib -----------------
async function transSave(jr, id, formdata) {

}