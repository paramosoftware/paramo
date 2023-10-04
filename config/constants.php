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

    "versao" => "1.3.1",
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

    # Lista de extensões permitidas para upload de arquivos
    # Para adicionar uma nova extensão, adicione uma nova linha no formato "extensao" => "tipo_mime"
    # Caso o sistema não esteja configurado para processar uma extensão,
    # o arquivo será ignorado independente da configuração abaixo.
    "extensoes_permitidas" => [
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "png" => "image/png",
        "gif" => "image/gif",
        "pdf" => "application/pdf"
    ],

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
        ]
    ]
];


