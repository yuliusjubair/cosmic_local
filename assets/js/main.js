$(".ExportDialog").on("click", function() {
        $('#modal_form').modal('show');
});

$("#ExportExcel").on("click", function() {
        $('.buttons-excel').click();   
});

$("#ExportCsv").on("click", function() {
    $('.buttons-csv').click();   
});

function open_dialog(){
    $('#modal_form').modal('show');
}

function block() {
    var body = $('#panel-body');
    var w = '100%';
    var h = '150%';
    var trb = $('#throbber');
    var position = body.offset(); // top and left coord, related to document
    var top = '1';
    trb.css({
        width: w,
        height: h,
        opacity: 0.7,
        position: 'absolute',

        top:        0,
        left:       0
    });
    trb.show();
}
function unblock() {
    var trb = $('#throbber');
    trb.hide();
}