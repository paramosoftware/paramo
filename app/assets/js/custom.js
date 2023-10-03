
function showLoadingSpinner() {
    $("body").css("overflow", "hidden");
    $("#backdrop-spinner").show();
}

function hideLoadingSpinner() {
    $("body").css("overflow", "auto");
    $("#backdrop-spinner").hide();
}
