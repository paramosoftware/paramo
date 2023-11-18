<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Páramo - Sistema de Gerenciamento de Acervos">
    <meta name="author" content="Páramo Software">
    <meta name="keyword" content="Gerenciamento, Acervos, Biblioteca, Museu, Arquivo, Patrimônio, Cultura">
    <!--
        * CoreUI - Free Bootstrap Admin Template
        * @version v4.2.0
        * @link https://coreui.io
        * Copyright (c) 2022 creativeLabs Łukasz Holeczek
        * Licensed under MIT (https://coreui.io/license)
    -->

    <title><?= config::get(["nome_instituicao"]); ?></title>

    <link rel="shortcut icon" type="image/x-icon" href="<?= config::get(["favicon"]); ?>">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="assets/libraries/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.css">
    <link href="assets/libraries/@coreui/chartjs/css/coreui-chartjs.css" rel="stylesheet">
    <link href="assets/css/style.css?v=<?= config::get(["versao"]); ?>" rel="stylesheet">

    <?php
    $vs_custom_css = config::get(["pasta_lib"]) . config::get(["pasta_business"]) . "/layout/css/";
    if (file_exists($vs_custom_css) && is_dir($vs_custom_css))
    {
        foreach (glob($vs_custom_css . "*.css") as $vs_css_file)
        {
            $vs_css_custom_folder = config::get(["pasta_assets", "custom", "css"]);
            $vs_css_custom_file = $vs_css_custom_folder . basename($vs_css_file);

            if (!file_exists($vs_css_custom_file) || filemtime($vs_css_file) > filemtime($vs_css_custom_file))
            {
                copy($vs_css_file, $vs_css_custom_file);
            }

            echo '<link href="assets/custom/css/' . basename($vs_css_file) . '?v=' . filemtime($vs_css_file) . '" rel="stylesheet">';
        }
    }
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="assets/js/custom.js?v=<?= config::get(["versao"]); ?>"></script>
</head>