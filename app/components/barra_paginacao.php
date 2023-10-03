<?php



if (!isset($vs_tela))
        $vs_tela = "";
        
    if (isset($pa_parametros_campo["nome"]))
        $vs_nome_campo_paginacao = $pa_parametros_campo["nome"];
    else
        exit();

    $va_parametros_campo = array();
    $va_parametros_campo = [
        "html_combo_input", 
        "nome" => $vs_nome_campo_paginacao, 
        "label" => "Ir pág.", 
        "objeto" => "Paginador", 
        "sem_valor" => false, 
        "parametros_inicializacao" => $pa_parametros_campo["numero_registros"],
        "css-class" => "form-control"
    ];
   
    $vo_combo_paginas = new html_combo_input($vs_tela, $vs_nome_campo_paginacao);
    
    $vo_paginador = new paginador($vn_numero_registros);
    $va_paginador = $vo_paginador->ler_lista();

    $vn_numero_primeira_pagina = ($vn_pagina_atual - 2);

    if ( (count($va_paginador) - $vn_numero_primeira_pagina) < 4)
        $vn_numero_primeira_pagina = count($va_paginador) - 4;

    if ($vn_numero_primeira_pagina <= 0)
        $vn_numero_primeira_pagina = 1;

    $vn_numero_ultima_pagina = $vn_numero_primeira_pagina + 4;
    
    if ($vn_numero_ultima_pagina > count($va_paginador))
        $vn_numero_ultima_pagina = count($va_paginador);
?>

<div class="filtro-step col-md-3">
    <label class="form-label" for="exampleFormControlInput1">&nbsp;</label>
    <nav aria-label="...">
        <ul class="pagination">
            <?php    
                $vs_css_botao_pagina = "";
                if ($vn_pagina_atual == 1)
                    $vs_css_botao_pagina = " disabled";
            ?>

            <li class="page-item<?php print $vs_css_botao_pagina; ?>">
                <a class="page-link <?php print $vs_nome_campo_paginacao; ?>" id="bnt_pagina_anterior_<?php print $vs_nome_campo_paginacao; ?>" href="#" tabindex="-1" aria-disabled="true">
                    Anterior
                </a>
            </li>
            
            <?php
            if ($vn_pagina_atual >= 5 && false)
            {
                $vs_css_botao_pagina = "";
                if ($vn_pagina_atual == 1)
                    $vs_css_botao_pagina = " active";

            ?>
                <li class="page-item<?php print $vs_css_botao_pagina; ?>" aria-current="page"><a class="page-link" href="#">1</a></li>
                <li class="page-item disabled" aria-current="page"><a class="page-link" href="#">...</a></li>
            <?php
            }
            ?>

            <?php
            $contador = $vn_numero_primeira_pagina;
            while ($contador <= $vn_numero_ultima_pagina)
            {
                $vs_css_botao_pagina = "";
                if ($vn_pagina_atual == $contador)
                    $vs_css_botao_pagina = " active";
            ?>

                <li class="page-item<?php print $vs_css_botao_pagina; ?>">
                    <a class="page-link <?php print $vs_nome_campo_paginacao; ?>" href="#">
                        <?php print $contador; ?>
                    </a>
                </li>
            
            <?php
                $contador++;
            }
            ?>

            <?php
            if ( $vn_pagina_atual < (count($va_paginador) - 3) && false)
            {
                if ( (count($va_paginador) -  $vn_pagina_atual) > 4)
                {
                ?>
                    <li class="page-item disabled" aria-current="page"><a class="page-link" href="#">...</a></li>
                
                <?php
                }
                
                $vs_css_botao_pagina = "";
                if ($vn_pagina_atual == count($va_paginador))
                    $vs_css_botao_pagina = " active";
                ?>

                <li class="page-item<?php print $vs_css_botao_pagina; ?>" aria-current="page">
                    <a class="page-link" href="#"> <?php print count($va_paginador); ?></a>
                </li>
            <?php
            }
            ?>

            <?php    
                $vs_css_botao_pagina = "";
                if ($vn_pagina_atual == count($va_paginador))
                    $vs_css_botao_pagina = " disabled";
            ?>

            <li class="page-item<?php print $vs_css_botao_pagina; ?>">
                <a class="page-link <?php print $vs_nome_campo_paginacao; ?>" id="bnt_proxima_pagina_<?php print $vs_nome_campo_paginacao; ?>" href="#">Próxima</a>
            </li>
        </ul>
    </nav>
</div>

<div class="filtro-space col-md-2">
    &nbsp;
</div>

<div class="filtro-ir col-md-1 text-right">
    <div class="input-group">
        <!--
        <button class="btn btn-primary" type="button">
            Ir
        </button>
        -->
        <?php 
            $va_valores_paginacao[$vs_nome_campo_paginacao] = $vn_pagina_atual;
            $vo_combo_paginas->build($va_valores_paginacao, $va_parametros_campo); 
        ?>
    </div>
</div>

<script>

$(document).on('click', ".<?php print $vs_nome_campo_paginacao; ?>", function(event)
{
    event.preventDefault();

    if ($(this).attr("id") == "bnt_proxima_pagina_<?php print $vs_nome_campo_paginacao; ?>")
        vn_pagina = parseInt($("#<?php print $vs_nome_campo_paginacao; ?>").val()) + 1;

    else if ($(this).attr("id") == "bnt_pagina_anterior_<?php print $vs_nome_campo_paginacao; ?>")
        vn_pagina = parseInt($("#<?php print $vs_nome_campo_paginacao; ?>").val()) - 1;

    else
        vn_pagina = $(this).text().trim();

        
    $("#campo_paginacao").val('<?php print $vs_nome_campo_paginacao; ?>');  
    $("#<?php print $vs_nome_campo_paginacao; ?>").val(vn_pagina);
    
    $("#form_lista").submit();
}
);

$(document).on('change', "#<?php print $vs_nome_campo_paginacao; ?>", function()
{
    $("#campo_paginacao").val('<?php print $vs_nome_campo_paginacao; ?>');
    $("#form_lista").submit();
}
);

</script>