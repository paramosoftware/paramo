<?php
global $vs_recurso_sistema_nome_plural, $vs_id_objeto_importacao, $va_usuario;
require_once dirname(__FILE__) . "/components/entry_point.php";
$vs_caminho_arquivo = $_POST["caminho_arquivo"] ?? null;
$vb_montar_menu = true;
$vn_step = $_POST["step"] ?? 1;

if (isset($_SESSION["importacao"]) && $_SESSION["importacao"]) {
    $vo_importacao = &$_SESSION["importacao"];
}

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
            "resultado" => $ps_resultado, "mensagens" => [$ps_mensagem], "tipo_operacao" => $ps_tipo_operacao, "codigo_registro" => $ps_codigo_registro
        ];
    }

    public function complementar_operacao_atual($ps_mensagem): void
    {
        $this->operacoes[array_key_last($this->operacoes)]["mensagens"][] = $ps_mensagem;
    }

    public function finalizar_relatorio(): array
    {
        $this->fim = new DateTime('now', $this->timezone);
        $this->duracao = $this->fim->diff($this->inicio);

        return [
            "objeto_importado" => $this->id_objeto_importacao,
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
        $vs_id_objeto_importacao = $_GET["obj"];

        if ($vo_importacao) {
           unset($_SESSION["importacao"]);
        }
        $_SESSION["importacao"] = new importacao_refatorado($vs_id_objeto_importacao);

        break;
    case 2:
       $vo_importacao->set_caminho_arquivo_importacao(move_import_file());
       $vo_importacao->set_campos_origem_label(get_header_file($_SESSION["importacao"]->get_caminho_arquivo_importacao(), pathinfo($_SESSION["importacao"]->get_caminho_arquivo_importacao(), PATHINFO_EXTENSION))[0]);
       $vo_importacao->inicializar_campos_edicao_objeto_importacao();

       $vo_importacao->set_modo_importacao($_POST["parametros_importacao"]["import_mode"]);
       $vo_importacao->set_separador_hierarquia($_POST["parametros_importacao"]["import_separator_hierarchy"]);

       $vo_importacao->set_debug(isset($_POST["parametros_importacao"]["import_debug"]));
       $vo_importacao->set_tolerancia_erros(isset($_POST["parametros_importacao"]["import_allow_errors"]));


        break;
    case 3:
        $vo_importacao->set_campos_destino_selecao($_POST["campos_destino_selecao"]);

        break;
    case 4:
        $vo_importacao->inicializar_variantes_campos_importacao(
            isset($_POST["campos_valor_padrao"]) ? $_POST["campos_valor_padrao"] : [],
            isset($_POST["campos_criar_itens_relacionados"]) ? $_POST["campos_criar_itens_relacionados"] : [],
            isset($_POST["campos_separador"]) ? $_POST["campos_separador"] : [],
            isset($_POST["campos_subcampos_separador"]) ? $_POST["campos_subcampos_separador"] : [],

        );

        $vo_importacao->set_dados_origem(get_data_csv($vo_importacao->get_caminho_arquivo_importacao(), ',', 0, true, false));
        $va_resultado_importacao = [];
        break;
}

function get_codigo_objeto_from_nome($ps_id_objeto_busca, $ps_atributo_busca, $ps_atributo_retorno, $ps_valor_busca)
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


function montar_header_importacao($pa_campos_importacao, $po_objeto_importacao, $pa_parametros_importacao, $pa_variantes_importacao): array
{
    $va_header_importacao = [
        "campos" => $pa_campos_importacao,
        "objeto_importacao" => $po_objeto_importacao,
        "is_item_acervo" => is_item_acervo(get_class($po_objeto_importacao)),
        "parametros" => validar_parametros_importacao($pa_parametros_importacao),
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
    $va_header_importacao["campos_relacionamento"] = $va_header_importacao["objeto_importacao"]->inicializar_relacionamentos();

    // Livro, por exemplo, não contem todos os campos de relacionamento pois alguns destes são declarados previamente no pai
    // Aqui tratamos de popular todos os campos de relacionamento possíveis ( para futuras comparacoes na funcao de import )
    $vs_id_objeto_pai = $va_header_importacao["objeto_importacao"]->get_objeto_pai();
    $vo_objeto_pai = new $vs_id_objeto_pai;
    $va_header_importacao["campos_relacionamento"] = array_merge($va_header_importacao["campos_relacionamento"], $vo_objeto_pai->inicializar_relacionamentos());
    return $va_header_importacao;
}


function relacionar_dados_input($pa_destino, $pa_origem, $po_objeto_relacionamento): array
{
    $dados_input = [];

    foreach ($pa_destino as $index => $campo_destino)
    {
        if (array_key_exists($campo_destino, $po_objeto_relacionamento->get_campos_edicao()))
        {
            $dados_input[$index] = [
                "campo_origem" => $pa_origem[$index],
                "campo_destino" => $campo_destino,
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

function is_item_acervo($ps_id_objeto = null, $ps_campo_objeto = null)
{
    $vs_id_objeto = $ps_id_objeto;

    if ($ps_campo_objeto) {
        if (isset($ps_campo_objeto["objeto"])) {
            $vs_id_objeto = $ps_campo_objeto["objeto"];
        } elseif(get_campo_tem_subcampo($ps_campo_objeto)) {
            $vs_id_objeto = $ps_campo_objeto["subcampos"][array_key_first($ps_campo_objeto["subcampos"])]["objeto"];
        }
    }

    return $vs_id_objeto ? is_subclass_of(new $vs_id_objeto, "texto") : $vs_id_objeto;
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

function get_campo_tem_relacionamento($ps_atributo_destino, $pa_campos_relacionamento): bool
{
    return array_key_exists($ps_atributo_destino, $pa_campos_relacionamento);

}
function get_campo_tem_dependencia($ps_campo): bool
{
    return isset($ps_campo["dependencia"]);
}
function get_campo_tem_subcampo($pa_campo)
{
    return (isset($pa_campo["subcampos"]));

}
function get_propriedades_busca_objeto($pa_campo_destino)
{
    return [
        "objeto" => $pa_campo_destino["objeto"],
        "atributo_busca" => $pa_campo_destino["atributos"][1],
        "atributo_retorno" => $pa_campo_destino["atributos"][0]
    ];
}

function processar_import($pa_header_importacao, $pa_dados_importacao): array
{
    // Um header de importacao deve ter os campos que serão importados, o objeto de importacao e os parametros de importacao
    // A funcao, por consequencia recebe esse header e os dados que serão processados com base no header

    global $va_parametros_importacao, $va_usuario, $vn_usuario_logado_instituicao_codigo;
    $vs_usuario_codigo = $va_usuario["usuario_codigo"];
    $va_campos_edicao = $pa_header_importacao["objeto_importacao"]->get_campos_edicao();

    $vo_relatorio_importacao = new LogImportacao(
        get_class($pa_header_importacao["objeto_importacao"]),
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
                // TODO: Remover hardcoding aqui. Snippet de desenvolvimento
                $va_dados_row_insercao["texto_publicado_online"] = "1";
                $va_dados_row_insercao["texto_publicado_online_chk"] = "1";
                $va_dados_row_insercao["item_acervo_acervo_codigo"] = "1";
                //

                $va_parametros_identificacao_registro = get_parametros_identificacao(
                    $pa_header_importacao["campos"],
                    "identificador",
                    $pa_header_importacao["is_item_acervo"]);

                if (!empty($va_parametros_identificacao_registro))
                {
                    $va_resultado_busca_registro = get_existencia_registro(
                        $pa_header_importacao["objeto_importacao"],
                        $dados_row_importacao[$va_parametros_identificacao_registro["posicao"]],
                        $va_parametros_identificacao_registro, $pa_header_importacao["is_item_acervo"]);
                    if (empty($va_resultado_busca_registro))
                    {
                        if ($pa_header_importacao["parametros"]["import_mode"] == "update")
                        {
                            $vo_relatorio_importacao->adicionar_operacao("Negativo", "Objeto não encontrado em operação de atualização.", "Atualização");
                            continue;
                        }

                    } elseif ($pa_header_importacao["parametros"]["import_mode"] == "create")
                    {
                        if (!$pa_header_importacao["parametros"]["import_allow_errors"])
                        {
                            $vo_relatorio_importacao->adicionar_operacao("Negativo", "Objeto já existente em operação de criação sem tolerância de erros.", "Criação");
                            continue;
                        }
                        unset($pa_header_importacao["campos"][$va_parametros_identificacao_registro["posicao"]]);
                        unset($dados_row_importacao[$va_parametros_identificacao_registro["posicao"]]);
                    } else
                    {
                        $va_dados_row_insercao[get_class($pa_header_importacao["objeto_importacao"]) . "_codigo"] = $va_resultado_busca_registro[0][get_class($pa_header_importacao["objeto_importacao"]) . "_codigo"];
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
                $vs_id_campo_destino_atual = $pa_header_importacao["campos"][$vn_col_importacao]["campo_destino"];

                $va_campo_destino_atual = $va_campos_edicao[$vs_id_campo_destino_atual];
                $va_campo_importacao_atual = $pa_header_importacao["campos"][$vn_col_importacao];
                $vs_chave_campo_destino = $va_campo_importacao_atual["campo_destino"];

                $va_dados_row_insercao[$pa_header_importacao["campos"][$vn_col_importacao]["campo_destino"]] = $vs_dado_col_importacao;

                if (get_campo_tem_relacionamento($vs_chave_campo_destino, $pa_header_importacao["campos_relacionamento"]) || get_campo_tem_dependencia($va_campo_destino_atual))
                {
                    if (!is_item_acervo(null, $va_campo_destino_atual)) {
                        // Se não é item de acervo, é item de lista controlada, logo precisamos do primeiro subcampo
                        if (!isset($va_campo_destino_atual["objeto"]) && get_campo_tem_subcampo($va_campo_destino_atual)) {
                            $vs_chave_primeiro_subcampo_destino_atual = array_key_first($va_campo_destino_atual["subcampos"]);
                            $va_campo_destino_atual = $va_campo_destino_atual["subcampos"][$vs_chave_primeiro_subcampo_destino_atual];

                        }

                    }
                    // Exportacao traz o NOME, precisamos do CÓDIGO, seja de IDENTIFICAÇAO (item de acervo) ou CODIGO INTERNO (listas controladas)
                    $va_propriedades_busca = get_propriedades_busca_objeto($va_campo_destino_atual);
                    $vs_valor_busca = $vs_dado_col_importacao;
                    $vs_codigo_atributo = get_codigo_objeto_from_nome(
                            $va_propriedades_busca["objeto"],
                            $va_propriedades_busca["atributo_busca"],
                            $va_propriedades_busca["atributo_retorno"],
                            $vs_valor_busca);

                    if (empty($vs_codigo_atributo))
                    {
                        if ($pa_header_importacao["parametros"]["import_allow_errors"])
                        {
                            unset($pa_header_importacao["campos"][$vn_col_importacao]);
                            unset($dados_row_importacao[$vn_col_importacao]);
                            $vo_relatorio_importacao->complementar_operacao_atual("Dado " . $dados_row_importacao[$vn_col_importacao] . " ignorado nessa operação, pois o objeto não existe.");
                        }
                        continue;
                    }

                }
                // Feeding da array de insercao atual

                //TODO: Converter isso em funcao, e considerar tolerancia de erros. chave de código sem atributo presente = falha
                if (strpos($vs_chave_campo_destino, "_codigo")) {
                    $va_dados_row_insercao[$vs_chave_campo_destino] = $vs_codigo_atributo;

                } else {
                    $va_dados_row_insercao[$vs_chave_campo_destino] = $vs_dado_col_importacao;
                }


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

            $pa_header_importacao["objeto_importacao"]->iniciar_transacao();
            $vo_relatorio_importacao->adicionar_operacao(
                "Positivo",
                "Objeto manipulado com sucesso. ",
                "Main", // TODO: Definir com mais especificidade
                $pa_header_importacao["objeto_importacao"]->salvar($va_dados_row_insercao));
            $pa_header_importacao["objeto_importacao"]->finalizar_transacao();
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
                                    <input type="hidden" name="obj" value="<?= $_GET["obj"]; ?>">
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