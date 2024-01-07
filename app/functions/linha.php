<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    if (!isset($vs_id_objeto_tela) || !$vs_id_objeto_tela)
    {
        if (isset($vs_tela))
            $vs_id_objeto_tela = $vs_tela;
        else
        {
            if (isset($_GET['tela']))
                $vs_id_objeto_tela = $_GET['tela'];
            else
                $vs_id_objeto_tela = "";
        }
    }

    if (isset($vs_nome_campo))
        $vs_nome_campo_lookup = $vs_nome_campo;

    if (!isset($vs_nome_campo_lookup))
    {
        if (isset($_GET['campo_lookup']))
            $vs_nome_campo_lookup = $_GET['campo_lookup'];
        else
            $vs_nome_campo_lookup = "";
    }

    if (!isset($vs_nome_campo_codigos))
    {
        if (isset($_GET['campo_codigos']))
            $vs_nome_campo_codigos = $_GET['campo_codigos'];
        else
            $vs_nome_campo_codigos = "";
    }

    if (!isset($vb_mostrar_subcampos))
    {
        if (isset($_GET['mostrar_subcampos']))
            $vb_mostrar_subcampos = $_GET['mostrar_subcampos'];
        else
            $vb_mostrar_subcampos = 1;
    }

    if (!isset($vn_linha_codigo))
    {
        if (isset($_GET['codigo']))
            $vn_linha_codigo = $_GET['codigo'];
        else
            $vn_linha_codigo = "";
    }

    if (!isset($vs_linha_valor))
    {
        if (isset($_GET['valor']))
            $vs_linha_valor = $_GET['valor'];
        else
            $vs_linha_valor = "";
    }

    if (!isset($va_valores_linha))
        $va_valores_linha = $_GET;

    if (isset($_GET['pode_editar']))
        $vb_pode_editar = $_GET['pode_editar'];
    elseif (!isset($vb_pode_editar))
        $vb_pode_editar = false;    

    if (!isset($vb_pode_remover))
        $vb_pode_remover = true;

    //////////////////////////////////////////////////////////////

    $va_campos_linha = array();

    if ($vs_id_objeto_tela)
    {
        $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);

        if (!isset($pn_objeto_codigo))
            $pn_objeto_codigo = "";

        $vs_nome_campo_pai = $vs_nome_campo_lookup;
        if ($vs_nome_campo_codigos)
        {
            $vs_nome_campo_pai = $vs_nome_campo_codigos;

            $va_campos_edicao = $vo_objeto->get_campos_edicao();

            if (isset($va_campos_edicao[$vs_nome_campo_codigos]))
                $va_parametros_campo = $va_campos_edicao[$vs_nome_campo_codigos];
        }

        $va_campos_linha = $vo_objeto->get_subcampos($vs_nome_campo_pai, $vn_linha_codigo);
    }
?>

<div id="linha_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>"
<?php 
    print ' class="input-group mb-3 linha_' . $vs_nome_campo_lookup . '"';
?>
>
    
    <div class="form-control cor-interna-edit no-border">
        <?php 
            if (!is_array($vs_linha_valor) && $vs_linha_valor)
            {
            ?>
            <div class="row my-1">
                <div class="">
                    <?php
                        print $vs_linha_valor;

                        if (count($va_campos_linha) && (isset($va_parametros_campo["esconder_subcampos"]) && !$va_parametros_campo["esconder_subcampos"]))
                        {
                            $vs_linha_campo = $vs_nome_campo_lookup . "_" . $vn_linha_codigo;
                        ?>
                            <div style="float:right">
                                <button class="btn btn-primary detalhes dropdown-toggle" type="button" id="btn_detalhes_<?php print $vs_linha_campo; ?>" onclick="toggle_detalhes('<?php print $vs_linha_campo; ?>')">
                                    Detalhes
                                </button>
                            </div>
                        <?php
                        }
                    ?>
                    <input type="hidden" id="<?php print $vs_nome_campo_codigos . "_" . $vn_linha_codigo; ?>" value="<?php print htmlspecialchars($vn_linha_codigo); ?>">
                </div>
            </div>
        <?php
            }
        ?>

        <?php
            if (count($va_campos_linha))
            {
            ?>
            <div class="row" id="div_campos_linha_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>"
                <?php if ($vs_linha_valor && (isset($va_parametros_campo["esconder_subcampos"]) && $va_parametros_campo["esconder_subcampos"]))
                    print ' style="display:none"';
                ?>
                >
                <?php
                    $vn_linha_atual = 1;
                    $vn_contador_campos = 1;
                    $vn_linha_codigo_tratado = $vn_linha_codigo;
                    
                    // Se for permitido adicionar o mesmo valor mais de uma vez,
                    // os códigos vêm com um numerador separado por "_", que deve ser descartado
                    ////////////////////////////////////////////////////////////////////////////
                    
                    if (strpos($vn_linha_codigo, "_"))
                    {
                        $vn_linha_codigo_tratado = explode("_", $vn_linha_codigo)[0];
                    }

                    foreach ($va_campos_linha as $vs_key_campo => $v_campo)
                    {
                        if ($vn_contador_campos == 1)
                            print "<div>";
                        elseif (isset($v_campo["linha"]) && $v_campo["linha"] != $vn_linha_atual)
                        {
                            print "</div><div>";
                            $vn_linha_atual = $v_campo["linha"];
                        }

                        // Vamos verificar se este campo pode ser exibido
                        /////////////////////////////////////////////////

                        if (isset($v_campo["dependencia_exibicao"]))
                        {
                            if (isset($va_valores_linha[$v_campo["dependencia_exibicao"]["atributo"]]) && ($va_valores_linha[$v_campo["dependencia_exibicao"]["atributo"]] != $v_campo["dependencia_exibicao"]["valor"]) )
                                continue;
                            else
                            {
                                $va_partes_atributo = explode("_0_", $v_campo["dependencia_exibicao"]["atributo"]);

                                if (isset($va_valores_linha[$va_partes_atributo[0] . "_" . $vn_linha_codigo]))
                                {
                                    if (is_array($va_valores_linha[$va_partes_atributo[0] . "_" . $vn_linha_codigo]))
                                        $vs_valor_comparar_dependencia_exibicao = $va_valores_linha[$va_partes_atributo[0] . "_" . $vn_linha_codigo][$va_partes_atributo[1]];
                                    
                                    elseif (isset($va_valores_linha[$va_partes_atributo[0] . "_" . $vn_linha_codigo]))
                                        $vs_valor_comparar_dependencia_exibicao = $va_valores_linha[$va_partes_atributo[0] . "_" . $vn_linha_codigo];
                                }

                                if (isset($vs_valor_comparar_dependencia_exibicao) && $vs_valor_comparar_dependencia_exibicao != $v_campo["dependencia_exibicao"]["valor"])
                                    continue;
                            }
                        }
                        /////////////////////////////////////////////////

                        // Adiciona o código do "pai" no filtro
                        ///////////////////////////////////////

                        if (isset($v_campo["dependencia"]))
                        {
                            if ($v_campo["dependencia"]["campo"] == ($vs_nome_campo_codigos))
                                $va_valores_linha[$v_campo["dependencia"]["campo"]] = $vn_linha_codigo;

                            elseif (isset($va_valores_linha[$v_campo["dependencia"]["campo"] . "_" . $vn_linha_codigo]))
                                $va_valores_linha[$v_campo["dependencia"]["atributo"]] = $va_valores_linha[$v_campo["dependencia"]["campo"] . "_" . $vn_linha_codigo];

                            elseif (isset($va_valores_linha[$v_campo["dependencia"]["campo"]]))
                                $va_valores_linha[$v_campo["dependencia"]["atributo"]] = $va_valores_linha[$v_campo["dependencia"]["campo"]];
                        }

                        $vo_campo = new $v_campo[0]($vs_id_objeto_tela, $vs_key_campo, "linha", "edicao");

                        if (!is_array($v_campo["nome"]) && isset($va_valores_linha[$v_campo["nome"] . "_" . $vn_linha_codigo_tratado]))
                            $va_valores_linha[$v_campo["nome"] . "_" . $vn_linha_codigo] = $va_valores_linha[$v_campo["nome"] . "_" . $vn_linha_codigo_tratado];
                        
                        $vo_campo->build($va_valores_linha, $v_campo);

                        if ($vn_contador_campos == count($va_campos_linha))
                            print "</div>";

                        $vn_contador_campos++;
                    }
                    ?>
                </div>
            <?php
            }
        ?>
    </div>

    <?php if ($vb_pode_editar && isset($va_parametros_campo["objeto"]))
    {
    ?>
        <button class="btn btn-primary float-end btn-edit" type="button" id="btn_edit_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>">
            <svg class="icon">
            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-pencil"></use>
            </svg>
        </button>
    <?php
    }
    ?>

    <?php if ($vb_pode_remover && (!isset($va_parametros_campo["readonly"]) || !$va_parametros_campo["readonly"]))
    {
    ?>
        <button class="btn btn-primary float-end btn-trash" type="button" id="btn_rem_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>">
            <svg class="icon">
            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-trash"></use>
            </svg>
        </button>
    <?php
    }
    ?>
    
</div>

<script>

<?php
if ($vb_pode_editar && isset($va_parametros_campo["objeto"]))
{
?>

$(document).on('click', "#btn_edit_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>", function()
{
    <?php 
        $vn_objeto_linha_codigo = $vn_linha_codigo;
        if (isset($va_parametros_campo["permitir_repeticao_termo"]) && ($va_parametros_campo["permitir_repeticao_termo"] == "true"))
        {
            $vn_objeto_linha_codigo = explode("_", $vn_linha_codigo)[0];
        }
    ?>

    vs_url_editar = "editar.php?obj=<?php print $va_parametros_campo["objeto"]; ?>&cod=<?php print $vn_objeto_linha_codigo; ?>";
    window.open(vs_url_editar, "_blank");
});

<?php
}
?>

<?php
if ($vb_pode_remover)
{
?>

$(document).on('click', "#btn_rem_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>", function()
{
    vb_alterou_cadastro = true;
    
    $("#linha_<?php print $vs_nome_campo_lookup . "_" . $vn_linha_codigo; ?>").remove();

    <?php if ($vs_nome_campo_codigos) { ?>
        va_codigos = $("#<?php print $vs_nome_campo_codigos ?>").val().split("|");
        va_codigos.splice($.inArray('<?php print $vn_linha_codigo; ?>', va_codigos), 1);

        va_lista_codigos_atualizada = "";
            
        for (vn_key in va_codigos) 
        {
            if (va_lista_codigos_atualizada == "")
                va_lista_codigos_atualizada = va_codigos[vn_key];
            else
                va_lista_codigos_atualizada = va_lista_codigos_atualizada + "|" + va_codigos[vn_key];
        }

        <?php
        if (isset($va_parametros_campo["controlar_exibicao"]))
        {
            foreach($va_parametros_campo["controlar_exibicao"] as $vs_campo_controlar)
            {
            ?>
                atualizar_exibicao_<?php print $vs_campo_controlar ?>(va_lista_codigos_atualizada);
            <?php
            }
        }
        ?>

        if (va_codigos.length <= 5)
            $("#div_remover_todos_<?php print $vs_nome_campo_lookup ?>").hide();

        $("#<?php print $vs_nome_campo_codigos ?>").val(va_lista_codigos_atualizada);
    <?php } ?>

    $("#<?php print $vs_nome_campo_lookup ?>").prop("disabled", false);
    $("#<?php print $vs_nome_campo_lookup ?>").val('');
    $("#<?php print $vs_nome_campo_lookup ?>").show();
    $("#<?php print $vs_nome_campo_lookup ?>").focus();

    atualizar_dependencias_<?php print $vs_nome_campo_lookup ?>("");
});

<?php
}
?>

<?php
if (count($va_campos_linha))
{
?>



<?php
}
?>

</script>

