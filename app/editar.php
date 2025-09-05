<?php

$vb_montar_menu = true;
require_once dirname(__FILE__) . "/components/entry_point.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>
<div id="backdrop-spinner">
    <div class="text-center loading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
    </div>
</div>

<?php
    require_once dirname(__FILE__) ."/components/sidebar.php";

    if (!$vb_pode_editar && !config::get(["f_acesso_leitura_form_cadastro"]) && !$vb_pode_inserir)
        exit();

    // editar.php será alterada para permitir edição em lote
    // Na edição em lote, os dados dos registros vêm no $_POST, não no $_GET
    ///////////////////////////////////////////////////////////////////////

    $va_objeto = array();
    $va_objeto_portugues = array();
    $vb_duplicacao = 0;
    $vb_back_from_saving = false;
    $vb_back_from_saving_and_dup = false;
    $vs_id_registro_salvo = "";
    $vn_objeto_codigo = "";
    $vn_formato_ficha_codigo = "";

    $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);

    $vb_acesso_invalido_registro = false;

    foreach ($vo_objeto->controlador_acesso as $vs_parametro_controlador => $vs_atributo_controlador)
    {
        if ($va_parametros_controle_acesso[$vs_parametro_controlador] == "_ALL_") continue;

        if ((trim($va_parametros_controle_acesso[$vs_parametro_controlador]) == "")  && ($vo_objeto->get_chave_primaria()[0] != $vs_parametro_controlador))
        {
            if (!isset($va_parametros_controle_acesso["_combinacao_"]) || (isset($va_parametros_controle_acesso["_combinacao_"]) && $va_parametros_controle_acesso["_combinacao_"] != "OR") )
                exit();
            else 
                $vb_acesso_invalido_registro = true;
        }
        elseif (isset($va_parametros_controle_acesso["_combinacao_"]) && $va_parametros_controle_acesso["_combinacao_"] == "OR")
        {
            $vb_acesso_invalido_registro = false;
            break;
        }
    }

    if ($vb_acesso_invalido_registro)
        exit();

    $va_chave_primaria = $vo_objeto->get_chave_primaria();
    if (is_array($va_chave_primaria) && count($va_chave_primaria))
        $vs_chave_primaria_objeto = reset($va_chave_primaria);
    else
        $vs_chave_primaria_objeto = $vs_id_objeto_tela . "_codigo";

    $vb_edicao_lote = false;

    if (isset($_POST['modo']) && ($_POST['modo'] == "lote"))
    {
        $vb_edicao_lote = true;

        $vs_formato_listagem = "default";
        require_once dirname(__FILE__). "/functions/montar_listagem.php";

        $va_objeto_codigo = array();
        $va_objetos_pais_codigos = array();

        foreach($va_itens_listagem as $va_item_listagem)
        {
            $va_objeto_codigo[] = $va_item_listagem[$vs_chave_primaria_objeto];

            $vo_objeto_temp = $vo_objeto;
            while ($vo_objeto_temp->get_objeto_pai())
            {
                $vs_id_objeto_pai = $vo_objeto_temp->get_objeto_pai();

                $vo_objeto_pai = new $vs_id_objeto_pai('');
                $vs_chave_primaria_objeto_pai = $vo_objeto_pai->get_chave_primaria()[0];

                $va_objetos_pais_codigos[$vs_chave_primaria_objeto_pai][] = $va_item_listagem[$vs_chave_primaria_objeto_pai];

                $vo_objeto_temp = $vo_objeto_pai;
            }
        }

        // Adiciona ao form os códigos dos registros
        ////////////////////////////////////////////

        $vn_objeto_codigo = implode("|", $va_objeto_codigo);

        // Adiciona ao form os códigos dos objetos pais de todos os registros
        /////////////////////////////////////////////////////////////////////

        foreach($va_objetos_pais_codigos as $vs_chave_primaria_objeto_pai => $va_objeto_pai_codigos)
        {
            $va_objeto[$vs_chave_primaria_objeto_pai] = implode("|", $va_objeto_pai_codigos);
        }

        $vs_modo = "lote";
    }
    else
    {
        if (isset($_GET['cod']))
            $vn_objeto_codigo = $_GET['cod'];

        if (isset($_GET['dup']))
            $vb_duplicacao = 1;

        if (isset($_GET['save']))
            $vb_back_from_saving = true;

        if (isset($_GET['save_dup']))
            $vb_back_from_saving_and_dup = true;

        if (isset($_COOKIE["formato_ficha_" . $vs_id_objeto_tela]))
            $vn_formato_ficha_codigo = $_COOKIE["formato_ficha_" . $vs_id_objeto_tela];

        $vs_modo = "edicao";
    }

    if ($vn_objeto_codigo && !$vb_edicao_lote) 
    {
        if (isset($_SESSION[$vs_id_objeto_tela]["numero_registros"]))
            $vn_numero_registros_listagem = $_SESSION[$vs_id_objeto_tela]["numero_registros"];
        else
            $vn_numero_registros_listagem = 1;

        $vn_numero_maximo_paginas = ceil($vn_numero_registros_listagem/20);

        $vn_pagina_atual = 1;
        if (isset($_SESSION[$vs_id_objeto_tela]["campo_paginacao"]) && isset($_SESSION[$vs_id_objeto_tela][$_SESSION[$vs_id_objeto_tela]["campo_paginacao"]]))
            $vn_pagina_atual = $_SESSION[$vs_id_objeto_tela][$_SESSION[$vs_id_objeto_tela]["campo_paginacao"]];
        else
            $_SESSION[$vs_id_objeto_tela]["campo_paginacao"] = "paginacao_topo";

        if (in_array($vn_objeto_codigo, ["p", "n", "f", "l"]))
        {
            if ( ($vn_objeto_codigo == "p") && ($vn_pagina_atual > 1) )
                $vn_pagina_atual = $vn_pagina_atual - 1;

            elseif ( ($vn_objeto_codigo == "n") && ($vn_pagina_atual < $vn_numero_maximo_paginas) )
                $vn_pagina_atual = $vn_pagina_atual + 1;

            elseif ( ($vn_objeto_codigo == "f") && isset($_SESSION[$vs_id_objeto_tela]["codigo_primeiro_lista"]) ) {
                $vn_pagina_atual = 1;
                $vn_objeto_codigo = $_SESSION[$vs_id_objeto_tela]["codigo_primeiro_lista"];
            }

            elseif ( ($vn_objeto_codigo == "l") && isset($_SESSION[$vs_id_objeto_tela]["codigo_ultimo_lista"]) ) {
                $vn_pagina_atual = $vn_numero_maximo_paginas;
                $vn_objeto_codigo = $_SESSION[$vs_id_objeto_tela]["codigo_ultimo_lista"];
            }

            $_SESSION[$vs_id_objeto_tela][$_SESSION[$vs_id_objeto_tela]["campo_paginacao"]] = $vn_pagina_atual;
            
            $vb_usar_parametros_sessao = true;
            $vs_modo = "listagem";
            
            require dirname(__FILE__)."/functions/montar_listagem.php";

            $vs_modo = "edicao";

            // A listagem_codigos só é atualizada após a chamada para /montar_listagem.php
            if ($vn_objeto_codigo == "p")
                $vn_objeto_codigo = end($_SESSION[$vs_id_objeto_tela]["listagem_codigos"]);
            elseif ($vn_objeto_codigo == "n")
                $vn_objeto_codigo = reset($_SESSION[$vs_id_objeto_tela]["listagem_codigos"]);
        }

        $va_itens_listagem_codigos = array();
        if (isset($_SESSION[$vs_id_objeto_tela]["listagem_codigos"]))
            $va_itens_listagem_codigos = $_SESSION[$vs_id_objeto_tela]["listagem_codigos"];

        if ($vo_objeto->validar_acesso_registro($vn_objeto_codigo, $va_parametros_controle_acesso))
            $va_objeto = $vo_objeto->ler($vn_objeto_codigo, "ficha", $vn_idioma_catalogacao_codigo);

        if (!count($va_objeto))
        {
            print "Não é possível carregar formulário de cadastro. (Erro ao ler o registro.)";
            exit();
        }

        if (($vb_back_from_saving || $vb_back_from_saving_and_dup) && $vo_objeto->get_atributo_identificador())
            $vs_id_registro_salvo = $va_objeto[$vo_objeto->get_atributo_identificador()];

        // Se o idioma de catalogação é diferente do Português, lê os valores em Português separadamente
        if ($vn_idioma_catalogacao_codigo != 1)
        {
            $vo_objeto_portugues = new $vs_id_objeto_tela($vs_id_objeto_tela);
            $va_objeto_portugues = $vo_objeto_portugues->ler($vn_objeto_codigo, "ficha", 1);
        }        

        if ($vb_duplicacao)
        {
            $vs_modo = "duplicacao";
            $vn_objeto_codigo = "";

            if ($vo_objeto->get_objeto_pai())
                $va_objeto[$vo_objeto->get_campo_relacionamento_pai()] = "";

            $va_objeto[$vo_objeto->get_atributo_identificador()] = "";
            $va_objeto["representante_digital_codigo"] = "";
        }

        //var_dump($va_objeto);
    }
    elseif (!$vb_pode_inserir)
    {
        exit();
    }
    else
    {
        if (count($_GET))
            $va_objeto = $_GET;

        if (isset($_GET['bibliografia']))
            $va_objeto["texto_bibliografia_codigo"] = $_GET['bibliografia'];
    }

    if (!isset($va_objeto["recurso_sistema_codigo"]))
        $va_objeto["recurso_sistema_codigo"] = $vn_recurso_sistema_codigo;

    // Se a instituição não é administradora, a instituição
    // a que o usuário pertence sempre é filtro de listagem
    ///////////////////////////////////////////////////////

    if (!$vb_usuario_logado_instituicao_admin)
        $va_objeto["instituicao_codigo"] = $vn_usuario_logado_instituicao_codigo;

    ///////////////////////////////////////////////////////

?>

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">

        <?php require_once dirname(__FILE__) ."/components/header.php"; ?>

        <form method="post" enctype="multipart/form-data" action="functions/salvar.php" id="form_cadastro">
            <input type="hidden" name="modo" id="modo" value="<?php print $vs_modo; ?>">
            <input type="hidden" name="recurso_sistema_codigo" id="recurso_sistema_codigo" value="<?php print $vn_recurso_sistema_codigo; ?>">
            <input type="hidden" name="obj" id="obj" value="<?php print $vs_id_objeto_tela; ?>">
            <input type="hidden" name="<?php print $vs_chave_primaria_objeto; ?>" id="<?php print $vs_chave_primaria_objeto; ?>" value="<?php print $vn_objeto_codigo; ?>">
            <input type="hidden" name="usuario_logado_codigo" id="usuario_logado_codigo" value="<?php print $vn_usuario_logado_codigo; ?>">

            <?php if (($vs_id_objeto_tela != "usuario") && (($vs_id_objeto_tela != "instituicao"))) {
            ?>
                <input type="hidden" name="instituicao_codigo" id="instituicao_codigo" value="<?php print $vn_usuario_logado_instituicao_codigo; ?>">
            <?php
            }
            ?>

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            
                            <div class="card-header row no-margin-side">
                                <div class="col-md-9 px-0">
                                <?php 
                                    if (!$vn_objeto_codigo)
                                        print $vs_nome_botao_novo . " ";
                                    
                                    print "<a class='link-sem-estilo' href='listar.php?obj=". $vs_id_objeto_tela . "'>";
                                    print "<u>". htmlspecialchars($vs_recurso_sistema_nome_singular) . "</u>";
                                    print "</a>";

                                    if ($vn_objeto_codigo && !$vb_duplicacao)
                                    {
                                        $vs_campo_identificador = "item_acervo_identificador";

                                        if (!empty($va_objeto[$vs_campo_identificador]))
                                        {
                                            print " (". htmlspecialchars($va_objeto[$vs_campo_identificador]) .")";
                                        }
                                    }
                                ?>
                                </div>


                                <?php
                                $vo_visualizacao = new visualizacao("");

                                $va_filtros_visualizacao = array();
                                $va_filtros_visualizacao["visualizacao_recurso_sistema_codigo"] = $vn_recurso_sistema_codigo;
                                $va_filtros_visualizacao["visualizacao_habilitado"] = 1;

                                if ($vn_objeto_codigo)
                                    $va_filtros_visualizacao["visualizacao_contexto_visualizacao_codigo"] = 2;
                                else
                                    $va_filtros_visualizacao["visualizacao_contexto_visualizacao_codigo"] = 1;

                                $va_itens = $vo_visualizacao->ler_lista($va_filtros_visualizacao);

                                if (count($va_itens) > 0) : ?>

                                    <div class="col-md-3">
                                    <?php
                                        $va_parametros_campo = [
                                            "html_combo_input",
                                            "nome" => "formato_ficha_" . $vs_id_objeto_tela,
                                            "label" => "Ficha",
                                            "objeto" => "visualizacao",
                                            "sem_valor" => false,
                                            "dependencia" => [
                                                [
                                                    "campo" => "recurso_sistema_codigo",
                                                    "atributo" => "visualizacao_recurso_sistema_codigo"
                                                ]
                                            ],
                                            "nao_montar_se_vazio" => true,
                                            "valor_padrao" => 0
                                        ];

                                        $vb_ficha_completa_novo_registro = config::get(["f_ficha_completa_novo_registro"]) ?? false;
                                        
                                        if ($vn_objeto_codigo || $vb_ficha_completa_novo_registro)
                                            $va_parametros_campo["valores"][0] = "Ficha completa";

                                        $vo_combo_formatos = new html_combo_input($vs_id_objeto_tela, "formato_ficha_" . $vs_id_objeto_tela, "header");

                                        if (isset($va_parametros_filtros_form))
                                            $va_valores = array_merge($_GET, $va_parametros_filtros_form);
                                        else
                                            $va_valores = $_GET;

                                        $va_valores["recurso_sistema_id"] = $vs_id_objeto_tela;
                                        $va_valores["recurso_sistema_codigo"] = $vn_recurso_sistema_codigo;

                                        // Se existe uma única visualização, ela é a padrão
                                        ///////////////////////////////////////////////////
                                        
                                        if ( ($vn_formato_ficha_codigo == "") && count($va_itens) == 1)
                                            $vn_formato_ficha_codigo = $va_itens[0]["visualizacao_codigo"];

                                        $va_valores["formato_ficha_" . $vs_id_objeto_tela] = $vn_formato_ficha_codigo;

                                        $vo_combo_formatos->build($va_valores, $va_parametros_campo);
                                    ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($vb_back_from_saving || $vb_back_from_saving_and_dup)
                            {
                            ?>
                                <div class="text-center alert alert-success">
                                <?php 
                                    if ($vb_back_from_saving)
                                    {
                                        if ($vs_id_registro_salvo)
                                            print "Registro <u>". htmlspecialchars($vs_id_registro_salvo) ."</u> salvo com sucesso";
                                        else
                                            print "Dados salvos com sucesso!";
                                    }
                                    else
                                    {
                                        if ($vs_id_registro_salvo)
                                            print "Registro ". htmlspecialchars($vs_id_registro_salvo) ." salvo e duplicado com sucesso";
                                        else
                                            print "Registro salvo e duplicado!";
                                    }
                                ?>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="card-body">

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                        <?php if ($vb_pode_inserir)
                                        {
                                        ?>
                                            <button class="btn btn-primary px-4 mt-4 mt-sm-0" type="button" id="btn_novo"><?php print $vs_nome_botao_novo; ?></button>
                                        <?php
                                        }
                                        ?>

                                        <div class="btn-group me-2 espacamento-esquerda-10 mt-4 mt-sm-0 flex-wrap" role="group" aria-label="First group">
                                            <?php if ($vn_objeto_codigo)
                                            {
                                            ?>
                                                <button class="btn btn-outline-primary" type="button" id="btn_ficha">Ficha</button>

                                                <?php if ($vb_pode_inserir) { ?>
                                                    <button class="btn btn-outline-primary" type="button" id="btn_duplicar">Duplicar</button>
                                                <?php } ?>

                                                <?php if ($vb_pode_excluir) { ?>
                                                    <button class="btn btn-outline-primary" type="button" id="btn_excluir">Excluir</button>
                                                <?php } ?>
                                            <?php
                                            }
                                            ?>

                                            <button class="btn btn-outline-primary" type="button" id="btn_voltar_lista">Voltar</button>
                                        </div>
                                        

                                        <?php
                                        if ($vn_objeto_codigo && count($va_itens_listagem_codigos) && !$vb_edicao_lote)
                                        {
                                            $vb_atualizar_codigos_listagem = false;
                                            $vb_exibir_botao_anterior = true;
                                            $vb_exibir_botao_proximo = true;

                                            $vb_primeiro_item_pagina = false;
                                            $vb_ultimo_item_pagina = false;

                                            $vn_indice_item_listagem = array_search($vn_objeto_codigo, $va_itens_listagem_codigos);

                                            if ($vn_indice_item_listagem == 0)
                                                $vb_primeiro_item_pagina = true;

                                            if ($vn_indice_item_listagem == (count($va_itens_listagem_codigos) - 1))
                                                $vb_ultimo_item_pagina = true;

                                            if (!$vb_primeiro_item_pagina)
                                                $vn_item_anterior_codigo = $va_itens_listagem_codigos[$vn_indice_item_listagem-1];
                                            else
                                                $vn_item_anterior_codigo = "p";

                                            if (!$vb_ultimo_item_pagina)
                                                $vn_proximo_item_codigo = $va_itens_listagem_codigos[$vn_indice_item_listagem+1];
                                            else
                                                $vn_proximo_item_codigo = "n";
                                            
                                            if (($vn_pagina_atual == 1) && $vb_primeiro_item_pagina)
                                                $vb_exibir_botao_anterior = false;

                                            if (($vn_pagina_atual == $vn_numero_maximo_paginas) && $vb_ultimo_item_pagina)
                                                $vb_exibir_botao_proximo = false;
                                        ?>

                                            <div class="btn-group me-2 espacamento-esquerda-10 flex-wrap mt-4 mt-sm-0" role="group" aria-label="Second group">
                                                <?php if ($vb_exibir_botao_anterior)
                                                {
                                                ?>
                                                    <button class="btn btn-outline-primary btn-nav flex-centered h-40" type="button" id="btn_primeiro" value="f"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                                                          <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-outline-primary btn-nav flex-centered h-40" type="button" id="btn_nav" value="<?php print $vn_item_anterior_codigo; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
                                                        </svg>
                                                    </button>
                                                <?php
                                                }
                                                ?>

                                                <?php if ($vb_exibir_botao_proximo)
                                                {
                                                ?>
                                                    <button class="btn btn-outline-primary btn-nav flex-centered h-40" type="button" id="btn_proximo" value="<?php print $vn_proximo_item_codigo; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-outline-primary btn-nav flex-centered h-40" type="button" id="btn_ultimo" value="l"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                                                          <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708"/>
                                                          <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708"/>
                                                        </svg>
                                                    </button>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    
                                    <?php
                                        $vb_exibir_botao_salvar_duplicar = config::get(["f_exibir_botao_salvar_duplicar"]) ?? false;
                                    ?>

                                    <div class="col-md-3 text-right">
                                        <?php if (!$vn_objeto_codigo && $vb_exibir_botao_salvar_duplicar) { ?>
                                            <button class="btn btn-primary btn-salvar-duplicar" type="button" id="btn_salvar_duplicar_top">
                                                Salvar e duplicar
                                            </button>
                                        <?php } ?>

                                        <?php if ($vb_pode_editar || $vb_pode_inserir) { ?>
                                            <button class="btn btn-primary btn-salvar" type="button" id="btn_salvar_top">
                                                Salvar
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>

                                <br>

                                <!-- FORM-->
                                <div class="row no-margin-side" id="filtro">
                                    <div class="col-12">

                                        <?php
                                            $pn_objeto_codigo = $vn_objeto_codigo;

                                            require dirname(__FILE__) ."/functions/configurar_campos_tela.php";

                                            $vo_form_cadastro = new html_form_cadastro($vs_id_objeto_tela, $va_abas_form, $va_campos, $va_objeto, $va_objeto_portugues, $va_recursos_sistema_permissao_edicao);
                                            
                                            $vs_campo_foco = $vo_form_cadastro->build($vn_objeto_codigo, $vn_usuario_logado_instituicao_codigo, $vn_usuario_logado_acervo_codigo, $vs_modo, $vb_ler_campos_banco);
                                        ?>

                                    </div>
                                </div>
                                <!-- / FORM-->
                                <br>

                                <div class="row">
                                    <div class="filter-documents -new col-md-9">
                                    </div>
                                    
                                    <div class="col-md-3 text-right">
                                        <?php if (!$vn_objeto_codigo && $vb_exibir_botao_salvar_duplicar) { ?>
                                            <button class="btn btn-primary btn-salvar-duplicar" type="button" id="btn_salvar_duplicar_bottom">
                                                Salvar e duplicar
                                            </button>
                                        <?php } ?>

                                        <?php if ($vb_pode_editar || $vb_pode_inserir) { ?>
                                            <button class="btn btn-primary btn-salvar" type="button" id="btn_salvar_bottom">
                                                Salvar
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                </div>
                <!-- /.row-->
            </div>

            <div class="container-lg" id="div-image-container">
            </div>

        </div>

    </form>

</div>

<?php require_once dirname(__FILE__)."/components/footer.php"; ?>

<script>

var vb_alterou_cadastro = false;

$(document).ready(function() 
{
    <?php
    if ($vn_objeto_codigo)
    {
    ?>
        vb_alterou_cadastro = false;
    <?php
    }
    ?>

    <?php
    if ($vs_campo_foco)
    {
    ?>
        if(!$("#<?php print $vs_campo_foco; ?>").prop("disabled"))
            $("#<?php print $vs_campo_foco; ?>").focus();
    <?php
    }
    ?>
});

$(document).on('click', ".btn-tab", function() {
    $('.btn-tab').removeClass('active');
    $('.tab').hide();
    $('#tab_'+$(this).attr('id')).show();
    $(this).addClass('active');
});

$(document).on('click', ".btn-salvar-duplicar", function() {
    $("#modo").val("dup");
    
    salvar();
});

$(document).on('click', ".btn-salvar", function() {
    salvar();
});

function salvar()
{
    vb_alterou_cadastro = false;

    $.post("functions/validar_dados_form.php", $.param($("#form_cadastro").serializeArray()), function(response)
    {
        if (response == 1) {
            $("#form_cadastro").submit();
        } else {
            alert(response);
            //console.log(response);
        }
    });
}

$(window).bind('beforeunload', function() {
    if (vb_alterou_cadastro)
        return 1;
});

$(document).on('keyup', ".numero", function(event)
{
    vb_alterou_cadastro = true;
});

$(document).on('change', ".combo", function(event)
{
    vb_alterou_cadastro = true;
});

$(document).on('click', "#btn_voltar_lista", function()
{
    vs_url_voltar = "listar.php?obj=<?php print $vs_id_objeto_tela; ?>&back=1";

    <?php
        if ($vn_bibliografia_codigo)
        {
        ?>
            vs_url_voltar = vs_url_voltar + "&bibliografia=" + <?php print $vn_bibliografia_codigo; ?>;
        <?php
        }
    ?>

    window.location.href = vs_url_voltar;
});

$(document).on('click', "#btn_novo", function()
{
    vs_url_novo = window.location.href = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>";

    <?php if ($vn_bibliografia_codigo)
    {
    ?>
    
    vs_url_novo = vs_url_novo + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
    
    <?php
    }
    ?>

    window.location.href= vs_url_novo;    
});

$(document).on('click', ".btn-nav", function() 
{
    window.location.href = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>&cod="+$(this).val();
});

$(document).on('click', "#btn_ficha", function()
{
    vs_url_ficha = "ficha.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";

    <?php if ($vn_bibliografia_codigo)
    {
    ?>
    
    vs_url_ficha = vs_url_ficha + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
    
    <?php
    }
    ?>

    window.location.href= vs_url_ficha;
});

$(document).on('click', "#btn_duplicar", function()
{
    vs_url_duplicar = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>&dup=1";

    <?php if ($vn_bibliografia_codigo)
    {
    ?>
    
    vs_url_duplicar = vs_url_duplicar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
    
    <?php
    }
    ?>

    window.location.href= vs_url_duplicar;
});

$(document).on('change', "#formato_ficha_<?php print $vs_id_objeto_tela; ?>", function()
{
    document.cookie = "formato_ficha_<?php print $vs_id_objeto_tela; ?>=" + $(this).val();

    vs_url_alterar_formato = "editar.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";


    <?php if ($vb_duplicacao)
    {
    ?>

    vs_url_alterar_formato = vs_url_alterar_formato + "&dup=1";

    <?php
    }
    ?>

    <?php if ($vn_bibliografia_codigo)
    {
    ?>
    
    vs_url_alterar_formato = vs_url_duplicar + "&bibliografia=<?php print $vn_bibliografia_codigo; ?>"
    
    <?php
    }
    ?>

    window.location.href = vs_url_alterar_formato;
});

$(document).on('click', "#btn_representantes_digitais", function()
{
    window.location.href = "representantes_digitais.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";
});

$(document).on('click', "#btn_excluir", function()
{
    window.location.href = "confirmar_exclusao.php?obj=<?php print $vs_id_objeto_tela; ?>&cod=<?php print $vn_objeto_codigo; ?>";
});

<?php
foreach($va_campos as $vs_key_campo => $va_parametros_campo)
{

if (isset($va_parametros_campo["regra_exibicao"]))
{
    foreach($va_parametros_campo["regra_exibicao"] as $vs_campo => $va_valores_desejados)
    {
        if (!is_array($va_valores_desejados))
            $va_valores_desejados = array($va_valores_desejados);

        $vs_valores_desejados = implode("|", $va_valores_desejados);
    }
    ?>

    function atualizar_exibicao_<?php print $vs_key_campo; ?>(ps_valor)
    {
        atualizar_exibicao_campo('<?php print $vs_key_campo; ?>', ps_valor, '<?php print $vs_valores_desejados; ?>', '<?php print $va_parametros_campo[0]; ?>');
    }

<?php
}

// Vamos verificar se os subcampos do campo contém regras de exibição
/////////////////////////////////////////////////////////////////////

if (isset($va_parametros_campo["subcampos"]))
{
    foreach ($va_parametros_campo["subcampos"] as $vs_key_subcampo => $va_subcampo)
    {
        if (isset($va_subcampo["regra_exibicao"]))
        {
        ?>

            function atualizar_exibicao_<?php print $vs_key_subcampo; ?>(ps_sufixo, ps_valor)
            {
                <?php
                foreach($va_subcampo["regra_exibicao"] as $vs_campo => $va_valores_desejados)
                {
                    if (!is_array($va_valores_desejados))
                        $va_valores_desejados = array($va_valores_desejados);

                    $vs_valores_desejados = implode("|", $va_valores_desejados);
                }
                ?>

                //console.log()
                atualizar_exibicao_campo('<?php print $vs_key_subcampo; ?>'+ps_sufixo, ps_valor, '<?php print $vs_valores_desejados; ?>', '<?php print $va_subcampo[0]; ?>');
            }

        <?php
        }
    }
}

}
?>

function atualizar_exibicao_campo(ps_campo, ps_valor, ps_valores_desejados, ps_tipo_campo)
{
    vb_exibir_campo = false;

    vs_campo_desabilitar = ps_campo;
    if (ps_tipo_campo == "html_multi_itens_input")
        vs_campo_desabilitar = "numero_" + ps_campo;

    pa_valores_desejados = ps_valores_desejados.split("|");

    let i = 0;
    while (i < pa_valores_desejados.length)
    {
        v_valor_desejado = pa_valores_desejados[i];

        if (v_valor_desejado == "nao_vazio") 
        {
            v_valor_desejado_campo = "''";
            vs_operador = "!=";
        } 
        else if (v_valor_desejado.substring(0, 2) == "<>") 
        {
            v_valor_desejado_campo = v_valor_desejado.replace("<>", "");
            vs_operador = "!=";
        }
        else 
        {
            v_valor_desejado_campo = v_valor_desejado;
            vs_operador = "==";
        }

        if ( (typeof ps_valor) == "string" )
            va_valores = ps_valor.split("|");

        else if ((typeof ps_valor) == "boolean")
        {
            if (ps_valor)
                va_valores = ['1'];
            else
                va_valores = ['0'];
        }

        for (v_valor in va_valores)
        {
            ps_valor = va_valores[v_valor];

            switch (vs_operador)
            {
                case "==":
                    if (ps_valor == v_valor_desejado_campo)
                        vb_exibir_campo = true;
                    
                    break;

                case "!=":
                    if (ps_valor != v_valor_desejado_campo)
                        vb_exibir_campo = true;
                    
                    break;
            }
        }

        i++;
    }
    
    if (vb_exibir_campo)
    {
        $("#div_"+ps_campo).show();
        desabilitar_campo(vs_campo_desabilitar, false, ps_tipo_campo);
    }
    else
    {
        $("#div_"+ps_campo).hide();
        desabilitar_campo(vs_campo_desabilitar, true, ps_tipo_campo);
    }
}

function desabilitar_campo(ps_campo, pb_desabilitar, ps_tipo_campo) 
{
    vs_tipo_campo = $("#" + ps_campo).attr("class");

    switch (vs_tipo_campo) 
    {
        case "lookup":
            $("#" + ps_campo + "_codigo").prop("disabled", pb_desabilitar);
            break;

        default:
            $("#" + ps_campo).prop("disabled", pb_desabilitar);
            break;
    }

    if (ps_tipo_campo == 'html_date_input') 
    {
        $("#" + ps_campo + "_dia_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_mes_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_ano_inicial").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_dia_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_mes_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_ano_final").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_presumido").prop("disabled", pb_desabilitar);
        $("#" + ps_campo + "_sem_data").prop("disabled", pb_desabilitar);
    }
    else if (ps_tipo_campo == "html_multi_itens_input")
    {
        $("#" + ps_campo).prop("disabled", false);

        if (pb_desabilitar)
            $("#" + ps_campo).val(0);
    } 
}

function toggle_detalhes(ps_campo)
{
    var div = document.getElementById("div_campos_linha_"+ps_campo);

    div.style.display=div.style.display=='none'?'':'none';

    var elems = document.querySelectorAll("#btn_detalhes_"+ps_campo);

    if(div.style.display === '')
    {
        [].forEach.call(elems, function(el)
        {
            el.classList.remove("dropdown-toggle");
            el.classList.add("dropdown-toggle-revert");
        });
    }
    else
    {
        [].forEach.call(elems, function(el)
        {
            el.classList.remove("dropdown-toggle-revert");
            el.classList.add("dropdown-toggle");
        });
    }

    //$("#filtro").find("#div_campos_linha_'+ps_campo").first().focus();

    return false;
}

</script>

</body>

</html>