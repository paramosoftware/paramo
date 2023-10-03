<?php

if (!isset($vs_nome_campo))
{
    print "Não é possível criar link para cadastro (nome).";
    exit();
}

?>

<div class="row mb-3">
    <div class="col-2 mb-3">			
        <button class="btn btn-primary px-4" type="button" id="lnk_cadastrar_<?php print $vs_nome_campo ?>">Cadastrar</button>
    </div>

    <div class="mb-3 col-8">
        <div>
            <input class="form-control w-100 mb-3" type="text" value="<?php print htmlspecialchars($vs_termo_busca); ?>" readonly>
        
            <?php 
            if (isset($va_campos_salvar) && count($va_campos_salvar) > 1)
            {
                $vn_contador_campos_salvar = 1;
                while ($vn_contador_campos_salvar < count($va_campos_salvar))
                {
                    $va_parametros_campo = $va_campos_edicao_objeto_campo[$va_campos_salvar[$vn_contador_campos_salvar]];

                    $vs_formato = "";
                    if (isset($va_parametros_campo["formato"]))
                        $vs_formato = $va_parametros_campo["formato"];

                    $vo_campo = new $va_parametros_campo[0]($vs_id_objeto_tela, $va_parametros_campo["nome"], $vs_formato, "edicao");

                    $va_valores_form = array();
                    $vo_campo->build($va_valores_form, $va_parametros_campo);

                    $vn_contador_campos_salvar++;
                }
            }
        ?>
        <div>
    </div>
</div>