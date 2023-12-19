<?php
# ==================================== CONFIGURAÇÕES DE DADOS DE ACESSO ==================================== #
# Faça uma cópia deste arquivo na pasta config/custom e remomeie para envs.php.
# Altere os valores abaixo conforme as suas configurações.

return [

    # ================================================ BANCO DE DADOS ================================================ #

    # As extensões mysqli e pdo são suportadas pelo sistema.
    "db_extension" => "pdo", # mysqli ou pdo
    "db_host" => "REPLACE_HOST", # localhost ou endereço do servidor
    "db_name" => "REPLACE_NAME",
    "db_user" => "REPLACE_USER",
    "db_password" => "REPLACE_PASSWORD",

    # ================================================ EMAIL ================================================ #
    
    # Caso use o Gmail, é necessário gerar uma senha de aplicativo (https://support.google.com/accounts/answer/185833)
    # Necesário habilitar o envio de e-mail em config/custom.php
    "smtp_email" => "",
    "smtp_password" => "",
    "smtp_host" => "",
    "smtp_port" => "", # 465 ou 587
    "smtp_name" => "Páramo - Sistema de Gereciamento de Acervos",

    # ================================================ GOOGLE DRIVE ================================================ #
    
    # Configurações para integração com Google Drive (https://console.cloud.google.com/apis/credentials)
    # Necessário habilitar a integração com o Google Drive em config/custom.php
    "drive_client_id" => "",
    "drive_client_secret" => "",
    "drive_scopes" => "",
    "drive_redirect_uri" => ($_SESSION["redirect_url"] ?? ($_SERVER['HTTP_HOST'] . '/paramo/app/')) .  "functions/redirect.php",
    "drive_api_key" => "",

];
