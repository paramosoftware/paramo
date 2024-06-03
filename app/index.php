<?php
    $vb_montar_menu = true;

    require_once dirname(__FILE__) . "/components/entry_point.php";

    session::set_redirect_url();

    if ($vs_id_objeto_tela != "")
    {
        unset($_SESSION["setor_sistema_acessado_codigo"][$vs_id_objeto_tela]);
    }
    unset($_SESSION["instituicao_visualizar_como"]);

?>
<!DOCTYPE html>
<html lang="pt-br">

<?php require_once dirname(__FILE__) . "/components/header_html.php"; ?>

<body>

<?php
    require_once dirname(__FILE__) . "/components/ler_valor.php";
    require_once dirname(__FILE__) . "/components/sidebar.php";
?>

<?php
    $vb_logado_como_habilitado = config::get(["f_logado_como"]) ?? false;
    $vb_logado_como = isset($_SESSION["instituicao_logado_como"]) && $vb_logado_como_habilitado;

    // variáveis de controle de acesso setadas em autenticar_usuario.php
    $va_recursos_sistema = $va_recursos_sistema ?? array();
    $va_usuario_logado_setores_sistema = $va_usuario_logado_setores_sistema ?? array();
    $va_usuario_logado_acervos = $va_usuario_logado_acervos ?? array();
    $vb_usuario_administrador = $vb_usuario_administrador ?? false;
    $vb_usuario_logado_instituicao_admin = $vb_usuario_logado_instituicao_admin ?? false;
    $vb_usuario_super_admin = $vb_usuario_administrador && $vb_usuario_logado_instituicao_admin;
    $vn_usuario_logado_instituicao_codigo = $vn_usuario_logado_instituicao_codigo ?? 0;
    $vn_usuario_logado_codigo = $vn_usuario_logado_codigo ?? 0;
    $vs_id_objeto_tela = $vs_id_objeto_tela ?? "";

    $vo_dashboard = new dashboard;
    $vs_objeto_item_acervo_nome = $vo_dashboard->get_objeto_item_acervo_nome();
    $vs_atributo_instituicao_objeto_item_acervo = $vo_dashboard->get_atributo_instituicao_objeto_item_acervo();
    $vs_atributo_acervo_objeto_item_acervo = $vo_dashboard->get_atributo_acervo_objeto_item_acervo();
    $vs_filtro_busca_geral = $vo_dashboard->get_filtro_busca_geral();
    $va_regras_exibicao = $vo_dashboard->get_regras_exibicao(!$vb_logado_como && $vb_logado_como_habilitado);

    $va_setores = get_setores($va_usuario_logado_setores_sistema);
    $va_objetos_itens_acervo = get_objetos_itens_acervo($va_setores, $va_recursos_sistema);
    $va_info_setores = get_info_setores($va_setores, $vn_usuario_logado_instituicao_codigo, $va_selecoes_compartilhadas_codigos ?? array());

    $va_valores_data = array();
    $va_filtros_busca = array();

    $vs_termo_busca = isset($_GET["busca"]) ? trim($_GET["busca"]) : "";
    $vs_busca_id = isset($_GET["busca_id"]) ? trim($_GET["busca_id"]) : "";

    if ($vs_termo_busca == "")
    {
        // Variáveis usadas em montar_listagem.php
        $va_valores_data["log_data_dia_inicial"] = date('d');
        $va_valores_data["log_data_mes_inicial"] = date('m');
        $va_valores_data["log_data_ano_inicial"] = date('Y');
        //$va_log_info = array($vs_id_objeto_tela, $vn_usuario_logado_codigo, $va_valores_data);
    }

    ?>

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">

        <?php require_once dirname(__FILE__) . "/components/header.php"; ?>

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <div class="row">
                    <?php if ( ($vs_termo_busca == "") && ($vs_busca_id == "") ) : ?>
                        <div class="col-md-12">
                            <?php require dirname(__FILE__) . "/components/dashboard_estatisticas.php"; ?>
                        </div>

                        <?php
                        foreach ($va_regras_exibicao as $va_secoes)
                        {
                            foreach ($va_secoes as $va_secao)
                            {
                                if ($va_secao["exibir"])
                                {
                                    $va_cards = get_cards($va_secao["card"], $va_secao["regras"]);

                                    if (count($va_cards))
                                    {
                                        echo "<h5 class='border-bottom border-bottom-3 border-bottom-danger'>";
                                        echo htmlspecialchars($va_secao["titulo"]. " ");
                                        if ($va_secao["descricao"] != "")
                                        {
                                            echo '<span title="' . htmlspecialchars($va_secao["descricao"]) . '">';
                                            echo '<svg class="icon"><use xlink:href="assets/libraries/@coreui/icons/svg/free.svg#cil-flag-alt"></use></svg>';
                                            echo '</span>';
                                        }
                                        echo "</h5>";

                                        echo "<div class='row mt-2'>";

                                        foreach ($va_cards as $va_card)
                                        {
                                            require dirname(__FILE__) . "/components/dashboard_card.php";
                                        }

                                        echo "</div>";
                                    }
                                }
                            }
                        }
                        ?>

                    <?php else:
                        $va_parametros_filtros_consulta = array();
                        //$va_parametros_filtros_consulta[$vs_objeto_item_acervo_nome . "_codigo_0_" . $vs_atributo_instituicao_objeto_item_acervo] = $vn_usuario_logado_instituicao_codigo;

                        $vb_busca_id = false;
                        if ( ($vs_termo_busca != "") && ($vs_busca_id == "") )
                        {
                            $va_parametros_filtros_consulta[$vs_filtro_busca_geral] = [$vs_termo_busca, "LIKE"];
                        }
                        else
                        {
                            $vb_busca_id = true;
                            $vs_classe_base = class_exists("texto") ? "texto" : "item_acervo";
                            $va_parametros_filtros_consulta[$vs_classe_base . "_codigo_0_item_acervo_identificador"] = [$vs_busca_id, "="];
                        }                        
                        
                        require dirname(__FILE__) . "/components/dashboard_resultado_busca.php";
                    ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php require_once dirname(__FILE__) . "/components/footer.php"; ?>

<script>

    $(document).on('click', ".btn-tab", function () {
        $('.acervo').hide();
        $('.acervo-' + $(this).attr('id')).show();
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 100);
    });

    <?php if ($vb_usuario_super_admin && $vb_logado_como_habilitado && false) : ?>

    $(document).ready(function () {
        $('.acervo').hide();
    });

    <?php endif; ?>

</script>
</body>
</html>


<?php
function get_setores($pa_usuario_logado_setores_sistema): array
{
    $va_setores_nomes = array();

    foreach ($pa_usuario_logado_setores_sistema as $va_setor)
    {
        $va_setores_nomes[$va_setor['setor_sistema_nome']] = $va_setor;
    }

    ksort($va_setores_nomes);

    return $va_setores_nomes;
}

function get_objetos_itens_acervo(array $pa_setores, array $pa_recursos_sistema): array
{
    $va_objetos_itens_acervo = array();

    foreach ($pa_setores as $vs_setor_sistema_nome_menu => $va_setor)
    {
        if (!isset($va_setor["setor_sistema_recurso_sistema_codigo"]))
            continue;

        foreach ($va_setor["setor_sistema_recurso_sistema_codigo"] as $va_recurso_sistema)
        {
            if (isset($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]) && ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]))
            {
                $vs_objeto_item_acervo = $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"];

                if (in_array($vs_objeto_item_acervo, array_keys($pa_recursos_sistema)))
                    $va_objetos_itens_acervo[$vs_objeto_item_acervo] = [
                        "nome_singular" => $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_nome_singular"],
                        "nome_plural" => $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_nome_plural"],
                        "genero_gramatical" => $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_genero_gramatical_codigo"]["genero_gramatical_codigo"]
                    ];
            }
        }
    }

    return $va_objetos_itens_acervo;
}

function get_info_setores(array $pa_setores, $pn_usuario_logado_instituicao_codigo, $pa_selecoes_compartilhadas_codigos): array
{
    $vo_dashboard = new dashboard;
    $vs_objeto_item_acervo_nome = $vo_dashboard->get_objeto_item_acervo_nome();
    $vs_atributo_acervo_objeto_item_acervo = $vo_dashboard->get_atributo_acervo_objeto_item_acervo();
    $vs_atributo_instituicao_objeto_item_acervo = $vo_dashboard->get_atributo_instituicao_objeto_item_acervo();

    $vn_usuario_logado_instituicao_codigo = $pn_usuario_logado_instituicao_codigo;

    $va_info_setores = array();

    foreach ($pa_setores as $vs_setor_sistema_nome_menu => $va_setor)
    {
        if (!isset($va_setor["setor_sistema_recurso_sistema_codigo"]))
            continue;

        $vn_numero_itens = 0;
        $vs_objeto_item_acervo = "";

        foreach ($va_setor["setor_sistema_recurso_sistema_codigo"] as $va_recurso_sistema)
        {
            if (isset($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]) && ($va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_item_acervo"]))
            {
                $vs_objeto_item_acervo = $va_recurso_sistema["setor_sistema_recurso_sistema_codigo"]["recurso_sistema_id"];
                $vo_item_acervo = new $vs_objeto_item_acervo($vs_objeto_item_acervo);

                $vb_controlar_acesso_instituicao = config::get(["controle_acesso", "_atributos_", "instituicao_codigo"]) ?? false;
                $va_filtros_recurso_sistema = array();

                if ($vb_controlar_acesso_instituicao)
                    $va_filtros_recurso_sistema[$vs_objeto_item_acervo_nome . "_codigo_0_" . $vs_atributo_instituicao_objeto_item_acervo] = $vn_usuario_logado_instituicao_codigo;

                if (count($pa_selecoes_compartilhadas_codigos))
                    $va_filtros_recurso_sistema["item_selecao_codigo"] = implode("|", $pa_selecoes_compartilhadas_codigos);

                $vn_numero_itens_recurso = $vo_item_acervo->ler_numero_registros($va_filtros_recurso_sistema);

                $vn_numero_itens = $vn_numero_itens + $vn_numero_itens_recurso;
            }
        }

        if (isset($va_setor["setor_sistema_recurso_sistema_padrao_codigo"]))
            $vs_objeto_item_acervo = $va_setor["setor_sistema_recurso_sistema_padrao_codigo"]["recurso_sistema_id"];

        $va_info_setores[] = [
            "codigo" => $va_setor['setor_sistema_codigo'],
            "nome" => $va_setor['setor_sistema_nome'],
            "quantidade" => $vn_numero_itens,
            "link_recurso_sistema" => $vs_objeto_item_acervo
        ];
    }

    return $va_info_setores;

}

function get_cards($ps_tipo, $pa_regras): array
{
    $va_cards = array();

    if ($ps_tipo == "instituicao")
        $va_cards = get_cards_instituicoes($pa_regras);
    else if ($ps_tipo == "acervo")
        $va_cards = get_cards_acervos($pa_regras);

    return $va_cards;

}

function get_cards_instituicoes($pa_regras = array()): array
{
    $vo_dashboard = new dashboard;
    $vs_objeto_item_acervo_nome = $vo_dashboard->get_objeto_item_acervo_nome();
    $vs_atributo_acervo_objeto_item_acervo = $vo_dashboard->get_atributo_acervo_objeto_item_acervo();
    $vs_atributo_instituicao_objeto_item_acervo = $vo_dashboard->get_atributo_instituicao_objeto_item_acervo();

    $vb_usuario_pode_ver_todas_instituicoes = config::get(["f_usuario_pode_ver_todas_instituicoes"]) ?? false;

    if ($vb_usuario_pode_ver_todas_instituicoes)
    {
        $vo_instituicao = new instituicao;
        $va_instituicoes = $vo_instituicao->ler_lista(null, "ficha");
    }
    else
        global $va_instituicoes;
    
    
    $vo_setor_sistema = new setor_sistema;
    $va_setores_sistema = $vo_setor_sistema->ler_lista(null, "navegacao");

    $va_cards_instituicoes = [];

    foreach ($va_instituicoes as $va_instituicao)
    {
        $vs_instituicao_nome = $va_instituicao["instituicao_nome"];
        $vs_instituicao_codigo = $va_instituicao["instituicao_codigo"];
        $vs_instituicao_imagem = "";
        if (isset($va_instituicao["representante_digital_codigo"][0])) {
            $vs_instituicao_imagem = $va_instituicao["representante_digital_codigo"][0]["representante_digital_path"];
        }

        $va_itens_acervo = [];

        foreach ($va_setores_sistema as $va_setor_sistema)
        {
            if (!isset($va_setor_sistema["setor_sistema_recurso_sistema_codigo"]))
                continue;

            foreach ($va_setor_sistema["setor_sistema_recurso_sistema_codigo"] as $va_recursos_sistema)
            {
                $vn_numero_itens = 0;

                foreach ($va_recursos_sistema as $va_recurso_sistema)
                {
                    if (isset($va_recurso_sistema["recurso_sistema_item_acervo"]) && ($va_recurso_sistema["recurso_sistema_item_acervo"]))
                    {
                        $vs_objeto_item_acervo = $va_recurso_sistema["recurso_sistema_id"];
                        $vo_item_acervo = new $vs_objeto_item_acervo($vs_objeto_item_acervo);

                        if (isset($va_recurso_sistema["recurso_sistema_agrupado_acervo"]) && $va_recurso_sistema["recurso_sistema_agrupado_acervo"])
                            $vn_numero_itens = $vo_item_acervo->ler_numero_registros([$vs_objeto_item_acervo_nome . "_codigo_0_" . $vs_atributo_acervo_objeto_item_acervo . "_0_acervo_instituicao_codigo" => $va_instituicao["instituicao_codigo"]]);
                        else
                            $vn_numero_itens = $vo_item_acervo->ler_numero_registros([$vs_objeto_item_acervo_nome . "_codigo_0_" . $vs_atributo_instituicao_objeto_item_acervo => $va_instituicao["instituicao_codigo"]]);

                        if ($vn_numero_itens)
                        {
                            $va_itens_acervo[] = [
                                "nome" => ($vn_numero_itens == 1) ? strtolower($va_recurso_sistema["recurso_sistema_nome_singular"]) : strtolower($va_recurso_sistema["recurso_sistema_nome_plural"]),
                                "link" => "listar.php?obj=" . $vs_objeto_item_acervo . "&" . $vs_objeto_item_acervo_nome . "_codigo_0_" .
                                    $vs_atributo_instituicao_objeto_item_acervo . "=" . $va_instituicao["instituicao_codigo"],
                                "quantidade" => $vn_numero_itens
                            ];
                        }
                    }
                }
            }
        }

        if (!verificar_regras($pa_regras, $va_instituicao, count($va_itens_acervo)))
        {
            continue;
        }

        $va_cards_instituicoes[] = [
            "tipo" => "instituicao",
            "codigo" => $vs_instituicao_codigo,
            "titulo" => $vs_instituicao_nome,
            "imagem" => $vs_instituicao_imagem,
            "href" => "listar.php?obj=instituicao&instituicao_codigo=" . $vs_instituicao_codigo,
            "itens" => $va_itens_acervo
        ];
    }

    return $va_cards_instituicoes;
}

function get_cards_acervos(array $pa_regras): array
{
    $vo_dashboard = new dashboard;
    $vs_objeto_item_acervo_nome = $vo_dashboard->get_objeto_item_acervo_nome();
    $vs_atributo_acervo_objeto_item_acervo = $vo_dashboard->get_atributo_acervo_objeto_item_acervo();

    global $va_usuario_logado_acervos;

    $va_cards_acervos = array();

    foreach ($va_usuario_logado_acervos as $va_acervo)
    {
        $va_parametros_card_acervo = $vo_dashboard->get_parametros_card_acervo($va_acervo["acervo_setor_sistema_codigo"]["setor_sistema_codigo"]);

        $vo_acervo = new $va_parametros_card_acervo['objeto_acervo'];
        $va_acervo = $vo_acervo->ler($va_acervo['acervo_codigo'], "ficha");

        $vs_acervo_nome = $va_acervo["acervo_nome"] ?? $va_acervo["entidade_nome"] ?? ["[Sem rótulo]"];
        $vs_acervo_instituicao_codigo = $va_acervo["acervo_instituicao_codigo"]["instituicao_codigo"];

        $vo_item_acervo = new $vs_objeto_item_acervo_nome;
        $vn_numero_itens = $vo_item_acervo->ler_numero_registros([$vs_atributo_acervo_objeto_item_acervo => $va_acervo["acervo_codigo"]]);

        $vs_image = "";
        if (isset($va_acervo["representante_digital_codigo"][0]))
            $vs_image = $va_acervo["representante_digital_codigo"][0]["representante_digital_path"];

        if (!verificar_regras($pa_regras, $va_acervo, $vn_numero_itens))
        {
            continue;
        }

        $vs_acervo_cor = "";
        if (isset($va_acervo["acervo_cor"]) && $va_acervo["acervo_cor"])
        {
            $vs_acervo_cor = $va_acervo["acervo_cor"];
        }

        $vs_href = $va_parametros_card_acervo["link"] . $va_acervo["acervo_codigo"];

        $va_informacoes_acervo = array();

        foreach ($va_parametros_card_acervo['atributos'] as $vs_atributo_acervo)
        {
            $va_informacoes_acervo[] = ler_valor1($vs_atributo_acervo, $va_acervo);
        }

        $va_informacoes_acervo[] = $vn_numero_itens . (($vn_numero_itens > 1) ? " itens" : " item");

        $va_cards_acervos[] = [
            "tipo" => "acervo",
            "titulo" => $vs_acervo_nome,
            "codigo" => $vs_acervo_instituicao_codigo,
            "cor" => $vs_acervo_cor,
            "href" => $vs_href,
            "itens" => $va_informacoes_acervo,
            "imagem" => $vs_image
        ];
    }

    return $va_cards_acervos;
}

function verificar_regras(array $pa_regras, $pa_objeto, $pn_numero_itens): bool
{
    $comparacoes = [
        '==' => function($a, $b) { return $a == $b; },
        '!=' => function($a, $b) { return $a != $b; },
        '>' => function($a, $b) { return $a > $b; },
        '<' => function($a, $b) { return $a < $b; },
        '>=' => function($a, $b) { return $a >= $b; },
        '<=' => function($a, $b) { return $a <= $b; },
        'in' => function($a, $b) { return in_array($a, $b); },
        'not in' => function($a, $b) { return !in_array($a, $b); }
    ];

    foreach ($pa_regras as $vs_atributo => $va_regra)
    {
        $operador = $va_regra["operador"];
        $va_valores = $va_regra["valores"];

        if ($vs_atributo == "numero_itens")
        {
            if (!$comparacoes[$operador]($pn_numero_itens, $va_valores))
            {
                return false;
            }
        }
        elseif (strpos($vs_atributo, "_0_") !== false)
        {
            $va_atributos = explode("_0_", $vs_atributo);

            $valor_atual = $pa_objeto;
            foreach ($va_atributos as $vs_atributo)
            {
                $valor_atual = $valor_atual[$vs_atributo] ?? null;
            }

            if (!$comparacoes[$operador]($valor_atual, $va_valores))
            {
                return false;
            }

        }
        elseif (isset($pa_objeto[$vs_atributo]))
        {
            if (!$comparacoes[$operador]($pa_objeto[$vs_atributo], $va_valores))
            {
                return false;
            }
        }
    }

    return true;
}