<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    session::validar_csrf();

    if (autorizacao::usuario_externo())
    {
        http_response_code(403);
        exit();
    }

    $vs_visualizacao = "lista";

    require_once dirname(__FILE__) . "/montar_listagem.php";

    $vn_selecao_codigo = (int) ($_POST["selecao"] ?? 0);

    if (!autorizacao::autorizar_mutacao_selecao($vn_selecao_codigo))
    {
        http_response_code(403);
        exit();
    }

    $vo_selecao = new selecao($vs_id_objeto_tela);
    $va_selecao = $vo_selecao->ler($vn_selecao_codigo, "navegacao");

    $va_itens_selecao = array();

    if (isset($va_selecao["selecao_item_codigo"]))
    {
        foreach ($va_selecao["selecao_item_codigo"] as $va_item)
        {
            $va_itens_selecao[] = $va_item["selecao_item_codigo"];
        }
    }

    foreach($va_itens_listagem as $va_item_listagem)
    {
        $vn_objeto_codigo = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];

        if (!in_array($vn_objeto_codigo, $va_itens_selecao) && autorizacao::autorizar_leitura_registro($vo_objeto, (int) $vn_objeto_codigo))
            $vo_selecao->adicionar_item($vn_selecao_codigo, $vn_objeto_codigo);
    }
?>