$(document).ajaxComplete(function (e,x) {
    var rspns = x.responseJSON;
    if(typeof rspns == "undefined")
    {
        return false;
    }

    if(typeof rspns.staffModal !== "undefined" && rspns.staffModal == 1)
    {
        $("#staff_modal").modal('show');
    }
});