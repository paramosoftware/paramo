<?php

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php
    require_once dirname(__FILE__) ."/components/sidebar.php";
?>

<div class="wrapper d-flex flex-column min-vh-100 bg-light">
    <?php require_once dirname(__FILE__) ."/components/header.php"; ?>

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">Validar importação <?php print $vs_recurso_sistema_nome_plural; ?></div>

                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="validar_migracao.php">
                                <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
                                
                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="file" name="arquivo" class="form-control">

                                            <input type="file" name="variacoes" class="form-control">

                                            <input type="file" name="blacklist" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:10px">
                                    <div class="text-center">
                                        <button class="btn btn-primary btn-importar" type="submit">
                                            Validar
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="campo_formulario">
                            <?php
                                if (count($_FILES))
                                {
                                    $vs_arquivo_destino = "import/" . $_FILES["arquivo"]["name"];
                                    $vs_arquivo_variacoes_termo = "";
                                    $vs_arquivo_blacklist = "";

                                    if (!move_uploaded_file($_FILES["arquivo"]["tmp_name"], $vs_arquivo_destino))
                                    {
                                        print "Erro ao salvar arquivo!";
                                        exit();
                                    }

                                    if (isset($_FILES["variacoes"]["tmp_name"]))
                                    {
                                        $vs_arquivo_variacoes_termo = "import/" . $_FILES["variacoes"]["name"];

                                        if (!move_uploaded_file($_FILES["variacoes"]["tmp_name"], $vs_arquivo_variacoes_termo))
                                        {
                                            print "Erro ao salvar arquivo!";
                                            exit();
                                        }
                                    }

                                    if (isset($_FILES["blacklist"]["tmp_name"]))
                                    {
                                        $vs_arquivo_blacklist = "import/" . $_FILES["blacklist"]["name"];

                                        if (!move_uploaded_file($_FILES["blacklist"]["tmp_name"], $vs_arquivo_blacklist))
                                        {
                                            print "Erro ao salvar arquivo!";
                                            exit();
                                        }
                                    }

                                    ini_set('output_buffering','On');
                                    ob_implicit_flush(true);
                                    ob_end_flush(); 

                                    $v_file = @fopen($vs_arquivo_destino, "r");

                                    if ($v_file) 
                                    {
                                        set_time_limit(3600);

                                        print "Validando...: ";
                                        
                                        $va_objeto = array();
                                        $va_identificadores_objetos_relacionados = array();

                                        $contador_linhas = 1;
                                        
                                        while ( (($vs_linha = fgetcsv($v_file, 4096)) !== false) )
                                        {
                                            $va_atributos = $vs_linha;

                                            $vo_objeto = new $vs_id_objeto_tela;

                                            $vo_importacao = new importacao();
                                            $va_importacao = $vo_importacao->ler_lista(["importacao_recurso_sistema_codigo" => $vo_objeto->get_recurso_sistema_codigo()], "ficha");

                                            $contador_campos = 0;

                                            foreach($va_importacao[0]["importacao_campo_sistema_codigo"] as $va_campo_importacao)
                                            {
                                                $vs_campo_importacao_nome = $va_campo_importacao["campo_sistema_nome"];

                                                $va_campos_importacao[$vs_campo_importacao_nome] = $va_campo_importacao;
                                                $va_valores_origem[$vs_campo_importacao_nome] = trim($va_atributos[$contador_campos]);

                                                $contador_campos++;
                                            }

                                            foreach($va_campos_importacao as $vs_key_campo_importacao => $va_campo_importacao)
                                            {
                                                $vs_valor = $va_valores_origem[$vs_key_campo_importacao];

                                                if ($va_campo_importacao["campo_sistema_identificador_recurso_sistema"])
                                                {
                                                    // Se é um campo que identifica o registro, tenta ler o código do registro do banco
                                                    // Verifica se o campo identificador pertence ao objeto pai
                                                    // (Mas, na verdade tinha que fazer um loop até o objeto não possuir mais pai)
                                                    
                                                    if ($vo_objeto->get_objeto_pai())
                                                    {
                                                        $vs_objeto_pai = $vo_objeto->get_objeto_pai();
                                                        $vo_objeto_pai = new $vs_objeto_pai;
                                                        
                                                        if (isset($vo_objeto_pai->get_atributos()[$vs_key_campo_importacao]))
                                                            $va_parametro_leitura[$vo_objeto->get_campo_relacionamento_pai() . "_0_" . $vs_key_campo_importacao] = $vs_valor;
                                                    }
                                                    elseif (isset($vo_objeto->get_atributos()[$vs_key_campo_importacao]))
                                                        $va_parametro_leitura[$vs_key_campo_importacao] = $vs_valor;

                                                    $va_objeto = $vo_objeto->ler_lista($va_parametro_leitura, "ficha");

                                                    if (count($va_objeto)) 
                                                    {
                                                        $va_objeto = $va_objeto[0];
                                                    }
                                                }
                                            }

                                            if (count($va_objeto))
                                            {
                                                $va_valores_nao_encontrados = array();

                                                foreach($va_campos_importacao as $vs_key_campo_importacao => $va_campo_importacao)
                                                {
                                                    if (!$va_campo_importacao["campo_sistema_identificador_recurso_sistema"])
                                                    {
                                                        $vs_valor = $va_valores_origem[$vs_key_campo_importacao];
                                                        $va_valor = explode(";", $vs_valor);

                                                        $vs_objeto_relacionado_campo_identificador = "";
                                                        
                                                        if ( ($va_campo_importacao["campo_sistema_tipo_codigo"]["tipo_campo_sistema_codigo"] == 5) && isset($va_objeto[$vs_key_campo_importacao]))
                                                        {
                                                            $vn_objeto_relacionado_recurso_sistema_codigo = $va_campo_importacao["campo_sistema_objeto_chave_estangeira_codigo"]["recurso_sistema_codigo"];

                                                            if (isset($va_identificadores_objetos_relacionados[$vn_objeto_relacionado_recurso_sistema_codigo]))
                                                                $vs_objeto_relacionado_campo_identificador = $va_identificadores_objetos_relacionados[$vn_objeto_relacionado_recurso_sistema_codigo];
                                                            
                                                            if (!$vs_objeto_relacionado_campo_identificador)
                                                            {
                                                                $vo_campo_sistema = new campo_sistema;
                                                                $va_campos_sistema_objeto_relacionado = $vo_campo_sistema->ler_lista(["campo_sistema_recurso_sistema_codigo" => $vn_objeto_relacionado_recurso_sistema_codigo]);

                                                                foreach ($va_campos_sistema_objeto_relacionado as $va_campo_objeto_relacionado)
                                                                {
                                                                    if ($va_campo_objeto_relacionado["campo_sistema_identificador_recurso_sistema"])
                                                                    {
                                                                        $vs_objeto_relacionado_campo_identificador = $va_campo_objeto_relacionado["campo_sistema_nome"];

                                                                        $va_identificadores_objetos_relacionados[$vn_objeto_relacionado_recurso_sistema_codigo] = $vs_objeto_relacionado_campo_identificador;
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        foreach($va_valor as $vs_valor)
                                                        {
                                                            if (trim($vs_valor))
                                                            {
                                                                $va_valor_original = explode(" ", trim($vs_valor));
                                                                $va_valor_tratado = array();

                                                                foreach($va_valor_original as $vs_parte_valor_original)
                                                                {
                                                                    if (trim($vs_parte_valor_original) != "")
                                                                        $va_valor_tratado[] = trim($vs_parte_valor_original);
                                                                }

                                                                $vs_valor = implode(" ", $va_valor_tratado);

                                                                $vb_achou_valor = false;

                                                                if ($vs_objeto_relacionado_campo_identificador)
                                                                {
                                                                    foreach ($va_objeto[$vs_key_campo_importacao] as $va_objeto_relacionado_importado)
                                                                    {
                                                                        if (trim(strtolower($vs_valor)) == trim(strtolower($va_objeto_relacionado_importado[$vs_objeto_relacionado_campo_identificador])))
                                                                            $vb_achou_valor = true;

                                                                        //var_dump($vs_valor, $va_objeto_relacionado_importado[$vs_objeto_relacionado_campo_identificador], $vb_achou_valor);
                                                                    }                                                         
                                                                
                                                                    if (!$vb_achou_valor)
                                                                    {
                                                                        if ($vs_arquivo_variacoes_termo)
                                                                        {
                                                                            $v_file_variacoes_termo = @fopen($vs_arquivo_variacoes_termo, "r");

                                                                            if ($v_file_variacoes_termo) 
                                                                            {
                                                                                while ( (($vs_linha_variacoes_termo = fgetcsv($v_file_variacoes_termo, 4096)) !== false) )
                                                                                {
                                                                                    if (trim(strtolower($vs_linha_variacoes_termo[0])) == trim(strtolower($vs_valor)))
                                                                                    {
                                                                                        foreach ($va_objeto[$vs_key_campo_importacao] as $va_objeto_relacionado_importado)
                                                                                        {
                                                                                            if (trim($vs_linha_variacoes_termo[1]) == trim($va_objeto_relacionado_importado[$vs_objeto_relacionado_campo_identificador]))
                                                                                                $vb_achou_valor = true;
                                                                                        }    
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                if (!$vb_achou_valor)
                                                                {
                                                                    if ($vs_arquivo_blacklist)
                                                                    {
                                                                        $v_file_blacklist = @fopen($vs_arquivo_blacklist, "r");

                                                                        if ($v_file_blacklist) 
                                                                        {
                                                                            while ( (($vs_linha_blacklist = fgetcsv($v_file_blacklist, 4096)) !== false) )
                                                                            {
                                                                                if (trim(strtolower($vs_linha_blacklist[0])) == trim(strtolower($vs_valor)))
                                                                                {
                                                                                    $vs_valor = $vs_valor . " (blacklisted)";
                                                                                    //$vb_achou_valor = true;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }

                                                                if (!$vb_achou_valor)
                                                                    $va_valores_nao_encontrados[] = $vs_valor;
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            //$vo_objeto->importar($va_atributos, $vn_usuario_logado_codigo, $vb_sobrescrever_valores);

                                            //if ($contador_linhas > 1)
                                                //print ", ";

                                            //var_dump($va_valores_nao_encontrados);exit();
                                            if (count($va_valores_nao_encontrados) && true)
                                            {
                                                print "<br>" . $contador_linhas . ": " . trim($va_atributos[0]);
                                                print " [" . implode(" | ", $va_valores_nao_encontrados) . "]";
                                            }

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