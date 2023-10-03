<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    $vo_selecao = new selecao($vs_id_objeto_tela);

    // Se não vem passado o código da seleção, tem que criar uma nova
    if ($_POST["selecao_codigo"])
        $vn_selecao_codigo = $_POST["selecao_codigo"];
    else
    {
        $va_selecao['selecao_tipo_codigo'] = 2;
        $va_selecao['selecao_nome'] = "Consulta ao acervo: " . date('d-m-Y');
        $va_selecao['usuario_logado_codigo'] = $vn_usuario_logado_codigo;

        $vn_selecao_codigo = $vo_selecao->salvar($va_selecao);
    }

    $vo_selecao->adicionar_item($vn_selecao_codigo, $_POST["item_codigo"]);
?>