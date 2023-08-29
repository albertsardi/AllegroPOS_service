//========================
// function for AG_Grid
// using jQuery
//========================

function agGrid_getIndex(gridOptions, find) {
   var idx = 0;
   var rowData = gridOptions.rowData
   for (let row of rowData) {
       if (row.InvNo == find) return idx;
       idx++;
   }
   return -1;
}

function setColModel(model, opt = {}) { 
   var newModel = [];
   var arr1 = { headerName: '', width: 10, valueGetter: 'node.rowIndex + 1' }; //add sequence number
   var arr2 = {
      headerName: '', cellRenderer: function (row) {
         return `<button type='button' class='btn btn-sm btn-danger cmDelrow my-2'><i class='fa fa-close'></i></button>`;
      }
   };
   newModel = newModel.concat(arr1, model);
   if (!opt.delRowHide) newModel = newModel.concat( arr2);
   return newModel;
}

var setLookupButton = function (row, modalId) { 
   modalId = '#' + modalId;
   return  "<button class='btn btn-sm btn-secondary cmLookup' type='button' data-idx=11 data-toggle='modal' data-target='"+modalId+"'><i class='fa fa-ellipsis-h'></i></button>"+
                            "   "+row.value;
}