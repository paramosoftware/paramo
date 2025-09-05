<?php

# ==================================== CONSTANTES DO SISTEMA ==================================== #
# Essas configurações normalmente não precisam ser alteradas, mas caso seja necessário,
# crie um arquivo settings.php na pasta config/custom e adicione as configurações que deseja alterar.
# Exemplo:
# <?php
#  return [
#      "pasta_logs" => dirname(__FILE__) . "/../app/logs/"
#  ];
# Não altere este arquivo diretamente, pois ele poderá ser sobrescrito em atualizações futuras.

return [
    # ================================================ DEPURAÇÃO ================================================ #

    "versao" => "1.4.36",
    "pasta_logs" => dirname(__FILE__) . "/../src/logs/",

    # ================================================ ASSETS E MÍDIA  ================================================ #

    $vs_pasta_app = dirname(__FILE__) . "/../app/",
    $vs_pasta_assets = $vs_pasta_app . "assets/",
    $vs_pasta_custom = $vs_pasta_assets . "custom/",
    $vs_pasta_media = $vs_pasta_app . "media/",
    $vs_pasta_images = $vs_pasta_media . "images/",

    "pasta_app" => "$vs_pasta_app",
    "pasta_vendors" => dirname(__FILE__) . "/../src/vendors/",
    "pasta_lib" => dirname(__FILE__) . "/../src/lib/",

    "pasta_assets" => [
        "images" => $vs_pasta_assets . "img/",
        "css" => $vs_pasta_assets . "css/",
        "js" => $vs_pasta_assets . "js/",
        "custom" => [
            "images" => $vs_pasta_custom . "img/",
            "css" => $vs_pasta_custom . "css/",
            "js" => $vs_pasta_custom . "js/",
        ]
    ],

    "pasta_media" => [
        "documents" => $vs_pasta_media . "documents/",
        "downloads" => $vs_pasta_media . "downloads/",
        "videos" => $vs_pasta_media . "videos/",
        "audios" => $vs_pasta_media . "audios/",
        "images" => [
            "large" => $vs_pasta_images . "large/",
            "medium" => $vs_pasta_images . "medium/",
            "thumb" => $vs_pasta_images . "thumb/",
            "original" => $vs_pasta_images . "original/",
        ],
        "temp" => $vs_pasta_media . "temp/",
    ],


    # É possível personalizar o logo do sistema pela parte administrativa.
    # Caso não seja definido um logo personalizado, o logo padrão será utilizado.
    "logo" => file_exists($vs_pasta_custom . "img/logo.png") ?
        "assets/custom/img/logo.png" : "assets/img/logo.png",

    # Caso queira utilizar um rodapé personalizado nos e-mails enviados pelo sistema, adicione o caminho para a imagem aqui.
    "smtp_email_footer" => file_exists($vs_pasta_custom . "img/custom-email-footer.png") ?
        $vs_pasta_custom . "img/custom-email-footer.png" : "",

    # Caso queira utilizar um favicon personalizado, adicione um arquivo com o nome favicon.png na pasta assets/custom/img
    "favicon" => (file_exists($vs_pasta_custom . "img/favicon.png") ?
        "assets/custom/img/favicon.png" : file_exists($vs_pasta_custom . "img/logo.png")) ?
        "assets/custom/img/logo.png" : "assets/img/favicon.png",

    "media_types" => [
        "image/jpeg" => [
            "folder" => "images",
            "format" => "jpg"
        ],
        "image/png" => [
            "folder" => "images",
            "format" => "png"
        ],
        "image/gif" => [
            "folder" => "images",
            "format" => "gif"
        ],
        "image/bmp" => [
            "folder" => "images",
            "format" => "bmp"
        ],
        "image/svg+xml" => [
            "folder" => "images",
            "format" => "svg"
        ],
        "image/tiff" => [
            "folder" => "images",
            "format" => "tiff"
        ],
        "image/tif" => [
            "folder" => "images",
            "format" => "tif"
        ],
        "image/raw" => [
            "folder" => "images",
            "format" => "raw"
        ],
        "application/pdf" => [
            "folder" => "images",
            "format" => "pdf"
        ],
        "audio/mpeg" => [
            "folder" => "audios",
            "format" => "mp3"
        ],
        "audio/x-m4a" => [
            "folder" => "audios",
            "format" => "m4a"
        ],
        "audio/wav" => [
            "folder" => "audios",
            "format" => "wav"
        ],
        "audio/x-ms-wma" => [
            "folder" => "audios",
            "format" => "wma"
        ],
        "video/mp4" => [
            "folder" => "videos",
            "format" => "mp4"
        ],
        "video/webm" => [
            "folder" => "videos",
            "format" => "webm"
        ],
        "video/avi" => [
            "folder" => "videos",
            "format" => "avi"
        ],
        "video/quicktime" => [
            "folder" => "videos",
            "format" => "mov"
        ],
        "video/x-ms-wmv" => [
            "folder" => "videos",
            "format" => "wmv"
        ],
        "video/x-flv" => [
            "folder" => "videos",
            "format" => "flv"
        ],
        "video/x-matroska" => [
            "folder" => "videos",
            "format" => "mkv"
        ]
    ]
];


