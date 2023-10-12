<?php
require_once dirname(__FILE__) . "/../src/vendors/simplexlsx/SimpleXLSX.php";
require_once dirname(__FILE__) . "/../src/vendors/simplexlsx/SimpleXLSXEx.php";

global $vs_recurso_sistema_nome_plural, $vs_id_objeto_tela;

$vs_id_objeto_tela = $_GET["obj"] ?? $_POST["obj"];

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

$vn_step = $_POST["step"] ?? 1;

switch ($vn_step)
{
    case 1:
        break;
    case 2:
        $vs_caminho_arquivo = move_import_file();
        $va_rows = get_header_file($vs_caminho_arquivo, pathinfo($vs_caminho_arquivo, PATHINFO_EXTENSION));
        $va_campos = get_campos_edicao($vs_id_objeto_tela);
        break;
    case 3:
}

function get_header_file($ps_caminho_arquivo, $ps_extensao): array
{
    $va_rows = array();

    if ($ps_extensao == "csv")
    {
        $va_rows = utils::get_data_csv($ps_caminho_arquivo, ',', 1);
    } else
    {
        if ($xlsx = SimpleXLSX::parse($ps_caminho_arquivo))
        {
            $va_rows = iterator_to_array($xlsx->readRows(0, 1));
        }
    }

    return $va_rows;
}

function move_import_file(): string
{
    $vs_pasta_import = config::get(["pasta_media", "import"]);

    if (!isset($_FILES["arquivo"]) || $_FILES["arquivo"]["error"] != UPLOAD_ERR_OK)
    {
        return "";
    }

    $va_arquivo = $_FILES["arquivo"];
    $vs_caminho_arquivo = $vs_pasta_import . utils::sanitize_file_name($va_arquivo["name"]);

    if (move_uploaded_file($va_arquivo["tmp_name"], $vs_caminho_arquivo))
    {
        return $vs_caminho_arquivo;
    }

    return "";
}

function get_campos_edicao($ps_id_objeto_tela)
{
    $vo_objeto = new $ps_id_objeto_tela();
    return $vo_objeto->inicializar_campos_edicao();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php require_once dirname(__FILE__) . "/components/sidebar.php"; ?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <?php require_once dirname(__FILE__) . "/components/header.php"; ?>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">Importar <?= $vs_recurso_sistema_nome_plural; ?></div>

                        <div class="card-body">
                            <?php if ($vn_step == 1) : ?>
                                <form method="post" enctype="multipart/form-data" action="importar.php">
                                    <input type="hidden" name="obj" value="<?= $vs_id_objeto_tela; ?>">
                                    <input type="hidden" name="step" value="2">
                                    <div class="row no-margin-side" id="filtro">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="file" name="arquivo" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3 pr-3">
                                        <div class="text-end">
                                            <button class="btn btn-primary btn-importar" type="submit">
                                                Continuar
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <?php require_once dirname(__FILE__) . "/components/importar_instrucoes.php"; ?>

                            <?php elseif ($vn_step == 2) : ?>

                                <?php if ($vs_caminho_arquivo == "" || count($va_rows) == 0) : ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php if ($vs_caminho_arquivo == "") : ?>
                                            <p>Erro ao carregar arquivo.</p>
                                        <?php elseif (count($va_rows) == 0) : ?>
                                            <p>Arquivo vazio.</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row mt-3 pr-3">
                                        <div class="text-end">
                                            <a href="importar.php" class="btn btn-primary btn-importar">
                                                Voltar
                                            </a>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <form method="post" enctype="multipart/form-data" action="importar.php">
                                        <input type="hidden" name="obj" value="<?= $vs_id_objeto_tela; ?>">
                                        <input type="hidden" name="step" value="3">
                                        <input type="hidden" name="caminho_arquivo" value="<?= $vs_caminho_arquivo; ?>">

                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Coluna de origem</th>
                                                <th>Campo de destino</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($va_rows[0] as $vn_index => $vs_header) : ?>
                                                <tr>
                                                    <td><?= $vs_header; ?></td>
                                                    <td>
                                                        <select class="form-control" name="<?= $vn_index; ?>">
                                                            <option value="">Selecione</option>
                                                            <?php foreach ($va_campos as $vs_id => $va_atributos) : ?>
                                                                <option value="<?= $vs_id ?>"
                                                                    <?= strpos(strtolower($va_atributos["label"]), strtolower($vs_header)) !== false ? "selected" : ""; ?> >
                                                                    <?= $va_atributos["label"]; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <div class="row mt-3 pr-3">
                                            <div class="text-end">
                                                <button class="btn btn-primary btn-importar" type="submit">
                                                    Continuar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
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