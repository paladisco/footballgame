// ----- General reusable function for ajax calls -----
function ajaxCall(url,target,callbackFunction) {
    $.ajax({
        url: url,
        cache: false,
        spinnerTarget: target
    }).done(function( html ) {
        callbackFunction(html);
    });
}

$('.modal').on('hidden.bs.modal', function() {
    $(this).removeData('bs.modal');
    $(this).html('')
});