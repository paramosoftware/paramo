<header class="header header-sticky mb-4">
    <div class="container-fluid">
        <?php if (!$vb_usuario_externo) { ?>
        <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <svg class="icon icon-lg">
            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-menu"></use>
            </svg>
        </button>
        <?php } ?>
        
        <a class="header-brand d-md-none" href="#">
            <img width="118" src="<?php print config::get(["logo"]); ?>">
        </a>

        <?php if (isset($_SESSION["instituicao_logado_como"]) || isset($va_instituicoes)) : ?>
        <?php
            foreach ($va_instituicoes as $va_instituicao)
            {
                if ( (isset($_SESSION["instituicao_logado_como"]) && ($va_instituicao["instituicao_codigo"] == $_SESSION["instituicao_logado_como"])) || isset($_SESSION["instituicao_visualizar_como"]))
                {
                ?>
                    <ul class="header-nav me-auto">
                        <form method="post" action="index.php">
                            <button class="btn btn-outline-primary" type="submit" name="instituicao_logado_como" value="0"
                                    title="Voltar para o a visualização padrão">

                                Sair de <?php print htmlspecialchars($va_instituicao["instituicao_nome"]); ?>
                                            
                                <svg class="icon">
                                    <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-exit-to-app"></use>
                                </svg>
                            </button>
                        </form>
                    </ul>
                <?php

                    break;
                }
            }
            ?>
        <?php endif; ?>
        
        <ul class="header-nav ms-auto">
            <?php if (!$vb_usuario_externo) : ?>
                <li class="nav-item">

                    <div class="input-group row-gap-05">
                        <form method="get" action="index.php">
                        <div class="input-group header-identificador">
                        
                        <?php
                            if (!isset($vs_busca_id))
                                $vs_busca_id = "";

                            $vs_objeto_busca = class_exists("texto") ? "texto" : "item_acervo";

                            $vo_autocomplete_identificador = new html_autocomplete("item_acervo", "busca_id");

                            $pa_parametros_campo = array (
                                'html_autocomplete',
                                'nome' => ['busca_id', $vs_objeto_busca . '_codigo'],
                                'label' => '',
                                'objeto' => $vs_objeto_busca,
                                'atributos' => [$vs_objeto_busca . '_codigo', 'item_acervo_identificador'],
                                'procurar_por' => 'item_acervo_identificador',
                                'operador' => 'LIKERIGHT',
                                'valor_no_input' => true,
                                'placeholder' => 'Busca por identificador',
                                'forcar_configuracao_padrao' => 1 // força o uso da configuração padrão para autocompletes e não o padrão da classe em get_campo_autocomplete()
                            );

                            $pa_valores_form = array($vs_objeto_busca . '_codigo' => '', "busca_id" => $vs_busca_id);

                            $vo_autocomplete_identificador->build($pa_valores_form, $pa_parametros_campo);
                        ?>
                        
                        <button class="btn btn-primary" type="submit">
                            <svg class="icon">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-search"></use>
                            </svg>
                        </button>
                        
                        </div>
                        </form>

                        <div class="me-2">&nbsp;</div>
                        
                        <form method="get" action="index.php">
                        <div class="input-group">

                        <?php
                            if (!isset($vs_termo_busca))
                                $vs_termo_busca = "";
                        ?>

                        <input class="form-control" required type="text" placeholder="Busca geral" aria-label="Buscar" name="busca" value="<?php print htmlspecialchars($vs_termo_busca); ?>">
                        
                        <button class="btn btn-primary" type="submit">
                            <svg class="icon">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-search"></use>
                            </svg>
                        </button>

                        </div>
                        </form>

                    </div>
                </li>
            <?php endif; ?>
        </ul>
        
        <ul class="header-nav ms-3">
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        <svg class="icon icon-lg me-2 avatar-img">
                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-user"></use>
                        </svg>
                    </div>
                </a>
            
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-2">
                        <div class="fw-semibold"><?php print htmlspecialchars($vs_usuario_logado_nome); ?></div>
                    </div>

                    <?php if (!$vb_usuario_externo) : ?>
                        <a class="dropdown-item" href="listar.php?obj=selecao">
                        <svg class="icon me-2">
                            <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-mouse"></use>
                        </svg>Minhas seleções
                        </a>
                    <?php endif; ?>

                    <a class="dropdown-item" href="editar_senha.php">
                    <svg class="icon me-2">
                        <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-bell"></use>
                    </svg>Alterar senha
                    </a>
                    
                    <?php if ($vb_usuario_administrador && $vb_usuario_logado_instituicao_admin && !isset($_SESSION["instituicao_logado_como"])) : ?>
                        <div class="dropdown-header bg-light py-2">
                            <div class="fw-semibold">Personalizar</div>
                        </div>
                        <a class="dropdown-item" href="alterar_logo.php">
                            <svg class="icon me-2">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-filter-photo"></use>
                            </svg>Alterar logo
                        </a>
                    <?php endif; ?>

                    <?php if (config::get(["f_logado_como"]) && isset($va_instituicoes)) : ?>
                        <div class="dropdown-header bg-light py-2">
                            <div class="fw-semibold">Visualizar como</div>
                        </div>
                    <form method="post" action="index.php">
                    <?php foreach ($va_instituicoes as $va_instituicao) : ?>
                        <button class="dropdown-item" type="submit" name="instituicao_logado_como" value="<?php print $va_instituicao["instituicao_codigo"]; ?>">
                            <svg class="icon me-2">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-house"></use>
                            </svg><?php print htmlspecialchars($va_instituicao["instituicao_nome"]); ?>
                        </button>
                     <?php endforeach; ?>
                    </form>
                    <?php endif; ?>

                    <div class="dropdown-divider"></div>



                    <?php if (config::get(["f_integracao_google_drive"]) ?? false) : ?>
                        <?php $authUrl = google_drive::get_auth_url($_SESSION["usuario_logado_codigo"] ?? 0, 'drive'); ?>
                        <?php if (empty($authUrl)) : ?>
                            <a class="dropdown-item" id="desconectar-google-drive">
                                <svg class="icon me-2">
                                    <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                                </svg>Desconectar Google Drive
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form method="post" action="functions/sair.php">
                        <input type="hidden" name="sair" value="true">
                        <button class="dropdown-item" type="submit">
                            <svg class="icon me-2">
                                <use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-account-logout"></use>
                            </svg>Sair
                        </button>
                    </form>

                </div>
            </li>
        </ul>

    </div>
    
    <div class="header-divider"></div>
    
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
                <li class="breadcrumb-item">
                    <a href="index.php" class="px-0 text-cor-laranja text-decoration-none" style="text-decoration: none !important;">
                    Início
                </a>
                </li>
                
                <?php if ($vs_recurso_sistema_nome_plural)
                {
                ?>
                    <li class="breadcrumb-item active">
                        <span>
                        <?php 
                            if ($vs_recurso_sistema_nome_plural)
                                print htmlspecialchars($vs_recurso_sistema_nome_plural);
                            elseif (isset($va_breadcrumb) && count($va_breadcrumb))
                                print htmlspecialchars(join(" > ", $va_breadcrumb));
                        ?>
                        </span>
                    </li>
                <?php
                }
                ?>
            </ol>
        </nav>
    </div>
</header>


<script>
    $(document).ready(function () {
        $("#desconectar-google-drive").click(function () {
            $.post("functions/sair.php", {desconectar_google_drive: true }, function (data) {
                window.location.reload();
            });
        });
    });
</script>