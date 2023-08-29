//using metro
var m=null;
var t=null; var t2=null; var t3=null;
var gline=-1;

//------------------------------
//  FORM EVENT
//------------------------------
$(document).ready(function() {
    $('.cmLookup').click(function() {
        //Metro.dialog.open('#demoDialog1');
        //Metro.dialog.open('#modal-account');
        m=$(this).attr('lookup-modal'); 
        t=$(this).attr('lookup-target'); 
        Metro.dialog.open('#'+m);
    });
    //lookup modal select
    $('a.dialog-link').click(function(e) {
        e.preventDefault();
        var sel=$(this).text(); console.log(sel);
        if(t.indexOf("grid-") == -1) {
            //form
            $("input[name='"+t+"']").val(sel).change();
        } else {
            //grid
            //alert(t);
            if(t.substring(t.length - 2) != '[]') t=t+='[]';
            $("input[name='"+t+"']:eq("+gline+")" ).val(sel).change();
            var sel2 = $(this).closest("tr").find('td:eq(1)').text();
            if(t2.substring(t2.length - 2) != '[]') t2=t2+='[]';
            $("input[name='"+t2+"']:eq("+gline+")" ).val(sel2).change();
            var sel3 = $(this).closest("tr").find('td:eq(2)').text();
            if(t3.substring(t3.length - 2) != '[]') t3=t3+='[]';
            $("input[name='"+t3+"']:eq("+gline+")" ).val(sel3).change();
        }
        Metro.dialog.close('#'+m);
        //$('button#cmModalClose').click();
    })
    //format input numeric
    //$("input.num").css('text-align','right').number(true,0); //css('background-color','red'); //0 digit di belakang koma
    //var nv=$("input[type='num']").val();
    //$("input[type='num']").val('Rp. '+nv);
    // $("input[type='num']").autoNumeric('init', {aSign: "€ "});

    //format date using datepicker.js
    /*
    $("[data-toggle='datepicker']").datepicker({
        //autoHide:true,
        format:'yyyy-mm-dd'
    });
    */

    //combo using select.js
    $(".select2").select2({
        placeholder: 'Select an option'
    });
})

//------------------------------
//  GRID EDIT EVENT
//------------------------------
$(document).ready(function() {
    $('.cmGridLookup').click(function() {
        //alert('jsjs');
        //Metro.dialog.open('#demoDialog1');
        //Metro.dialog.open('#modal-account');
        m=$(this).attr('lookup-modal'); //if($('#'+gm).length == 0) { console.log('Error: modal window '+gm+' not exist'); } 
        t=$(this).attr('lookup-target');
        t2=$(this).attr('lookup-target2');
        t3=$(this).attr('lookup-target3');
        gline=$(this).parent().attr('line');
        Metro.dialog.open('#'+m);
    });
    //cmAddline click
    $('button#cmAddline').click(function() {
        //alert('add line '+lastline);
        lastline=lastline+1;
        //$("div.grow[line='"+lastline+"']").show(); //jquery version
        $( 'div.grow:eq('+lastline+')' ).removeClass('d-none');
        if (typeof(gridevent_addrow) == "function") {
            gridevent_addrow(lastline);//run grid event
        }
    })
    //cmDelline click
    $('button.cmDelline').click(function() {
        target=$(this).parent().attr('line');
        //alert(target);
        //$('div.grow[line='+target+']').hide(); //pakai jquery
        $( 'div.grow:eq('+target+')' ).addClass('d-none');
        if (typeof(gridevent_delrow) == "function") {
            gridevent_delrow(target);//run grid event
        }
    })
})


//------------------------------
// JS Edit GRID function 
//------------------------------
//load grid
function GridLoad(TransNo) {
    //var dataSource= 'dat/listall';
    //var dataSource= '_listall.php';
    //$('#t1').data('table').loadData(dataSource, true);

    //editgrid
    $.ajaxSetup({
        async: false
    });
    //var dataSource= '_loaddataarray.php?jr=transdetail&id='+TransNo ;
    var dataSource= '_loadtrans.php?jr=grid&id='+TransNo ;
    $.getJSON(dataSource, function(data, status) {
        var xrow=0;
        for(var row=0;row<data.length;row++) {
            for (var nm in data[row]) {
                //if (data.hasOwnProperty(nm)) {
                    var col='grid-'+nm+'[]'; 
                    $("input[name='"+col+"']:eq("+xrow+")").val(data[row][nm]);
                //}
            }
            xrow++;
            //visible row if data exist
            $('.grow:eq('+row+')').removeClass('d-none');
        }
        lastline=row;
        //visible last row for edit
        $('.grow:eq('+row+')').removeClass('d-none');
    })  
    .error(function(jqXHR, textStatus, errorThrown) {
        console.log("error " + textStatus);
        console.log("incoming Text " + jqXHR.responseText);
    });
}
//get call value
function cell(row,col) {
    if(col.substr(-2)!='[]') col=col+'[]'; //nama pakai []
    var v=$("input[name='grid-"+col+"']:eq("+row+")").val();
    if (typeof v === "undefined") v='';
    return v;
}
//set call value
function setcell(row, col, val) {
    if(col.substr(-2)!='[]') col=col+'[]'; //nama pakai []
    $("input[name='grid-"+col+"']:eq("+row+")").val(val);
}
//load grid
function gridload(gdata) {
    $("div.grow").hide();
    var a=0;
    $.each( gdata, function( key, value ) {
        $.each( gdata[a], function( key, value ) {
            setcell(a,key,value);
        });
        //calcall(a);
        //make line visible
        $("div.grow[line='"+a+"']").show();
        a=a+1;
    });
    lastline=a;
    //make line visible
    $("div.grow[line='"+lastline+"']").show();
}
function gridtojson(fld) {
    var a=0;
    var fldkey=$('.grow input:first').attr('name');
    var data=[];
    //alert(ln.InvNo); exit;
    $.each( $(".grow"), function() {
        //alert(cell(a, fldkey));
        if(!(cell(a, fldkey)=='')) {
            var ln={};
            //ln['InvNo']=cell(a, 'InvNo');
            //ln['AmountPaid']=cell(a, 'AmountPaid');
            for(var b=0;b<fld.length;b++) {
                var f=fld[b];
                ln[f]=cell(a, f);
            }
            data.push(ln);
        }
        a=a+1;
    });
    //data=JSON.stringify(data);
    return data;
}



































































//------------------------------
// JS Edit GRID function
//------------------------------

//load form
// function FormLoad(TransNo) {
//     // var dataSource= '_loaddatarow.php?jr=transhead&id='+TransNo ;
//     //var dataSource= '_loadtrans.php?jr=form&id='+TransNo ;
//     var dataSource= "_loadtrans/"+TransNo;
//     //dataSource= "http://localhost/LV_PikeAdmin/public/_loadtrans/"+TransNo;
//     //dataSource= "{{ public_path()}}/_loadtrans/"+TransNo;
//     //dataSource= '{{ route("_loadTrans", ":id") }}'; dataSource= dataSource.replace(':id', TransNo);
//     //dataSource= '{{ route("loadTrans", ":id") }}'; dataSource= dataSource.replace(':id', TransNo);

//     console.log(dataSource);
//     $.getJSON(dataSource, function(data, status) {
//         console.log(data);
//         for (var nm in data) {
//             //var result='';
//             // obj.hasOwnProperty() is used to filter out properties from the object's prototype chain
//             if (data.hasOwnProperty(nm)) {
//                 //result += nm;
//                 $("input[name='"+nm+"']").val(data[nm]);
//                 $("input[name='"+nm+"']").css('background-color','red');
//                 $("input").val('xyz');
//                 //alert(result);
//             }
//         }
//     })
//     .error(function(jqXHR, textStatus, errorThrown) {
//         console.log("error " + textStatus);
//         console.log("incoming Text " + jqXHR.responseText);
//     });
// }
