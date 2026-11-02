<?php

require_once dirname(__FILE__) . "/autenticar_usuario.php";

if (isset($_GET['cod']) && $_GET['cod']) 
{
    $pn_objeto_codigo = (int) $_GET['cod'];
    $pn_img_number = (int) ($_GET['img'] ?? 0);

    $vo_objeto = autorizacao::resolver_objeto($vs_id_objeto_tela);

    if (!($vo_objeto instanceof objeto_base) || !autorizacao::autorizar_leitura_registro($vo_objeto, $pn_objeto_codigo))
    {
        http_response_code(404);
        exit();
    }

    $va_objeto = $vo_objeto->ler($pn_objeto_codigo, "ficha");

    if (!is_array($va_objeto) || !count($va_objeto))
    {
        http_response_code(404);
        exit();
    }

    $va_valor_campo = $va_objeto["representante_digital_codigo"] ?? [];

    echo '<div id="gallery" style="height:550px">';

    $vn_initial_image_view = 0;
    $vn_counter = 0;

    foreach ($va_valor_campo as $va_valores_linha) 
    {
        $vs_representante_digital_formato = $va_valores_linha['representante_digital_formato'];
        $vs_representante_digital_path = $va_valores_linha['representante_digital_path'];

        if ($vs_representante_digital_formato != "jpg") 
        {
            continue;
        }

        if ($va_valores_linha['representante_digital_codigo'] == $pn_img_number) 
        {
            $vn_initial_image_view = $vn_counter;
        }

        $vn_counter++;

        echo '<div style="display: none">';
        echo utils::get_img_html_element($vs_representante_digital_path, "large", "card-img-top");
        echo '</div>';
    }

    echo '</div>';
}
?>

<script>
    new Viewer(document.getElementById('gallery'), {
        inline: true,
        initialViewIndex: <?= $vn_initial_image_view ?>
    });

    $(window).scroll(function() {
        $("#div-image-container").css({
            "margin-top": ($(window).scrollTop()) + "px"
        });
    });
</script>