<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    if (!$vb_pode_editar && !$vb_pode_ler)
        exit();

    if (!$vs_id_objeto_tela)
    {
        print "Não é possível carregar a listagem. (objeto)";
        exit();
    }

    if (isset($_GET["campo_modal"]))
        $vs_campo_modal = $_GET["campo_modal"];
    else
        exit();
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

                    require_once dirname(__FILE__)."/components/listagem.php"
                ?>
            </div>
        </div>
    </div>
</div>