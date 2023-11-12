<?php

require_once dirname(__FILE__) . "/../components/entry_point.php";

if (!$vb_pode_editar && !$vb_pode_ler) {
    utils::log(
        "Tentativa de acesso sem permissão: ",
        __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
    );
    exit();
}

if (!$vs_id_objeto_tela)
{
    utils::log(
        "Nenhum objeto foi informado: ",
        __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
    );
    print "Não é possível carregar a listagem. (objeto)";
    exit();
}

if (isset($_GET["campo_modal"]))
{
    $vs_campo_modal = $_GET["campo_modal"];
}
else
{
    utils::log(
        "Nenhum campo modal foi informado: ",
        __FILE__ . " - " . __LINE__ . " - " . var_export($_SESSION, true) . " - " . var_export($_POST, true)
    );
    print "Não é possível carregar a listagem. (campo_modal)";
    exit();
}

?>

<div class="">
    <div class="">
        <div class="card">
            <div class="card-header"><?php print $vs_recurso_sistema_nome_plural; ?></div>

            <div class="card-body">
                <?php
                $vb_primeiro_carregamento = false;
                $vn_numero_registros_lista = 200;
                $vs_target_ui = "modal";

                require_once dirname(__FILE__) . "/../components/listagem.php"
                ?>
            </div>
        </div>
    </div>
</div>