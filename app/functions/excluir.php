<?php

    require_once dirname(__FILE__) . "/../components/entry_point.php";

    if (!$vb_pode_excluir)
    {
        utils::log(
            "Tentativa de exclusão sem permissão: ",
            __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " .
            var_export($_SESSION, true) . " - " . var_export($_POST, true)
        );
        session::redirect();
    }

    $vs_id_objeto = $_POST["obj"];

    $vn_codigo_objeto = "";
    if (isset($_POST["cod"]))
        $vn_codigo_objeto = $_POST["cod"];

    $vo_objeto = new $vs_id_objeto('');

    if (!$vo_objeto->validar_acesso_registro($vn_codigo_objeto, $va_parametros_controle_acesso) || in_array($vn_codigo_objeto, $vo_objeto->registros_protegidos))
    {
        utils::log(
            "Tentativa de exclusão sem permissão: ",
            __FILE__ . " - " . __LINE__ . " - " .
            var_export($_SESSION, true) . " - " . var_export($_POST, true)
        );
        session::redirect();
    }

    $vs_chave_primaria_objeto = $vo_objeto->get_chave_primaria()[0];

    $va_objetos_codigos = array();
    $va_objetos_codigos = explode("|", $vn_codigo_objeto);

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