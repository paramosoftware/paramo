<?php
    if (!isset($vs_novo_id_campo))
        $vs_novo_id_campo = $_GET['campo'] . "_F_" . $_GET["instancia"];

    $vs_id_campo_normalizado = str_replace(",", "_", $vs_id_campo);
    $vs_novo_id_campo_normalizado = str_replace(",", "_", $vs_novo_id_campo);

    if (!isset($vn_contador_filtros_adicionados))
        $vn_contador_filtros_adicionados = $_GET["numero_filtros"];

    $vb_multiplas_instancias_campo = true;
    $vs_modo = "listagem";

    if (!isset($vs_valor_concatenador))
        $vs_valor_concatenador = "";
?>

<div class="row mb-3" style="margin-top:10px" id="div_<?php print $vs_novo_id_campo_normalizado; ?>">

    <div class="col-2" style="margin-top: 10px">
        <label class="form-label">
            Operador
        </label>

        <select class="form-select input" name="concatenadores[]" onchange="atualizar_filtro(this, '<?php print $vs_novo_id_campo_normalizado; ?>');">
            <?php if ($vn_contador_filtros_adicionados == 0)
            {
            ?>
                <option value=""></option>
            <?php
            }

            if ($vn_contador_filtros_adicionados > 0)
            {
            ?>            
                <option value="AND"
                <?php if ($vs_valor_concatenador == "AND")
                    print " selected";
                ?>
                >E</option>

                <option value="OR"
                <?php if ($vs_valor_concatenador == "OR")
                    print " selected";
                ?>
                >OU</option>
            <?php
            }
            ?>

            <option value="NOT"
            <?php if ($vs_valor_concatenador == "NOT")
                print " selected";
            ?>
            >NÃO</option>
        </select>
    </div>

    <div class="col-9">
    <?php
        require dirname(__FILE__)."/../functions/montar_campos.php";
    ?>
    </div>

    <div class="col-1" style="margin-top:42px; float:left">
        <button class="btn btn-primary float-end btn-trash" type="button" id="btn_rem_<?php print $vs_novo_id_campo_normalizado; ?>" onclick="remover_filtro_busca('<?php print $vs_novo_id_campo_normalizado; ?>', '<?php print $vs_id_campo_normalizado; ?>')">
            <svg class="icon">
            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-trash"></use>
            </svg>
        </button>
    </div>

</div>