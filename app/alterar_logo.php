<?php
    $vb_montar_menu = true;
    require_once dirname(__FILE__) . "/components/entry_point.php";

    if (!($vb_usuario_administrador && $vb_usuario_logado_instituicao_admin))
    {
        utils::log(
            "Tentativa de alterar logo sem permissão: ",
            __FILE__ . " - " . __LINE__ . " - " . __FUNCTION__ . " - " .
            var_export($_SESSION, true) . " - " . var_export($_POST, true)
        );
        session::redirect();
    }

    function upload_logo(): array
    {
        $va_extensoes = config::get(["extensoes_permitidas"]);
        $va_mimes = array_values($va_extensoes);

        $vs_assets_folder = config::get(["pasta_assets", "custom", "images"]);

        $file = $_FILES["logo"] ?? null;

        if($file["name"] == "" || $file["size"] == 0) {
            return array(true, "");
        }

        if ($file["error"] != 0) {
            return array(false, "Erro ao enviar a logo.");
        }

        if (getimagesize($file["tmp_name"]) === false) {
            return array(false, "Formato de imagem inválido.");
        }

        if (!(in_array($file["type"], $va_mimes))) {
            return array(false, "Formato de imagem inválido.");
        }

        if ($file["size"] > 1048576) {
            return array(false, "Tamanho máximo de imagem excedido.");
        }

        $image = imagecreatefromstring(file_get_contents($file["tmp_name"]));

        $file_path = $vs_assets_folder . "logo.png";

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $new_width = 400;
        $new_height = floor($height * ($new_width / $width));
        $tmp_image = imagecreatetruecolor($new_width, $new_height);
        imagealphablending($tmp_image, false);
        imagesavealpha($tmp_image, true);
        imagecopyresampled($tmp_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $image = $tmp_image;

        if (!imagepng($image, $file_path)) {
            return array(false, "Erro ao enviar a logo.");
        }

        return array(true, "Logo alterado com sucesso.");

    }


    if (isset($_FILES["logo"])) {
        $va_retorno = upload_logo();
        $vb_sucesso = $va_retorno[0];

        if ($vb_sucesso)
        {
           session::redirect("alterar_logo.php?sucesso=true");
        }

        $vs_mensagem = $va_retorno[1];
    }

    if (isset($_GET["sucesso"])) {
        $vb_sucesso = true;
        $vs_mensagem = "Logo alterado com sucesso.";
    }

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php require_once dirname(__FILE__) . "/components/sidebar.php"; ?>


<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <?php require_once dirname(__FILE__)."/components/header.php"; ?>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">Alterar logo do sistema</div>

                        <div class="card-body">
                            <?php if (isset($vb_sucesso)) : ?>
                                <div class="alert alert-<?php echo $vb_sucesso ? "success" : "danger"; ?>" role="alert">
                                    <?php echo $vs_mensagem ?? ""; ?>
                                </div>
                            <?php endif; ?>
                            <form method="post" enctype="multipart/form-data" action="alterar_logo.php">
                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                    </div>

                                    <div class="col-md-3 text-right">
                                        <button class="btn btn-primary btn-salvar" type="submit">
                                            Salvar
                                        </button>
                                    </div>
                                </div>

                                <br>

                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="file" name="logo" id="logo" class="form-control" accept="image/png, image/jpeg">
                                            <small class="form-text text-muted">Recomenda-se uma imagem com proporção 4:3. Tamanho máximo: 1 MB</small>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <br>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once dirname(__FILE__) . "/components/footer.php"; ?>
</body>
</html>



