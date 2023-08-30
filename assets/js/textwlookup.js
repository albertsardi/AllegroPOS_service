//--------------------------------------------------
// JQuery function for Text with Lookupup Function
// (c) 2022 Albert
// ------------------------------------------------

//const { forEach } = require("lodash");

$.fn.textwlookup = function(val, label2) {
	var name = this.attr('id').replace('-lookup','')
    $("input[name='"+name+"']").val(val);
    //$("label#"+name+"-val2").text(label2);
    $("input[name='"+name+"Label']").val(label2);
};


$(document).ready(function () {
    $('.select2').each(async function (i) {
        var sel = $('#' + $(this).attr('id'))
        var api = $(this).attr('api')
        sel.select2({
            theme: "bootstrap",
            placeholder: "select item",
        });
        if (api != '') {
            sel.select2({
                //theme: "bootstrap",
                /*ajax: {
                    url: 'http://localhost/lav7_PikeAdmin/'+api,
                    dataType: 'json',
                },*/
                templateResult: function (dat) {
                    //var r = dat.toString().split('|');
                    var r = [dat.id, dat.text];
                    var $result = $(
                        '<div class="row">' +
                        '<div class="col-md-3">' + r[0] + '</div>' +
                        '<div class="col-md-1">-</div>' +
                        '<div class="col-md-8">' + r[1] + '</div>' +
                        '</div>'
                    );
                    return $result;
                },
                templateSelection: function (dat) {
                    var r = [dat.id, dat.text];
                    var result = $(
                        '<div class="row">' +
                        '<div class="col-md-3">' + r[0] + '</div>' +
                        '<div class="col-md-9">' + r[1] + '</div>' +
                        '</div>'
                    );
                    return result;
                },
                //matcher: function(term, text) {
                // TODO: search nya mash belum jalamconsole.log([term, text]);
                //return text.toUpperCase().indexOf(term.toUpperCase())>=0 || option.val().toUpperCase().indexOf(term.toUpperCase())>=0;
                //if (text.toUpperCase().indexOf(term.toUpperCase())==0) return true;
                //return false;
                //}
            });

            //populate data
            var resp = await fetch('http://localhost/lav7_PikeAdmin/' + api)
            if (resp.statusText=='OK') {
                let dat = await resp.json();
                for(let r of dat.results ) {
                    var opt = new Option(r.text, r.id, false, false);
                    sel.append(opt).trigger('change');
                }
            }
        }
    });

    
    $('.select2').on("select2:select", function (e) { 
        console.log(e.params.data)
    });
    
    // $('#AccCode').val({id:'CS01'}).trigger('change');
    // $('#AccCode').val('CS01').trigger('change');
    // $('#AccCode').val(3).trigger('change');
    //$('#AccCode').select2('data', { id:"CS01", text: "CARMEL SEJATI, PT xxx"});
    // $('#AccCode').select2('val', 3).trigger('change');
    // $('#AccCode').select2('val', 'CS01').trigger('change');
    // $('#AccCode').val(3).select2().trigger('change');
    //$('#AccCode').val( { id: "AKI001", text: "KO AKI", selected: true })

}); 