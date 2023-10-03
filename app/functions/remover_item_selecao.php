<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    $vn_selecao_codigo = $_POST["selecao_codigo"];

    $vo_selecao = new selecao($vs_id_objeto_tela);
    $vo_selecao->remover_item($vn_selecao_codigo, $_POST["item_codigo"]);
?>