<?php

?>
<script>
    const SCOPES = '<?= config::get(["drive_scopes"]) ?>';
    const CLIENT_ID = '<?= config::get(["drive_client_id"]) ?>';
    const API_KEY = '<?= config::get(["drive_api_key"]) ?>';

    let tokenClient;
    let accessToken = '<?= $accessToken ?? '' ?>';
    let pickerInited = false;
    let gisInited = false;


    function openOAuthPopup(authUrl) {
        popupwindow(authUrl, 'name', 600, 400);
    }


    function changeGoogleDriveButton(status) {
        if (status !== 'error') {
            const divs = document.getElementsByClassName('google-drive');
            if (divs.length > 0) {
                for (let i = 0; i < divs.length; i++) {
                    let campo = divs[i].id.split("div_")[1];
                    window["atualizar_campo_" + campo]();
                }
            } else {
                const spanButton = document.getElementById('google-drive-button');
                const campoTipo = spanButton.getAttribute('data-campo-tipo');
                spanButton.innerHTML = '<button class="btn btn-outline-primary px-4" type="button" ' +
                    'onclick="createPicker' + campoTipo +  '()">Adicionar do Google Drive</button>';
            }
        }
    }

    function popupwindow(url, title, w, h) {
        const left = (screen.width / 2) - (w / 2);
        const top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

    function onApiLoad() {
        gapi.load('picker', onPickerApiLoad);
    }

    function onPickerApiLoad() {
        pickerInited = true;
    }

    function gisLoaded() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: CLIENT_ID,
            scope: SCOPES,
            callback: '',
        });
        gisInited = true;
    }
</script>