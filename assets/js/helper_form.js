// function showLookup($lookupType) {
//     Metro.dialog.open('#demoDialog1');
// }


 //target lookup dialog

//Form Event
var m=-1; //modal dialog
var line = -1;
var lookup_target = '';
var lookup_select = '';
$(document).ready(function() {
    //init form
   // $('select.select2').select2();
   
    //helper check box
    $('input[type=checkbox]').change(function() {
        //var name=$(this).attr('name').substr(3);
        var name=$(this).attr('name');
        var v=0;
        if($(this).prop('checked')) v=1;
        $('input[name='+name+']').val( v );
    })
    // $("[data-toggle='datepicker']").datepicker({
    //     autoHide:true,
    //     format:'yyyy-mm-dd'
    // });

    //cmLookup click
   $('.btnlookup').click(function () {
      var m=$(this).attr('target-modal'); 
      if($('#modal-'+m).length==0) { 
         alert('modal '+m+' not exist'); return;
      } else {
         $('#modal-'+m).modal({show: true })
      }
         
   });
    // $('button.cmLook').click(function() {
    //     //check if modal exit
    //     var modal=$(this).attr('data-target'); //alert(modal);
    //     if($(modal).length == 0) { alert('modal window '+modal+' not exist');exit(); }
    //     //show lookup
    //     target=$(this).attr('target'); //alert(target);
    //     //$('.modal').modal('show');
    //     $(modal).modal('show');
    // })

    //FORM MODAL FUnction
   $('.modal').on('click', 'td', function (e) {
      e.preventDefault();    
      var table = $(this).closest('table').DataTable();
      var row = table.row($(this).closest('tr')).data();
      lookup_select = row;
      //console.log(lookup_select)
      //$("input[name='"+_lookup_target+"-val1']").val(row['AccNo']);
      //$("label[name='"+_lookup_target+"-val2']").text(row['AccName']);
      //$('#modal-maccount').modal('hide')
      $('.modal').modal('hide')
   });
   /*$('.modal').on('show.bs.modal', function (event) {
      var zIndex = 1040 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
         
      var button = $(event.relatedTarget) // Button that triggered the modal
      if (button[0] == undefined) return;
      lookup_target=button[0]['attributes']['target'].value; //set target
      var line = button.closest('.grid-row').attr('line');
      if (line != undefined) { //by grid
         lookup_target=[lookup_target, line]
      }
   });*/
   // trigger after mmodal close
   /*$('.modal').on('hide.bs.modal', function (event) {
      if(lookup_target==undefined) lookup_target='';
      if(!lookup_target.isArray) { //trigger by form
         $("input[name='"+lookup_target+"']").val(lookup_select.AccCode);
         $("label[name='"+lookup_target+"-val2']").text(lookup_select.AccName);
      } else { //trigger by grid
         var line=lookup_target[1]
         var target=lookup_target[0]
         //cellset(line, target, lookup_select.ProductCode);
         //cellset(line, target, lookup_select.ProductName);
      }
      onModalClose()
   });*/
   $(document).on({
      'show.bs.modal': function() {
         var zIndex = 1040 + (10 * $('.modal:visible').length);
         $(this).css('z-index', zIndex);
         setTimeout(function() {
         $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
         }, 0);
      },
      'hidden.bs.modal': function() {
         if ($('.modal:visible').length > 0) {
         // restore the modal-open class to the body element, so that scrolling works
         // properly after de-stacking a modal.
         setTimeout(function() {
            $(document.body).addClass('modal-open');
         }, 0);
         }
      }
   }, '.modal');


    //numeric format
    //$("input[type='num']").css('text-align','right'); //.css('background-color','red');
    //$(".number").autoNumeric('init', {aSign: '$ '});


    //using metro
    var m=-1; //modal dialog
    var t=-1; //target lookup dialog
    //lookup button click
   /*$('button.cmLookup').click(function() {
        m=$(this).attr('lookup-modal'); //alert('m='+m);
        t=$(this).attr('lookup-target'); //alert('t='+t);
        if($(m).length != 0) {
            //Metro.dialog.open('#'+m);
        } else {
            alert('modal window '+m+' not exist');
        }
    })*/
    //lookup modal select
    $('a.lookup-item').click(function(e) {
      e.preventDefault();
      var sel=$(this).text();
       //alert(sel);
        // alert(t+' - '+line);
        // alert('sel='+sel+' t='+t+' line='+line);
        if(t.indexOf("grid-") == -1) {
            //form
            $("input[name='"+t+"']").val(sel).change();
        } else {
            //grid
            //if(t.substring(t.length - 2) != '[]') t=t+='[]';
            //$("input[name='"+t+"']:eq("+gline+")" ).val(sel).change();
        }
        //Metro.dialog.close('#'+m);
        $('button#cmModalClose').click();
    })
   function lookup_itemclick(itm) {
      alert(itm);
   }
    //format input numeric
    //$("input.num").css('text-align','right').number(true,0); //css('background-color','red'); //0 digit di belakang koma
    //var nv=$("input[type='num']").val();
    //$("input[type='num']").val('Rp. '+nv);
    // $("input[type='num']").autoNumeric('init', {aSign: "€ "});

    //format date using datepicker.js
    // $("[data-toggle='datepicker']").datepicker({
    //     //autoHide:true,
    //     format:'yyyy-mm-dd'
    // });
})

//------------------------------
// JS FORM function
//------------------------------
function form_ajaxloaddata(link = '', other = []) {
   $.ajax({
        //url: "http://localhost:3100/ajax_dataload/product/012475",
        url: link,
        success: function (resp) {
           if (resp.data == undefined) {
              console.log('ERROR: Form no data')
              return;
           } 
            res = resp.data[0];
            //console.log(res);
            $.each(res, function (f, v) {
               var nm = $("input[name='" + f + "']");
                var t= nm.attr('type');
                //console.log(t);
                switch(t) {
                    case 'date':
                        nm.val(v);
                        break;
                    case 'checkbox':
                        nm.prop("checked", v);
                        break;
                    case 'label':
                        $("label#" + f).text(v);
                        break;
                    case 'checkbox':
                        nm.prop("checked", v);
                        break;
                    default :
                        nm.val(v);
                        break;
                }
                //nm.val(moment().format('DD/MM/YYYY'));
                //$("input[name='TransDate']").moment('111');
            });

        }
   });
}

//------------------------------
// JS AJAX FORM Jquery function
// TODO: check procedure ini gak bisa di jalankan, outputnya tidak jalan
//------------------------------
// using example $('#form-expense').submit( Ajax_post('/post-form', 'form-expense') );
function Ajax_post(url, formId, type='POST') {
   var formdata=$('#'+formId).serialize();
    $.ajax({
       type:'POST',
       //url:'/datasave_product', //using local
       //url:'http://localhost:500/api/datasave_product',
       url:url,
       //data:{name:name, password:password, email:email},
       data: formdata,
       success:function(res){
          alert(res.success);
          console.log(res.data);
          return res;
       },
       error:function(res){
         //console.log(JSON.stringify(res));
         alert(res.error);
          console.log(res.data);
          return res;
      }
    });
 }

function tb(name, val) {
   $(`input[name='${name}']`).val(val);
}

function sleep(ms) {
    return new Promise((resolve) => {
       setTimeout(resolve, ms);
    });
 }

