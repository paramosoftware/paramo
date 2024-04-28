<?php

$va_info_setores = $va_info_setores ?? [];

?>

<div class="card deashboard-card mb-4">
    <div class="card-header">
        <?php 
            if (!$vb_usuario_externo)
                print "Estatísticas do Acervo";
            else
                print "Itens disponíveis para consulta";
        ?>
    </div>

    <div class="card-body">
        <?php
        $contador_setores = 1;
        foreach ($va_info_setores as $va_info_setor)
        {
            if ( ($contador_setores % 2) == 1 )
                print '<div class="row">';
            ?>

            <div class="col-sm-6">
                <div class="row">
                    <div class="col-6">
                        <div class="border-start border-start-4 border-start-danger px-3 mb-3">
                            <a style="text-decoration:none; color:unset;" href="listar.php?obj=<?php print htmlspecialchars($va_info_setor["link_recurso_sistema"]); ?>&s=<?php print $va_info_setor["codigo"]; ?>">
                                <small class="text-medium-emphasis"><?php print $va_info_setor["nome"]; ?></small>
                                <div class="fs-5 fw-semibold"><?php print $va_info_setor["quantidade"]; ?> itens</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if ( ($contador_setores % 2) == 0 || ($contador_setores == count($va_info_setores)) )
                print '</div>';

            $contador_setores++;
        }
        ?>
    </div>
</div>
