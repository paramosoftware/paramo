
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

// --- Exportação em partes ---------------------------------------------------

let exportProgressMissing = 0;
let exportProgressCancelRequested = false;
let exportProgressLast = -1;
let exportProgressLastChange = 0;

function resetProgressModal() {
    $("#modal-progresso-title").text("Processando");
    $("#modal-progresso-status").empty();
    $("#modal-progresso-arquivos").empty();
    $("#barra-progresso").show();
    $("#barra-progresso .progress-bar").css("width", "0%").text("0%");
    $("#modal-progresso-cancel").text("Cancelar");
    $("#modal-progresso-cancel").attr("onclick", "cancelProcessing = true;");
}

function startExportProgress(file) {
    exportProgressMissing = 0;
    exportProgressCancelRequested = false;
    exportProgressLast = -1;
    exportProgressLastChange = Date.now();
    cancelProcessing = false;

    resetProgressModal();
    updateModalProgress(0);

    getExportProgress(file);
}

function getExportProgress(file) {
    $.ajax({
        url: 'functions/progress.php',
        type: "POST",
        dataType: "json",
        data: {
            file: file,
            export: 1
        },
        success: function (data) {
            data = data || {};

            if (cancelProcessing && !exportProgressCancelRequested) {
                exportProgressCancelRequested = true;
                $.post('functions/progress.php', { file: file, stop: true });
                $("#modal-progresso-title").text("Cancelando...");
            }

            if (!data.found) {
                exportProgressMissing++;
                if (exportProgressMissing >= 60) {
                    addErrorMessage("Não foi possível acompanhar a exportação. Verifique os arquivos disponíveis na pasta temporária.");
                    return;
                }
            } else {
                exportProgressMissing = 0;
            }

            const progressoAtual = data.progress || 0;

            if (progressoAtual !== exportProgressLast) {
                exportProgressLast = progressoAtual;
                exportProgressLastChange = Date.now();
            } else if (Date.now() - exportProgressLastChange > 15 * 60 * 1000) {
                // A gravação do arquivo não publica progresso; 15 min sem
                // alteração indica que o processo foi interrompido.
                addErrorMessage("A exportação parou de responder. Os arquivos concluídos continuam disponíveis na pasta temporária.");
                return;
            }

            renderExportParts(data.parts || []);

            if (data.done) {
                finishExportProgress(data.parts || [], data.cancelled === true);
                return;
            }

            updateModalProgress(progressoAtual);

            setTimeout(function () {
                getExportProgress(file);
            }, 1000);
        },
        error: function () {
            exportProgressMissing++;
            if (exportProgressMissing >= 60) {
                addErrorMessage("Não foi possível acompanhar a exportação.");
                return;
            }

            setTimeout(function () {
                getExportProgress(file);
            }, 2000);
        }
    });
}

function renderExportParts(parts) {
    const container = $("#modal-progresso-arquivos");

    if (!container.length || !parts.length) {
        return;
    }

    let html = '<div class="list-group">';

    parts.forEach(function (part) {
        const size = part.size ? (part.size / 1048576).toFixed(1) + " MB" : "";
        const label = $("<div>").text(part.label || part.file).html();

        html += '<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" target="_blank" rel="noopener" ' +
                'href="functions/serve_file.php?folder=temp&download=true&file=' + encodeURIComponent(part.file) + '">' +
                '<span>' + label + '</span>' +
                '<small class="text-muted">' + size + '</small></a>';
    });

    html += '</div>';

    container.html(html);
}

function finishExportProgress(parts, cancelled) {
    $("#modal-progresso-title").text(cancelled ? "Exportação cancelada" : "Exportação concluída");
    $("#barra-progresso").hide();
    $("#modal-progresso-cancel").text("Fechar");
    $("#modal-progresso-cancel").attr("onclick", "closeModalProgress();");

    if (!parts.length) {
        $("#modal-progresso-status").html("<div class='alert alert-warning'>Nenhum arquivo foi gerado.</div>");
        return;
    }

    $("#modal-progresso-status").html(cancelled
        ? "A exportação foi interrompida. Os arquivos já concluídos podem ser baixados:"
        : "Clique nos arquivos abaixo para baixar:");

    renderExportParts(parts);
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

