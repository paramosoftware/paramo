
let cancelProcessing = false;
let modalProgress = null;
function showLoadingSpinner() {
    $("body").css("overflow", "hidden");
    $("#backdrop-spinner").show();
}

function hideLoadingSpinner() {
    $("body").css("overflow", "auto");
    $("#backdrop-spinner").hide();
}


function getProgress(file, download = false) {
    $.ajax({
        url: 'functions/progress.php',
        type: "POST",
        data: {
            file: file
        },
        success: function (data) {
            if (cancelProcessing) {
                cancelProcess(file);
            } else if (isNaN(data)) {
                addErrorMessage("Ocorreu um erro ao processar o arquivo: " + data);
            } else if (data < 100) {
                setTimeout(function () {
                    getProgress(file, download);
                    updateModalProgress(data);
                }, 500);
            } else {
                completeProgress(file, download);
            }
        }
    });
}

function cancelProcess(file) {
    $.ajax({
        url: 'functions/progress.php',
        type: "POST",
        data: {
            file: file,
            stop: true
        },
        success: function (data) {
            closeModalProgress();
        }
    });
}

function updateModalProgress(progress) {

    if (!modalProgress) {
        modalProgress = $("#modal-progresso").clone();
    }

    if (!$('#modal-progresso').hasClass('show')) {
        $("#modal-progresso").modal("show");
    }

    $("#barra-progresso .progress-bar").css("width", progress + "%");
    $("#barra-progresso .progress-bar").text(Math.floor(progress) + "%");
}

function completeProgress(file, download = false) {
    updateModalProgress(100);
    $("#modal-progresso-cancel").text("Fechar");
    $("#modal-progresso-cancel").attr("onclick", "closeModalProgress();");
    $("#modal-progresso-title").text("Concluído");
    $("#barra-progresso").hide();
    $("#modal-progresso-status").html("<a target='_blank' href='functions/serve_file.php?folder=temp" + (download ? "&download=true" : "") + "&file=" + file + "'>Clique aqui</a> para acessar o arquivo");
}

function closeModalProgress() {
    cancelProcessing = false;
    $("#modal-progresso").modal("hide");
    $("#modal-progresso").replaceWith(modalProgress);
    modalProgress = null;
}

function addErrorMessage(message) {
    cancelProcessing = true;
    $("#modal-progresso-cancel").text("Fechar");
    $("#modal-progresso-cancel").attr("onclick", 'closeModalProgress();');
    $("#barra-progresso").hide();
    $("#modal-progresso-status").append("<div class='alert alert-danger'>" + message + "</div>")
}

