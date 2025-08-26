<?php
    if (!isset($vs_tela))
        exit();

    if (!isset($pa_parametros_campo["nome"]))
        exit();
    else
        $vs_nome_campo = $pa_parametros_campo["nome"];

    if (!isset($pa_parametros_campo["label"]))
        $vs_label_campo = 'Label não atribuído';
    else
        $vs_label_campo = $pa_parametros_campo["label"];
    
    if (!isset($vn_numero_campo))
        $vn_numero_campo = 1;

    if (!isset($vb_pode_exibir))
        $vb_pode_exibir = true;

    if (!isset($vb_pode_remover))
        $vb_pode_remover = true;

    if (!isset($vs_label_objeto))
        $vs_label_objeto = "";

    $vs_preview_only = false;
    if (isset($pa_parametros_campo["preview_only"]))
        $vs_preview_only = $pa_parametros_campo["preview_only"];
    
    $vn_valor_campo_codigo = "";

    $vb_integracao_google_drive = config::get(["f_integracao_google_drive"]) ?? false;

    if ($vb_integracao_google_drive)
    {
        $authUrl = google_drive::get_auth_url($_SESSION["usuario_logado_codigo"], 'drive');
    }
?>

<?php 
if (!isset($pa_parametros_campo["atualizacao"]))
{
?>

<div class="accordion mb-3 google-drive" id="div_<?php print $vs_nome_campo ?>"
<?php
    if (!$vb_pode_exibir)
        print ' style="display:none"';
?>
>

<?php
}
?>
    <div class="accordion-item">
        <?php 
            $vs_css_accordion_show = config::get(["f_abrir_campo_representantes_digitais"]) ? "show" : "";
            $vs_css_button_show = "collapsed";
            $vs_aria_expanded = "false";
            if (isset($pa_parametros_campo["atualizacao"]))
            {
                $vs_css_button_show = "";
                $vs_css_accordion_show = "show";
                $vs_aria_expanded = "true";
            }
        ?>

        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button <?php print $vs_css_button_show ?>" type="button" data-coreui-toggle="collapse" data-coreui-target="#collapseOne_<?php print $vs_nome_campo ?>" aria-expanded="<?php print $vs_aria_expanded ?>" aria-controls="collapseOne_<?php print $vs_nome_campo ?>"><?php print $vs_label_campo; ?></button>
        </h2>
        
        <div class="accordion-collapse collapse <?php print $vs_css_accordion_show ?>" id="collapseOne_<?php print $vs_nome_campo ?>" aria-labelledby="headingOne" data-coreui-parent="#div_<?php print $vs_nome_campo ?>">
            <div class="accordion-body cor-interna-edit">
                <?php if (!$vs_preview_only)
                {
                ?>
                    <div class="mb-3 d-flex gap-1 align-items-center">
                        <?php if ($vb_pode_editar) { ?>
                            <button class="btn btn-outline-primary px-4" type="button" id="btn_adicionar_campo_<?php print $vs_nome_campo; ?>">Adicionar</button>
                        <?php } ?>

                        <?php if ($vb_integracao_google_drive) : ?>
                            <span id="google-drive-button" data-campo-tipo="<?= $pa_parametros_campo["tipo"] ?>">
                            <?php if (!empty($authUrl)) : ?>
                                <button class="btn btn-outline-primary px-4" type="button" onclick="openOAuthPopup('<?= $authUrl ?>')">Conectar ao Google Drive</button>
                            <?php else : ?>
                                <button class="btn btn-outline-primary px-4" type="button" onclick="createPicker<?= $pa_parametros_campo["tipo"] ?>()">Adicionar do Google Drive</button>
                             <?php endif; ?>
                            </span>
                        <?php endif; ?>


                        <?php if (isset($va_valor_campo) && (count($va_valor_campo) > 0) && $vb_pode_editar) : ?>
                            <button class="btn btn-outline-primary px-4" type="button" id="btn_baixar_todos_<?php print $vs_nome_campo; ?>">Baixar todos</button>
                            
                            <?php 
                            $vb_todos_publicados_online = true;
                            foreach($va_valor_campo as $va_valores_linha)
                            {
                                if (isset($va_valores_linha['representante_digital_publicado_online']))
                                {
                                    if (!$va_valores_linha['representante_digital_publicado_online'])
                                    {
                                        $vb_todos_publicados_online = false;
                                        break;
                                    }
                                }
                            }
                            ?>

                                <input type="checkbox" class="form-check-input" id="chk_publicar_todos_online"<?= ($vb_todos_publicados_online) ? " checked" : "" ?>
                                > Publicar todos online
                                <button class="btn btn-outline-primary px-4 ml-auto" type="button" id="btn_remover_todos_<?php print $vs_nome_campo; ?>">Remover todos</button>
                        <?php endif; ?>

                        <br>
                    </div>
                <?php
                }
                ?>
                
                <div class="container-fluid cards-representantes bg-trasparent my-4" style="position: relative;">
                    <div class="row  row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3" id="div_campos_<?php print $vs_nome_campo; ?>">
                    <?php
                        if (isset($va_valor_campo))
                        {
                            if (count($va_valor_campo))
                            {
                                $vn_valor_campo_codigo = array();
                                $contador = 1;

                                foreach($va_valor_campo as $va_valores_linha)
                                {
                                    $vs_rd_path = $va_valores_linha["representante_digital_path"] ?? "";
                                    $vn_linha_codigo = $va_valores_linha["representante_digital_codigo"] ?? "";
                                    $vn_rp_tipo_codigo = $va_valores_linha["representante_digital_tipo_codigo"] ?? "";
                                    $vs_rp_legenda = $va_valores_linha["representante_digital_legenda"] ?? "";
                                    $vs_rd_formato = $va_valores_linha["representante_digital_formato"] ?? "";

                                    $vs_download_link = "functions/serve_file.php?file=" . $vs_rd_path . "&size=original&download=1&name=" . $vs_label_objeto . "-" . $vn_numero_campo;

                                    // if ($pa_parametros_campo["tipo"] == 2)
                                    //     $vs_download_link .= "&folder=documents";

                                ?> 
                                    <div class="col espacamento-documentos rp" style="max-width:190px;" id="<?php print $vs_nome_campo . "_" . $vn_linha_codigo; ?>">
                                            <div class="card h-100 shadow-sm no-border no-shadow">

                                            <?php
                                            if ($vs_rd_formato == "link")
                                            {
                                                print utils::get_embedded_media($vs_rd_path, 215);
                                            }
                                            elseif ($vs_rd_formato == "jpg")
                                            {
                                                print utils::get_img_html_element($vs_rd_path, "thumb", "card-img-top image-viewer", "img_" . ($vn_linha_codigo-1), $vs_rp_legenda);
                                            }
                                            elseif ($vs_rd_formato == "pdf")
                                            {
                                                $vs_download_path = utils::get_file_url($vs_rd_path, "original");

                                                print '<span href="' . $vs_download_path . '" target="_blank">';
                                                print utils::get_img_html_element($vs_rd_path, "thumb", "card-img-top iframe-viewer", null, $vs_rp_legenda);
                                                print '</span>';

                                            }
                                            elseif ($vs_rd_formato == "mp3" || $vs_rd_formato == "mp4")
                                            {
                                                print utils::get_media_html_element($vs_rd_path);
                                            }

                                            ?>

                                            <?php if ($vb_pode_remover && !$vs_preview_only)
                                            {
                                            ?>
                                                <div class="card-body no-pad-lr bg-light">
                                                    <?php
                                                        $va_parametros_campo = [
                                                            "html_combo_input",
                                                            "nome" => "representante_digital_tipo_codigo_" . $vn_linha_codigo,
                                                            "label" => "",
                                                            "objeto" => "tipo_representante_digital",
                                                            "sem_valor" => true,
                                                            "nao_montar_se_vazio" => true
                                                        ];

                                                        $vo_combo_tipos = new html_combo_input("tipo_representante_digital", "representante_digital_tipo_codigo_" . $vn_linha_codigo);

                                                        $va_valores["representante_digital_tipo_codigo_" . $vn_linha_codigo] = $vn_rp_tipo_codigo ;

                                                        $vo_combo_tipos->build($va_valores, $va_parametros_campo);
                                                    ?>

                                                    <div style="margin-bottom:10px">
                                                        <label for="representante_digital_legenda_<?php print $vn_linha_codigo; ?>">Legenda</label>
                                                        <textarea type="text" id="representante_digital_legenda_<?php print $vn_linha_codigo; ?>" name="representante_digital_legenda_<?php print $vn_linha_codigo; ?>"><?php print $vs_rp_legenda; ?></textarea>
                                                    </div>

                                                    <div style="margin-bottom:10px">
                                                    <input type="checkbox" class="chk-publicar-online form-check-input" id="representante_digital_publicado_online_<?php print $vn_linha_codigo; ?>" name="representante_digital_publicado_online_<?php print $vn_linha_codigo; ?>"
                                                    <?php
                                                        if ($va_valores_linha['representante_digital_publicado_online'])
                                                            print " checked";
                                                    ?>
                                                    > Publicar online
                                                    </div>

                                                    <div class="clearfix mb-3"> 
                                                        <span class="float-start card-text footer-card"><?php print $vn_numero_campo; ?></span>

                                                        <div class="text-end">
                                                            <button class="btn btn-primary float-end btn-trash btn_remover" type="button" id="btn_rem_rd_<?php  print $vn_linha_codigo; ?>" title="Remover">
                                                                <svg class="icon">
                                                                    <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-trash"></use>
                                                                </svg>
                                                            </button>

                                                            <a class="btn btn-primary btn-trash mx-1 btn_download" type="button" id="btn_download_rd_<?php  print $vn_linha_codigo; ?>" title="Baixar"
                                                            href="<?php print $vs_download_link; ?>" target="_blank"
                                                            >
                                                                <svg class="icon">
                                                                    <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-cloud-download"></use>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>                                  
                                                </div>
                                            <?php
                                            }
                                            ?>
                                       </div>
                                    </div>

                                <?php
                                    $vn_valor_campo_codigo[] = $vn_linha_codigo;
                                    $vn_numero_campo++;
                                }

                                $vn_valor_campo_codigo = join("|", $vn_valor_campo_codigo);
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="<?php print $vs_nome_campo ?>" name="<?php print $vs_nome_campo ?>" value="<?php print $vn_valor_campo_codigo; ?>">
    <input type="hidden" id="numero_<?php print $vs_nome_campo ?>" name="numero_<?php print $vs_nome_campo ?>" value="<?php print ($vn_numero_campo-1); ?>">
    <input type="hidden" id="rd_codigos_remover_<?php print $vs_nome_campo ?>" name="rd_codigos_remover_<?php print $vs_nome_campo ?>" value="">
    <input type="hidden" id="upload_logged_<?php print $vs_nome_campo ?>" name="upload_logged_<?php print $vs_nome_campo ?>" value="0">

    <?php


    $va_extensoes_permitidas = config::get(["extensoes_permitidas"]);
    $mimes = array();
    $vb_campo_representante_digital = $vs_nome_campo == "representante_digital_codigo";

    if (!$vb_campo_representante_digital) {
        foreach ($va_extensoes_permitidas as $vs_extensao_permitida => $vs_mime_type)
        {
            if ($vs_extensao_permitida != "pdf")
            {
                unset($va_extensoes_permitidas[$vs_extensao_permitida]);
            }
        }
    }

    $vs_tamanho_maximo_upload = min((int)ini_get('post_max_size'), (int)ini_get('upload_max_filesize'));

    ?>

    <div id="myModal_<?php print $vs_nome_campo ?>" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                   <div class="nav nav-tabs btn-group" id="nav-tab" role="tablist">
                        <button class="btn btn-outline-primary active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-arquivos" type="button" role="tab">
                            Arquivos
                        </button>
                       <?php if ($vb_campo_representante_digital) : ?>
                            <button class="btn btn-outline-primary" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-links" type="button" role="tab">
                                Link externos
                            </button>
                       <?php endif; ?>
                   </div>
                </div>
                <div class="modal-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-arquivos" role="tabpanel" tabindex="0">
                            <input type="file"
                                   class="filepond"
                                   name="filepond"
                                   id="filepond_<?php print $vs_nome_campo ?>"
                                   multiple
                                   data-allow-reorder="true"
                                   data-max-file-size="<?php print $vs_tamanho_maximo_upload; ?>MB"
                                   data-max-files="20"
                                   accept="<?php
                                   foreach($va_extensoes_permitidas as $vs_extensao_permitida => $vs_mime_type)
                                   {
                                       $mimes[] = "'".$vs_mime_type."'";
                                   }
                                   print implode(",", $mimes);

                                   ?>
                        ">
                            <div class="modal-footer">
                                <button id='btn_upload_<?php print $vs_nome_campo ?>' type="button" class="btn btn-outline-primary px-4">Upload</button>
                                <button id='closeModal_<?php print $vs_nome_campo ?>' type="button" class="btn btn-outline-primary px-4" data-bs-dismiss="modal">Voltar</button>
                            </div>
                        </div>
                        <?php if ($vb_campo_representante_digital) require 'campo_link_externo.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(function()
    {
        $("#div_campos_<?php print $vs_nome_campo; ?>").sortable(
            {
                update: function(event, ui)
                {
                    var va_lista_codigos_atualizada = "";

                    $('.espacamento-documentos').each(function(index)
                    {
                        if (va_lista_codigos_atualizada == "")
                            va_lista_codigos_atualizada = $(this).attr("id").replace("<?php print $vs_nome_campo ?>_", "");
                        else
                            va_lista_codigos_atualizada = va_lista_codigos_atualizada + "|" + $(this).attr("id").replace("<?php print $vs_nome_campo ?>_", "");
                    });

                    $('#<?php print $vs_nome_campo ?>').val(va_lista_codigos_atualizada);
                }
            });
    });
</script>

<?php
if (!isset($pa_parametros_campo["atualizacao"]))
{
?>

</div>

<link rel="stylesheet" href="assets/libraries/viewerjs/viewer.css" />

<script src="assets/libraries/viewerjs/viewer.js"></script>

<script src="assets/libraries/bootstrap/js/bootstrap.min.js"></script>

<link href="assets/libraries/filepond/filepond.css" rel="stylesheet">

<script src="assets/libraries/filepond/filepond.js"></script>

<script>


<?php
if ($pa_parametros_campo["tipo"] == 1 || $pa_parametros_campo["tipo"] == 2) {
?>
$(document).on('click', ".image-viewer, .iframe-viewer", function () {

    if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
        return;
    }

    this.timeout = setTimeout(() => {
        this.timeout = null;
    }, 3000);

    if ((document.querySelector('#sidebar')) != null)
        coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle();

    $(".container-lg").addClass("container-lg-com-imagem");
    $(".container-lg").removeClass("container-lg");

    if ($(this).hasClass('image-viewer')) {
        vs_url_campo_atualizado = 'functions/campo_preview_representantes_digitais.php?obj=<?php print $vs_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>&img=' + $(this).attr('id').replace("img_", "");

        $.get(vs_url_campo_atualizado, function (data, status) {
            $("#div_<?php print $vs_nome_campo ?>").hide();

            let div = document.createElement('div');
            let close_btn = document.createElement('button');
            close_btn.type = 'button';
            close_btn.classList.add('btn_fechar_imagem');
            close_btn.classList.add('btn');
            close_btn.classList.add('btn-outline-primary');
            close_btn.classList.add('close-media-viewer');
            close_btn.textContent = "X";

            div.appendChild(close_btn);

            $("#div-image-container").html(div);
            $("#div-image-container").append(data);

            $("#div-image-container").show();
            $(".btn_fechar_imagem").show();
        });
    } else if ($(this).hasClass('iframe-viewer')) {
        let path = $(this).parent().attr('href');

        let iframe = document.createElement('iframe');
        iframe.src = path;
        iframe.width = '100%';
        iframe.height = $(window).height() * 0.75;

        let div = document.createElement('div');
        let close_btn = document.createElement('button');
        close_btn.type = 'button';
        close_btn.classList.add('btn_fechar_imagem');
        close_btn.classList.add('btn');
        close_btn.classList.add('btn-outline-primary');
        close_btn.classList.add('close-media-viewer');
        close_btn.textContent = "X";

        div.appendChild(close_btn);
        div.appendChild(iframe);

        $("#div_<?php print $vs_nome_campo ?>").hide();
        $("#div-image-container").html(div);

        $(window).scroll(function () {
            $("#div-image-container").css({ "margin-top": ($(window).scrollTop()) + "px" });
        });

        $("#div-image-container").show();
        $(".btn_fechar_imagem").show();
    }
});

$(document).on('click', ".btn_fechar_imagem", function () {
    $(".container-lg-com-imagem").addClass("container-lg");
    $(".container-lg-com-imagem").removeClass("container-lg-com-imagem");

    $('.btn_fechar_imagem').hide();

    $("#div-image-container").hide();
    $("#div_<?php print $vs_nome_campo ?>").show();
    coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle();
});
<?php
}
?>

var pond;
$(document).on('click', "#btn_adicionar_campo_<?php print $vs_nome_campo; ?>", function()
{
    FilePond.setOptions({
        instantUpload: false,
        server: 
        {
            process:
            {
                url: 'functions/upload.php',
                ondata: (formData) => {
                    formData.append('obj', '<?php print $vs_tela; ?>');
                    formData.append('<?php print $vs_chave_primaria_objeto; ?>', <?php print $vn_objeto_codigo; ?>);
                    formData.append('representante_digital_tipo_codigo', '<?php print $pa_parametros_campo["tipo"]; ?>');
                    formData.append('representante_digital_campo_nome', '<?php print $vs_nome_campo; ?>');
                    formData.append('numero_<?php print $vs_nome_campo ?>', $("#numero_<?php print $vs_nome_campo ?>").val());
                    formData.append('upload_logged_<?php print $vs_nome_campo ?>', $("#upload_logged_<?php print $vs_nome_campo ?>").val());
                    return formData;
                },
                onload: (response) =>
                {
                    console.log(response);

                    vn_numero_representantes_digitais = parseInt($("#numero_<?php print $vs_nome_campo ?>").val()) + 1;
                    $("#numero_<?php print $vs_nome_campo ?>").val(vn_numero_representantes_digitais);

                    if (response.trim() == "logged")
                        $("#upload_logged_<?php print $vs_nome_campo ?>").val(1);
                }
            }
        },
        labelIdle: "Clique aqui ou arraste neste espaço os arquivos." +
            "<br><span style='font-size:0.8em'><b>Tamanho máximo:</b> <?php print $vs_tamanho_maximo_upload; ?> MB </span>" +
            "<span style='font-size:0.8em'><b>Extensões permitidas:</b> <?php print implode(", ", array_keys($va_extensoes_permitidas)); ?></span>",
        allowRevert: false,
        maxParallelUploads: 1
    });

    pond = FilePond.create(
        document.querySelector("#filepond_<?php print $vs_nome_campo ?>")
    );
    
    $("#myModal_<?php print $vs_nome_campo ?>").modal('show');

    $('#filepond_<?php print $vs_nome_campo ?>').on('FilePond:processfiles', function (e) 
    {
        $("#closeModal_<?php print $vs_nome_campo ?>").html("Concluir");
    });
}
);

$(document).on('click', "#btn_upload_<?php print $vs_nome_campo ?>", function()
{

    const allowedFileTypes = [
        <?php
        foreach($va_extensoes_permitidas as $vs_extensao_permitida => $vs_mime_type)
        {
            $mimes[] = "'".$vs_mime_type."'";
        }
        print implode(",", $mimes);
        ?>
    ];

    const files = pond.getFiles();

    const modal = document.getElementById('myModal_<?php print $vs_nome_campo ?>');

    if (files.length === 0)
    {
        modal.getElementsByClassName('modal-body')[0].insertAdjacentHTML('beforeend',
            "<div class='alert alert-warning alert-dismissible' role='alert'>Nenhum arquivo selecionado</div>");
        setTimeout(function() {
            modal.getElementsByClassName('modal-body')[0].lastChild.remove();
            }, 3000
        );
        return;
    }

    for (let i = 0; i < files.length; i++)
    {
        if (!allowedFileTypes.includes(files[i].fileType)) {
            const fileType = (files[i].fileType).split("/").pop();
            modal.getElementsByClassName('modal-body')[0].insertAdjacentHTML('beforeend',
                "<div class='alert alert-warning alert-dismissible' role='alert'>Extensão não permitida: " + fileType + "</div>");
            setTimeout(function() {
                modal.getElementsByClassName('modal-body')[0].lastChild.remove();
                }, 3000
            );
            return;
        }
    }

    pond.processFiles();
});

$(document).on("shown.bs.modal", '#myModal_<?php print $vs_nome_campo ?>', function ()
{
});

$(document).on("hidden.bs.modal", '#myModal_<?php print $vs_nome_campo ?>', function ()
{
    atualizar_campo_<?php print $vs_nome_campo ?>();
});


function atualizar_campo_<?php print $vs_nome_campo ?>() {
    jQuery.ajaxSetup({async:false});

    vs_codigos_remover = $("#rd_codigos_remover_<?php print $vs_nome_campo ?>").val();

    vs_url_campo_atualizado = 'functions/montar_campos.php?obj=<?php print $vs_tela; ?>&campo=<?php print $vs_nome_campo; ?>&cod=<?php print $vn_objeto_codigo; ?>&modo=edicao&atualizacao=1';

    $.get(vs_url_campo_atualizado, function(data, status)
    {
        $("#div_<?php print $vs_nome_campo ?>").html(data);
    });

    if (vs_codigos_remover != "")
    {
        va_codigos_remover = vs_codigos_remover.split(",");
        vs_lista_codigos = $("#<?php print $vs_nome_campo ?>").val();
        va_lista_codigos = vs_lista_codigos.split("|");

        // Remove os itens já marcados para exclusão
        ////////////////////////////////////////////

        for (vn_key in va_codigos_remover)
        {
            console.log("#<?php print $vs_nome_campo . "_"; ?>"+va_codigos_remover[vn_key]);
            $("#<?php print $vs_nome_campo . "_"; ?>"+va_codigos_remover[vn_key]).remove();

            // Remove o item excluído a lista de códigos

            va_lista_codigos.splice(va_lista_codigos.indexOf(va_codigos_remover[vn_key]), 1);
        }

        $("#<?php print $vs_nome_campo ?>").val(va_lista_codigos.join('|'));
        $("#rd_codigos_remover_<?php print $vs_nome_campo ?>").val(vs_codigos_remover);
        $("#numero_<?php print $vs_nome_campo ?>").val(va_lista_codigos.length);

        // Renumera os itens que sobraram
        /////////////////////////////////
        contador = 1;
        for (vn_key in va_lista_codigos)
        {
            $("#n_"+va_lista_codigos[vn_key]).text(contador);
            contador++;
        }
    }
}


$(document).on('click', ".btn_remover_new", function()
{
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_rem_new_rd_', ''));
    $("#new_<?php print $vs_nome_campo . "_"; ?>"+vn_item_codigo).remove();
});

$(document).on('click', "#chk_publicar_todos_online", function()
{
    $(".chk-publicar-online").prop("checked", $(this).prop("checked"));
});

$(document).on('click', ".chk-publicar-online", function()
{
    if (!$(this).prop("checked"))
        $("#chk_publicar_todos_online").prop("checked", false);
    else
    {
        let vb_publicar_todos = true;

        $(".chk-publicar-online").each(function( index ) 
        {
            if (!$(this).prop("checked"))
                vb_publicar_todos = false;
        });

        $("#chk_publicar_todos_online").prop("checked", vb_publicar_todos);
    }
});

</script>

<?php if ($vb_pode_remover)
{
?>

<script>
$(document).on('click', "#btn_baixar_todos_<?php print $vs_nome_campo; ?>", function()
{
    let obj = $("#obj").val();

    window.open('functions/download.php?obj='+obj+'&cod=<?php print $vn_objeto_codigo; ?>'+'&tipo_rd=<?=$vs_nome_campo?>', '_blank');
});

$(document).on('click', "#btn_remover_todos_<?php print $vs_nome_campo; ?>", function()
{
    vs_lista_codigos_remover = $("#rd_codigos_remover_<?php print $vs_nome_campo ?>").val();
    vs_lista_representantes_digitais = $("#<?php print $vs_nome_campo ?>").val().replace(/\|/g, ',');

    if (vs_lista_codigos_remover != "")
        vs_lista_codigos_remover = vs_lista_codigos_remover + "," + vs_lista_representantes_digitais;
    else
        vs_lista_codigos_remover = vs_lista_representantes_digitais;

    $("#rd_codigos_remover_<?php print $vs_nome_campo ?>").val(vs_lista_codigos_remover);
    $("#<?php print $vs_nome_campo ?>").val('');
    $("#numero_<?php print $vs_nome_campo ?>").val(0);

    $("#div_campos_<?php print $vs_nome_campo ?>").empty();
    $(this).hide();
});

$(document).on('click', ".btn_remover", function()
{
    vn_item_codigo = parseInt($(this).attr('id').replace('btn_rem_rd_', ''));

    $("#<?php print $vs_nome_campo . "_"; ?>"+vn_item_codigo).remove();

    if ($("#rd_codigos_remover_<?php print $vs_nome_campo; ?>").val().length == 0)
        $("#rd_codigos_remover_<?php print $vs_nome_campo; ?>").val(vn_item_codigo);
    else
    {
        va_codigos = $("#rd_codigos_remover_<?php print $vs_nome_campo; ?>").val().split(",");

        if (!va_codigos.includes($(this).val().toString()))
        {
            va_codigos.push(vn_item_codigo);
            $("#rd_codigos_remover_<?php print $vs_nome_campo; ?>").val(va_codigos.toString());
        }
    }

    va_codigos_mantidos = $("#<?php print $vs_nome_campo ?>").val().split("|");

    va_lista_codigos_atualizada = "";
        
    for (vn_key in va_codigos_mantidos)
    {
        if (va_codigos_mantidos[vn_key] != vn_item_codigo)
        {
            if (va_lista_codigos_atualizada == "")
                va_lista_codigos_atualizada = va_codigos_mantidos[vn_key];
            else
                va_lista_codigos_atualizada = va_lista_codigos_atualizada + "|" + va_codigos_mantidos[vn_key];
        }
    }

    $("#<?php print $vs_nome_campo ?>").val(va_lista_codigos_atualizada);
    $("#numero_<?php print $vs_nome_campo ?>").val(va_codigos_mantidos.length-1);
});

</script>

<?php

    if ($vb_integracao_google_drive)
    {
        require 'upload_google_drive.php';
    }

}
?>

<?php
}
?>