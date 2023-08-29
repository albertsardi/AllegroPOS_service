//--------------------------------------------------
// JQuery Edit Grid Function
// (c) 2018 Albert
// ------------------------------------------------

var gm=null;
var gt=null;
var gline=-1;
var lastline=0;
$(document).ready(function() {
    
    $('.cmGridLookup').click(function() {
        //alert('jsjs');
        //Metro.dialog.open('#demoDialog1');
        //Metro.dialog.open('#modal-account');
        gm=$(this).attr('lookup-modal'); //if($('#'+gm).length == 0) { console.log('Error: modal window '+gm+' not exist'); } 
        gt=$(this).attr('lookup-target');
        gline=$(this).parent().attr('line');
        Metro.dialog.open('#'+gm);
    });
    //modal select 
    //dimatikan karena bentrok dengan form
    // $('.modal a').click(function(e) { 
    //     e.preventDefault();
    //     var sel=$(this).text(); 
    //     console.log(sel);
    //     var cell=$("input[name='grid-"+gt+"[]']:eq("+gline+")");
    //     cell.val(sel);
    //     cell.change();
    //     //Metro.dialog.close('#'+gm);
    // })
    
    //cmAddline click
    $('button#cmAddline').click(function() {
        //alert('add line '+lastline);
        lastline=lastline+1;
        //$("div.grow[line='"+lastline+"']").show(); //jquery version
        $( 'div.grow:eq('+lastline+')' ).removeClass('d-none');
        gridevent_addrow(lastline);//run grid event
    })
    
    //cmDelline click
    $('button.cmDelline').click(function() {
        target=$(this).parent().attr('line');
        //alert(target);
        //$('div.grow[line='+target+']').hide(); //pakai jquery
        $( 'div.grow:eq('+target+')' ).addClass('d-none');
        gridevent_delrow(target);//run grid event
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

