//=======================================================================
//  EDIT GRID
//  plugin library
//  Created 2019 by Albertsardi@google.com
//=======================================================================


var target = null;
var _egrid = null;
var _egrid_option = [];
var _egrid_addButton=`<div class='row row-button'><button id='cmAddline' onclick='_grid_addline()' type='button' class='xbtn xbtn-tertiary cell'>Add new line</button></div>`;
var _egrid_lastline=-1;
var _egrid_selrow=-1;

//[insert edit form here]

//------------------------------
//  GRID EDIT EVENT
//------------------------------



//------------------------------
//  MODALGRID EVENT
//------------------------------
//$('#listAcc').on('click', 'td', function (e) {
// $('.modal-table').on('click', 'td', function (e) {
//     e.preventDefault();
//     var table = $(this).closest('table').DataTable();
//     var row = table.row($(this).closest('tr')).data();

//     $("input[name='"+target+"']:eq("+_egrid_selrow+")").val(row['AccNo']);
//     //$("input[name='"+target+"-val1']:eq("+_egrid_selrow+")").val(row['AccNo']);
//     $("input[name='"+target+"-val2']:eq("+_egrid_selrow+")").val(row['AccName']);
//     //$('#modal-maccount').modal('hide')
//     $('.modal').modal('hide')
// });
//$('#modal-maccount').on('show.bs.modal', function (event) {
// $('.modal').on('show.bs.modal', function (event) {
//     var button = $(event.relatedTarget) // Button that triggered the modal
//     //var recipient = button.data('target') // Extract info from data-* attributes
//     //var recipient = button.data('#modal-maccount')
//     //console.log(button[0]['attributes'][3]['data-target']);
//     target=button[0]['attributes']['target'].value; //set target
//     _egrid_selrow = button.closest('.grid-row').attr('line');
//  });
// -------------------------------




 //------------------------------
// JS Edit GRID function
//------------------------------
//get call value
function cell(row, col) {
    if (col.substr(-2) != "[]") col = col + "[]"; //nama pakai []
    var v = $("input[name='grid-" + col + "']:eq(" + row + ")").val();
    if (typeof v === "undefined") v = "";
    return v;
}
//set call value
function setcell(row, col, val) {
    if (col.substr(-2) != "[]") col = col + "[]"; //nama pakai []
    $("input[name='grid-" + col + "']:eq(" + row + ")").val(val);
}




//------------------------------
// JS Edit GRID function
//------------------------------
function editgrid_ajaxload(grid, dataSource, option) {
    _egrid = $('#'+grid);
   _egrid_option = option;
   
    $.ajax({
       url: dataSource,
       success: function (resp) {
          resp = JSON.parse(resp);
          //console.log(resp.status);
          //populate header
          $.each(option.columns, function (idx, col) {
             //console.log(idx + ' X- ' + col.name)
             if (col.caption == undefined) col.caption = col.name
             if (col.width == undefined) col.width = 100
             if (col.type == 'textlookup') col.width=col.width+100
             _egrid.append(`<label style='width:${col.width}px;'>${col.caption}</label>`) 
          })
          
          //populate detail
          //resp.data = JSON.parse(resp.data);
          if (resp.data == undefined) {
             console.log('ERROR: Form no data')
             //return; //exit function
          } else {
            //have data, then load 
            //console.log(resp.data)
             var a = 0;
             $.each(resp.data, function (idx, val) {
                var line = resp.data[a];
                //console.log()
                var b = 0;
                _grid_line(_egrid, line, a, option)
                a = a + 1;
             })
          }

          //insert last line
          _grid_line(_egrid, line, a, option)
          a = a + 1;

          _egrid.append(_egrid_addButton);
          _egrid_lastline=a;
       }
    });
 };
function _grid_line(grid, line, idx, option) {
   var a = 0;
   var html='';
   html+= "<div class='grid-row grid-row"+idx+" form-inline' line="+idx+">";
   $.each(option.columns, function (f,v) {
      var col = option.columns[a];
      var v = line[col.name];
      if (v == undefined) v = '';
      if (col.width == undefined) col.width = '100px';
      if (col.type == undefined) col.type = 'text';
      switch (col.type) {
         case 'text':
            html+= `<input type='text' name='${col.name}' value='${v}' placeholder='input ${col.name}' style='width:${col.width}px' class='form-control-sm cell'></input>`
            break;
         case 'textlookup':
            var modal = col.modal; if (col.modal == undefined) console.log(`ERROR:modal ${col.name} undefine`);
            html+= `<div class="nput-group-prepend">
                           <input type='text' name='${col.name}'  value='${v}' placeholder='input ${col.name}' class='form-control-sm cell' ></input>
                           <button type="button" onclick="_grid_btnlookup(${idx},'${col.name}')" class="btn btn-outline-secondary" data-toggle="modal" data-target="#modal-${modal}" target="${col.name}"><i class="fa fa-search"></i></button>
                           </div>`
            break;
         case 'number':
            html+= `<input type='numeric' name='${col.name}'  value='${v}' placeholder='input ${col.name}' style='width:${col.width}px' class='form-control-sm cell'></input>`
            break;
         case 'label':
               html+= `<input type='text' name='${col.name}' value='${v}' placeholder='input ${col.name}' style='width:${col.width}px' class='form-control-sm form-control-plaintext cell' readonly></input>`
               break;
      }
      a = a + 1;
   });
   html+= `<button id='btnDel-row-${idx}' type='button' onclick='_grid_delline(${idx})' class='cmDelline xbtn xbtn-tertiary cell'>X</button>`;
   html+= `</div>`;
   grid.append(html);
}
function _grid_btnlookup(idx, btn_lookup) { 
   lookup_target = [idx,btn_lookup];
}
function _grid_delline(id) {
   $('.grid-row'+id).remove();
   if (typeof me.egrid_eventRowDel === "function")  {
      egrid_eventRowDel(id)
   }
}
function _grid_addline() {
   _grid_line(_egrid, line, _egrid_lastline, _egrid_option);
   $('div.row-button').remove();
   _egrid.append(_egrid_addButton);
   _egrid_lastline=_egrid_lastline+1;
}
function cell(row, name) { 
   return $(`input[name='${name}']:eq(${row})`).val() 
}
function cellset(row, name, v) { 
   $(`input[name='${name}']:eq(${row})`).val(v) 
}
