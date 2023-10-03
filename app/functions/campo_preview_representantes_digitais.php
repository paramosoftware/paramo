<?php

    require_once dirname(__FILE__) . "/autenticar_usuario.php";

    if (isset($_GET['cod']) && $_GET['cod'])
    {
        $pn_objeto_codigo = $_GET['cod'];
        $pn_img_number = $_GET['img'];

        $vo_objeto = new $vs_id_objeto_tela($vs_id_objeto_tela);
        $va_objeto = $vo_objeto->ler($pn_objeto_codigo, "ficha");

        $va_valor_campo = $va_objeto["representante_digital_codigo"];

        echo '<div id="gallery" style="height:550px">';

        $contador_imagens = 1;
        $vs_img_base64 = "";

        foreach($va_valor_campo as $va_valores_linha)
        {

            $vs_representante_digital_formato = $va_valores_linha['representante_digital_formato'];
            $vs_representante_digital_path = $va_valores_linha['representante_digital_path'];

            if ($vs_representante_digital_formato != "jpg")
            {
                continue;
            }

            echo '<div style="display: none">';
            echo utils::get_img_html_element($vs_representante_digital_path, "large", "card-img-top");
            echo '</div>';

            $contador_imagens++;
        }

        echo '</div>';
    }
?>

<script>
    new Viewer(document.getElementById('gallery'), { inline: true });

    $(window).scroll(function()
    {
        $("#div-image-container").css({"margin-top": ($(window).scrollTop()) + "px"});
    });
</script>