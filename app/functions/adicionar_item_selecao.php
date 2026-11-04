<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    session::validar_csrf();

    if (autorizacao::usuario_externo())
    {
        http_response_code(403);
        exit();
    }

    $vo_objeto = autorizacao::resolver_objeto($vs_id_objeto_tela);

    if (!($vo_objeto instanceof objeto_base) || !autorizacao::autorizar_leitura_registro($vo_objeto, (int) ($_POST["item_codigo"] ?? 0)))
    {
        http_response_code(403);
        exit();
    }

    $vo_selecao = new selecao($vs_id_objeto_tela);

    // Se não vem passado o código da seleção, tem que criar uma nova
    if (!empty($_POST["selecao_codigo"]))
    {
        $vn_selecao_codigo = (int) $_POST["selecao_codigo"];

        if (!autorizacao::autorizar_mutacao_selecao($vn_selecao_codigo))
        {
            http_response_code(403);
            exit();
        }
    }
    else
    {
        $va_selecao['selecao_tipo_codigo'] = 2;
        $va_selecao['selecao_nome'] = "Consulta ao acervo: " . date('d-m-Y');
        $va_selecao['usuario_logado_codigo'] = $vn_usuario_logado_codigo;

        $vn_selecao_codigo = $vo_selecao->salvar($va_selecao);
    }

    $vo_selecao->adicionar_item($vn_selecao_codigo, (int) $_POST["item_codigo"]);
?>