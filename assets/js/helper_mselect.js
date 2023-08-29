//========================
// function for multi kolumn select2
// using jQuery
//========================

function init_mselect(obj, dt) {
      obj.select2({
         placeholder: `Choose a ${dt[1]}`,
         templateResult: function(data) {
            var str = data.text.split(' - ')
            //if str[0]=='-' str[1]='-';
            // console.log(str)
            var result = jQuery(
            '<div class="row">' +
                  '<div class="col-md-3">' + str[0] + '</div>' +
                  '<div class="col-md-9">' + str[1] + '</div>' +
            '</div>'
            );
            return result;
         },
      });
}