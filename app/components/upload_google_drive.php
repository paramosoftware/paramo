<?php

require_once dirname(__FILE__) . "/upload_google_drive_common.php";

$vn_campo_tipo = $pa_parametros_campo["tipo"] ?? 1;
$vs_nome_campo = $vs_nome_campo ?? "representante_digital_codigo";

$vb_upload_lote = false;
if (!isset($vs_chave_primaria_objeto))
{
    $vb_upload_lote = true;
}

$va_extensoes_permitidas = config::get(["extensoes_permitidas"]);

$vs_mime_types = "";
$vs_extensoes_permitidas = "";

foreach ($va_extensoes_permitidas as $vs_extensao => $vs_mime_type)
{
    $vs_mime_types .= $vs_mime_type . ",";
    $vs_extensoes_permitidas .= $vs_extensao . ", ";
}

$vs_mime_types = substr($vs_mime_types, 0, -1);
$vs_extensoes_permitidas = substr($vs_extensoes_permitidas, 0, -2);

if ($vn_campo_tipo == 2)
{
    $vs_mime_types = "application/pdf";
    $vs_extensoes_permitidas = "pdf";
}

$vs_tamanho_maximo_upload = min((int)ini_get('post_max_size'), (int)ini_get('upload_max_filesize'));

?>
<div id="modal-google-drive<?= $vn_campo_tipo ?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-6">
                    <h5>Arquivos selecionados</h5>
                    <p class="small m-0"><b>Tamanho máximo do arquivo:</b> <?= $vs_tamanho_maximo_upload; ?> MB</p>
                    <p class="small m-0"><b>Extensões permitidas:</b> <?= $vs_extensoes_permitidas ?></p>

                    <?php if ($vb_upload_lote): ?>
                    <div class="form-check form-switch mt-2">
                        <label class="small" for="criar-agrupamentos<?= $vn_campo_tipo ?>">Criar agrupamentos a partir da estrutura de pastas</label>
                        <input type="checkbox" id="criar-agrupamentos<?= $vn_campo_tipo ?>" class="form-check-input">
                    </div>
                    <div class="form-input mt-2">
                        <label class="small" for="profundidade-pastas<?= $vn_campo_tipo ?>">Profundidade na leitura da estrutura de pastas</label>
                        <input type="number" min="1" max="100" id="profundidade-pastas<?= $vn_campo_tipo ?>"  class="form-control-sm form-control" value="2" style="width: 15%;">
                    </div>
                    <?php endif; ?>

                </div>
                <div class="col-md-6 text-end">
                    <button type="button" id="button-google-drive-upload<?= $vn_campo_tipo ?>"
                            class="btn btn-primary align-right"
                            onclick="getGoogleDriveFilesIds<?= $vn_campo_tipo ?>()"
                            disabled style="display: none;">
                        Upload
                    </button>
                    <button type="button" class="btn btn-primary" onclick="closeGoogleDriveModal<?= $vn_campo_tipo ?>()">
                        Fechar
                    </button>
                </div>
            </div>
            <div class="m-3 row justify-content-between">
                <div class="col-md-6 m-0 p-0">
                    <button id="button-google-drive-remove-all<?= $vn_campo_tipo ?>" type="button"
                            class="btn btn-outline-primary text-start"
                            onclick="removeAllGoogleDriveFilesCards<?= $vn_campo_tipo ?>()" disabled style="display: none;">
                        Remover todos
                    </button>
                </div>

                <div class="col-md-6 text-end m-0 p-0">
                    <button type="button" class="btn btn-outline-primary" onclick="createPicker<?= $vn_campo_tipo ?>()">
                        Adicionar do Google Drive
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <div id="google-drive-files-ids<?= $vn_campo_tipo ?>" data-google-drive-files-ids=""></div>
                <div id="google-drive-grid-cards<?= $vn_campo_tipo ?>" class="row">
                    <h5 class="text-center">Nenhum arquivo selecionado</h5>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="modal-loading-google-drive<?= $vn_campo_tipo ?>" data-coreui-backdrop="static" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-6">
                    <h5 id="modal-loading-title-google-drive<?= $vn_campo_tipo ?>" >Fazendo upload dos arquivos</h5>
                </div>
                <div class="col-md-6 text-end">
                    <button id="button-cancel-google-drive-loading<?= $vn_campo_tipo ?>"
                            type="button"
                            class="btn btn-primary"
                            onclick="cancelGoogleDriveUpload<?= $vn_campo_tipo ?>()">
                        Cancelar
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="progress-bar-google-drive<?= $vn_campo_tipo ?>"
                     class="progress" role="progressbar"
                     aria-valuenow="0"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     style="height: 20px">
                    <div class="progress-bar progress-bar-striped progress-bar-animated text-center" style="width: 0">0%</div>
                </div>
                <div id="status-report-google-drive<?= $vn_campo_tipo ?>" class="mt-2"></div>
                <div id="error-report-google-drive<?= $vn_campo_tipo ?>" class="mt-2 small lh-base"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function createPicker<?= $vn_campo_tipo ?>() {

        $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('hide');

        const showPicker = () => {
            const docsView = new google.picker.DocsView(google.picker.ViewId.DOCS)
                .setParent('root')
                .setIncludeFolders(true)
                .setSelectFolderEnabled(true)
                .setMimeTypes('<?= $vs_mime_types ?>');

            const sharedDriveView = new google.picker.DocsView(google.picker.ViewId.DOCS)
                .setIncludeFolders(true)
                .setSelectFolderEnabled(true)
                .setEnableDrives(true)
                .setMimeTypes('<?= $vs_mime_types ?>');

            const freeView = new google.picker.DocsView(google.picker.ViewId.DOCS)
                .setIncludeFolders(true)
                .setSelectFolderEnabled(true)
                .setMimeTypes('<?= $vs_mime_types ?>');


            const picker = new google.picker.PickerBuilder()
                .addView(docsView)
                .addView(sharedDriveView)
                .addView(freeView)
                .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
                .setOAuthToken(accessToken)
                .setDeveloperKey(API_KEY)
                .setCallback(pickerCallback<?= $vn_campo_tipo ?>)
                .setMaxItems(10000)
                .setRelayUrl(window.location.protocol + '//' + window.location.host)
                .build();
            picker.setVisible(true);
        }

        tokenClient.callback = async (response) => {
            if (response.error !== undefined) {
                throw (response);
            }
            accessToken = response.access_token;
            showPicker();
        };

        if (accessToken === null) {
            tokenClient.requestAccessToken({prompt: 'consent'});
        } else {
            tokenClient.requestAccessToken({prompt: ''});
        }
    }

    function pickerCallback<?= $vn_campo_tipo ?>(data) {
        if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {

            const files = data[google.picker.Response.DOCUMENTS];

            let cards = '';

            for (let i = 0; i < files.length; i++) {

                const file = files[i];
                const id = file.id;
                const url = file.url;
                let icon = file.iconUrl.replace('/16/', '/32/');
                const name = file.name;
                const type = file.type;

                if (type === 'photo') {
//                    icon = 'https://drive.google.com/uc?id=' + id;
                }

                if (document.getElementById('card-' + id) !== null) {
                    continue;
                }

                cards += `
                    <div class="col-md-6 col-12">
                        <div class="card mb-3" id="card-${id}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="${icon}" alt="${name}" style="width: 32px; height: 32px; object-fit: cover;"/>
                                    </div>
                                    <div class="col-8">
                                        <a class="d-inline-block text-truncate" style="width: 200px" href="${url}" target="_blank">${name}</a>
                                    </div>
                                    <div class="col-2">
                                       <button class="btn btn-primary-outline float-end btn-trash" type="button" onclick="removeGoogleDriveFileId('${id}')">
                                          <svg class="icon">
                                             <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-trash"></use>
                                          </svg>
                                       </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            const googleDriveGridCard = document.getElementById('google-drive-grid-cards<?= $vn_campo_tipo ?>');
            const googleDriveFilesIdsElement = document.getElementById('google-drive-files-ids<?= $vn_campo_tipo ?>');
            const googleDriveFilesIds = googleDriveFilesIdsElement.getAttribute('data-google-drive-files-ids');

            if (googleDriveFilesIds === '') {
                googleDriveGridCard.innerHTML = '';
            }
            googleDriveGridCard.innerHTML += cards;

            if (googleDriveFilesIds === '') {
                googleDriveFilesIdsElement.setAttribute('data-google-drive-files-ids', files.map(file => file.id).join('|'));
            } else {
                const googleDriveFilesIdsList = googleDriveFilesIds.split('|');
                for (let i = 0; i < files.length; i++) {
                    const id = files[i].id
                    if (!googleDriveFilesIdsList.includes(id)) {
                        googleDriveFilesIdsElement.setAttribute('data-google-drive-files-ids', googleDriveFilesIds + '|' + id);
                    }
                }
            }

            if (files.length > 0) {
                $('#button-google-drive-upload<?= $vn_campo_tipo ?>').attr('disabled', false).show();
                $('#button-google-drive-remove-all<?= $vn_campo_tipo ?>').attr('disabled', false).show();
            }

            $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('show');
        } else if (data[google.picker.Response.ACTION] == google.picker.Action.CANCEL) {
            $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('show');
        }
    }

    function openGoogleDriveModal<?= $vn_campo_tipo ?>() {

        const googleDriveFilesIdsElement = document.getElementById('google-drive-files-ids<?= $vn_campo_tipo ?>');
        const googleDriveFilesIds = googleDriveFilesIdsElement.getAttribute('data-google-drive-files-ids');

        if (googleDriveFilesIds === '') {
            createPicker<?= $vn_campo_tipo ?>()
        } else {
            $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('show');
        }

    }

    function closeGoogleDriveModal<?= $vn_campo_tipo ?>() {
        $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('hide');
    }

    function removeAllGoogleDriveFilesCards<?= $vn_campo_tipo ?>() {
        const googleDriveFilesIdsElement = document.getElementById('google-drive-files-ids<?= $vn_campo_tipo ?>');
        googleDriveFilesIdsElement.setAttribute('data-google-drive-files-ids', '');

        const googleDriveGridCard = document.getElementById('google-drive-grid-cards<?= $vn_campo_tipo ?>');
        googleDriveGridCard.innerHTML = `
            <h4 class="text-center">Nenhum arquivo selecionado</h4>
        `;

        $('#button-google-drive-remove-all<?= $vn_campo_tipo ?>').prop('disabled', true).hide();
        $('#button-google-drive-upload<?= $vn_campo_tipo ?>').prop('disabled', true).hide();
    }

    function removeGoogleDriveFileId(id) {

        const googleDriveFilesIdsElement = document.getElementById('google-drive-files-ids<?= $vn_campo_tipo ?>');
        const googleDriveFilesIds = googleDriveFilesIdsElement.getAttribute('data-google-drive-files-ids');

        const newGoogleDriveFilesIds = googleDriveFilesIds.split('|').filter(fileId => fileId !== id).join('|');

        googleDriveFilesIdsElement.setAttribute('data-google-drive-files-ids', newGoogleDriveFilesIds);

        const card = document.getElementById(`card-${id}`);
        card.remove();

        if (newGoogleDriveFilesIds === '') {
            const googleDriveGridCard = document.getElementById('google-drive-grid-cards<?= $vn_campo_tipo ?>');
            googleDriveGridCard.innerHTML = `
                <h4 class="text-center">Nenhum arquivo selecionado</h4>
            `;

            $('#button-google-drive-remove-all<?= $vn_campo_tipo ?>').prop('disabled', true).hide();
            $('#button-google-drive-upload<?= $vn_campo_tipo ?>').prop('disabled', true).hide();
        }

    }


    function getGoogleDriveFilesIds<?= $vn_campo_tipo ?>() {

        showLoadingSpinner();
        let promises = [];

        const googleDriveFilesIdsElementSelection = document.getElementById('google-drive-files-ids<?= $vn_campo_tipo ?>');
        const selectedGoogleDriveFilesIds = googleDriveFilesIdsElementSelection.getAttribute('data-google-drive-files-ids');

        const formData = new FormData();

        formData.append('get_list_all_google_drive_files_ids', selectedGoogleDriveFilesIds);

        <?php if ($vb_upload_lote) : ?>
            const createAgrupamentos = document.getElementById('criar-agrupamentos<?= $vn_campo_tipo ?>').checked;
            const profundidadePastas = document.getElementById('profundidade-pastas<?= $vn_campo_tipo ?>').value;

            formData.append('create_agrupamentos', createAgrupamentos);
            formData.append('profundidade_pastas', profundidadePastas);

            let acervoCodigo = document.getElementById('item_acervo_acervo_codigo').value;
            if (acervoCodigo === '' || acervoCodigo === null || acervoCodigo === undefined) {
                acervoCodigo = '';
            }
            formData.append('item_acervo_acervo_codigo', acervoCodigo);
        <?php endif; ?>

        let ids;

        let request = $.ajax({
            url: 'functions/upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                ids = data;
            },
            error: function (data) {
                updateGoogleDriveModalLoading<?= $vn_campo_tipo ?>('Aconteceu algum problema');
                changeToLoadingModal<?= $vn_campo_tipo ?>();
                hideLoadingSpinner();
                appendMessageToErrorReport<?= $vn_campo_tipo ?>('Não foi possível acessar os arquivos no Google Drive. Recarregue a página e tente novamente.');
            }
        });

        promises.push(request);

        $.when.apply(null, promises).done(function () {
            hideLoadingSpinner();
            changeToLoadingModal<?= $vn_campo_tipo ?>();
            downloadGoogleDriveFiles<?= $vn_campo_tipo ?>(ids);
        });

    }

    let uploadRequests<?= $vn_campo_tipo ?> = [];
    let cancelUploadRequests<?= $vn_campo_tipo ?> = false;

    function downloadGoogleDriveFiles<?= $vn_campo_tipo ?>(googleDriveFilesIds) {
        googleDriveFilesIds = googleDriveFilesIds.split("|");
        showLoadingSpinner();
        let batchSize = googleDriveFilesIds.length < 100 ? 20 : 5;
        let timeout = googleDriveFilesIds.length < 100 ? 30000 : 10000;
        let promises = [];
        let i = 0;
        let completed = 0;
        let startTime = performance.now();
        let errors = [];

        function processBatch() {
            let batchEnd = Math.min(i + batchSize, googleDriveFilesIds.length);
            for (; i < batchEnd; i++) {
                const formData = new FormData();

                let fileId = googleDriveFilesIds[i];
                formData.append('representante_digital_tipo_codigo', '<?= $vn_campo_tipo ?>');
                formData.append('representante_digital_campo_nome', '<?= $vs_nome_campo ?>');
                formData.append('google_drive_files_ids', fileId);

                <?php if ($vb_upload_lote) : ?>
                const createAgrupamentos = document.getElementById('criar-agrupamentos<?= $vn_campo_tipo ?>').checked;
                formData.append('create_agrupamentos', createAgrupamentos);
                <?php endif; ?>

                <?php if ($vb_upload_lote) : ?>
                $("#form_upload").serializeArray().forEach(function (field) {
                    formData.append(field.name, field.value);
                });
                <?php else : ?>
                formData.append('obj', '<?php print $vs_tela; ?>');
                formData.append('<?= $vs_chave_primaria_objeto; ?>', <?= $vn_objeto_codigo; ?>);
                formData.append('numero_<?php print $vs_nome_campo ?>', $("#numero_<?= $vs_nome_campo ?>").val());
                formData.append('upload_logged_<?php print $vs_nome_campo ?>', $("#upload_logged_<?= $vs_nome_campo ?>").val());
                <?php endif; ?>

                let request = $.ajax({
                    url: 'functions/upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 0,
                    success: function (data) {
                        completed++;

                        let endTime = performance.now();
                        timeout = Math.ceil((endTime - startTime) / completed) * batchSize;

                        if (batchSize < 20) {
                            batchSize++;
                        }

                        <?php if ($vb_upload_lote) : ?>
                        adicionarArquivoImportado(data);
                        if (!data.includes('|') && data.trim() !== '') {
                            errors.push(fileId);
                        }
                        <?php endif; ?>
                        updateProgressBar<?= $vn_campo_tipo ?>(completed / googleDriveFilesIds.length * 100);
                        updateStatusReport<?= $vn_campo_tipo ?>(startTime, completed, googleDriveFilesIds.length);
                    },
                    error: function (data) {
                        if (data.responseText) {
                            let fileName = JSON.parse(data.responseText).file;
                            let error = JSON.parse(data.responseText).error;
                            let errorMessage = '<b>' + fileName + '</b>' + ': ' + error;
                            appendMessageToErrorReport<?= $vn_campo_tipo ?>(errorMessage);
                        } else {
                            errors.push(fileId);
                        }
                        completed++;
                        updateProgressBar<?= $vn_campo_tipo ?>(completed / googleDriveFilesIds.length * 100);
                        updateStatusReport<?= $vn_campo_tipo ?>(startTime, completed, googleDriveFilesIds.length);
                    }
                });
                uploadRequests<?= $vn_campo_tipo ?>.push(request);
                promises.push(request);
            }

            if (i < googleDriveFilesIds.length && !cancelUploadRequests<?= $vn_campo_tipo ?>) {
                hideLoadingSpinner();
                setTimeout(processBatch, timeout);
            } else if (!cancelUploadRequests<?= $vn_campo_tipo ?>) {
                hideLoadingSpinner();
                Promise.allSettled(promises).then(async function (results) {
                    showLoadingSpinner();
                    await processErrors(errors);
                    hideLoadingSpinner();
                    updateGoogleDriveModalLoading<?= $vn_campo_tipo ?>();
                });
            }
        }

        processBatch();
    }

    async function processErrors(ids) {
        if (ids.length === 0) {
            return;
        }

        const formData = new FormData();
        formData.append('get_files_names_from_ids', ids.join('|'));

        await $.ajax({
            url: 'functions/upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                let errorMessage = data.map(function (file) {
                    return '<b>' + file + '</b>: ' + ' Erro indefinido (possivelmente instabilidade na conexão)';
                }).join('<br>');

                appendMessageToErrorReport<?=$vn_campo_tipo ?>(errorMessage);
            }
        });

        return;
    }

    function updateProgressBar<?= $vn_campo_tipo ?>(progress) {
        progress = parseInt(progress).toString();
        const progressBar = $('#progress-bar-google-drive<?= $vn_campo_tipo ?>');

        if (progress > parseInt(progressBar.attr('aria-valuenow')) || progress == '0') {
            progressBar.attr('aria-valuenow', progress);
            progressBar.children().first().css('width', `${progress}%`);
            progressBar.children().first().html(`${progress}%`);
        }
    }
    
    function updateStatusReport<?= $vn_campo_tipo ?>(startTime, completed, total) {
        const statusReport = document.getElementById('status-report-google-drive<?= $vn_campo_tipo ?>');

        if (statusReport) {
            let processados = `Processados ${completed} de ${total} arquivos.`;

            const totalElapsedTimeInSeconds = (performance.now() - startTime) / 1000;
            const hours = Math.floor(totalElapsedTimeInSeconds / 3600);
            const minutes = Math.floor(totalElapsedTimeInSeconds / 60) % 60;
            const seconds = Math.floor(totalElapsedTimeInSeconds % 60);


            let decorrido = `Tempo decorrido: ${hours > 0 ? hours + 'h' : ''} ${minutes > 0 ? minutes + 'm' : ''} ${seconds > 0 ? seconds + 's' : ''}`;

            let html = `<div class="d-flex justify-content-between">
                <div>${processados}</div>
                <div>${decorrido}</div>
            </div>`;

            const averagePerFile = totalElapsedTimeInSeconds / completed;
            const remaining = (total - completed) * averagePerFile;

            if (completed > 0 && completed < total && remaining > 120) {
                const hoursRemaining = Math.floor(remaining / 3600);
                const minutesRemaining = Math.floor(remaining / 60) % 60;
                const secondsRemaining = Math.floor(remaining % 60);

                html += `Tempo restante estimado:
                    ${hoursRemaining > 0 ? hoursRemaining + 'h' : ''}
                    ${minutesRemaining > 0 ? minutesRemaining + 'm' : ''}
                    ${secondsRemaining > 0 && hoursRemaining === 0 ? secondsRemaining + 's' : ''}
                `;
            }

            statusReport.innerHTML = html;
        }
    }

    function appendMessageToErrorReport<?= $vn_campo_tipo ?>(message, clear = false) {
        const statusReport = document.getElementById('error-report-google-drive<?= $vn_campo_tipo ?>');

        if (statusReport) {
            if (clear) {
                statusReport.innerHTML = '';
            }

            statusReport.innerHTML += message + '<br>';
        }
    }

    function changeToLoadingModal<?= $vn_campo_tipo ?>() {
        removeAllGoogleDriveFilesCards<?= $vn_campo_tipo ?>();
        $('#modal-google-drive<?= $vn_campo_tipo ?>').modal('hide');
        $('#modal-loading-google-drive<?= $vn_campo_tipo ?>').modal('show');
    }

    function updateGoogleDriveModalLoading<?= $vn_campo_tipo ?>(title = 'Upload finalizado') {
        $('#modal-loading-title-google-drive<?= $vn_campo_tipo ?>').html(title);
        $('#button-cancel-google-drive-loading<?= $vn_campo_tipo ?>').html('Fechar')
        $('#button-cancel-google-drive-loading<?= $vn_campo_tipo ?>').attr('onclick', 'closeGoogleDriveModalLoading<?= $vn_campo_tipo ?>()')
    }

    function closeGoogleDriveModalLoading<?= $vn_campo_tipo ?>() {
        <?php if (!$vb_upload_lote) : ?>
            atualizar_campo_<?= $vs_nome_campo; ?>();
        <?php endif; ?>
        updateProgressBar<?= $vn_campo_tipo ?>(0);
        appendMessageToErrorReport<?= $vn_campo_tipo ?>('', true);

        $('#modal-loading-google-drive<?= $vn_campo_tipo ?>').modal('hide');
    }

    function cancelGoogleDriveUpload<?= $vn_campo_tipo ?>() {
        showLoadingSpinner();
        for (let i = 0; i < uploadRequests<?= $vn_campo_tipo ?>.length; i++) {
            uploadRequests<?= $vn_campo_tipo ?>[i].abort();
        }
        cancelUploadRequests<?= $vn_campo_tipo ?> = true;
        <?php if (!$vb_upload_lote) : ?>
            atualizar_campo_<?= $vs_nome_campo; ?>();
        <?php endif; ?>
        updateProgressBar<?= $vn_campo_tipo ?>(0);
        hideLoadingSpinner();
        $('#modal-loading-google-drive<?= $vn_campo_tipo ?>').modal('hide');
    }


</script>

<script async defer src="https://apis.google.com/js/api.js" onload="onApiLoad()"></script>
<script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
