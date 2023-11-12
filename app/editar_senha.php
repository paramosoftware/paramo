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

    $vs_foco = "usuario_senha";

    $va_campos_login["usuario_senha"] = [
        "html_text_input", 
        "nome" => "usuario_senha", 
        "label" => "Senha", 
        "formato" => "senha",
        "igual_campo" => ["repetir_senha" => "Repetir senha"]
    ];

    $va_campos_login["repetir_senha"] = [
        "html_text_input", 
        "nome" => "repetir_senha", 
        "label" => "Repetir senha", 
        "formato" => "senha"
    ];
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">

    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <form method="post" action="functions/salvar.php" id="form_alterar_senha">
        <input type="hidden" name="obj" id="obj" value="usuario">
        <input type="hidden" name="usuario_codigo" id="usuario_codigo" value="<?php print $vn_usuario_logado_codigo; ?>">
        <input type="hidden" name="usuario_alterar_senha" id="usuario_alterar_senha" value="1">    
        <input type="hidden" name="escopo" id="escopo" value="_senha">

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">Alterar senha</div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                    </div>
                                    
                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary btn-salvar" type="button" id="btn_salvar">
                                            Salvar
                                        </button>
                                    </div>
                                </div>

                                <br>
                                
                                <!-- FORM-->
                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">

                                    <?php
                                        $va_valores_form = array();
                                        foreach ($va_campos_login as $vs_key_campo => $v_campo)
                                        {
                                            $vo_campo = new $v_campo[0]('usuario', $v_campo["nome"]);
                                            $vo_campo->build($va_valores_form, $v_campo);
                                        }
                                    ?>

                                    </div>
                                </div>
                                <!-- / FORM-->

                                <br>

                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>
        </div>
    </form>
</div>
<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>

<?php 
if ($vs_foco)
{
?>

$(document).ready(function() 
{
    $("#<?php print $vs_foco; ?>").focus();
});

<?php
}
?>

$(document).on('click', "#btn_salvar", function()
{
    if ($("#usuario_senha").val().trim() == "")
    {
        alert("A senha não pode ser nula!");
        $("#usuario_senha").focus();
    }
    else if ($("#usuario_senha").val() != $("#repetir_senha").val())
        alert("Os valores da senha e da senha repetida não coincidem!");
    else
        $("#form_alterar_senha").submit();
});

</script>

</body>
</html>