<?php
    if (!defined("AUTOLOAD"))
    {
        require_once dirname(__FILE__) . "/../../autoload.php";
    }

    require dirname(__FILE__) . "/../components/debug.php";
    require_once dirname(__FILE__) . "/../components/ler_valor.php";

    $vn_usuario_codigo = session::get_logged_user();

    if (!$vn_usuario_codigo)
    {
        session::logout();
    }

    $vb_ler_campos_banco = $vb_ler_campos_banco ?? false;
    $vo_usuario = $vb_ler_campos_banco ? new objeto_base("usuario") : new usuario;
    $va_usuario = $vo_usuario->ler($vn_usuario_codigo, "ficha");

    if (empty($va_usuario))
    {
        session::logout();
    }

    if (!isset($vb_modo_navegacao))
        $vb_modo_navegacao = false;

    if (!isset($vb_montar_menu))
        $vb_montar_menu = false;

    if (!isset($vs_id_objeto_tela))
    {
        if (isset($_POST['obj']))
            $vs_id_objeto_tela = $_POST['obj'];
        elseif (isset($_GET['obj']))
            $vs_id_objeto_tela = $_GET['obj'];
        else
            $vs_id_objeto_tela = "";
    }

    $vn_setor_sistema_acessado_codigo = "";
    if (isset($_GET['s']))
    {
        $vn_setor_sistema_acessado_codigo = $_GET['s'];
        $_SESSION["setor_sistema_acessado_codigo"] = [$vs_id_objeto_tela => $vn_setor_sistema_acessado_codigo];
    }
    elseif (isset($_SESSION["setor_sistema_acessado_codigo"][$vs_id_objeto_tela]))
    {
        $vn_setor_sistema_acessado_codigo = $_SESSION["setor_sistema_acessado_codigo"][$vs_id_objeto_tela];
    }

    if (isset($_POST["instituicao_logado_como"]) && config::get(["f_logado_como"]))
    {
        if ($_POST["instituicao_logado_como"] == "0")
        {
            unset($_SESSION["instituicao_logado_como"]);
        }
        else
        {
            $vn_instituicao_logado_como = $_POST["instituicao_logado_como"];
            $_SESSION["instituicao_logado_como"] = $vn_instituicao_logado_como;
        }
    }


    // Novo modo de funcionamento do sistema: Acervo ou Bibliografia
    // Por isso vou ter que checar se a bibliografia vem ou não escolhida
    /////////////////////////////////////////////////////////////////////

    $vn_bibliografia_codigo = "";
    if (isset($_GET['bibliografia']))
        $vn_bibliografia_codigo = $_GET['bibliografia'];
        
    $vn_recurso_sistema_codigo = "";
    $vs_recurso_sistema_nome_singular = "";
	$vs_recurso_sistema_nome_plural = "";
    $vn_recurso_sistema_genero_gramatical_codigo = 1;

    // Lê as informações do recurso do sistema que vai ser manipulado
    /////////////////////////////////////////////////////////////////

    if ($vs_id_objeto_tela)
    {
        $vo_recurso_sistema = new recurso_sistema();
        $va_recurso_sistema = $vo_recurso_sistema->ler_lista(['recurso_sistema_id' => $vs_id_objeto_tela], "lista", 1, 1);

        if (count($va_recurso_sistema))
        {
            $va_recurso_sistema = $va_recurso_sistema[0];

            $vn_recurso_sistema_codigo = $va_recurso_sistema["recurso_sistema_codigo"];

            if (isset($va_recurso_sistema["recurso_sistema_nome_singular"]))
                $vs_recurso_sistema_nome_singular = $va_recurso_sistema["recurso_sistema_nome_singular"];

            if (isset($va_recurso_sistema["recurso_sistema_nome_plural"]))
	            $vs_recurso_sistema_nome_plural = $va_recurso_sistema["recurso_sistema_nome_plural"];
            
            if (isset($va_recurso_sistema["recurso_sistema_genero_gramatical_codigo"]["genero_gramatical_codigo"]))
                $vn_recurso_sistema_genero_gramatical_codigo = $va_recurso_sistema["recurso_sistema_genero_gramatical_codigo"]["genero_gramatical_codigo"];

        }
        elseif (!$vb_modo_navegacao)
            exit();
    }

    if (!isset($vn_idioma_catalogacao_codigo))
    {
        if (isset($_POST['idioma']))
            $vn_idioma_catalogacao_codigo = $_POST['idioma'];
        elseif (isset($_GET['idioma']))
            $vn_idioma_catalogacao_codigo = $_GET['idioma'];
        else
            $vn_idioma_catalogacao_codigo = 1;
    }
    
    $vn_usuario_logado_instituicao_codigo = "";
    $vs_usuario_logado_instituicao_nome = "";
    $vb_usuario_logado_instituicao_admin = false;

    $va_usuario_logado_setores_sistema = array();
    $vn_usuario_logado_setor_sistema_codigo = "";

    $vn_usuario_logado_acervo_codigo = "";

    $vb_usuario_administrador = false;
    $vb_usuario_externo = false;
    $va_usuario_grupos_usuario = array();
    
    $vb_pode_ler = false;
    $vb_pode_inserir = false;
    $vb_pode_editar = false;
    $vb_pode_substituir = false;
    $vb_pode_excluir = false;
    $vb_pode_editar_lote = false;
    $vb_pode_excluir_lote = false;


    $_SESSION["usuario_logado_codigo"] = $va_usuario["usuario_codigo"];
    $vn_usuario_logado_codigo = $va_usuario["usuario_codigo"];
    $vs_usuario_logado_nome = $va_usuario["usuario_nome"];

    if (isset($va_usuario["usuario_instituicao_codigo"]["instituicao_codigo"]))
    {
        $vn_usuario_logado_instituicao_codigo = $va_usuario["usuario_instituicao_codigo"]["instituicao_codigo"];
        $vs_usuario_logado_instituicao_nome = $va_usuario["usuario_instituicao_codigo"]["instituicao_nome"];
        $vb_usuario_logado_instituicao_admin = $va_usuario["usuario_instituicao_codigo"]["instituicao_admin"] ?? 0;
    }

    // O código do usuário admin é hardcoded até segunda ordem
    //////////////////////////////////////////////////////////

    if ($va_usuario["usuario_tipo_codigo"]["tipo_usuario_codigo"] == 2)
        $vb_usuario_administrador = true;

    if ($va_usuario["usuario_tipo_codigo"]["tipo_usuario_codigo"] == 3)
    {
        $vb_usuario_externo = true;
        $vb_pode_ler = true;

        $vo_selecao = new selecao;
            
        $va_selecoes_compartilhadas = $vo_selecao->ler_lista(["selecao_usuario_compartilhamento_codigo" => $vn_usuario_logado_codigo], "lista");

        $va_selecoes_compartilhadas_codigos = array();

        foreach ($va_selecoes_compartilhadas as $va_selecao)
        {
            $va_selecoes_compartilhadas_codigos[] = $va_selecao["selecao_codigo"];
        }
    }

    //////////////////////////////////////////////////////////

    if (
        !($vb_usuario_administrador && $vb_usuario_logado_instituicao_admin)
        &&
        (in_array($vs_id_objeto_tela, ["grupo_usuario"]) || in_array($vs_id_objeto_tela, config::get(["sidebar"])["configuracoes"]))
    )
    {
        exit();
    }

    $va_usuario_logado_setores_codigos = array();
    $va_usuario_logado_setores_nomes = array();

    $vo_setor_sistema = new setor_sistema('');

    if ($vb_usuario_administrador)
    {
        $va_usuario_logado_setores_sistema = $vo_setor_sistema->ler_lista(null, "navegacao");
    }
    elseif (isset($va_usuario["usuario_setor_sistema_codigo"]))
    {
        $va_usuario_setores_sistema = array();
        foreach ($va_usuario["usuario_setor_sistema_codigo"] as $va_usuario_setor_sistema)
        {
            $va_usuario_setores_sistema[] = $va_usuario_setor_sistema["usuario_setor_sistema_codigo"]["setor_sistema_codigo"];
        }

        if (count($va_usuario_setores_sistema))
            $va_usuario_logado_setores_sistema = $vo_setor_sistema->ler_lista(["setor_sistema_codigo" => implode("|", $va_usuario_setores_sistema)], "navegacao");
    }

    foreach ($va_usuario_logado_setores_sistema as $va_setor)
    {
        $va_usuario_logado_setores_codigos[] = $va_setor['setor_sistema_codigo'];
        $va_usuario_logado_setores_nomes[] = $va_setor['setor_sistema_nome'];
    }

    $vn_usuario_logado_setor_sistema_codigo = join("|", $va_usuario_logado_setores_codigos);

    ////////////////////////////////////////////////////////////////////////////////

    $va_usuario_logado_acervos = array();
    $va_usuario_logado_acervos_codigos = array();
    $va_usuario_logado_acervos_nomes = array();

    $vb_controlar_acesso_acervo = config::get(["controle_acesso", "_atributos_", "acervo_codigo"]) ?? false;

    // Verifica se o usuário faz parte de um grupo para
    // o qual não é preciso controlar acesso a acervos
    ///////////////////////////////////////////////////

    $vb_controlar_acesso_acervo_usuario = true;

    if ($vb_controlar_acesso_acervo)
    {
        if (isset($va_usuario['usuario_grupo_usuario_codigo']))
        {
            foreach ($va_usuario['usuario_grupo_usuario_codigo'] as $va_grupo_usuario)
            {
                if (isset($va_grupo_usuario["usuario_grupo_usuario_codigo"]["grupo_usuario_codigo"]) && !$va_grupo_usuario["usuario_grupo_usuario_codigo"]["grupo_usuario_controlar_acesso_acervos"])
                {
                    $vb_controlar_acesso_acervo_usuario = false;
                    break;
                }
            }
        }
    }

    $va_instituicoes = array();
    
    if (($vb_usuario_administrador && $vb_usuario_logado_instituicao_admin) || ($vb_controlar_acesso_acervo === FALSE))
    {
        $vo_instituicao = new instituicao('');
        $va_instituicoes = $vo_instituicao->ler_lista();
        $va_usuario_logado_instituicoes_codigos = array();

        foreach ($va_instituicoes as $va_instituicao)
        {
            $va_usuario_logado_instituicoes_codigos[] = $va_instituicao['instituicao_codigo'];
        }

        $vn_usuario_logado_instituicao_codigo = join("|", $va_usuario_logado_instituicoes_codigos);

        $vo_acervo = new acervo('');

        $va_filtro_acervo = array();

        if ($vn_setor_sistema_acessado_codigo)
            $va_filtro_acervo["acervo_setor_sistema_codigo"] = $vn_setor_sistema_acessado_codigo;

        if (isset($_SESSION["instituicao_logado_como"]))
        {
            $va_filtro_acervo["acervo_instituicao_codigo"] = $_SESSION["instituicao_logado_como"];

            $va_usuario_logado_acervos = $vo_acervo->ler_lista($va_filtro_acervo);

            $vn_usuario_logado_instituicao_codigo = $_SESSION["instituicao_logado_como"];

            $vo_instituicao = new instituicao();
            $va_instituicao = $vo_instituicao->ler_lista(["instituicao_codigo" => $vn_usuario_logado_instituicao_codigo], "ficha");

            $vs_usuario_logado_instituicao_nome = $va_instituicao[0]["instituicao_nome"];
            $vb_usuario_logado_instituicao_admin = $va_instituicao[0]["instituicao_admin"] ?? 0;
        }
        else
        {
            $va_usuario_logado_acervos = $vo_acervo->ler_lista($va_filtro_acervo);
        }
    }
    elseif ($vb_usuario_administrador || !$vb_controlar_acesso_acervo_usuario)
    {
        $vo_acervo = new acervo('');

        $va_filtro_acervo["acervo_instituicao_codigo"] = $vn_usuario_logado_instituicao_codigo;

        $va_acervos = $vo_acervo->ler_lista($va_filtro_acervo, "lista");

        foreach ($va_acervos as $va_acervo)
        {
            if (!$vn_setor_sistema_acessado_codigo || $va_acervo["acervo_setor_sistema_codigo"]["setor_sistema_codigo"] == $vn_setor_sistema_acessado_codigo)
                $va_usuario_logado_acervos[] = $va_acervo;
        }

    }
    elseif (isset($va_usuario["usuario_acervo_codigo"]))
    {
        foreach ($va_usuario["usuario_acervo_codigo"] as $va_acervo)
        {
            if (!$vn_setor_sistema_acessado_codigo || $va_acervo["usuario_acervo_codigo"]["acervo_setor_sistema_codigo"]["setor_sistema_codigo"] == $vn_setor_sistema_acessado_codigo)
                $va_usuario_logado_acervos[] = $va_acervo["usuario_acervo_codigo"];
        }
    }

    foreach ($va_usuario_logado_acervos as $va_acervo)
    {
        $va_usuario_logado_acervos_codigos[] = $va_acervo['acervo_codigo'];
        $va_usuario_logado_acervos_nomes[] = $va_acervo['acervo_codigo'];
    }

    $vn_usuario_logado_acervo_codigo = join("|", $va_usuario_logado_acervos_codigos);

    if (config::get(["f_keywords"]))
    {

        $va_usuario_logado_instituicoes_keywords = array();
        if (isset($va_usuario["usuario_instituicao_keyword_codigo"]))
        {
            foreach ($va_usuario["usuario_instituicao_keyword_codigo"] as $va_keyword)
            {
                $va_usuario_logado_instituicoes_keywords[] = $va_keyword["usuario_instituicao_keyword_codigo"]['instituicao_codigo'];
            }
        }

        if (!$vb_usuario_administrador)
            $vn_usuario_logado_instituicao_keyword_codigo = join("|", $va_usuario_logado_instituicoes_keywords);
        else
            $vn_usuario_logado_instituicao_keyword_codigo = $vn_usuario_logado_instituicao_codigo;

        $va_usuario_logado_keywords = array();
        if (isset($va_usuario["usuario_keyword_codigo"]))
        {
            foreach ($va_usuario["usuario_keyword_codigo"] as $va_keyword)
            {
                $va_usuario_logado_keywords[] = $va_keyword["usuario_keyword_codigo"]['keyword_codigo'];
            }
        }

        $vn_usuario_logado_keyword_codigo = join("|", $va_usuario_logado_keywords);


        $va_usuario_logado_especies_documentais = array();

        if (isset($va_usuario["usuario_especie_documental_codigo"]))
        {
            foreach ($va_usuario["usuario_especie_documental_codigo"] as $va_especie_documental)
            {
                $va_usuario_logado_especies_documentais[] = $va_especie_documental["usuario_especie_documental_codigo"]['especie_documental_codigo'];
            }
        }
        elseif ($vn_usuario_logado_acervo_codigo)
            $va_usuario_logado_especies_documentais[] = "_ALL_";

        $vn_usuario_logado_especie_documental_codigo = join("|", $va_usuario_logado_especies_documentais);

    }



    
    $va_recursos_sistema = array();
    $va_recursos_sistema_temp = array();
    $va_recursos_sistema_nomes = array();

    if (!isset($va_recursos_sistema_permissao_edicao))
        $va_recursos_sistema_permissao_edicao = array();

    $va_permissoes_usuario = array();

    if ($vb_usuario_administrador)
    {
        $vo_recurso_sistema = new recurso_sistema();
        $va_recursos_sistema = $vo_recurso_sistema->ler_lista();

        foreach($va_recursos_sistema as $va_recurso_sistema)
        {
            $va_recursos_sistema_nomes[$va_recurso_sistema["recurso_sistema_id"]] = $va_recurso_sistema["recurso_sistema_nome_plural"];
            $va_recursos_sistema_temp[$va_recurso_sistema["recurso_sistema_id"]] = $va_recurso_sistema;
        }
    }

    if (isset($va_usuario['usuario_grupo_usuario_codigo']))
    {
        foreach($va_usuario['usuario_grupo_usuario_codigo'] as $va_grupo_usuario)
        {
            $va_usuario_grupos_usuario[] = $va_grupo_usuario["usuario_grupo_usuario_codigo"]["grupo_usuario_codigo"];

            $vo_grupo_usuario = new grupo_usuario;

            if ( ($vn_recurso_sistema_codigo) && (!$vb_usuario_administrador) )
            {
                $va_permissoes_usuario = $vo_grupo_usuario->ler_permissoes($va_grupo_usuario["usuario_grupo_usuario_codigo"]["grupo_usuario_codigo"], $vn_recurso_sistema_codigo);

                if (isset($va_permissoes_usuario["pode_ler"]))
                    $vb_pode_ler = $vb_pode_ler || $va_permissoes_usuario["pode_ler"];

                if (isset($va_permissoes_usuario["pode_inserir"]))
                    $vb_pode_inserir = $vb_pode_inserir || $va_permissoes_usuario["pode_inserir"];

                if (isset($va_permissoes_usuario["pode_editar"]))
                    $vb_pode_editar = $vb_pode_editar || $va_permissoes_usuario["pode_editar"];

                if (isset($va_permissoes_usuario["pode_substituir"]))
                    $vb_pode_substituir = $vb_pode_substituir || $va_permissoes_usuario["pode_substituir"];

                if (isset($va_permissoes_usuario["pode_excluir"]))
                    $vb_pode_excluir = $vb_pode_excluir || $va_permissoes_usuario["pode_excluir"];

                if (isset($va_permissoes_usuario["pode_editar_lote"]))
                    $vb_pode_editar_lote = $vb_pode_editar_lote || $va_permissoes_usuario["pode_editar_lote"];

                if (isset($va_permissoes_usuario["pode_excluir_lote"]))
                    $vb_pode_excluir_lote = $vb_pode_excluir_lote || $va_permissoes_usuario["pode_excluir_lote"];
            }

            if ( ($vb_montar_menu) && (!$vb_usuario_administrador) )
            {
                $vo_grupo_usuario = new grupo_usuario;

                $va_grupo_usuario = $vo_grupo_usuario->ler($va_grupo_usuario["usuario_grupo_usuario_codigo"]['grupo_usuario_codigo'], "ficha");

                foreach($va_grupo_usuario['grupo_usuario_recurso_sistema_codigo'] as $va_recurso_sistema)
                {
                    if ( $va_recurso_sistema["grupo_usuario_recurso_sistema_ler"] || $va_recurso_sistema["grupo_usuario_recurso_sistema_editar"] )
                    {
                        if ($va_recurso_sistema["grupo_usuario_recurso_sistema_editar"])
                            $va_recursos_sistema_permissao_edicao[] = $va_recurso_sistema['grupo_usuario_recurso_sistema_codigo']["recurso_sistema_id"];

                        $va_recursos_sistema_nomes[$va_recurso_sistema['grupo_usuario_recurso_sistema_codigo']["recurso_sistema_id"]] = $va_recurso_sistema['grupo_usuario_recurso_sistema_codigo']["recurso_sistema_nome_plural"];
                        $va_recursos_sistema_temp[$va_recurso_sistema['grupo_usuario_recurso_sistema_codigo']["recurso_sistema_id"]] = $va_recurso_sistema;
                    }
                }
            }
        }
    }
    
    ksort($va_recursos_sistema_nomes);

    $va_recursos_sistema = array();
    foreach(array_keys($va_recursos_sistema_nomes) as $vs_key_recurso_sistema)
    {
        $va_recursos_sistema[$vs_key_recurso_sistema] = $va_recursos_sistema_temp[$vs_key_recurso_sistema];
    }

    $vn_usuario_logado_grupo_usuario_codigo = join("|", $va_usuario_grupos_usuario);

    if ($vn_recurso_sistema_genero_gramatical_codigo == 1)
        $vs_nome_botao_novo = "Nova";
    else
        $vs_nome_botao_novo = "Novo";

    // Vamos ver se este recurso do sistema está associado a algum fluxo
    $vo_fluxo = new fluxo;
    $va_fluxos = $vo_fluxo->ler_lista(["fluxo_recurso_sistema_codigo" => $vn_recurso_sistema_codigo], "ficha");
    
    $va_etapas_sem_acesso = $vo_fluxo->ler_etapas_sem_acesso($va_fluxos, $va_usuario_grupos_usuario);

    if ($vb_usuario_administrador)
    {
        $va_recursos_sistema_permissao_edicao[] = "_all_";

        $vb_pode_inserir = true;
        $vb_pode_editar = true;
        $vb_pode_substituir = true;
        $vb_pode_excluir = true;
        $vb_pode_editar_lote = true;
        $vb_pode_excluir_lote = true;
    }

    $va_parametros_controle_acesso = array();

    foreach (config::get(["controle_acesso", "_atributos_"]) as $vs_key_parametro => $vs_variavel_parametro)
    {
        $va_parametros_controle_acesso[$vs_key_parametro] = $$vs_variavel_parametro;
    }

    $va_parametros_controle_acesso["_combinacao_"] = config::get(["controle_acesso", "_combinacao_"]);
?>