<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";

$vs_file_name = "relatorio-" . date("Y-m-d-H-i-s") . ".pdf";
utils::callback_progress($vs_file_name, 0);
require_once dirname(__FILE__) . "/../components/terminar_requisicao.php";

$vs_relatorio = $_POST["relatorio"] ?? "";
$vs_data_inicial = $_POST["data_inicial"] ?? "";
$vs_data_final = $_POST["data_final"] ?? "";
$vs_tipo_operacao = $_POST["tipo_operacao"] ?? "";
$ps_agrupador = $_POST["agrupador"] ?? "";
$vn_setor_sistema_codigo = $_POST["setor_sistema_codigo"] ?? "";
$vn_campo_sistema_codigo = $_POST["campo_sistema_codigo"] ?? "";
$vn_ordenacao_relatorio = $_POST["ordenacao_relatorio"] ?? 1;
$vn_ordenacao_relatorio_catalogacao = $_POST["ordenacao_relatorio_catalogacao"] ?? 1;
$vb_incluir_porcentagem = $_POST["incluir_porcentagem"] ?? false;
$vb_incluir_porcentagem_catalogacao = $_POST["incluir_porcentagem_catalogacao"] ?? false;
$vb_ordenar_por_quantidade = $vn_ordenacao_relatorio == 2;

$va_labels_agrupadores = [
    "dia" => "Dia",
    "mes" => "Mês",
    "ano" => "Ano",
    "usuario" => "Usuário",
];

$va_labels_tipos_operacoes = [
    "1" => "Criação de registros",
    "2" => "Atualização de registros",
];

if (!isset($vs_id_objeto_tela))
{
    utils::callback_progress($vs_file_name, "Não foi possível encontrar o objeto da tela");
    utils::log(
        "Ocorreu um erro ao gerar o relatório.",
        "Não foi possível encontrar o objeto da tela: " . var_export($_POST, true)
    );
    exit();
}

$vs_file_path = config::get(["pasta_media", "temp"]) . $vs_file_name;

$report = null;
try {
    $report = new report($vs_file_path);
} catch (Exception $e) {
    utils::callback_progress($vs_file_name, "Não foi possível criar o relatório");
    utils::log("Ocorreu um erro inicializar o relatório.", $e->getMessage());
}

if ($vs_relatorio == "quantitativo")
{
    list($vs_campo_sistema_id, $vs_objeto_relacionado_campo_identificador, $vs_campo_alias) = get_parametros_relatorio_quantitativo($vn_campo_sistema_codigo);

    $vo_objeto = new $vs_id_objeto_tela;

    $va_parametros_filtros_consulta = array();
    require_once dirname(__FILE__) . "/montar_filtros_busca.php";
    $va_itens_listagem = $vo_objeto->ler_lista_quantitativa($vs_campo_sistema_id, $vs_objeto_relacionado_campo_identificador, $va_parametros_filtros_consulta, $vb_ordenar_por_quantidade);

    $report->vs_title = "Relatório";
    $vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";
    $report->va_subheadings[] = ["label" => "Acervo", "value" => $vs_acervo];
    $report->va_subheadings[] = ["label" => "Agrupado por", "value" => $vs_campo_alias];
    $report->va_subheadings[] = ["label" => "Ordenado por", "value" => ($vb_ordenar_por_quantidade ? "Quantidade" : "Nome")];
    $report->va_table_header = [$vs_campo_alias, "Quantidade"];
    $report->va_itens_keys = [$vs_objeto_relacionado_campo_identificador, "Q"];
    $report->vb_percentage = boolval($vb_incluir_porcentagem);
    $report->vs_num_cols = boolval($vb_incluir_porcentagem) ? '%{70,20,10}' : '%{80,20}';
}
elseif ($vs_relatorio == "estatisticas_catalogacao")
{

    $vo_objeto = new $vs_id_objeto_tela;

    $va_parametros_filtros_consulta = array();
    $va_itens_listagem = $vo_objeto->ler_estatisticas_catalogacao($vs_data_inicial, $vs_data_final, $vs_tipo_operacao, $ps_agrupador, $vb_ordenar_por_quantidade);

    $vs_acervo = $vs_recurso_sistema_nome ?? $vs_recurso_sistema_nome_plural ?? "não informado";
    $report->vs_title = "Relatório de catalogação";
    $report->va_subheadings[] = ["label" => "Acervo", "value" => $vs_acervo];

    if (isset($va_labels_tipos_operacoes[$vs_tipo_operacao]))
    {
        $report->va_subheadings[] = ["label" => "Operação", "value" => $va_labels_tipos_operacoes[$vs_tipo_operacao]];
    }

    $report->va_table_header = [$va_labels_agrupadores[$ps_agrupador], "Quantidade"];
    $report->va_itens_keys = ["agrupador", "Q"];
    $report->vb_percentage = boolval($vb_incluir_porcentagem_catalogacao);
    $report->vs_num_cols = boolval($vb_incluir_porcentagem_catalogacao) ? '%{70,20,10}' : '%{80, 20}';

}
elseif ($vs_relatorio == "pesquisa_usuario")
{
    $vo_objeto = new objeto_base;
    $va_parametros_filtros_consulta = array();
    $va_itens_listagem = $vo_objeto->ler_atividades_pesquisa_usuario($vs_data_inicial, $vs_data_final, $vn_setor_sistema_codigo);

    $report->vs_title = "Relatório de pesquisas do usuário";
    $report->vs_new_table_on = "setor";
    $report->vs_group_on = "campo";
    $report->va_table_header = ["Valor pesquisado", "Frequência"];
    $report->va_itens_keys = ["valor", "Q"];
    $report->vb_alternate_row_color = false;
    $report->vb_percentage = boolval($vb_incluir_porcentagem);
    $report->vs_num_cols = boolval($vb_incluir_porcentagem) ? '%{70,20,10}' : '%{80, 20}';

    if ($vn_setor_sistema_codigo)
    {
        $vo_setor = new setor_sistema;
        $va_setor = $vo_setor->ler($vn_setor_sistema_codigo );
        $vs_acervo = $va_setor["setor_sistema_nome"];
    }

    $report->va_subheadings[] = ["label" => "Acervo", "value" => $vs_acervo ?? "Todos"];
}


if ($vs_data_inicial)
{
    $vo_periodo = new periodo;
    $vo_periodo->set_data_inicial($vs_data_inicial);
    $vs_data_final ? $vo_periodo->set_data_final($vs_data_final) : $vo_periodo->set_data_final($vs_data_inicial);

    $vs_data_inicial = $vo_periodo->get_data_inicial_exibicao();
    $vs_data_final = $vo_periodo->get_data_final_exibicao();
    $vs_data = $vs_data_final != "" ? $vs_data_inicial . " a " . $vs_data_final : $vs_data_inicial;

    $report->va_subheadings[] = ["label" => "Período", "value" => $vs_data];
}

$report->va_itens = $va_itens_listagem ?? [];
$report->process();
$report->Output();

utils::callback_progress($vs_file_name, 100);
utils::clear_temp_folder("-5 minutes");
exit();

function get_parametros_relatorio_quantitativo($pn_campo_sistema_codigo)
{
    $vo_campo_sistema = new campo_sistema;
    $va_campo_sistema = $vo_campo_sistema->ler($pn_campo_sistema_codigo, "ficha");

    $vs_campo_sistema_id = $va_campo_sistema["campo_sistema_nome"];

    $vs_campo_sistema_nome = $va_campo_sistema["campo_sistema_campo_sistema_superior_codigo"]["campo_sistema_nome"] ?? null;

    if ($vs_campo_sistema_nome)
    {
        $vs_campo_sistema_id = $vs_campo_sistema_nome . "_0_" . $vs_campo_sistema_id;
    }

    $va_campos_sistema_objeto_relacionado = $vo_campo_sistema->ler_lista(
        ["campo_sistema_recurso_sistema_codigo" => $va_campo_sistema["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_codigo"]]
    );

    $vs_objeto_relacionado_campo_identificador = "";
    foreach ($va_campos_sistema_objeto_relacionado as $va_campo_objeto_relacionado)
    {
        if (isset($va_campo_objeto_relacionado["campo_sistema_identificador_recurso_sistema"]) && $va_campo_objeto_relacionado["campo_sistema_identificador_recurso_sistema"])
        {
            $vs_objeto_relacionado_campo_identificador = $va_campo_objeto_relacionado["campo_sistema_nome"];
        }
    }

    if (!$vs_objeto_relacionado_campo_identificador)
    {
        session::log_and_redirect_error(
            "Erro ao imprimir relatório",
            "Não foi possível encontrar o campo identificador do objeto relacionado" . __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " . var_export($va_campos_sistema_objeto_relacionado, true),
            true
        );
    }

    return [$vs_campo_sistema_id, $vs_objeto_relacionado_campo_identificador, $va_campo_sistema["campo_sistema_alias"]];
}

?>