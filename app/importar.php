<?php
require_once dirname(__FILE__) . "/components/entry_point.php";
require_once config::get(["pasta_vendors"]) . "/simplexlsx/src/SimpleXLSX.php";
require_once config::get(["pasta_vendors"]) . "/simplexlsx/src/SimpleXLSXEx.php";

use Shuchkin\SimpleXLSX;

global $vs_recurso_sistema_nome_plural, $vs_id_objeto_importacao, $va_usuario;
global $vn_usuario_logado_instituicao_codigo, $va_usuario;
$vn_usuario_codigo = $va_usuario["usuario_codigo"];
$vs_caminho_arquivo = $_POST["caminho_arquivo"] ?? null;
$vb_montar_menu = true;
$vn_step = $_POST["step"] ?? 1;
$vs_id_objeto_importacao = $_GET["obj"] ?? $_POST["obj"];
$vs_parametros_importacao_json = isset($_POST["parametros_importacao"]) ? json_encode($_POST["parametros_importacao"]) : $_POST["parametros_importacao_json"] ?? "";
$vs_campos_destino_selecao_json = isset($_POST["campos_destino_selecao"]) ? json_encode($_POST["campos_destino_selecao"]) : $_POST["campos_destino_selecao_json"] ?? "";
$vo_importacao = new importacao_refatorado($vn_usuario_logado_instituicao_codigo, $vn_usuario_codigo, $vs_id_objeto_importacao);
$vo_importacao->inicializar_campos_edicao_objeto_importacao();

switch ($vn_step)
{
    case 1:
        break;
    case 2:
        $vo_importacao->set_caminho_arquivo_importacao(move_import_file());
        $vo_importacao->set_campos_origem_label(get_file_data($vo_importacao->get_caminho_arquivo_importacao(), true)[0]);
        break;
    case 3:
        $vo_importacao->set_campos_origem_label(get_file_data($vo_importacao->get_caminho_arquivo_importacao(),true)[0]);
        $vo_importacao->set_parametros_importacao($_POST["parametros_importacao"] ?? []);
        $vo_importacao->set_campos_destino_selecao(json_decode($vs_campos_destino_selecao_json, true));
        break;
    case 4:
        $vo_importacao->set_campos_origem_label(get_file_data($vo_importacao->get_caminho_arquivo_importacao(),true)[0]);
        $vo_importacao->set_parametros_importacao($_POST["parametros_importacao"] ?? []);
        $vo_importacao->set_campos_destino_selecao(json_decode($vs_campos_destino_selecao_json, true));
        $vo_importacao->inicializar_variantes_campos_importacao(
            $_POST["campos_valor_padrao"] ?? [],
            $_POST["campos_criar_itens_relacionados"] ?? [],
            $_POST["campos_separador"] ?? [],
            $_POST["campos_subcampos_separador"] ?? [],
        );
        $vo_importacao->set_dados_origem(get_file_data($vo_importacao->get_caminho_arquivo_importacao()));
        $va_resultado_importacao = $vo_importacao->importar();
        break;
}

function get_file_data($ps_caminho_arquivo, $pb_return_only_headers = false, $pb_remove_header = true): array
{
    $vs_extensao_arquivo = pathinfo($ps_caminho_arquivo, PATHINFO_EXTENSION);
    $va_rows = [];

    if ($vs_extensao_arquivo == "xlsx" || $vs_extensao_arquivo == "xls")
    {
        $xlsx = SimpleXLSX::parse($ps_caminho_arquivo);
        if (!$xlsx) { return []; }

        if ($pb_return_only_headers)
        {
            $va_rows = iterator_to_array($xlsx->readRows(0, 1));
        }
        else
        {
            $va_rows = iterator_to_array($xlsx->readRows());
            if ($pb_remove_header)
            {
                unset($va_rows[0]);
            }
        }
    }
    else if ($vs_extensao_arquivo == "csv")
    {
        if ($pb_return_only_headers)
        {
            $va_rows = get_data_csv($ps_caminho_arquivo, ",", 1);
        }
        else
        {
            $va_rows = get_data_csv($ps_caminho_arquivo, ",", 0, true);
        }
    }

    return $va_rows;
}

function move_import_file(): string
{
    $vs_pasta_import = config::get(["pasta_media", "temp"]);
    if (!isset($_FILES["arquivo"]) || $_FILES["arquivo"]["error"] != UPLOAD_ERR_OK)
    {
        return "";
    }

    $va_arquivo = $_FILES["arquivo"];
    $vs_caminho_arquivo = $vs_pasta_import . utils::sanitize_file_name($va_arquivo["name"]);

    if (move_uploaded_file($va_arquivo["tmp_name"], $vs_caminho_arquivo))
    {
        return $vs_caminho_arquivo;
    }

    return "";
}

function get_data_csv($ps_file_path, $ps_delimiter = ",", $pn_limit_num_rows = 0, $pb_remove_header = false, $pb_assign_header_labels_on_cols = false): array
{
    // TODO: Alguns documentos de entrada podem conter colunas fantasma. Melhorar essa funcao futuramente pra evitar rows de insercao inválidas
    $handle = fopen($ps_file_path, "r");
    $rows = array();

    if ($handle !== false)
    {
        $row = 0;
        while (($data = fgetcsv($handle, 0, $ps_delimiter)) !== false)
        {
            if ($pb_assign_header_labels_on_cols && !$pb_remove_header && $row != 0)
            {
                $rows[] = array_combine($rows[0], $data);
            }
            else
            {
                $rows[] = $data;
            }

            $row++;
            if ($pn_limit_num_rows > 0 && $row >= $pn_limit_num_rows)
            {
                break;
            }
        }
        fclose($handle);
    }

    if ($pb_remove_header)
    {
        unset($rows[0]);
    }

    return $rows;
}

function get_chave_parametro($ps_chave, $pa_parametro)
{
    return current(array_filter(array_keys($pa_parametro), function ($vs_chave_parametro) use ($ps_chave)
    {
        return strpos($vs_chave_parametro, $ps_chave);
    }));
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
                                <?php if ($vo_importacao->get_caminho_arquivo_importacao() == "" || count($vo_importacao->get_campos_origem_label()) == 0) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php if ($vo_importacao->get_caminho_arquivo_importacao() == "") : ?>
                                            <p>Erro ao carregar arquivo.</p>
                                        <?php elseif (count($vo_importacao->get_campos_origem_label()) == 0) : ?>
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
                                        <input type="hidden" name="step" value="3">
                                        <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                        <input type="hidden" name="parametros_importacao_json"  value="<?= htmlspecialchars($vs_parametros_importacao_json); ?>">
                                        <input type="hidden" name="caminho_arquivo_importacao" value="<?= $vo_importacao->get_caminho_arquivo_importacao(); ?>">
                                        <h2>Relacionamento de campos</h2>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Coluna de origem</th>
                                                <th>Campo de destino</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($vo_importacao->get_campos_origem_label() as $vn_campo_origem_atual => $vs_campo_origem) : ?>
                                                <input type="hidden" name="campos_origem[<?= $vn_campo_origem_atual ?>]"
                                                       value="<?= $vs_campo_origem; ?>">
                                                <tr>
                                                    <td><?= $vs_campo_origem; ?></td>
                                                    <td>
                                                        <label>
                                                            <select class="form-control"
                                                                    name="campos_destino_selecao[<?= $vn_campo_origem_atual; ?>]">
                                                                <option value="">Selecione</option>
                                                                <?php foreach ($vo_importacao->get_campos_edicao() as $vs_campo_destino_nome => $va_campo_destino_atributos) : ?>
                                                                    <option value="<?= $vs_campo_destino_nome ?>"
                                                                        <?= strpos(strtolower($va_campo_destino_atributos["label"]), strtolower($vs_campo_origem)) !== false ? "selected" : ""; ?> >
                                                                        <?= $va_campo_destino_atributos["label"]; ?>
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
                                                <button id="continuar-step-2" class="btn btn-primary btn-importar"
                                                        type="submit">
                                                    Continuar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                            <?php elseif ($vn_step == 3) : ?>
                                <form method="post" enctype="multipart/form-data" action="importar.php" class="p-4">
                                    <h2>Definição de variáveis de importação</h2>
                                    <input type="hidden" name="step" value="4">
                                    <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                    <input type="hidden" name="parametros_importacao_json"  value="<?= htmlspecialchars($vs_parametros_importacao_json); ?>">
                                    <input type="hidden" name="campos_destino_selecao_json" value="<?= htmlspecialchars($vs_campos_destino_selecao_json); ?>">
                                    <input type="hidden" name="caminho_arquivo_importacao" value="<?= $vo_importacao->get_caminho_arquivo_importacao(); ?>">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Coluna de origem</th>
                                            <th>Campo de destino selecionado</th>
                                            <th>Valor padrão</th>
                                            <th>Criar novos itens relacionados?</th>
                                            <th>Tipo de relação padrão</th>
                                            <th>Separador de valores</th>
                                            <th>Separador de subcampos</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($vo_importacao->get_campos_destino_selecao() as $vn_index_campo_destino => $va_campo_destino): ?>
                                            <?php
                                            $vs_label_campo_origem_atual = $vo_importacao->get_campo_origem_label($vn_index_campo_destino);
                                            $vs_label_campo_destino_atual = $vo_importacao->get_campo_destino_selecao($vn_index_campo_destino);
                                            $va_campo_destino_atual = $vo_importacao->get_campo_edicao($vs_label_campo_destino_atual);
                                            ?>
                                            <tr>
                                                <td><?= $vs_label_campo_origem_atual ?></td>
                                                <td><?= $va_campo_destino_atual["label"] ?>
                                                    (<?= $vs_label_campo_destino_atual ?>)
                                                </td>
                                                <td>
                                                    <?php

                                                    $vs_tipo_input = $va_campo_destino_atual[0];

                                                    if ((in_array($vs_tipo_input, ["html_combo_input", "html_autocomplete"])))
                                                    {
                                                        $vo_objeto_relacionamento = new $va_campo_destino_atual["objeto"];
                                                    }
                                                    if (isset($vo_objeto_relacionamento) && ($vo_objeto_relacionamento->ler_numero_registros([""])) < 100): ?>
                                                        <?php
                                                        $va_campo_importacao_lista_controlada = $vo_objeto_relacionamento->ler_lista();
                                                        ?>
                                                        <label>
                                                            <select class="form-control"
                                                                    name="campos_valor_padrao[<?= $vn_index_campo_destino ?>]">
                                                                <option value="">Selecione</option>
                                                                <?php foreach ($va_campo_importacao_lista_controlada as $vn_index_registro => $va_dado_registro): ?>
                                                                    <?php
                                                                    $vs_dado_nome = get_chave_parametro("nome", $va_dado_registro);
                                                                    $vs_dado_codigo = get_chave_parametro("codigo", $va_dado_registro);
                                                                    ?>
                                                                    <option value="<?= $va_dado_registro[$vs_dado_codigo] ?>">
                                                                        <?= $va_dado_registro[$vs_dado_nome] ?>
                                                                    </option>
                                                                    <?php unset($vo_objeto_relacionamento); endforeach; ?>
                                                            </select>
                                                        </label>
                                                    <?php else: ?>
                                                        <label>
                                                            <input type="text"
                                                                   name="campos_valor_padrao[<?= $vn_index_campo_destino ?>]"
                                                                   class="form-control form-control-sm">
                                                        </label>
                                                    <?php endif; ?>
                                                </td>
                                                <td>

                                                    <label>
<!--                                                        TODO: Eventualmente usar a funcao get_campo_tem_relacionamento para que a checkbox também seja mostrada em  campos de multi input (campos que tem subcampos)-->
<!--                                                        Abaixo também se checa por relacionamento, mas não na profundidade da funcao citada acima -->
                                                        <?php if (strpos($vs_label_campo_destino_atual, "_codigo")) : ?>
                                                            <input type="checkbox" class="check-selecao"
                                                                   name="campos_criar_itens_relacionados[<?= $vn_index_campo_destino ?>]"/>
                                                        <?php else : ?>
                                                        <?php endif; ?>
                                                    </label>
                                                </td>
                                                <td>
                                                    <!-- Tipo de relacao padrao -->
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               name="campos_separador[<?= $vn_index_campo_destino ?>]"
                                                               class="form-control form-control-sm">
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php if ($va_campo_destino_atual[0] === "html_multi_itens_input"): ?>
                                                        <label>
                                                            <input type="text"
                                                                   name="campos_subcampos_separador[<?= $vn_index_campo_destino ?>]"
                                                                   class="form-control form-control-sm">
                                                        </label>
                                                    <?php endif; ?>
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
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Modo importação</th>
                                            <th>Objeto importação</th>
                                            <th>Duração</th>
                                            <th>Debug ativo?</th>
                                            <th>Tolerância de erros?</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <?= $va_resultado_importacao["modo_import"]; ?>
                                            </td>
                                            <td>
                                                <?= $va_resultado_importacao["objeto_importado"]; ?>
                                            </td>
                                            <td>
                                                <?= $va_resultado_importacao["duracao"]; ?>
                                            </td>
                                            <td>
                                                <?= isset($va_resultado_importacao["debug"]) ? ($va_resultado_importacao["debug"] ? 'Ativado' : 'Desativado') : ''; ?>
                                            </td>
                                            <td>
                                                <?= isset($va_resultado_importacao["tolerancia_erros"]) ? ($va_resultado_importacao["tolerancia_erros"] ? 'Ativado' : 'Desativada') : ''; ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Tipo de operação</th>
                                            <th>Resultado</th>
                                            <th>Acesso</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($va_resultado_importacao["operacoes"] as $va_operacao => $va_dados_operacao) :
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $va_operacao + 1 ?>
                                                </td>
                                                <td>
                                                    <?= $va_dados_operacao["tipo_operacao"] ?>
                                                </td>
                                                <td>
                                                    <?= $va_dados_operacao["resultado"] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo isset($va_dados_operacao["codigo_registro"]) ?
                                                        '<a href="editar.php?obj=' . $va_resultado_importacao["objeto_importado"] . '&cod=' . $va_dados_operacao["codigo_registro"] . '">' . $va_dados_operacao["codigo_registro"] . '</a>' :
                                                        $va_dados_operacao["mensagens"][0];
                                                    ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
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