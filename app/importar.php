<?php
global $vs_recurso_sistema_nome_plural, $vs_id_objeto_importacao, $va_usuario;
require_once dirname(__FILE__) . "/components/entry_point.php";
$vs_id_objeto_importacao = $_POST["obj"] ?? $_GET["obj"] ?? null;
$vs_caminho_arquivo = $_POST["caminho_arquivo"] ?? null;
$vb_montar_menu = true;
$vn_step = $_POST["step"] ?? 1;
$va_parametros_importacao = ($vn_step) > 2 ? json_decode($_POST["parametros_importacao"], true) : $_POST["parametros_importacao"] ?? null;


class LogImportacao
{
    private $inicio;
    private $fim;
    private $duracao;
    private $id_objeto_importacao;
    private $operacoes;
    private $timezone;
    private $modo_importacao;

    private $debug;
    private $tolerancia_erros;

    public function __construct($ps_id_objeto_importacao, $ps_modo_importacao, $pb_debug, $pb_tolerancia_erros)
    {
        $this->timezone = new DateTimeZone('America/Sao_Paulo');
        $this->inicio = new DateTime('now', $this->timezone);
        $this->id_objeto_importacao = $ps_id_objeto_importacao;
        $this->modo_importacao = $ps_modo_importacao;
        $this->debug = $pb_debug;
        $this->tolerancia_erros = $pb_tolerancia_erros;
        $this->operacoes = array();

    }

    public function adicionar_operacao($ps_resultado, $ps_mensagem, $ps_tipo_operacao, $ps_codigo_registro = null): void
    {
        $this->operacoes[] = [
            "resultado" => $ps_resultado, "mensagem" => $ps_mensagem, "tipo_operacao" => $ps_tipo_operacao, "codigo_registro" => $ps_codigo_registro
        ];
    }

    public function finalizar_relatorio(): array
    {
        $this->fim = new DateTime('now', $this->timezone);
        $this->duracao = $this->fim->diff($this->inicio);

        return [
            "operacoes" => $this->operacoes,
            "modo_import" => $this->modo_importacao,
            "duracao" => $this->duracao->format('%i minutos, %s segundos'),
            "debug" => $this->debug,
            "tolerancia_erros" => $this->tolerancia_erros,

        ];
    }

}

switch ($vn_step)
{
    case 1:
        break;
    case 2:
        $vs_caminho_arquivo = move_import_file();
        $va_rows = get_header_file($vs_caminho_arquivo, pathinfo($vs_caminho_arquivo, PATHINFO_EXTENSION));
        $va_campos_edicao = get_campos_edicao($vs_id_objeto_importacao);
        break;
    case 3:
        $va_campos_edicao = json_decode($_POST["campos_edicao"], true);
        $va_campos_destino_selecao = $_POST["campos_destino_selecao"];
        $va_campos_origem = $_POST["campos_origem"];
        $va_ponteiros_relacionamento = relacionar_dados_input($va_campos_destino_selecao, $va_campos_origem, $va_campos_edicao);
        break;
    case 4:
        $va_ponteiros_relacionamento = json_decode($_POST["ponteiros_relacionamento"], true);

        $va_campos_valor_padrao = $_POST["campos_valor_padrao"] + ["tipo_variante" => "campo_valor_padrao"] ?? [];
        $va_campos_separador = $_POST["campos_separador"] + ["tipo_variante" => "campo_separador"] ?? [];
        $va_campos_subcampos_separador = $_POST["campos_subcampos_separador"] ?? [];
        $va_campos_criar_itens_relacionados = $_POST["campos_criar_itens_relacionados"] ?? [];

        $va_header_import = montar_header_importacao($va_ponteiros_relacionamento, [$va_campos_valor_padrao, $va_campos_separador, $va_campos_subcampos_separador, $va_campos_criar_itens_relacionados], $vs_id_objeto_importacao, $va_parametros_importacao);

        $va_data_csv = get_data_csv($vs_caminho_arquivo, ',', 0, true, false);
        $va_resultado_importacao = process_import($va_header_import, $va_data_csv);
        break;
}

function get_codigo_objeto_from_nome($ps_id_objeto_busca, $ps_valor_busca, $ps_atributo_busca, $ps_atributo_retorno)
{
    $vo_objeto_de_busca = new $ps_id_objeto_busca;
    $va_parametro_busca[$ps_atributo_busca] = [$ps_valor_busca];
    $va_retorno_busca = $vo_objeto_de_busca->ler_lista($va_parametro_busca);
    return $va_retorno_busca[0][$ps_atributo_retorno];
}

function validar_parametros_importacao($pa_parametros_importacao)
{
    $pa_parametros_importacao["import_debug"] = isset($pa_parametros_importacao["import_debug"]);
    $pa_parametros_importacao["import_allow_errors"] = isset($pa_parametros_importacao["import_allow_errors"]);

    return $pa_parametros_importacao;
}


function montar_header_importacao($pa_campos_importacao, $pa_variantes_importacao, $ps_id_objeto_importacao, $pa_parametros_importacao): array
{
    $va_header_importacao = [
        "campos" => $pa_campos_importacao,
        "id_objeto" => $ps_id_objeto_importacao,
        "parametros" => validar_parametros_importacao($pa_parametros_importacao),
        "is_item_acervo" => is_item_acervo($pa_campos_importacao)
    ];

    // Variante de importacao = valores padrão de campo, separador de campo, separador de subcampo e demais valores atribuídos no step 3
    foreach ($pa_variantes_importacao as $va_variante_importacao)
    {
        foreach ($va_variante_importacao as $vn_index_variante_importacao => $va_dado_variante_importacao)
        {
            if ($vn_index_variante_importacao != "tipo_variante")
            {
                $va_header_importacao["campos"][$vn_index_variante_importacao][$va_variante_importacao["tipo_variante"]] = $va_dado_variante_importacao;

            }
        }
    }

    return $va_header_importacao;
}


function relacionar_dados_input($pa_destino, $pa_origem, $pa_campos_edicao): array
{
    $dados_input = [];
    foreach ($pa_destino as $index => $campo_destino)
    {
        if (array_key_exists($campo_destino, $pa_campos_edicao))
        {
            $dados_input[$index] = [
                "campo_origem" => $pa_origem[$index],
                "campo_destino" => $campo_destino,
                "campo_destino_parametros" => $pa_campos_edicao[$campo_destino],
            ];
        }
    }
    return $dados_input;
}

function get_header_file($ps_caminho_arquivo, $ps_extensao): array
{
    $va_rows = array();

    if ($ps_extensao == "csv")
    {
        $va_rows = get_data_csv($ps_caminho_arquivo, ',', 1);
    } else
    {
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

function get_campos_edicao($ps_id_objeto_tela)
{
    $vo_objeto = new $ps_id_objeto_tela();
    return $vo_objeto->inicializar_campos_edicao();
}

function get_data_csv($ps_file_path, $ps_delimiter = ",", $pn_limit_num_rows = 0, $pb_remove_header = false, $pb_assign_header_labels_on_cols = false): array
{
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
            } else
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

function get_parametros_obrigatorios($pa_parametros)
{
    return array_filter($pa_parametros, function ($parametro)
    {
        return $parametro["atributo_obrigatorio"];
    });
}

function get_parametros_obrigatorios_faltantes($pa_parametros_obrigatorios, $pa_parametros_fornecidos_importacao)
{
    // TODO: retornar map apenas com parametros obrigatorios não presentes no header base (?)
}

function get_chave_parametro($ps_chave, $pa_parametro)
{
    return current(array_filter(array_keys($pa_parametro), function ($vs_chave_parametro) use ($ps_chave)
    {
        return strpos($vs_chave_parametro, $ps_chave);
    }));
}

function get_identifier_parameters_from_import_header($pa_import_header, $ps_attribute): array
{
    // checando se campo de destino existe no header de importacao
    foreach ($pa_import_header as $vn_header_column => $va_header_column_data)
    {
        if (str_contains($va_header_column_data["campo_destino"], $ps_attribute))
        {
            return ["destino" => $va_header_column_data["campo_destino"], "origem" => $va_header_column_data["campo_origem"], "posicao" => $vn_header_column]; // !!
        }
    }
    return [];
}

function get_parametros_identificacao($pa_campos_importacao, $ps_atributo, $pb_is_item_acervo): array
{
    if ($pb_is_item_acervo)
    {
        foreach ($pa_campos_importacao as $vn_campo_importacao => $va_dados_campo_importacao)
        {
            if (str_contains($va_dados_campo_importacao["campo_destino"], $ps_atributo))
            {
                return ["campo_destino" => $va_dados_campo_importacao["campo_destino"], "campo_origem" => $va_dados_campo_importacao["campo_origem"], "posicao" => $vn_campo_importacao]; // !!

            }
        }
    }
    return [];
}

function is_item_acervo($pa_import_header): bool
{
    foreach ($pa_import_header as $vn_header_column => $va_header_column_data)
    {
        if (str_contains($va_header_column_data["campo_destino"], "texto"))
        {
            return true;
        }
    }
    return false;

}

function get_existencia_registro($po_objeto_busca, $ps_valor_busca_registro, $pa_parametros_identificacao_registro, $pb_is_item_acervo)
{
    $po_objeto_busca->inicializar_campos_importacao();
    $va_campos_importacao = $po_objeto_busca->get_campos_importacao();
    $va_resultado_busca = array();
    if ($pb_is_item_acervo)
    {
        $vs_campo_identificador_registro = $va_campos_importacao["identificador_registro"][0];
        $va_parametros_busca_registro[$vs_campo_identificador_registro] = [
            $ps_valor_busca_registro
        ];
        $va_resultado_busca = $po_objeto_busca->ler_lista($va_parametros_busca_registro);
    }

    return $va_resultado_busca;
}

function get_campo_tem_relacionamento($ps_atributo_destino): bool
{
    return strpos($ps_atributo_destino, "_codigo");

}

function process_import($pa_header_importacao, $pa_dados_importacao): array
{
    global $va_parametros_importacao, $va_usuario, $vn_usuario_logado_instituicao_codigo;
    $vs_usuario_codigo = $va_usuario["usuario_codigo"];

    // Um header de importacao deve ter os campos que serão importados, o objeto de importacao e os parametros de importacao
    // A funcao, por consequencia recebe esse header e os dados que serão processados com base no header
    $vo_objeto_de_importacao = new $pa_header_importacao["id_objeto"];
    $vo_relatorio_importacao = new LogImportacao(
        $pa_header_importacao["id_objeto"],
        $pa_header_importacao["parametros"]["import_mode"],
        $pa_header_importacao["parametros"]["import_debug"],
        $pa_header_importacao["parametros"]["import_allow_errors"]
    );
    foreach ($pa_dados_importacao as $dados_row_importacao)
    {
        $va_dados_row_insercao = array();
        if (in_array($pa_header_importacao["parametros"]["import_mode"], ["upsert", "update", "create"]))
        {
            if ($pa_header_importacao["is_item_acervo"])
            {
                $va_parametros_identificacao_registro = get_parametros_identificacao(
                    $pa_header_importacao["campos"],
                    "identificador",
                    $pa_header_importacao["is_item_acervo"]);

                if (!empty($va_parametros_identificacao_registro))
                {
                    $va_resultado_busca_registro = get_existencia_registro(
                        $vo_objeto_de_importacao,
                        $dados_row_importacao[$va_parametros_identificacao_registro["posicao"]],
                        $va_parametros_identificacao_registro, $pa_header_importacao["is_item_acervo"]);
                    if (empty($va_resultado_busca_registro))
                    {
                        if ($pa_header_importacao["parametros"]["import_mode"] == "update")
                        {
                            $vo_relatorio_importacao->adicionar_operacao("Negativo", "Objeto não encontrado em operação de atualização.", "Atualização");
                            continue;
                        }

                    } elseif ($pa_header_importacao["parametros"]["import_mode"] == "create" && !$pa_header_importacao["parametros"]["import_allow_+errors"])
                    {
                        $vo_relatorio_importacao->adicionar_operacao("Negativo", "Objeto já existente em operação de criação sem tolerância de erros.", "Criação");
                        continue;
                    } else
                    {
                        $va_dados_row_insercao[$pa_header_importacao["id_objeto"] . "_codigo"] = $va_resultado_busca_registro[0][$pa_header_importacao["id_objeto"] . "_codigo"];
                    }
                } elseif ($pa_header_importacao["parametros"]["import_mode"] == "update")
                {
                    $vo_relatorio_importacao->adicionar_operacao("Negativo", "Objeto sem identificador em operação de atualização.", "Atualização");
                    continue;
                }
            }
            // Tratamento de itens de lista controlada: checagem por nome e não  por código

        }
        foreach ($dados_row_importacao as $vn_col_importacao => $vs_dado_col_importacao)
        {
            if (array_key_exists($vn_col_importacao, $pa_header_importacao["campos"]))
            {
                $va_campo_atual = $pa_header_importacao["campos"][$vn_col_importacao];
                $vs_chave_campo_destino = $va_campo_atual["campo_destino"];
                $va_dados_row_insercao[$pa_header_importacao["campos"][$vn_col_importacao]["campo_destino"]] = $vs_dado_col_importacao;

                if (get_campo_tem_relacionamento($vs_chave_campo_destino))
                {
                    // Exportacao traz o NOME, precisamos do CÓDIGO
                    $vs_objeto_campo_busca = $va_campo_atual["campo_destino_parametros"]["objeto"];
                    $vs_valor_busca = $vs_dado_col_importacao;
                    $vs_atributo_busca = $va_campo_atual["campo_destino_parametros"]["atributos"][1];
                    $vs_atributo_retorno = $va_campo_atual["campo_destino_parametros"]["atributos"][0];

                    $vs_codigo_atributo = get_codigo_objeto_from_nome($vs_objeto_campo_busca, $vs_valor_busca, $vs_atributo_busca, $vs_atributo_retorno);
                    if (empty($vs_codigo_atributo))
                    {
                        if ($pa_header_importacao["parametros"]["import_allow_errors"])
                        {
                            unset($pa_header_importacao["campos"][$vn_col_importacao]);
                            unset($dados_row_importacao[$vn_col_importacao]);
                            // Adicionar sublog aqui avisando que na operacao X o valor Y foi desconsiderado por motivo Z
                        }
                        continue;
                    }
                    $vs_dado_col_importacao = $vs_codigo_atributo;
                }
                // Feeding da array de insercao atual
                $va_dados_row_insercao[$vs_chave_campo_destino] = $vs_dado_col_importacao;

                if (!isset($va_dados_row_insercao["item_acervo_identificador"]) && $pa_header_importacao["is_item_acervo"])
                {
                    $va_dados_row_insercao["item_acervo_identificador"] = "";
                }

                $va_dados_row_insercao["instituicao_codigo"] = $vn_usuario_logado_instituicao_codigo;

            }
        }
        if (isset($va_parametros_importacao["import_debug"]))
        {
            $vo_relatorio_importacao->adicionar_operacao("Positivo", "Objeto debugado.", "Debug");

        } else
        {
            $va_dados_row_insercao["usuario_logado_codigo"] = $vs_usuario_codigo;

            $vo_objeto_de_importacao->iniciar_transacao();
            $vo_relatorio_importacao->adicionar_operacao("Positivo", "Objeto manipulado com sucesso. ", "Main", $vo_objeto_de_importacao->salvar($va_dados_row_insercao));
            $vo_objeto_de_importacao->finalizar_transacao();
        }
    }

    return $vo_relatorio_importacao->finalizar_relatorio();

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
                                        <input type="hidden" name="parametros_importacao"
                                               value="<?= htmlentities(json_encode($va_parametros_importacao), ENT_QUOTES, "UTF-8", false); ?>">
                                        <input type="hidden" name="campos_edicao"
                                               value="<?= htmlentities(json_encode($va_campos_edicao), ENT_QUOTES, "UTF-8", false) ?>">
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
                                                <input type="hidden" name="campos_origem[<?= $vn_index ?>]"
                                                       value="<?= $vs_header; ?>">
                                                <tr>
                                                    <td><?= $vs_header; ?></td>
                                                    <td>
                                                        <label>
                                                            <select class="form-control"
                                                                    name="campos_destino_selecao[<?= $vn_index; ?>]">
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
                                    <input type="hidden" name="obj" value="<?= $vs_id_objeto_importacao; ?>">
                                    <input type="hidden" name="step" value="4">
                                    <input type="hidden" name="caminho_arquivo" value="<?= $vs_caminho_arquivo ?>">
                                    <input type="hidden" name="parametros_importacao"
                                           value="<?= htmlentities(json_encode($va_parametros_importacao), ENT_QUOTES, "UTF-8", false); ?>">
                                    <input type="hidden" name="ponteiros_relacionamento"
                                           value='<?= htmlentities(json_encode($va_ponteiros_relacionamento), ENT_QUOTES, "UTF-8", false) ?>'>
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
                                        <?php foreach ($va_ponteiros_relacionamento as $vn_index_ponteiro_relacionamento => $va_relacionamento) : ?>
                                            <tr>
                                                <td><?= $va_relacionamento["campo_origem"] ?></td>
                                                <td><?= $va_relacionamento["campo_destino_parametros"]["label"] ?>
                                                    (<?= $va_relacionamento["campo_destino"] ?>)
                                                </td>
                                                <td>
                                                    <?php
                                                    $vs_tipo_input = $va_relacionamento["campo_destino_parametros"][0];
                                                    if ((in_array($vs_tipo_input, ["html_combo_input", "html_autocomplete"])))
                                                    {
                                                        $vo_objeto_relacionamento = new $va_relacionamento["campo_destino_parametros"]["objeto"];
                                                    }
                                                    if (isset($vo_objeto_relacionamento) && ($vo_objeto_relacionamento->ler_numero_registros([""])) < 100): ?>
                                                        <?php
                                                        $va_relacionamento_lista_controlada = $vo_objeto_relacionamento->ler_lista();
                                                        ?>
                                                        <label>
                                                            <select class="form-control"
                                                                    name="campos_valor_padrao[<?= $vn_index_ponteiro_relacionamento ?>]">
                                                                <option value="">Selecione</option>
                                                                <?php foreach ($va_relacionamento_lista_controlada as $vn_index_registro => $va_dado_registro): ?>
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
                                                                   name="campos_valor_padrao[<?= $vn_index_ponteiro_relacionamento ?>]"
                                                                   class="form-control form-control-sm">
                                                        </label>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="checkbox" class="check-selecao"
                                                               name="campos_criar_itens_relacionados[<?= $vn_index_ponteiro_relacionamento ?>]"
                                                    </label>
                                                </td>
                                                <td>
                                                    <!--                                                      Tipo de relacao padrao -->
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="text"
                                                               name="campos_separador[<?= $vn_index_ponteiro_relacionamento ?>]"
                                                               class="form-control form-control-sm">
                                                    </label>
                                                </td>
                                                <td>
                                                    <?php if ($va_relacionamento["campo_destino_parametros"][0] === "html_multi_itens_input"): ?>
                                                        <label>
                                                            <input type="text"
                                                                   name="campos_subcampos_separador[<?= $vn_index_ponteiro_relacionamento ?>]"
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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <?= $va_resultado_importacao["modo_importacao"]; ?>
                                            </td>
                                            <td>
                                                <?= $va_resultado_importacao["objeto_importacao"]; ?>
                                            </td>
                                            <td>
                                                <?= $va_resultado_importacao["duracao_string"]; ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Numero</th>
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
                                                    <?= $va_dados_operacao["placeholder"] ?? $va_dados_operacao["result"] ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo isset($va_dados_operacao["codigo_objeto"]) ?
                                                        '<a href="editar.php?obj=' . $vs_id_objeto_importacao . '&cod=' . $va_dados_operacao["codigo_objeto"] . '">' . $va_dados_operacao["codigo_objeto"] . '</a>' :
                                                        '';
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