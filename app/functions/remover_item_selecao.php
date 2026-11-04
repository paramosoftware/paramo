<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    session::validar_csrf();

    if (autorizacao::usuario_externo())
    {
        http_response_code(403);
        exit();
    }

    $vn_selecao_codigo = (int) ($_POST["selecao_codigo"] ?? 0);
    $vn_item_codigo = (int) ($_POST["item_codigo"] ?? 0);

    if (!autorizacao::autorizar_mutacao_selecao($vn_selecao_codigo))
    {
        http_response_code(403);
        exit();
    }

    $vo_objeto = autorizacao::resolver_objeto($vs_id_objeto_tela);

    if (!($vo_objeto instanceof objeto_base) || !autorizacao::autorizar_leitura_registro($vo_objeto, $vn_item_codigo))
    {
        http_response_code(403);
        exit();
    }

    $vo_selecao = new selecao($vs_id_objeto_tela);
    $vo_selecao->remover_item($vn_selecao_codigo, $vn_item_codigo);
?>