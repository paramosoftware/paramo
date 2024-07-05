<?php
global $vs_recurso_sistema_nome_plural, $vs_id_objeto_importacao, $va_usuario;

$vs_id_objeto_importacao = $_GET["obj"] ?? $_POST["obj"];
$vs_caminho_arquivo = $_POST["caminho_arquivo"] ?? null;


$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";
$vn_step = $_POST["step"] ?? 1;
$va_parametros_importacao = ($vn_step) > 2 ? json_decode($_POST["parametros_importacao"], true) :  $_POST["parametros_importacao"] ?? null;
switch ($vn_step) {
    case 1:
        break;
    case 2:
        $vs_caminho_arquivo = move_import_file();
        $va_rows = get_header_file($vs_caminho_arquivo, pathinfo($vs_caminho_arquivo, PATHINFO_EXTENSION));
        $va_campos_edicao = get_campos_edicao($vs_id_objeto_importacao);
        break;
    case 3:
        $va_campos_edicao = get_campos_edicao($vs_id_objeto_importacao);
        $va_campos_destino_selecao = $_POST["campos_destino_selecao"];
        $va_campos_origem = $_POST["campos_origem"];
        $va_header_origem = json_decode($_POST["header_origem"], true);
        $va_ponteiros_relacionamento = relacionar_dados_input($va_campos_destino_selecao, $va_campos_origem, $va_campos_edicao);
        break;
        case 4:
        $va_ponteiros_relacionamento = json_decode($_POST["ponteiros_relacionamento"], true);
        $va_campos_valor_padrao = $_POST["campos_valor_padrao"];
        $va_campos_separador = $_POST["campos_separador"];

        $va_header_import = montar_header_import($va_ponteiros_relacionamento, $va_campos_valor_padrao, $va_campos_separador);
        $va_data_csv = get_data_csv($vs_caminho_arquivo, ',', 0, true, false);
        $va_objetos_importados = process_import($vs_id_objeto_importacao, $va_data_csv, $va_header_import, $va_usuario["usuario_codigo"]);
        break;
}
function get_codigo_objeto_from_nome($ps_id_objeto, $ps_atributo, $ps_valor) {
    $vo_objeto_de_busca = new $ps_id_objeto;
    $va_parametro_busca = [
            $ps_atributo => [
                $ps_valor
            ]
    ];
    return  $vo_objeto_de_busca->ler_lista($va_parametro_busca[$ps_atributo]);
}
function montar_header_import($pa_ponteiros_relacionamento, $pa_campos_valor_padrao, $pa_campos_separador): array
{
    return relacionar_valor_padrao($pa_campos_valor_padrao, relacionar_separador($pa_campos_separador, $pa_ponteiros_relacionamento));

}

;
function relacionar_valor_padrao($pa_valores_padrao, $pa_objeto_import): array
{
    foreach (array_keys($pa_valores_padrao) as $pa_valor_padrao_key) {
        $pa_objeto_import[$pa_valor_padrao_key]["campo_valor_padrao"] = $pa_valores_padrao[$pa_valor_padrao_key];
    }

    return $pa_objeto_import;
}

function relacionar_separador($pa_valores_separador, $pa_objeto_import): array
{
    foreach (array_keys($pa_valores_separador) as $pa_valor_separador_key) {
        $pa_objeto_import[$pa_valor_separador_key]["campo_separador"] = $pa_valores_separador[$pa_valor_separador_key];
    }
    return $pa_objeto_import;
}

function relacionar_dados_input($pa_destino, $pa_origem, $pa_campos_edicao): array
{
    $dados_input = [];
    foreach ($pa_destino as $index => $campo_destino) {
        if (array_key_exists($campo_destino, $pa_campos_edicao)) {
            $dados_input[$index] = [
                "campo_origem" => $pa_origem[$index],
                "campo_destino" => $campo_destino,
                "campo_destino_parametros" => $pa_campos_edicao[$campo_destino],
                "campo_posicao" => $index
            ];
        }
    }
    return $dados_input;
}

function get_header_file($ps_caminho_arquivo, $ps_extensao): array
{
    $va_rows = array();

    if ($ps_extensao == "csv") {
        $va_rows = get_data_csv($ps_caminho_arquivo, ',', 1);
    } else {
//        if ($xlsx = SimpleXLSX::parse($ps_caminho_arquivo))
//        {
//            $va_rows = iterator_to_array($xlsx->readRows(0, 1));
//        }
    }

    return $va_rows;
}

function move_import_file(): string
{
    $vs_pasta_import = config::get(["pasta_media", "temp"]);
    if (!isset($_FILES["arquivo"]) || $_FILES["arquivo"]["error"] != UPLOAD_ERR_OK) {
        return "";
    }

    $va_arquivo = $_FILES["arquivo"];
    $vs_caminho_arquivo = $vs_pasta_import . utils::sanitize_file_name($va_arquivo["name"]);

    if (move_uploaded_file($va_arquivo["tmp_name"], $vs_caminho_arquivo)) {
        return $vs_caminho_arquivo;
    }

    return "";
}

function get_campos_edicao($ps_id_objeto_tela)
{
    $vo_objeto = new $ps_id_objeto_tela();
    return $vo_objeto->inicializar_campos_edicao();
}

function get_data_csv($ps_file_path, $ps_delimiter = ",", $pn_limit_num_rows = 0, $pb_remove_header = false, $pb_assign_header_labels_on_cols = false): array
{
    $handle = fopen($ps_file_path, "r");
    $rows = array();

    if ($handle !== false) {
        $row = 0;
        while (($data = fgetcsv($handle, 0, $ps_delimiter)) !== false) {
            if ($pb_assign_header_labels_on_cols && !$pb_remove_header && $row != 0) {
                $rows[] = array_combine($rows[0], $data);
            } else {
                $rows[] = $data;
            }

            $row++;
            if ($pn_limit_num_rows > 0 && $row >= $pn_limit_num_rows) {
                break;
            }
        }
        fclose($handle);
    }

    if ($pb_remove_header) {
        unset($rows[0]);
    }

    return $rows;
}

function get_parametros_obrigatorios($pa_parametros)
{
    return array_filter($pa_parametros, function ($parametro) {
        return $parametro["atributo_obrigatorio"];
    });
}

function get_parametros_obrigatorios_faltantes($pa_parametros_obrigatorios, $pa_parametros_fornecidos_importacao)
{
    // TODO: retornar map apenas com parametros obrigatorios não presentes no header base (?)
}

function get_chave_parametro($ps_chave, $pa_parametro)
{
    return current(array_filter(array_keys($pa_parametro), function ($chave_parametro) use ($ps_chave) {
        return strpos($chave_parametro, $ps_chave);
    }));
}


function process_import($ps_objeto_de_importacao, $pa_dados_csv, $pa_header_import, $ps_usuario_codigo): array
{
    // todo: utilizar $va_parametros_importacao para interpretar o processamento de acordo com as selecoes do usuario
    $vo_objeto_de_importacao = new $ps_objeto_de_importacao;
    $vo_objetos_importados = array();
    foreach($pa_dados_csv as $row => $row_data) {
        $va_dados_row_salvar = array();
        foreach($row_data as $col => $col_data) {
            if (array_key_exists($col, $pa_header_import)) {
                $vs_chave_campo_destino = $pa_header_import[$col]["campo_destino"];
                $va_dados_row_salvar["usuario_logado_codigo"] = $ps_usuario_codigo;
                if (strpos($vs_chave_campo_destino, "_codigo")) {
                    // Exportacao sempre traz o NOME ao invés do código. essa parte do código trata de retornar essa identificacao caso o campo seja de relacionamento
                    $vs_id_objeto_busca =  $pa_header_import[$col]["campo_destino_parametros"]["objeto"];
                    $vs_atributo_objeto_busca = isset($pa_header_import[$col]["campo_destino_parametros"]["atributo"]) ?  $pa_header_import[$col]["campo_destino_parametros"]["atributo"] : $pa_header_import[$col]["campo_destino_parametros"]["atributos"][0];

                    $vs_valor_objeto_busca = $col_data;
                    // TODO: Modificar funcao get_codigo_objeto_from_nome pra retornar o nome adequadamente e não uma array. A query atual retorna todas as entradas do objeto sendo consultado
                    $vs_resultado_busca = get_codigo_objeto_from_nome($vs_id_objeto_busca, $vs_atributo_objeto_busca, $vs_valor_objeto_busca);
                    $col_data = $vs_resultado_busca;
                }
                $va_dados_row_salvar[$vs_chave_campo_destino] = $col_data;
                // TODO: remover hardcoding, obter essas informacoes corretamente do contexto
                $va_dados_row_salvar["item_acervo_identificador"] = "";
                $va_dados_row_salvar["item_acervo_acervo_codigo"] = "1";

                $va_dados_row_salvar["texto_publicado_online"] = "1";
                $va_dados_row_salvar["texto_publicado_online_chk"] = "1";

            }
        }
        $vo_objeto_de_importacao->iniciar_transacao();
        $vo_objetos_importados [] = $vo_objeto_de_importacao->salvar($va_dados_row_salvar);
        $vo_objeto_de_importacao->finalizar_transacao();
    }

    return $vo_objetos_importados;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php require_once dirname(__FILE__) . "/components/sidebar.php"; ?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <?php require_once dirname(__FILE__) . "/components/header.php"; ?>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">Importar <?= $vs_recurso_sistema_nome_plural; ?></div>
                        <div class="card-body">
                            <?php if ($vn_step == 1) : ?>
                                <?php

                                $vo_combo_teste = new html_combo_input($_GET["obj"], "livro_genero_textual_codigo");
                                $vo_objeto_teste = new $_GET["obj"];
                                ?>
                                <form method="post" enctype="multipart/form-data" action="importar.php">
                                    <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                    <input type="hidden" name="step" value="2">
                                    <div class="row no-margin-side" id="filtro">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="file" name="arquivo" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <?php require_once dirname(__FILE__) . "/components/importar_instrucoes.php"; ?>

                                    <div class="row mt-3 p-4">
                                        <div class="text-end">
                                            <button class="btn btn-primary btn-importar" type="submit">
                                                Continuar
                                            </button>
                                        </div>
                                    </div>
                                </form>


                            <?php elseif ($vn_step == 2) : ?>
                                <?php if ($vs_caminho_arquivo == "" || count($va_rows) == 0) : ?>

                                    <div class="alert alert-danger" role="alert">
                                        <?php if ($vs_caminho_arquivo == "") : ?>
                                            <p>Erro ao carregar arquivo.</p>
                                        <?php elseif (count($va_rows) == 0) : ?>
                                            <p>Arquivo vazio.</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row mt-3 pr-3">
                                        <div class="text-end">
                                            <a href="importar.php" class="btn btn-primary btn-importar">
                                                Voltar
                                            </a>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <form method="post" enctype="multipart/form-data" action="importar.php" class="p-4">
                                        <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                        <input type="hidden" name="step" value="3">
                                        <input type="hidden" name="caminho_arquivo" value="<?= $vs_caminho_arquivo; ?>">
                                        <input type="hidden" name="parametros_importacao" value="<?= htmlentities(json_encode($va_parametros_importacao), ENT_QUOTES, "UTF-8", false) ; ?>">
                                        <input type="hidden" name="header_origem" value="<?= htmlentities(json_encode($va_rows), ENT_QUOTES, "UTF-8", false) ?>">
                                        <h2>Relacionamento de campos</h2>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Coluna de origem</th>
                                                <th>Campo de destino</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($va_rows[0] as $vn_index => $vs_header) : ?>
                                                <input type="hidden" name="campos_origem[<?= $vn_index ?>]" value="<?= $vs_header; ?>">
                                                <tr>
                                                <td><?= $vs_header; ?></td>
                                                    <td>
                                                        <label>
                                                            <select class="form-control" name="campos_destino_selecao[<?= $vn_index; ?>]">
                                                                <option value="">Selecione</option>
                                                                <?php foreach ($va_campos_edicao as $vs_campo_id => $va_campo_atributos) : ?>
                                                                    <option value="<?= $vs_campo_id ?>"
                                                                        <?= strpos(strtolower($va_campo_atributos["label"]), strtolower($vs_header)) !== false ? "selected" : ""; ?> >
                                                                        <?= $va_campo_atributos["label"]; ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <div class="row mt-3 pr-3">
                                            <div class="text-end">
                                                <button id="continuar-step-2" class="btn btn-primary btn-importar" type="submit">
                                                    Continuar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                            <?php elseif ($vn_step == 3) : ?>
                                <form method="post" enctype="multipart/form-data" action="importar.php" class="p-4">
                                    <h2>Definição de variáveis de importação</h2>
                                    <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                    <input type="hidden" name="step" value="4">
                                    <input type="hidden" name="caminho_arquivo" value="<?= $vs_caminho_arquivo ?>">
                                    <input type="hidden" name="parametros_importacao" value="<?= htmlentities(json_encode($va_parametros_importacao), ENT_QUOTES, "UTF-8", false) ; ?>">
                                    <input type="hidden" name="ponteiros_relacionamento"
                                           value='<?= htmlentities(json_encode($va_ponteiros_relacionamento), ENT_QUOTES, "UTF-8", false) ?>'>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Coluna de origem</th>
                                            <th>Campo de destino selecionado</th>
                                            <th>Valor padrão</th>
                                            <th>Separador de valores</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($va_ponteiros_relacionamento as $ponteiro_relacionamento => $relacionamento) : ?>
                                            <tr>
                                                <td><?= $relacionamento["campo_origem"] ?></td>
                                                <td><?= $relacionamento["campo_destino_parametros"]["label"] ?>
                                                    (<?= $relacionamento["campo_destino"] ?>)
                                                </td>
                                                <td>
                                                    <?php if ($relacionamento["campo_destino_parametros"][0] == "html_combo_input" || $relacionamento["campo_destino_parametros"][0] == "html_autocomplete"): ?>
                                                        <?php
                                                        $vo_objeto_relacionamento = new $relacionamento["campo_destino_parametros"]["objeto"];
                                                        $va_relacionamento_lista_controlada = $vo_objeto_relacionamento->ler_lista();
                                                        ?>
                                                        <label>
                                                            <select class="form-control"
                                                                    name="campos_valor_padrao[<?= $relacionamento["campo_posicao"]; ?>]">
                                                                <option value="">Selecione</option>
                                                                <?php foreach ($va_relacionamento_lista_controlada as $registro => $dado): ?>
                                                                    <?php
                                                                    $vs_dado_nome = get_chave_parametro("nome", $dado);
                                                                    $vs_dado_codigo = get_chave_parametro("codigo", $dado);
                                                                    ?>
                                                                    <option value="<?= $dado[$vs_dado_codigo] ?>">
                                                                        <?= $dado[$vs_dado_nome] ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </label>


                                                    <?php else: ?>
                                                        <label>
                                                            <input type="text"
                                                                   name="campos_valor_padrao[<?= $relacionamento["campo_posicao"] ?>]"
                                                                   class="form-control form-control-sm">
                                                        </label>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               name="campos_separador[<?= $relacionamento["campo_posicao"] ?>]"
                                                               class="form-control form-control-sm">
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                        </tbody>
                                    </table>
                                    <div class="row mt-3 pr-3">
                                        <div class="text-end">
                                            <button class="btn btn-primary btn-importar" type="submit">
                                                Continuar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            <?php elseif ($vn_step == 4) : ?>
                                <div class="p-4">
                                    <h2>Conclusão de importação.</h2>
                                    <?php
                                    foreach ($va_objetos_importados as $objetos_importado) {
                                        echo "Objeto número: " . $objetos_importado . " com sucesso.<br>";
                                    }


                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__FILE__) . "/components/footer.php"; ?>
</body>
</html>
<script>

    const validar_relacionamentos_obrigatorios = (dom, classe_campos_obrigatorios) => {
        const campos_obrigatorios = dom.querySelectorAll(`.${classe_campos_obrigatorios}`);
            for (const campo of campos_obrigatorios) {
                if (campo.value.length === 0) {
                    return false;
                }
            }
        return true;
        }

    // document.getElementById('continuar-step-2').addEventListener('click', function(event){
    //     if(!validar_relacionamentos_obrigatorios(document, "campo_obrigatorio"))  {
    //         alert('Um ou mais campos de destino obrigatórios não foram preenchidos no relacionamento.')
    //         event.preventDefault();
    //     }
    // })

</script>