<?php

    $vb_montar_menu = true;
    require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>
<div id="backdrop-spinner">
    <div class="text-center loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__)."/components/sidebar.php"; ?>

<?php
    $va_campos_upload["tipo_representante_digital_codigo"] = [
        "html_combo_input",
        "nome" => "tipo_representante_digital_codigo",
        "label" => "Tipo do representante digital",
        "objeto" => "tipo_representante_digital",
        "atributos" => ["tipo_representante_digital_codigo", "tipo_representante_digital_nome"],
        "atributo" => "tipo_representante_digital_codigo",
        "sem_valor" => false
    ];

    $va_campos_upload["salvar_arquivo_original"] = [
        "html_checkbox_input",
        "nome" => "salvar_arquivo_original",
        "label" => "Salvar arquivo original?",
        "valor_padrao" => config::get(["salvar_arquivo_original"]) ? "1" : "0"
    ];

    $va_campos_upload["nome_arquivo_identificador"] = [
        "html_checkbox_input",
        "nome" => "nome_arquivo_identificador",
        "label" => "Nome do arquivo contém código identificador do registro",
        "valor_padrao" => "1"
    ];

    $va_campos_upload["criar_registros"] = [
        "html_checkbox_input",
        "nome" => "criar_registros",
        "label" => "Criar registros não existentes",
        "valor_padrao" => 1
    ];

    $va_campos_upload["processar_paginacao"] = [
        "html_checkbox_input",
        "nome" => "processar_paginacao",
        "label" => "Nome do arquivo possui número de página ou ordem de sequência da imagem",
        "controlar_exibicao" => [
            "separador_paginacao",
            "largura_paginacao"
        ]
    ];

    $va_campos_upload["separador_paginacao"] = [
        "html_text_input",
        "nome" => "separador_paginacao",
        "label" => "Número de página ou ordem de sequência da imagem aparece após este separador",
        "regra_exibicao" => [
            "processar_paginacao" => "1"
        ]
    ];

    $va_campos_upload["largura_paginacao"] = [
        "html_number_input",
        "nome" => "largura_paginacao",
        "label" => "Extensão (em número de caracteres) do identificador do número de página ou ordem de sequência",
        "regra_exibicao" => [
            "processar_paginacao" => "1"
        ]
    ];

    $va_campos_upload["selecao_nome"] = [
        "html_text_input",
        "nome" => "selecao_nome",
        "label" => "Registros criados ou modificados serão adicionados nesta seleção",
        "readonly" => true
    ];

    $va_valores_campos = array();
    $va_valores_campos["selecao_nome"] = "upload-" . $vs_id_objeto_tela . "-" . date("Y-m-d-H:i");


if (config::get(["f_integracao_google_drive"]) ?? false)
{
    $authUrl = google_drive::get_auth_url($_SESSION["usuario_logado_codigo"], 'drive');
}

$vn_espaco_livre = disk_free_space("/");
$vn_espaco_livre = (int)($vn_espaco_livre / 1024 / 1024 / 1024);

$vn_espaco_usado = disk_total_space("/");
$vn_espaco_usado = (int)($vn_espaco_usado / 1024 / 1024 / 1024);
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <form method="post" action="functions/upload.php" id="form_upload">
        <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
        <input type="hidden" name="usuario_codigo" id="usuario_codigo" value="<?php print $vn_usuario_logado_codigo; ?>">
        <input type="hidden" name="arquivos_importados" id="arquivos_importados">

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">Especificações dos arquivos para upload</div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                        <p class="small m-0"><b>Espaço livre no servidor:</b> <?= $vn_espaco_livre ?> GB de <?= $vn_espaco_usado ?> GB</p>
                                    </div>

                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary btn-back" type="button" id="btn_back" style="display:none">
                                            Voltar
                                        </button>

                                        <button class="btn btn-primary btn-next" type="button" id="btn_next">
                                            Próximo
                                        </button>

                                        <button class="btn btn-primary btn-upload" type="button" id="btn_upload" style="display:none">
                                            Upload
                                        </button>
                                    </div>
                                </div>

                                <br>

                                <!-- FORM-->
                                <div class="row no-margin-side" id="filtro">

                                    <div class="col-12" id="div_1">

                                        <?php
                                            foreach ($va_campos_upload as $vs_key_campo => $v_campo)
                                            {
                                                $vo_campo = new $v_campo[0]($vs_id_objeto_tela, $v_campo["nome"]);
                                                $vo_campo->build($va_valores_campos, $v_campo);
                                            }
                                        ?>

                                    </div>

                                    <div class="col-12" id="div_2" style="display:none">
                                        <?php
                                            $vo_objeto = new $vs_id_objeto_tela('');
                                            $va_campos = $vo_objeto->inicializar_campos_edicao();
                                            $va_abas_form = $vo_objeto->get_form_edicao("completo");

                                            $vo_form_cadastro = new html_form_cadastro($vs_id_objeto_tela, $va_abas_form, $va_campos, array(), array());
                                            $vs_campo_foco = $vo_form_cadastro->build('', $vn_usuario_logado_instituicao_codigo, $vn_usuario_logado_acervo_codigo);
                                        ?>
                                    </div>

                                    <div class="col-12" id="div_3" style="display:none">


                                        <input type="file"
                                            class="filepond"
                                            name="filepond"
                                            id="filepond"
                                            multiple
                                            data-allow-reorder="true"
                                            data-max-file-size="2MB"
                                            data-max-files="1000">


                                        <?php if (config::get(["f_integracao_google_drive"]) ?? false) : ?>
                                            <span id="google-drive-button" data-campo-tipo="<?= $pa_parametros_campo["tipo"] ?? 1 ?>">
                                                <?php if (!empty($authUrl)) : ?>
                                                    <button class="btn btn-outline-primary px-4" type="button"
                                                            onclick="openOAuthPopup('<?= $authUrl ?>')">Conectar ao Google Drive</button>
                                                <?php else : ?>
                                                    <button class="btn btn-outline-primary px-4" type="button"
                                                            onclick="openGoogleDriveModal<?= $pa_parametros_campo["tipo"] ?? 1 ?>()">Adicionar do Google Drive</button>
                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>

                                        <button class="btn btn-primary px-4" type="button" id="btn_remove_files" style="float:right; margin-top:5px; display:none">Remover todos</button>

                                    </div>

                                    <input type="hidden" id="div_atual" value="1">
                                </div>
                                <!-- / FORM-->

                                <br>

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                    </div>

                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary btn-back" type="button" id="btn_back" style="display:none">
                                            Voltar
                                        </button>

                                        <button class="btn btn-primary btn-next" type="button" id="btn_next">
                                            Próximo
                                        </button>

                                        <button class="btn btn-primary btn-upload" type="button" id="btn_upload" style="display:none">
                                            Upload
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>
        </div>
    </form>
</div>
<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<link href="assets/libraries/filepond/filepond.css" rel="stylesheet">

<script src="assets/libraries/filepond/filepond.js"></script>

<script>

var pond;
let va_arquivos_importados = [];

FilePond.setOptions({
    instantUpload: false,
    server:
    {
        process:
        {
            url: 'functions/upload.php',
            ondata: (formData) => {
                formData.append('representante_digital_tipo_codigo', '1');
                formData.append('representante_digital_campo_nome', 'representante_digital_codigo');

                // Adiciona todos os campos de edição no form a ser enviado
                jQuery.each( $("#form_upload").serializeArray(), function( i, field )
                {
                    formData.append(field.name, field.value);
                });

                return formData;
            },
            onload: (response) => {
                adicionarArquivoImportado(response);
            }
        }
    },
    labelIdle: "Clique aqui ou arraste neste espaço os arquivos de imagem",
    allowRevert: false,
    allowRemove: true,
    maxParallelUploads: 1
});


function adicionarArquivoImportado(arquivoImportado) {
    if (arquivoImportado.trim() !== "" && arquivoImportado.includes("|"))
    {
        let vs_arquivo_importado = arquivoImportado.trim();
        let va_infos_arquivo = vs_arquivo_importado.split("|");

        if (va_arquivos_importados.indexOf(va_infos_arquivo[1]) === -1)
        {
            va_arquivos_importados.push(va_infos_arquivo[1]);

            if ( $("#arquivos_importados").val().trim() == "" )
                $("#arquivos_importados").val(vs_arquivo_importado);
            else
                $("#arquivos_importados").val($("#arquivos_importados").val() + ';' + vs_arquivo_importado);
        }
    }
}



pond = FilePond.create(
    document.querySelector("#filepond")
);

$('#filepond').on('FilePond:addfile', function (e)
{
    $(".btn-upload").show();
    $("#btn_remove_files").show();
    $("#arquivos_importados").val('');
});

$('#filepond').on('FilePond:removefile', function (e)
{
    if (pond.getFiles().length == 0)
    {
        $(".btn-upload").hide();
        $("#btn_remove_files").hide();
    }
});

$('#filepond').on('FilePond:processfiles', function (e)
{
    $(".btn-upload").hide();
    $(".btn-back").show();
    $("#arquivos_importados").val('');
});

$(document).on('click', "#btn_remove_files", function()
{
    pond.removeFiles();

    $("#arquivos_importados").val('');

    $(".btn-upload").hide();
    $(this).hide();
});

$(document).on('click', ".btn-upload", function()
{
    $(".btn-upload").hide();
    $(".btn-back").hide();

    $("#arquivos_importados").val('');

    pond.processFiles();
});

$(document).on('click', ".btn-next", function()
{
    vn_div_atual = parseInt($("#div_atual").val());
    vn_proximo_div = vn_div_atual + 1;

    $("#div_"+vn_div_atual).hide();
    $("#div_"+vn_proximo_div).show();

    switch(vn_proximo_div)
    {
        case 2:
            $(".card-header").html("Salvar dados em registros a serem criados");
            $(".btn-back").show();

            break;

        case 3:
            $(".card-header").html("Upload de arquivos de imagem");
            $(".btn-next").hide();

            break;
    }

    $("#div_atual").val(vn_proximo_div);
});

$(document).on('click', ".btn-back", function()
{
    vn_div_atual = parseInt($("#div_atual").val());
    vn_div_anterior = vn_div_atual -1;

    $("#div_"+vn_div_atual).hide();
    $("#div_"+vn_div_anterior).show();

    switch(vn_div_anterior)
    {
        case 1:
            $(".card-header").html("Especificações dos arquivos para upload");
            $(".btn-back").hide();

            break;

        case 2:
            $(".card-header").html("Upload de arquivos de imagem");
            $(".btn-upload").hide();
            $(".btn-next").show();

            break;
    }

    $("#div_atual").val(vn_div_anterior);
});



<?php

$va_all_campos = array_merge($va_campos, $va_campos_upload);

foreach($va_all_campos as $vs_key_campo => $va_parametros_campo)
{

if (isset($va_parametros_campo["regra_exibicao"]))
{
    foreach($va_parametros_campo["regra_exibicao"] as $vs_campo => $va_valores_desejados)
    {
        if (!is_array($va_valores_desejados))
            $va_valores_desejados = array($va_valores_desejados);

        $vs_valores_desejados = implode("|", $va_valores_desejados);
    }
?>

function atualizar_exibicao_<?php print $vs_key_campo; ?>(ps_valor)
{
    atualizar_exibicao_campo('<?php print $vs_key_campo; ?>', ps_valor, '<?php print $vs_valores_desejados; ?>', '<?php print $va_parametros_campo[0]; ?>');
}

<?php
}

// Vamos verificar se os subcampos do campo contém regras de exibição
/////////////////////////////////////////////////////////////////////

if (isset($va_parametros_campo["subcampos"]))
{
foreach ($va_parametros_campo["subcampos"] as $vs_key_subcampo => $va_subcampo)
{
if (isset($va_subcampo["regra_exibicao"]))
{
?>

function atualizar_exibicao_<?php print $vs_key_subcampo; ?>(ps_sufixo, ps_valor)
{
    <?php
    foreach($va_subcampo["regra_exibicao"] as $vs_campo => $va_valores_desejados)
    {
        if (!is_array($va_valores_desejados))
            $va_valores_desejados = array($va_valores_desejados);

        $vs_valores_desejados = implode("|", $va_valores_desejados);
    }
    ?>

    //console.log()
    atualizar_exibicao_campo('<?php print $vs_key_subcampo; ?>'+ps_sufixo, ps_valor, '<?php print $vs_valores_desejados; ?>', '<?php print $va_subcampo[0]; ?>');
}

<?php
}
}
}

}
?>

function atualizar_exibicao_campo(ps_campo, ps_valor, ps_valores_desejados, ps_tipo_campo)
{
    vb_exibir_campo = false;

    vs_campo_desabilitar = ps_campo;
    if (ps_tipo_campo == "html_multi_itens_input")
        vs_campo_desabilitar = "numero_" + ps_campo;

    pa_valores_desejados = ps_valores_desejados.split("|");

    let i = 0;
    while (i < pa_valores_desejados.length)
    {
        v_valor_desejado = pa_valores_desejados[i];

        if (v_valor_desejado == "nao_vazio")
        {
            v_valor_desejado_campo = "''";
            vs_operador = "!=";
        }
        else if (v_valor_desejado.substring(0, 2) == "<>")
        {
            v_valor_desejado_campo = v_valor_desejado.replace("<>", "");
            vs_operador = "!=";
        }
        else
        {
            v_valor_desejado_campo = v_valor_desejado;
            vs_operador = "==";
        }

        if ( (typeof ps_valor) == "string" )
            va_valores = ps_valor.split("|");

        else if ((typeof ps_valor) == "boolean")
        {
            if (ps_valor)
                va_valores = ['1'];
            else
                va_valores = ['0'];
        }

        for (v_valor in va_valores)
        {
            ps_valor = va_valores[v_valor];

            switch (vs_operador)
            {
                case "==":
                    if (ps_valor == v_valor_desejado_campo)
                        vb_exibir_campo = true;

                    break;

                case "!=":
                    if (ps_valor != v_valor_desejado_campo)
                        vb_exibir_campo = true;

                    break;
            }
        }

        i++;
    }

    if (vb_exibir_campo)
    {
        $("#div_"+ps_campo).show();
        desabilitar_campo(vs_campo_desabilitar, false, ps_tipo_campo);
    }
    else
    {
        $("#div_"+ps_campo).hide();
        desabilitar_campo(vs_campo_desabilitar, true, ps_tipo_campo);
    }
}

function desabilitar_campo(ps_campo, pb_desabilitar, ps_tipo_campo)
{
    vs_tipo_campo = $("#" + ps_campo).attr("class");

    switch (vs_tipo_campo)
    {
        case "lookup":
            $("#" + ps_campo + "_codigo").prop("disabled", pb_desabilitar);
            break;

        default:
            $("#" + ps_campo).prop("disabled", pb_desabilitar);
            break;
    }

    if (ps_tipo_campo == 'html_date_input')
    {
        $("#" + ps_campo + "_dia_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_mes_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_ano_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_dia_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_mes_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_ano_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_presumido").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_sem_data").prop("disabled", pb_desabilitar);
    }
    else if (ps_tipo_campo == "html_multi_itens_input")
    {
        $("#" + ps_campo).prop("disabled", false);

        if (pb_desabilitar)
            $("#" + ps_campo).val(0);
    }
}

$(document).on('focus', "#separador_paginacao", function()
{
    //$(this).val("-");
    //$("#largura_paginacao").val("");
});

$(document).on('focus', "#largura_paginacao", function()
{
    //$(this).val("1");
    //$("#separador_paginacao").val("");
});

$(document).on('click', ".btn-tab", function() {
    $('.btn-tab').removeClass('active');
    $('.tab').hide();
    $('#tab_'+$(this).attr('id')).show();
    $(this).addClass('active');
});

</script>

<?php
    if (config::get(["f_integracao_google_drive"]) ?? false)
    {
        require 'components/upload_google_drive.php';
    }
?>

</body>
</html>