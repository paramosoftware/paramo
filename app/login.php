<?php
    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../autoload.php";
    }

    require dirname(__FILE__) . "/components/debug.php";

    session::start_session();
    session::set_redirect_url();

    if (session::get_logged_user())
    {
        session::redirect();
    }

    $vs_usuario_login = $_POST['usuario_login'] ?? "";
    $vs_usuario_senha = $_POST['usuario_senha'] ?? "";
    $vb_login_success = session::login($vs_usuario_login, $vs_usuario_senha);
?>

<!DOCTYPE html>
<html lang="pt-br">

<?php

    require_once dirname(__FILE__) . "/components/header_html.php";;

    $vs_login_msg = "";

    if ($vs_usuario_login != "" && $vs_usuario_senha != "")
    {
        if (!$vb_login_success)
        {
            $vs_login_msg = "Login ou senha incorretos.";
        }
    }
    else
    {
        if (count($_POST))
        {
            $vs_login_msg = "Login ou senha inválidos.";
        }
    }

    $vs_foco = "usuario_login";

    $va_campos_login["usuario_login"] = [
        "html_text_input",
        "nome" => "usuario_login",
        "label" => "Login",
        "foco" => true
    ];

    $va_campos_login["usuario_senha"] = [
        "html_text_input",
        "nome" => "usuario_senha",
        "label" => "Senha",
        "formato" => "senha"
    ];
?>

<body>

<form method="post" action="login.php">
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET["redirect"] ?? "") ; ?>">

    <?php
        $va_valores_form = array();
        foreach ($va_campos_login as $vs_key_campo => $v_campo)
        {
            $vo_campo = new $v_campo[0]('usuario', $v_campo["nome"]);
        }
    ?>

    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card-group d-block d-md-flex row">
                        <div class="card col-md-7 p-4 mb-0">
                            <div class="card-body">                                   
                                <div class='alert alert-warning' role='alert' id='login_message'
                                <?php if (!$vs_login_msg)
                                    print 'style="display:none"';
                                ?>
                                ><?php print $vs_login_msg; ?></div>                               

                                <h2>Login</h2>

                                <p class="text-medium-emphasis">Entre com seus dados de acesso</p>

                                <div class="input-group mb-3" id="div_usuario_login">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-user"></use>
                                        </svg>
                                    </span>

                                    <input class="form-control" type="text" placeholder="Usuário" id="usuario_login" name="usuario_login" maxlength="50">
                                </div>

                                <div class="input-group mb-4" id="div_usuario_senha">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control" type="password" placeholder="Senha" id="usuario_senha" name="usuario_senha" maxlength="50">
                                </div>

                                <div class="row">
                                    <div class="input-group">
                                        <div class="col-3">
                                            <button class="btn btn-primary px-4" type="submit" id="btn_entrar">
                                                Entrar
                                            </button>
                                            <button class="btn btn-primary px-4" type="button" id="btn_recuperar_senha" style="display:none">
                                                Recuperar
                                            </button>
                                        </div>
                                        <?php if (config::get(["f_envio_email"]) ?? false) : ?>
                                            <div class="col-9 text-end" id="div_recuperar_senha">
                                                <a class="px-0" onclick="mudarFormLogin()">
                                                    Recuperar a senha?
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card col-md-5 text-white bg-primary text-center">
                            <div class="card-body text-center d-flex align-items-center justify-content-center">
                                <?php $logo_class = config::get(["logo_class"]) ?? "w-75"; ?>
                                <img src="<?= config::get(["logo"]); ?>" alt="logo" class="img-fluid <?= $logo_class ?>">
                            </div>
                            <h5 class="text-center text-white mb-3">
                                <?= config::get(["descricao_instituicao"]); ?>
                            </h5>
                        </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</form>


<script>

function mudarFormLogin()
{
    $("#div_usuario_senha").hide();
    $("#div_recuperar_senha").hide();
    $("#btn_entrar").hide();
    $("#btn_recuperar_senha").show();
}

<?php
if ($vs_foco)
{
?>

$(document).ready(function()
{
    $("#<?php print $vs_foco; ?>").focus();

    if($("#login_message").html() !== "")
    {
        setTimeout(function()
        {
            $("#login_message").hide();
        }, 5000);
    }

});

<?php
}
?>

$(document).on('click', "#btn_recuperar_senha", function() {

    if ($("#usuario_login").val() === "")
    {
        $("#login_message").html("Informe o login para recuperar a senha.");

        setTimeout(function() {
            $("#login_message").hide();
        }, 5000);

        return;
    }

    $("#btn_recuperar_senha").prop("disabled", true);

    $.post("recuperar_senha.php", $.param($("form").serializeArray()), function(response)
    {
        $("#btn_recuperar_senha").prop("disabled", false);
        if (response)
        {
            $("#login_message").html(response);
            $("#login_message").show();

            setTimeout(function() {
                $("#login_message").hide();
            }, 5000);

            $("#div_usuario_senha").show();
            $("#div_recuperar_senha").show();
            $("#btn_entrar").show();
            $("#btn_recuperar_senha").hide();
        }
    });
});

</script>

</body>
</html>