<?php
if (!isset($vs_tela) || !isset($vn_objeto_codigo) || !isset($vs_chave_primaria_objeto))
    exit();

if (!isset($vs_nome_campo)) {
    $vs_nome_campo = "representante_digital";
}

if (!isset($pa_parametros_campo["tipo"])) {
    $pa_parametros_campo["tipo"] = 1;
}
?>


<div class="tab-pane fade" id="nav-links" role="tabpanel" tabindex="0">
    <div id="added-links"></div>

    <div class="input-group mb-3">
        <input type="text" class="form-control" id="link_externo" placeholder="Cole aqui o link externo">
        <button class="btn btn-primary" type="button" onclick="addLink()">
            <svg class="icon">
                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-plus"></use>
            </svg>
        </button>
    </div>
    <div id="link_externo_error" class="alert alert-warning small d-none">
        Link inválido. <br>
        Utilize o link de compartilhamento gerado pelos sites suportados. <br>
        Certifique-se que o link tenha um dos seguintes formatos (com ou sem www): <br><br>
        <b>Google Drive:</b>https://{drive|docs}.google.com/{file|documents}/d/XXXXXXXXXXXXXXXXXX/{preview|edit|view} <br><br>
        <b>SoundCloud:</b> https://soundcloud.com/XXXXXXXXX ou https://on.soundcloud.com/XXXXXXXXX <br><br>
        <b>Spotify:</b> https://open.spotify.com/track/XXXXXXXXX <br><br>
        <b>Vimeo:</b> https://vimeo.com/XXXXXXXXX <br><br>
        <b>YouTube:</b> https://youtube.com/watch?v=XXXXXXXXXXX ou https://youtu.be/XXXXXXXXXXX
    </div>

    <p class="small">
        <b>Sites suportados:</b> Google Drive, SoundCloud, Spotify, Vimeo e YouTube. <br>
        Os arquivos devem ser públicos para serem visualizados no sistema.
    </p>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary px-4" onclick="saveLinks()">
            Salvar links
        </button>
        <button id='closeModal_<?php print $vs_nome_campo ?>' type="button"
                class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Fechar
        </button>
    </div>
</div>

<script>

    function addLink() {
        let link = document.getElementById("link_externo").value;

        if (!link.startsWith("https://")) {
            link = "https://" + link;
        }

        if (validateLink(link)) {
            createLink(link);
            document.getElementById("link_externo").value = "";
        } else {
            document.getElementById("link_externo_error").classList.remove("d-none");
            setTimeout(function () {
                document.getElementById("link_externo_error").classList.add("d-none");
            }, 5000);
        }
    }

    function validateLink(link) {
        const hosts = [
            'youtube.com',
            'youtu.be',
            'vimeo.com',
            'soundcloud.com',
            'on.soundcloud.com',
            'drive.google.com',
            'docs.google.com',
            'spotify.com',
        ]

        try {
            new URL(link);
        } catch (_) {
            return false;
        }

        for (let i = 0; i < hosts.length; i++) {
            if (link.includes(hosts[i])) {
                return true;
            }
        }
    }

    function createLink(link) {
        const linkContainer = document.createElement("div");
        linkContainer.innerHTML = `
            <div class="d-flex justify-content-between mb-3">
                <input readonly type="text" class="form-control cor-interna-edit no-border" value="${link}">
                <button type="button" class="btn btn-primary" onclick="removeLink(this)">
                    <svg class="icon">
                        <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-trash"></use>
                    </svg>
                </button>
            </div>
        `;
        document.getElementById("added-links").appendChild(linkContainer);
    }

    function removeLink(element) {
        element.parentElement.remove();
    }

    function saveLinks() {
        const addedLinks = document.getElementById("added-links").querySelectorAll("input");
        const links = [];
        addedLinks.forEach(link => {
            links.push(link.value);
        });

        const formLinks = new FormData();
        formLinks.append("links", JSON.stringify(links));
        formLinks.append("obj", '<?php print $vs_tela; ?>');
        formLinks.append('<?php print $vs_chave_primaria_objeto; ?>', <?php print $vn_objeto_codigo; ?>);
        formLinks.append('representante_digital_tipo_codigo', '<?php print $pa_parametros_campo["tipo"]; ?>');
        formLinks.append('representante_digital_campo_nome', '<?php print $vs_nome_campo; ?>');
        formLinks.append('numero_<?php print $vs_nome_campo ?>', $("#numero_<?php print $vs_nome_campo ?>").val());
        formLinks.append('upload_logged_<?php print $vs_nome_campo ?>', $("#upload_logged_<?php print $vs_nome_campo ?>").val());

        $.ajax({
            url: 'functions/upload.php',
            type: 'POST',
            data: formLinks,
            processData: false,
            contentType: false,
            success: function (response) {
                vn_numero_representantes_digitais = parseInt($("#numero_<?php print $vs_nome_campo ?>").val()) + 1;
                $("#numero_<?php print $vs_nome_campo ?>").val(vn_numero_representantes_digitais);
                if (response.trim() == "logged") {
                    $("#upload_logged_<?php print $vs_nome_campo ?>").val(1);
                }

                $("#closeModal_<?php print $vs_nome_campo ?>").click();
            }
        });
    }

</script>
