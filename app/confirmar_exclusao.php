<?php

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>

<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php require_once dirname(__FILE__)."/components/sidebar.php"; ?>

<?php 
    $vn_objeto_codigo = "";
    $vb_exclusao_lote = false;

    if ( isset($_POST["modo"]) && ($_POST["modo"] == "lote") )
        $vb_exclusao_lote = true;
    else
    {
        if (isset($_GET['cod']))
            $vn_objeto_codigo = $_GET['cod'];
        else
        {
            session::log_and_redirect_error(
                "Nenhum código de objeto foi informado.",
                "Nenhum código de objeto foi informado: " . __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__,
                true
            );
        }
    }

    $vo_objeto = new $vs_id_objeto_tela('');

    if (!$vb_exclusao_lote)
    {
        if (!$vo_objeto->validar_acesso_registro($vn_objeto_codigo, $va_parametros_controle_acesso))
        {
            utils::log(
                "Tentativa de exclusão sem permissão: ",
                __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " .
                var_export($_SESSION, true) . " - " . var_export($_POST, true)
            );
            exit();
        }

        $va_parametros_filtros_consulta[$vo_objeto->get_chave_primaria()[0]] = $vn_objeto_codigo;
    }

    $vs_visualizacao = "navegacao";
    $vn_ordenacao = "";
    $vs_ordem = "";
    $vb_tem_filtros_consulta = true;

    require_once dirname(__FILE__)."/functions/montar_listagem.php";
    
    if (!$vb_exclusao_lote)
        $va_item_listagem = $va_itens_listagem[0];

    $va_relacionamentos_finais = array();
    $va_registros_nao_excluiveis = array();

    foreach($va_itens_listagem as $va_item_listagem)
    {
        $va_objeto_codigo[] = $va_item_listagem[$vo_objeto->get_chave_primaria()[0]];

        $va_relacionamentos = $vo_objeto->listar_relacionamentos($va_item_listagem[$vo_objeto->get_chave_primaria()[0]]);

        foreach($va_relacionamentos as $vs_alias_relacionamento => $va_relacionamento)
        {
            if (isset($va_relacionamentos_finais[$vs_alias_relacionamento]))
                $numero_relacionamentos = $va_relacionamento[0] + $va_relacionamentos_finais[$vs_alias_relacionamento][0];
            else
                $numero_relacionamentos = $va_relacionamento[0];

            $va_relacionamentos_finais[$vs_alias_relacionamento] = [$numero_relacionamentos, $va_relacionamento[1], $va_relacionamento[2]];

            if (!$va_relacionamento[2])
            {
                if (isset($va_item_listagem["main_field"]))
                    $va_registros_nao_excluiveis[] = $va_item_listagem["main_field"];
            }
        }
    }

    if (!$vn_objeto_codigo)
    {
        $vn_objeto_codigo = implode("|", $va_objeto_codigo);

        if (!$vo_objeto->validar_acesso_registro($vn_objeto_codigo, $va_parametros_controle_acesso))
        {
            utils::log(
                "Tentativa de exclusão sem permissão: ",
                __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " .
                var_export($_SESSION, true) . " - " . var_export($_POST, true)
            );
            exit();
        }
    }
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <form method="post" action="functions/excluir.php" id="form_lista">
        <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
        <input type="hidden" name="cod" id="cod" value="<?php print $vn_objeto_codigo; ?>">

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            
                            <div class="card-body">
                                <?php
                                    if (count($va_itens_listagem) == 1)
                                    {
                                        if (isset($va_item_listagem["main_field"]))
                                            print "<b>" . $va_item_listagem["main_field"] . "</b>";
                                        else
                                            print "Este registro";

                                        print " possui as seguintes associações no banco de dados:<br><br>";        
                                    }
                                    elseif (count($va_registros_nao_excluiveis))
                                    {
                                        print "<b>" . implode(", ", $va_registros_nao_excluiveis) . "</b> não podem ser excluídos.<br><br>";
                                        print " Associações existentes no banco de dados:<br><br>";     
                                    }
                                    
                                    foreach($va_relacionamentos_finais as $vs_alias_relacionamento => $va_relacionamento)
                                    {
                                        print $vs_alias_relacionamento . " [" . $va_relacionamento[0]  . "]";
                                        
                                        $vb_pode_excluir = true;
                                        if (!$va_relacionamento[2])
                                        {
                                            print " => Este relacionamento impede a exclusão do registro.";
                                            $vb_pode_excluir = false;
                                        }
                                        elseif ($va_relacionamento[1])
                                            print " => Esses registros também serão excluídos.";

                                        print "<br>";
                                    }   
                                ?>   
                                
                                <div class="col-md-12 text-right">
                                    <?php if ($vb_pode_excluir)
                                    {
                                    ?>
                                        Confirmar a exclusão do registro?
                                        <button class="btn btn-primary btn-salvar" type="submit" id="btn_excluir">
                                            Excluir
                                        </button>
                                        <?php
                                    }
                                    ?>

                                    <button class="btn btn-outline-primary btn_voltar" type="button" id="btn_voltar">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

    </form>
</div>

<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>

$(document).on('click', "#btn_voltar", function()
{
    history.back();
});

</script>

</body>
</html>