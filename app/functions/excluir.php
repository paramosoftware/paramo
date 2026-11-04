<?php

    require_once dirname(__FILE__) . "/../components/entry_point.php";

    session::validar_csrf();

    if (!$vb_pode_excluir)
    {
        utils::log(
            "Tentativa de exclusão sem permissão: ",
            __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " . utils::sanitizar_requisicao_para_log()
        );
        session::redirect();
    }

    $vs_id_objeto = $_POST["obj"];

    $vn_codigo_objeto = "";
    if (isset($_POST["cod"]))
        $vn_codigo_objeto = $_POST["cod"];

    $vo_objeto = autorizacao::resolver_objeto($vs_id_objeto);

    if (!($vo_objeto instanceof objeto_base))
    {
        http_response_code(400);
        exit();
    }

    $va_objetos_codigos = array_filter(explode("|", (string) $vn_codigo_objeto), "is_numeric");

    foreach ($va_objetos_codigos as $vn_objeto_codigo)
    {
        if (!autorizacao::autorizar_edicao_registro($vo_objeto, (int) $vn_objeto_codigo, "excluir") || in_array($vn_objeto_codigo, $vo_objeto->registros_protegidos))
        {
            utils::log(
                "Tentativa de exclusão sem permissão: ",
                __FILE__ . " - " . __LINE__ . " - " . utils::sanitizar_requisicao_para_log()
            );
            session::redirect();
        }
    }

    $vs_chave_primaria_objeto = $vo_objeto->get_chave_primaria()[0];

    $vb_exclusao_em_lote = count($va_objetos_codigos) > 1;

    if ($vb_exclusao_em_lote)
    {
        $vo_objeto->iniciar_transacao();
    }

    foreach($va_objetos_codigos as $vn_objeto_codigo)
    {
        $vo_objeto->excluir($vn_objeto_codigo);
    }

    if ($vb_exclusao_em_lote)
    {
        $vo_objeto->finalizar_transacao();
    }
    
    $vs_url_retorno = "listar.php?obj=". $vs_id_objeto . "&back=1";

    session::redirect($vs_url_retorno);
?>