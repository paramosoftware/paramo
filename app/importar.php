<?php

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php
    if (!$vb_pode_editar)
        exit();

    $vb_sobrescrever_valores = true;
    if (!isset($_POST["sobrescrever"]))
        $vb_sobrescrever_valores = false;

    require_once dirname(__FILE__) ."/components/sidebar.php";
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <?php require_once dirname(__FILE__) ."/components/header.php"; ?>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">Importar <?php print $vs_recurso_sistema_nome_plural; ?></div>

                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="importar.php">
                                <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
                                
                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="file" name="arquivo" class="form-control">
                                            <input type="checkbox" name="sobrescrever" <?php if ($vb_sobrescrever_valores) print " checked"; ?>> Substituir valores
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:10px">
                                    <div class="text-center">
                                        <button class="btn btn-primary btn-importar" type="submit">
                                            Importar
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="campo_formulario">
                            <?php
                                if (count($_FILES))
                                {
                                    $vs_arquivo_destino = "import/" . $_FILES["arquivo"]["name"];

                                    if (!move_uploaded_file($_FILES["arquivo"]["tmp_name"], $vs_arquivo_destino))
                                    {
                                        print "Erro ao salvar arquivo!";
                                        exit();
                                    }

                                    ini_set('output_buffering','On');
                                    ob_implicit_flush(true);
                                    ob_end_flush(); 

                                    $v_file = @fopen($vs_arquivo_destino, "r");

                                    if ($v_file) 
                                    {
                                        set_time_limit(3600);

                                        print "Importando...: ";
                                        
                                        $va_objeto = array();
                                        $contador_linhas = 1;
                                        
                                        while ( (($vs_linha = fgetcsv($v_file, 4096)) !== false) )
                                        {
                                            $va_atributos = $vs_linha;

                                            $vo_objeto = new $vs_id_objeto_tela;

                                            $vo_objeto->importar($va_atributos, $vn_usuario_logado_codigo, $vb_sobrescrever_valores);

                                            if ($contador_linhas > 1)
                                                print ", ";

                                            print trim($va_atributos[0]);
                                            @ob_flush();
                                            flush();

                                            $contador_linhas++;
                                        }

                                        if (!feof($v_file)) 
                                        {
                                            echo "Erro: falha na leitura do arquivo!\n";
                                        }

                                        fclose($v_file);
                                    }
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once dirname(__FILE__)."/components/footer.php"; ?>

</div>
</body>
</html>