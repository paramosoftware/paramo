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

    "versao" => "1.3.8",
    "pasta_logs" => dirname(__FILE__) . "/../src/logs/",

    # ================================================ ASSETS E MÍDIA  ================================================ #

    # É possível personalizar o logo do sistema pela parte administrativa.
    # Caso não seja definido um logo personalizado, o logo padrão será utilizado.
    "logo" => file_exists(dirname(__FILE__) . "/../app/assets/img/custom-logo.png") ?
        "assets/img/custom-logo.png" :
        "assets/img/logo.png" ?? "",

    # Caso queira utilizar um rodapé personalizado nos e-mails enviados pelo sistema, adicione o caminho para a imagem aqui.
    "smtp_email_footer" => file_exists(dirname(__FILE__) . "/../app/assets/img/custom-email-footer.png") ?
        dirname(__FILE__) . "/../app/assets/img/custom-email-footer.png" :
        "",

    # Caso queira utilizar um favicon personalizado, adicione um arquivo com o nome custom-favicon.png na pasta assets/img
    "favicon" => (file_exists(dirname(__FILE__) . "/../app/assets/img/custom-favicon.png") ?
        "assets/img/custom-favicon.png" : file_exists(dirname(__FILE__) . "/../app/assets/img/custom-logo.png")) ?
        "assets/img/custom-logo.png" : "assets/img/favicon.png",

    "pasta_assets" => [
        "images" => dirname(__FILE__) . "/../app/assets/img/",
    ],

    "pasta_media" => [
        "documents" => dirname(__FILE__) . "/../app/media/documents/",
        "downloads" => dirname(__FILE__) . "/../app/media/downloads/",
        "videos" => dirname(__FILE__) . "/../app/media/videos/",
        "audios" => dirname(__FILE__) . "/../app/media/audios/",
        "images" => [
            "large" => dirname(__FILE__) . "/../app/media/images/large/",
            "medium" => dirname(__FILE__) . "/../app/media/images/medium/",
            "thumb" => dirname(__FILE__) . "/../app/media/images/thumb/",
            "original" => dirname(__FILE__) . "/../app/media/images/original/",
        ],
        "temp" => dirname(__FILE__) . "/../app/media/temp/",
    ],

    "pasta_layouts" => dirname(__FILE__) . "/../src/lib/business/layout/",

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


