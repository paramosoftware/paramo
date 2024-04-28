<?php

$vb_montar_menu = true;
$vs_url_base = "listar.php?obj=";

if (!$vb_usuario_externo)
{
?>

<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <a href="index.php">
        <div class="sidebar-brand d-none d-md-flex">
            <img class="tamanho-imagem" src="<?php print config::get(["logo"]); ?>">
        </div>
    </a>

    <!-- MENU -->
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <?php 
        if (isset($va_usuario_logado_setores_sistema))
        {
            $va_setores_nomes = array();
            foreach ($va_usuario_logado_setores_sistema as $va_setor)
            {
                $va_setores_nomes[$va_setor['setor_sistema_nome']] = $va_setor;
            }
            
            ksort($va_setores_nomes);
            foreach ($va_setores_nomes as $vs_setor_sistema_nome_menu => $va_setor)
            {
                if (!isset($va_setor["setor_sistema_recurso_sistema_codigo"]))
                    continue;

                $vb_expandir_menu_auxiliar = false;
                $vb_expandir_setor = false;

                foreach ($va_setor["setor_sistema_recurso_sistema_codigo"] as $va_recurso_sistema)
                {
                    if ( ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"] == $vs_id_objeto_tela) && ($va_setor["setor_sistema_codigo"] == $vn_setor_sistema_acessado_codigo) )
                    {
                        $vb_expandir_setor = true;
                        break;
                    }
                }
            ?>

                <li class="nav-title
                <?php
                    if (!$vb_expandir_setor)
                        print ' nav-group"';
                    else
                        print ' nav-group show" aria-expanded="true"'
                ?>
                >
                    <a class="nav-link nav-group-toggle" href="#">
                        <?php print htmlspecialchars($va_setor['setor_sistema_nome']); ?>
                    </a>

                    <ul class="nav-group-items">

                        <?php
                        foreach ($va_setor["setor_sistema_recurso_sistema_codigo"] as $va_recurso_sistema)
                        {
                            // Antes de qualquer coisa, tem que ver se o usuário tem acesso ao recurso
                            // Os recursos acessíveis estão vindo em $va_recursos_sistema

                            if (in_array($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"], array_keys($va_recursos_sistema)))
                            {
                                $vb_item_acervo = false;
                                if(isset($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]) && ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]))
                                {
                                    $vb_item_acervo = true;

                                    $vs_recurso_sistema_id = $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"];

                                    $icons = [
                                        "livro" => "cil-book",
                                        "exemplar_periodico" => "cil-newspaper",
                                        "periodico" => "cil-newspaper",
                                        "documento" => "cil-description",
                                        "textual" => "cil-description",
                                        "entrevista" => "cil-microphone",
                                        "obra" => "cil-color-palette",
                                        "cartografico" => "cil-map",
                                        "iconografico" => "cil-image",
                                        "audiovisual" => "cil-video",
                                        "objeto" => "cil-3d"
                                    ];

                                    $vs_icon = $icons[$vs_recurso_sistema_id] ?? "";
                                ?>
                                    <li class="nav-item
                                    <?php
                                        if ( ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"] == $vs_id_objeto_tela) && (!$vn_bibliografia_codigo) )
                                            print ' nav-item-active';
                                    ?>
                                    ">
                                        <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"] . "&s=" . $va_setor["setor_sistema_codigo"]); ?>">
                                            <svg class="nav-icon">
                                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#<?php print $vs_icon; ?>"></use>
                                            </svg>
                                            <?php print htmlspecialchars($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_nome_plural"]); ?>
                                        </a>
                                    </li>
                                <?php
                                }

                                if ( ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"] == $vs_id_objeto_tela) && (!$vb_item_acervo) && (!$vn_bibliografia_codigo) )
                                    $vb_expandir_menu_auxiliar = true;
                            }
                        }
                        ?>

                        <li
                        <?php
                            if (!$vb_expandir_menu_auxiliar)
                                print ' class="nav-group"';
                            else
                                print 'class="nav-group show" aria-expanded="true"'
                        ?>
                        >
                            <a class="nav-link nav-group-toggle" href="#">
                            <svg class="nav-icon">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-list-rich"></use>
                            </svg> Cadastros auxiliares
                            </a>

                            <ul class="nav-group-items"
                            <?php
                                if ($vb_expandir_menu_auxiliar)
                                    print ' height="auto"';
                            ?>
                            >
                                <?php
                                    $va_recurso_sistema_nomes = array();
                                    foreach ($va_setor["setor_sistema_recurso_sistema_codigo"] as $va_recurso_sistema)
                                    {
                                        // Antes de qualquer coisa, tem que ver se o usuário tem acesso ao recurso
                                        // Os recursos acessíveis estão vindo em $va_recursos_sistema

                                        if (in_array($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"], array_keys($va_recursos_sistema)))
                                        {
                                            if(!isset($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]) || (!$va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]))
                                                $va_recurso_sistema_nomes[$va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"]] = $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_nome_plural"];
                                        }
                                    }

                                    ksort($va_recurso_sistema_nomes);
                                    foreach ($va_recurso_sistema_nomes as $vs_recurso_sistema_id_menu => $vs_recurso_sistema_nome_plural_menu)
                                    {
                                    ?>
                                        <li class="nav-item
                                        <?php
                                            if ( ($vs_recurso_sistema_id_menu == $vs_id_objeto_tela) && ($vb_expandir_menu_auxiliar) )
                                                print ' nav-item-active';
                                        ?>
                                        ">
                                            <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $vs_recurso_sistema_id_menu . "&s=" . $va_setor["setor_sistema_codigo"]); ?>">
                                                <span class="nav-icon"></span><?php print htmlspecialchars($vs_recurso_sistema_nome_plural_menu); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </li>

            <?php
            }
        }
        ?>

        <?php
        if (isset($va_usuario["usuario_bibliografia_codigo"]) && count($va_usuario["usuario_bibliografia_codigo"]))
        {
            $va_recursos_bibliografia = ["livro", "artigo", "parte_livro", "tese", "exemplar_periodico"];

            $vb_expandir_menu = false;
            if (in_array($vs_id_objeto_tela, $va_recursos_bibliografia))
                $vb_expandir_menu = true;
        ?>
                <li class="nav-title">Bibliografias</li>

                <?php
                $va_bibliografias_nomes = array();
                foreach ($va_usuario["usuario_bibliografia_codigo"] as $va_bibliografia)
                {
                    $va_bibliografias_nomes[$va_bibliografia['bibliografia_nome']] = $va_bibliografia;
                }
                
                ksort($va_bibliografias_nomes);
                foreach ($va_bibliografias_nomes as $vs_bibliografia_nome_menu => $va_bibliografia)
                {
                    $vb_expandir_menu_auxiliar = false;
                    if ($vn_bibliografia_codigo == $va_bibliografia["bibliografia_codigo"])
                        $vb_expandir_menu_auxiliar = true;
                ?>
                    <li 
                    <?php 
                        if (!$vb_expandir_menu_auxiliar)
                            print ' class="nav-group"';
                        else
                            print 'class="nav-group show" aria-expanded="true"' 
                    ?>
                    >
                        <a class="nav-link nav-group-toggle" href="#">
                        <svg class="nav-icon">
                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-list-rich"></use>
                        </svg> <?php print htmlspecialchars($va_bibliografia['bibliografia_nome']); ?>
                        </a>

                        <ul class="nav-group-items"
                        <?php 
                            if ($vb_expandir_menu_auxiliar)
                                print ' height="auto"';
                        ?>
                        >
                            <?php
                            foreach($va_recursos_sistema as $va_recurso_sistema)
                            {
                                if (in_array($va_recurso_sistema["recurso_sistema_id"], $va_recursos_bibliografia))
                                {
                            ?>
                                <li class="nav-item
                                <?php
                                    if ( ($va_recurso_sistema["recurso_sistema_id"] == $vs_id_objeto_tela) && ($vb_expandir_menu_auxiliar) )
                                        print ' nav-item-active';
                                ?>
                                ">
                                    <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $va_recurso_sistema["recurso_sistema_id"] . '&bibliografia=' . $va_bibliografia["bibliografia_codigo"]); ?>">
                                    <span class="nav-icon"></span><?php print htmlspecialchars($va_recurso_sistema["recurso_sistema_nome_plural"]); ?>
                                    </a>
                                </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                <?php
                }
            }
        ?>

        <?php
            $va_recursos_sidebar = config::get(["sidebar"]);
            
            $va_recursos_institucional = $va_recursos_sidebar["institucional"];
            $va_recursos_permissoes = $va_recursos_sidebar["permissoes"];

            // Só exibe opções de configuração se o usuário
            // pertencer a uma instituição administradora
            ///////////////////////////////////////////////

            $va_recursos_configuracoes = array();
            
            if ($vb_usuario_administrador && $vb_usuario_logado_instituicao_admin)
                $va_recursos_configuracoes = $va_recursos_sidebar["configuracoes"];
            else
                unset($va_recursos_permissoes[array_search("grupo_usuario", $va_recursos_permissoes)]);

            ///////////////////////////////////////////////

            $va_recursos_gerenciamento = array_merge($va_recursos_institucional, $va_recursos_permissoes, $va_recursos_configuracoes);

            $vb_exibir_gerenciamento = false;
            $vb_exibir_institucional = false;
            $vb_exibir_permissoes = false;
            $vb_exibir_configuracoes = false;

            if (count(array_intersect(array_keys($va_recursos_sistema), $va_recursos_gerenciamento)))
                $vb_exibir_gerenciamento = true;
            
            if (count(array_intersect(array_keys($va_recursos_sistema), $va_recursos_institucional)))
                $vb_exibir_institucional = true;

            if (count(array_intersect(array_keys($va_recursos_sistema), $va_recursos_permissoes)))
                $vb_exibir_permissoes = true;

            if (count(array_intersect(array_keys($va_recursos_sistema), $va_recursos_configuracoes)))
                $vb_exibir_configuracoes = true;

            $vb_expandir_gerenciamento = false;
            if ( in_array($vs_id_objeto_tela, $va_recursos_gerenciamento))
                $vb_expandir_gerenciamento = true;
        ?>

        <?php if ($vb_exibir_gerenciamento)
        {
        ?>
            <li class="nav-title
            <?php
                if (!$vb_expandir_gerenciamento)
                    print ' nav-group"';
                else
                    print ' nav-group show" aria-expanded="true"'
            ?>
            >
                <a class="nav-link nav-group-toggle" href="#">
                    GERENCIAMENTO
                </a>

                <ul class="nav-group-items">

                    <?php
                        if ($vb_exibir_institucional)
                        {
                            $vb_expandir_menu = false;
                            if (in_array($vs_id_objeto_tela, $va_recursos_institucional))
                                $vb_expandir_menu = true;
                        ?>

                        <li
                        <?php
                            if (!$vb_expandir_menu)
                                print ' class="nav-group"';
                            else
                                print 'class="nav-group show" aria-expanded="true"'
                        ?>
                        >
                            <a class="nav-link nav-group-toggle" href="#">
                            <svg class="nav-icon">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-institution"></use>
                            </svg>Institucional</a>

                            <ul class="nav-group-items"
                            <?php
                                if ($vb_expandir_menu)
                                    print ' height="auto"';
                            ?>
                            >
                                <?php
                                    foreach($va_recursos_sistema as $va_recurso_sistema)
                                    {
                                        if (isset($va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"]))
                                            $va_recurso_sistema = $va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"];

                                        if (in_array($va_recurso_sistema["recurso_sistema_id"], $va_recursos_institucional))
                                        {
                                    ?>
                                            <li class="nav-item"
                                            <?php
                                                if ($va_recurso_sistema["recurso_sistema_id"] == $vs_id_objeto_tela)
                                                    print ' nav-item-active';
                                            ?>
                                            ">
                                                <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $va_recurso_sistema["recurso_sistema_id"]); ?>">
                                                <span class="nav-icon"></span><?php print htmlspecialchars($va_recurso_sistema["recurso_sistema_nome_plural"]); ?>
                                                </a>
                                            </li>
                                    <?php
                                        }
                                    }
                                ?>

                                <?php if (config::get(["f_extroversao_atividades_usuario"])) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="filtro_relatorio_pesquisas.php">
                                        <span class="nav-icon"></span>Atividades de usuário
                                        </a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </li>
                    <?php
                    }
                    ?>

                    <?php
                        if ($vb_exibir_permissoes)
                        {
                            $vb_expandir_menu = false;

                            if (in_array($vs_id_objeto_tela, $va_recursos_permissoes))
                                $vb_expandir_menu = true;
                        ?>

                        <li
                        <?php
                            if (!$vb_expandir_menu)
                                print ' class="nav-group"';
                            else
                                print 'class="nav-group show" aria-expanded="true"'
                        ?>
                        ><a class="nav-link nav-group-toggle" href="#">
                            <svg class="nav-icon">
                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-group"></use>
                            </svg>Permissões</a>

                            <ul class="nav-group-items"
                            <?php
                                if ($vb_expandir_menu)
                                    print ' height="auto"';
                            ?>
                            >
                            <?php
                                foreach($va_recursos_sistema as $va_recurso_sistema)
                                {   
                                    if (isset($va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"]))
                                        $va_recurso_sistema = $va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"];

                                    if (in_array($va_recurso_sistema["recurso_sistema_id"], $va_recursos_permissoes))
                                    {
                                ?>
                                        <li class="nav-item"
                                        <?php
                                            if ($va_recurso_sistema["recurso_sistema_id"] == $vs_id_objeto_tela)
                                                print ' nav-item-active';
                                        ?>
                                        ">
                                            <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $va_recurso_sistema["recurso_sistema_id"]); ?>">
                                                <span class="nav-icon"></span><?php print htmlspecialchars($va_recurso_sistema["recurso_sistema_nome_plural"]); ?>
                                            </a>
                                        </li>
                                <?php
                                    }
                                }
                            ?>
                            </ul>
                        </li>
                    <?php
                    }
                    ?>

                    <?php
                        if ($vb_exibir_configuracoes)
                        {
                            $vb_expandir_menu = false;

                            if (in_array($vs_id_objeto_tela, $va_recursos_configuracoes))
                                $vb_expandir_menu = true;
                        ?>

                        <li
                        <?php
                            if (!$vb_expandir_menu)
                                print ' class="nav-group"';
                            else
                                print 'class="nav-group show" aria-expanded="true"'
                        ?>
                        >
                            <a class="nav-link nav-group-toggle" href="#">
                            <svg class="nav-icon">
                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-settings"></use>
                            </svg>Configurações</a>

                            <ul class="nav-group-items"
                            <?php
                                if ($vb_expandir_menu)
                                    print ' height="auto"';
                            ?>
                            >
                            <?php
                                foreach($va_recursos_sistema as $va_recurso_sistema)
                                {
                                    if (isset($va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"]))
                                        $va_recurso_sistema = $va_recurso_sistema["grupo_usuario_recurso_sistema_codigo"];

                                    if (in_array($va_recurso_sistema["recurso_sistema_id"], $va_recursos_configuracoes))
                                    {
                                ?>
                                        <li class="nav-item"
                                        <?php
                                            if ($va_recurso_sistema["recurso_sistema_id"] == $vs_id_objeto_tela)
                                                print ' nav-item-active';
                                        ?>
                                        ">
                                            <a class="nav-link" href="<?php print htmlspecialchars($vs_url_base . $va_recurso_sistema["recurso_sistema_id"]); ?>">
                                                <span class="nav-icon"></span><?php print htmlspecialchars($va_recurso_sistema["recurso_sistema_nome_plural"]); ?>
                                            </a>
                                        </li>
                                <?php
                                    }
                                }
                            ?>
                            </ul>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
        <?php
        }
        ?>
    </ul>
</div>

<?php
}
?>


<script src="assets/libraries/@coreui/coreui/js/coreui.bundle.min.js"></script>
<script src="assets/libraries/simplebar/js/simplebar.min.js"></script>
<script src="assets/libraries/@coreui/utils/js/coreui-utils.js"></script>

