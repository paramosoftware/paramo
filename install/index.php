<?php

const PHP_MIN_VERSION_REQUIRED = "7.4.0";
const MYSQL_MIN_VERSION_REQUIRED = "5.5.0";
const MARIADB_MIN_VERSION_REQUIRED = "10.0.0";
const BOOTSTRAP_CSS_HREF = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css";
const REQUIRED_EXTENSIONS = ["curl", "ctype", "exif", "fileinfo", "gd", "json", "mbstring", "pdo_mysql", "xml", "zip"];
const RECOMMENDED_EXTENSIONS = ["imagick", "zlib"];
const INSTALLATION_STEPS = ["REQUIREMENTS" => 1, "DATABASE" => 2, "CONFIGURATION" => 3, "CONFIRMATION" => 4, "FINISH" => 5];
const POST_VARIABLES = [
    "script" => ["step"],
    "database" => ["db_host", "db_create", "db_name", "db_user", "db_password", "db_overwrite"],
    "configuration" => ["institution_name", "admin_login", "admin_email", "admin_password"]
];
const MESSAGES = [
    "UNZIP" => [
        "SUCCESS" => [
            "TYPE" => "success",
            "MESSAGE" => "Os arquivos foram descompactados com sucesso."
        ],
        "ERROR" => [
            "TYPE" => "danger",
            "MESSAGE" => "Não foi possível descompactar os arquivos. Verifique as permissões de escrita da pasta do sistema (<strong>paramo</strong>)."
        ]
    ],
    "REQUIREMENTS" => [
        "RECOMMENDED_EXTENSIONS" => [
            "TYPE" => "warning",
            "MESSAGE" => "A(s) extensão(ões) recomendadas acima não foram encontradas. É possível continuar a instalação, mas algumas funcionalidades poderão estar indisponíveis."
        ],
        "SUCCESS" => [
            "TYPE" => "success",
            "MESSAGE" => "Os requisitos mínimos foram atendidos. Clique em avançar para continuar a instalação."
        ],
        "ERROR" => [
            "TYPE" => "danger",
            "MESSAGE" => "Os requisitos mínimos não foram atendidos. Verifique os itens acima e tente novamente."
        ]
    ],
    "DATABASE" => [
        "INFORMATION_DATABASE_CREATION" => [
            "TYPE" => "info",
            "MESSAGE" => "Na opção \"Criar banco de dados\", caso opte pela criação do banco de dados, o usuário informado abaixo deve ter permissão para criar o banco de dados. Caso contrário, o banco de dados já deve existir."
        ],
        "CONNECTION_SUCCESS" => [
            "TYPE" => "success",
            "MESSAGE" => "A conexão com o banco de dados foi estabelecida com sucesso."
        ],
        "CONNECTION_ERROR" => [
            "TYPE" => "danger",
            "MESSAGE" => "Não foi possível conectar ao banco de dados. Verifique se o MySQL/MariaDB está rodando e se os dados de acesso estão corretos."
        ],
        "OVERWRITE" => [
            "TYPE" => "danger",
            "MESSAGE" => "O banco de dados informado não está vazio. Caso opte por sobreescrever, todos os dados serão perdidos."
        ],
        "OVERWRITE_CONFIRMATION" => [
            "TYPE" => "warning",
            "MESSAGE" => "Deseja sobreescrever o banco de dados? Caso não, clique em voltar e informe outro banco de dados."
        ],
        "INSTALLATION_NOT_FOUND" => [
            "TYPE" => "danger",
            "MESSAGE" => "A instalação do banco de dados MySQL ou MariaDB não foi encontrada."
        ],
        "VERSION_VERIFICATION" => [
            "TYPE" => "warning",
            "MESSAGE" => "A versão do banco de dados não foi verificada. Certifique-se de que a versão instalada é compatível com o sistema (MySQL >= 5.5 ou MariaDB >= 10.0), caso contrário, a instalação poderá falhar."
        ],
    ],
    "CONFIGURATION" => [
        "INFORMATION" => [
            "TYPE" => "warning",
            "MESSAGE" => "Os dados abaixo serão utilizados para criar as configuraçãoes iniciais do sistema e poderão ser alterados posteriormente. Você poderá acessar o sistema com o login e senha informados.<strong> Lembre-se de anotar esses dados em algum lugar seguro. </strong>"
        ],
        "SUCCESS" => [
            "TYPE" => "success",
            "MESSAGE" => "As informações foram salvas com sucesso. Clique em avançar para continuar a instalação."
        ],
        "ERROR" => [
            "TYPE" => "danger",
            "MESSAGE" => "Não foi possível salvar as informações. Verifique os itens acima e tente novamente."
        ]
    ],
    "FINISH" => [
        "SUCCESS" => [
            "TYPE" => "success",
            "MESSAGE" => "A instalação foi concluída com sucesso."
        ],
        "INFORMATION_REINSTALL" => [
            "TYPE" => "warning",
            "MESSAGE" => "Caso deseje reinstalar o sistema, apague o arquivo <strong>envs.php</strong> localizado na pasta <strong>/config/custom</strong>."
        ]

    ],
    "FORM" => [
        "PASSWORD_PATTERN" => [
            "TYPE" => "info",
            "MESSAGE" => "Mínino 8 caracteres. Recomenda-se pelo menos 1 letra maiúscula, 1 letra minúscula, 1 número e 1 caractere especial."
        ],
    ],
    "TUTORIALS" => [
        "WRITING_PERMISSIONS" => [
            "TYPE" => "info",
            "MESSAGE" => "Caso não saiba alterar as permissões, verifique esse passo a passo: <a href=\"https://paramosoftware.com.br/documentacao/instalacao/permissoes-pastas\">Como dar permissões de leitura e escrita para uma pasta </a>"
        ],
    ],
    "ERRORS" => [
        "CREATE_DATABASE" => [
            "TYPE" => "danger",
            "MESSAGE" => "Erro ao criar o banco de dados. Verifique se o usuário informado tem permissão para criar bancos de dados"
        ],
        "RUN_SCRIPT" => [
            "TYPE" => "danger",
            "MESSAGE" => "Erro ao executar o script de criação do banco de dados."
        ],
        "CREATE_INSTITUTION" => [
            "TYPE" => "danger",
            "MESSAGE" => "Erro ao criar instituição"
        ],
        "CREATE_ADMIN" => [
            "TYPE" => "danger",
            "MESSAGE" => "Erro ao criar o usuário administrador."
        ],
        "CREATE_CONFIG_FILE" => [
            "TYPE" => "danger",
            "MESSAGE" => "Erro ao criar o arquivo de configuração."
        ]
    ]
];

define("DATABASE_PATH", dirname(__FILE__) . "/paramo.sql");
define("ASSETS_FOLDER", dirname(__FILE__) . "/../app/assets/custom/");
define("MEDIA_FOLDER", dirname(__FILE__) . "/../app/media/");
define("CONFIG_FOLDER", dirname(__FILE__) . "/../config/");
define("LOG_FOLDER", dirname(__FILE__) . "/../src/logs/");
define("HTML_PURIFIER_FOLDER", dirname(__FILE__) . "/../src/vendors/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer");

if (file_exists(CONFIG_FOLDER . "/custom/envs.php"))
{
    header("Location: ../app/login.php");
    exit();
}

$step = get_post_value("step");

if ($step == "")
{
    $step = INSTALLATION_STEPS["REQUIREMENTS"];
}

switch ($step)
{

    case INSTALLATION_STEPS["REQUIREMENTS"]:
        $_POST["step"] = INSTALLATION_STEPS["DATABASE"];
        get_html_page("requirements", get_html_step_1());
        break;

    case INSTALLATION_STEPS["DATABASE"]:
        $_POST["step"] = INSTALLATION_STEPS["CONFIGURATION"];
        get_html_page("database", get_html_step_2());
        break;

    case INSTALLATION_STEPS["CONFIGURATION"]:
        $database_checks = check_database_information();

        if ($database_checks[0])
        {
            $_POST["step"] = INSTALLATION_STEPS["CONFIRMATION"];
            get_html_page("configuration", get_html_step_3());
        }
        else
        {
            $_POST["step"] = INSTALLATION_STEPS["CONFIGURATION"];
            get_html_page("database", get_html_step_2($database_checks[1]));
        }
        break;

    case INSTALLATION_STEPS["CONFIRMATION"]:
        $configuration_checks = check_configuration_information();

        if ($configuration_checks[0])
        {
            $_POST["step"] = INSTALLATION_STEPS["FINISH"];
            get_html_page("confirmation", get_html_step_4());
        }
        else
        {
            $_POST["step"] = INSTALLATION_STEPS["CONFIRMATION"];
            get_html_page("configuration", get_html_step_3($configuration_checks[1]));
        }
        break;

    case INSTALLATION_STEPS["FINISH"]:
        $installation_result = install_system();

        if ($installation_result[0])
        {
            get_html_page("finish", get_html_step_5());
        }
        else
        {
            $_POST["step"] = INSTALLATION_STEPS["CONFIRMATION"];
            get_html_page("configuration", get_html_step_3($installation_result[1]));
        }
        break;

}

function get_html_page(string $active_tab, string $tab_content): void
{

    $html = '<!DOCTYPE html>';
    $html .= '<html lang="pt-br">';
    $html .= '<head>';
    $html .= '<meta charset="UTF-8">';
    $html .= '<title>Páramo | Instalação</title>';
    $html .= '<link rel="stylesheet" href="' . '../app/assets/css/style.css">';
    $html .= '</head>';
    $html .= '<body>';
    $html .= '<div class="container mt-5">';
    $html .= '<div class="row">';
    $html .= '<div class="col-md-12">';
    $html .= '<h1>Páramo - Instalação</h1>';

    $html .= get_nav_tabs($active_tab);

    $html .= get_tab_content($active_tab, $tab_content);

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</body>';
    $html .= '</html>';

    echo $html;
}

function get_html_step_1(): string
{

    $html = "";

    $unzip_files = unzip_files();
    $php_version = check_php_version();
    $db_version = check_db_version();
    $extensions = check_extensions();
    $recommendations = check_recommendations();

    $post_values = handle_post();

    $html .= "<br>";

    $html .= get_alert('<strong>PHP</strong>: ' . $php_version[1], ($php_version[0] ? "success" : "danger"));
    $html .= get_alert('<strong>Banco de Dados</strong>: ' . $db_version[1], ($db_version[0] ? "success" : "warning"));
    $html .= get_alert('<strong>Extensões</strong>: ' . $extensions[1], ($extensions[0] ? "success" : "danger"));
    $html .= get_alert('<strong>Recomendações</strong>: ' . $recommendations[1], ($recommendations[0] ? "success" : "warning"));

    if (!$recommendations[0])
    {
        $html .= get_alert(MESSAGES["REQUIREMENTS"]["RECOMMENDED_EXTENSIONS"]);
    }

    $html .= "<br>";

    $hidden_inputs = [];
    foreach ($post_values as $key => $value)
    {
        $hidden_inputs[] = get_hidden_input($key, $value)[0];
    }

    $button = get_button("Avançar");
    $form = get_form($hidden_inputs, $button);

    if (check_requirements() && $unzip_files)
    {
        $html .= '<div>';
        $html .= '<h5>';
        $html .= MESSAGES["REQUIREMENTS"]["SUCCESS"]["MESSAGE"];
        $html .= '</h5>';
        $html .= '</div>';
        $html .= $form;
    }
    else
    {
        if (!$unzip_files)
        {
            if (strpos($extensions[1], "zip") === false)
            {
                $html .= get_alert(MESSAGES["UNZIP"]["ERROR"]["MESSAGE"], MESSAGES["UNZIP"]["ERROR"]["TYPE"]);
                $html .= get_alert(MESSAGES["TUTORIALS"]["WRITING_PERMISSIONS"], "info", true);
            }
            $html .= '<link rel="stylesheet" href="' . BOOTSTRAP_CSS_HREF . '">';
        }

        $html .= get_alert(MESSAGES["REQUIREMENTS"]["ERROR"]);
    }

    return $html;

}

function get_html_step_2($error = ""): string
{

    $post_values = handle_post();

    if ($error == MESSAGES["DATABASE"]["OVERWRITE_CONFIRMATION"]["MESSAGE"])
    {
        return get_html_step_2_db_overwrite();
    }

    $html = '<br>';

    $html .= get_alert(MESSAGES["DATABASE"]["INFORMATION_DATABASE_CREATION"]);

    if ($error != "")
    {
        $html .= get_alert($error, "danger");
    }

    $fields_parameters = get_input_field_parameters();

    foreach (POST_VARIABLES["configuration"] as $key)
    {
        unset($fields_parameters[$key]);
    }

    unset($fields_parameters["db_overwrite"]);

    $inputs = get_form_fields($fields_parameters);

    $html .= "<br>";

    $hidden_inputs = [];

    foreach ($post_values as $key => $value)
    {
        if (array_key_exists($key, POST_VARIABLES["database"]))
        {
            continue;
        }

        $hidden_inputs[] = get_hidden_input($key, $value)[0];
    }

    $method_onclick = "document.getElementById('step').value = 1; document.getElementById('form').submit();";
    $button_back = get_button("Voltar", "button", "btn btn-outline-primary", $method_onclick);
    $button = get_button("Avançar");
    $form = get_form(array_merge($hidden_inputs, $inputs), $button_back . ' ' . $button);

    $html .= $form;

    return $html;
}

function get_html_step_2_db_overwrite(): string
{

    $post_values = handle_post();

    $html = '<br>';

    $html .= get_alert(MESSAGES["DATABASE"]["OVERWRITE"], "", true);

    $html .= '<h5>';

    $html .= MESSAGES["DATABASE"]["OVERWRITE_CONFIRMATION"]["MESSAGE"];

    $html .= '</h5>';

    $html .= "<br>";

    $hidden_inputs = [];

    foreach ($post_values as $key => $value)
    {
        $hidden_inputs[] = get_hidden_input($key, $value)[0];
    }

    $method_onclick = "document.getElementById('step').value = 2; document.getElementById('form').submit();";
    $button_back = get_button("Voltar", "button", "btn btn-outline-primary", $method_onclick);

    $method_onclick = "document.getElementById('db_overwrite').value = 1; document.getElementById('form').submit();";
    $button = get_button("Sobreescrever", "button", "btn btn-primary", $method_onclick);
    $form = get_form($hidden_inputs, $button_back . ' ' . $button);

    $html .= $form;

    return $html;

}

function get_html_step_3(string $error = ""): string
{

    $html = '<br>';

    $html .= get_alert(MESSAGES["CONFIGURATION"]["INFORMATION"], "", true);

    if ($error != "")
    {
        $html .= get_alert($error, "danger");
        $html .= get_alert(MESSAGES["TUTORIALS"]["WRITING_PERMISSIONS"], "info", true);
    }

    $fields_parameters = get_input_field_parameters();

    foreach (POST_VARIABLES["database"] as $key)
    {
        unset($fields_parameters[$key]);
    }

    $inputs = get_form_fields($fields_parameters);

    $html .= "<br>";

    $hidden_inputs = [];

    foreach ($_POST as $key => $value)
    {
        if (array_key_exists($key, POST_VARIABLES["configuration"]))
        {
            continue;
        }

        $hidden_inputs[] = get_hidden_input($key, $value)[0];
    }

    $method_onclick = "document.getElementById('db_overwrite').value = ''; document.getElementById('step').value = 2; document.getElementById('form').submit();";
    $button_back = get_button("Voltar", "button", "btn btn-outline-primary", $method_onclick);
    $button = get_button("Avançar");
    $form = get_form(array_merge($hidden_inputs, $inputs), $button_back . ' ' . $button);

    $html .= $form;

    return $html;
}

function get_html_step_4(): string
{

    $fields_parameters = get_input_field_parameters();
    $post_values = handle_post();

    $html = '<br>';

    $html .= '<h3>Confirme as configurações para a instalação do sistema: </h3>';

    $html .= '<br>';

    $html .= '<h5>Banco de dados: </h5>';
    $html .= '<ul>';


    foreach (POST_VARIABLES["database"] as $key)
    {

        if ($key == "db_create")
        {
            $value = ($post_values[$key] == 1) ? "Sim" : "Não";
            $html .= '<li class="mb-2"><strong>' . $fields_parameters[$key]["label"] . '</strong>' . $value . '</li>';
            continue;
        }

        if ($key == "db_overwrite")
        {
            if ($post_values["db_overwrite"] != "")
            {
                $html .= '<li class="mb-2"><strong>Sobreescrever o dados do banco: </strong> Sim </li>';
            }
            continue;
        }

        if ($key == "db_password")
        {
            $html .= '<li class="mb-2"><strong>' . $fields_parameters[$key]["label"] . ': </strong>';
            $html .= '<span style="color: white">' . htmlspecialchars($post_values[$key]) . '</span> (selecione o texto para visualizar a senha)</li>';
            continue;
        }


        $html .= '<li class="mb-2"><strong>' . $fields_parameters[$key]["label"] . ':</strong> ' . htmlspecialchars($post_values[$key]) . '</li>';
    }

    $html .= '</ul>';

    $html .= '<h5>Sistema: </h5>';

    $html .= '<ul>';

    foreach (POST_VARIABLES["configuration"] as $key)
    {

        if ($key == "admin_password")
        {
            $html .= '<li class="mb-2"><strong>' . $fields_parameters[$key]["label"] . ': </strong>';
            $html .= '<span style="color: white">' . htmlspecialchars($post_values[$key]) . '</span> (selecione o texto para visualizar a senha)</li>';
            continue;
        }

        $html .= '<li class="mb-2"><strong>' . $fields_parameters[$key]["label"] . ':</strong> ' . htmlspecialchars($post_values[$key]) . '</li>';
    }

    $html .= '</ul>';

    $hidden_inputs = [];

    foreach ($post_values as $key => $value)
    {
        $hidden_inputs[] = get_hidden_input($key, $value)[0];
    }

    $html .= "<br>";

    $method_onclick = "document.getElementById('db_overwrite').value = ''; document.getElementById('step').value = 3; document.getElementById('form').submit();";
    $button_back = get_button("Voltar", "button", "btn btn-outline-primary", $method_onclick);
    $button = get_button("Confirmar e instalar");

    $form = get_form(array_merge($hidden_inputs), $button_back . ' ' . $button);

    $html .= $form;

    return $html;

}

function get_html_step_5(): string
{

    $post_values = handle_post();

    $html = '<br>';

    $html .= get_alert(MESSAGES["FINISH"]["SUCCESS"]);
    $html .= get_alert(MESSAGES["FINISH"]["INFORMATION_REINSTALL"]);

    $html .= '<div>';
    $html .= '<h5>Para acessar o sistema, clique no botão abaixo</h5>';
    $html .= '</div>';

    $html .= '<div>';
    $html .= 'Essas são as credenciais de acesso ao sistema:';
    $html .= '<ul>';
    $html .= '<li>Login: ' . htmlspecialchars($post_values["admin_login"]) . '</li>';
    $html .= '<li>Senha: <span style="color: white"> ' . $_POST['admin_password'] . '</span> (selecione o texto, caso queira visualizar a senha)</li>';
    $html .= '</ul>';

    $html .= '</div>';
    $html .= '<a href="../app/login.php" class="btn btn-primary">Acessar o Sistema</a>';

    return $html;
}

function get_post_value($name)
{
    return $_POST[$name] ?? "";
}

function handle_post(): array
{
    $post_values = [];

    foreach (POST_VARIABLES as $post_value_array)
    {
        foreach ($post_value_array as $post_value_name)
        {
            $post_values[$post_value_name] = get_post_value($post_value_name);
        }
    }

    return $post_values;
}

function unzip_files(): bool
{
    if (file_exists(dirname(__FILE__) . "/../app"))
    {
        return true;
    }

    $zip = new ZipArchive;
    $res = $zip->open(dirname(__FILE__) . "/paramo.zip");

    if ($res === TRUE)
    {
        if ($zip->extractTo(dirname(__FILE__) . "/../")) {
            $zip->close();
            unlink(dirname(__FILE__) . "/paramo.zip");
            return true;
        } else {
            $zip->close();
            return false;
        }
    }

    return false;
}

function check_php_version(): array
{

    $php_version = explode("-", PHP_VERSION)[0];

    if (version_compare($php_version, PHP_MIN_VERSION_REQUIRED) < 0)
    {
        return array(false, "PHP v." . PHP_VERSION);
    }

    return array(true, "PHP v." . PHP_VERSION . " encontrada");
}

function check_database_empty(): array
{

    $post_values = handle_post();

    $db = get_database_connection()[1];

    $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ?";

    $stmt = $db->prepare($sql);

    $stmt->bind_param("s", $post_values["db_name"]);

    if (!$stmt->execute())
    {
        return array(false, "Não foi possível verificar se o banco de dados está vazio.");
    }

    $count = 0;
    $stmt->bind_result($count);

    $stmt->fetch();

    $stmt->close();

    if ($count != 0)
    {
        return array(false, MESSAGES["DATABASE"]["OVERWRITE_CONFIRMATION"]["MESSAGE"]);
    }

    return array(true, "O banco de dados está vazio.");

}

function check_database_information(): array
{
    $post_values = handle_post();

    $connection = $post_values["db_create"] == 1 ? get_database_connection() :
        get_database_connection($post_values["db_name"]);

    if (!$connection[0])
    {
        return array(false, $connection[1]);
    }

    if ($post_values["db_create"] == 1)
    {
        $create_database_permission = check_create_database_permission($connection[1]);
        if (!$create_database_permission)
        {
            return array(false, MESSAGES["ERRORS"]["CREATE_DATABASE"]["MESSAGE"]);
        }
    }

    if ($post_values["db_overwrite"] != 1)
    {
        $is_empty = check_database_empty();
        if (!$is_empty[0])
        {
            return array(false, $is_empty[1]);
        }
    }

    return array(true, MESSAGES["DATABASE"]["CONNECTION_SUCCESS"]["MESSAGE"]);
}

function check_create_database_permission($db): bool
{

    $post_values = handle_post();
    $sql = "CREATE DATABASE IF NOT EXISTS `" . $post_values["db_name"];
    $sql .= "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

    try {
        $db->query($sql);
    } catch (Exception $e) {
        return false;
    }

    $sql = "DROP DATABASE `" . $post_values["db_name"] . "`";

    try {
        $db->query($sql);
    } catch (Exception $e) {
        return true;
    }

    return true;
}

function check_db_version(): array
{
    if (!function_exists("shell_exec"))
    {
        return array(false, MESSAGES["DATABASE"]["VERSION_VERIFICATION"]["MESSAGE"]);
    }

    $shell_exec = shell_exec("mysql --version");
    preg_match('/\d+\.\d+\.\d+/', $shell_exec, $matches);

    $database_name = "";
    $database_version = $matches[0] ?? "";

    if (strpos(strtolower($shell_exec), "mariadb") !== false)
    {
        $database_name = "MariaDB";
    }
    elseif (strpos(strtolower($shell_exec), "mysql") !== false)
    {
        $database_name = "MySQL";
    }

    if ($database_name == "" || $database_version == "")
    {
        return array(false, MESSAGES["DATABASE"]["INSTALLATION_NOT_FOUND"]);
    }

    $min_version_required = ($database_name == "MariaDB") ? MARIADB_MIN_VERSION_REQUIRED : MYSQL_MIN_VERSION_REQUIRED;

    if (version_compare($database_version, $min_version_required) < 0)
    {
        return array(false, "Instalada $database_name v.$database_version - Requerida: $database_name v.$min_version_required");
    }
    else
    {
        return array(true, "$database_name v.$database_version encontrada");
    }
}

function check_extensions(): array
{
    $extensions = get_loaded_extensions();

    foreach (REQUIRED_EXTENSIONS as $extension)
    {
        if (!in_array($extension, $extensions))
        {
            return array(false, $extension);
        }
    }

    return array(true, "Todas as extensões requeridas estão instaladas.");
}

function check_recommendations(): array
{
    $extensions = get_loaded_extensions();

    foreach (RECOMMENDED_EXTENSIONS as $extension)
    {
        $absent_extensions = array();
        if (!in_array($extension, $extensions))
        {
            $absent_extensions[] = $extension;
        }

        if (count($absent_extensions) > 0)
        {
            return array(false, implode(" ", $absent_extensions));
        }
    }

    return array(true, "Todas as extensões recomendadas estão instaladas.");
}

function check_requirements(): bool
{
    $php_version = check_php_version();
    $extensions = check_extensions();

    if ($php_version[0] && $extensions[0])
    {
        return true;
    }

    return false;
}

function check_configuration_information(): array
{

    $check_permissions = check_folder_permissions();
    if (!$check_permissions[0])
    {
        return array(false, $check_permissions[1]);
    }

    return array(true, "");
}

function check_folder_permissions(): array
{

    $folders = array(CONFIG_FOLDER, MEDIA_FOLDER, ASSETS_FOLDER, LOG_FOLDER, HTML_PURIFIER_FOLDER);

    $non_readable_folders = array();
    $non_writable_folders = array();

    foreach ($folders as $folder)
    {
        if (!is_writable($folder))
        {
            $non_writable_folders[] = $folder;
        }

        if (!is_readable($folder))
        {
            $non_readable_folders[] = $folder;
        }
    }

    $message = "";

    if (count($non_readable_folders) > 0)
    {
        $message .= "Os seguintes diretórios não têm permissão de leitura: <br>";
        foreach ($non_readable_folders as $folder)
        {
            $message .= $folder . "<br>";
        }
    }

    if (count($non_writable_folders) > 0)
    {
        $message .= "Os seguintes diretórios não têm permissão de escrita: <br>";
        foreach ($non_writable_folders as $folder)
        {
            $message .= $folder . "<br>";
        }
    }

    if ($message != "") {
        $message .= "Altere as permissões para usuário e grupo para leitura e escrita. Utilize os comandos abaixo: <br>";
        $message .= "chmod -R 775 CAMINHO_DO_DIRETÓRIO <br>";
        $message .= "chown -R USUÁRIO:GRUPO CAMINHO_DO_DIRETÓRIO (exemplo: chown -R www-data:www-data CAMINHO_DO_DIRETÓRIO) <br>";
        return array(false, $message);
    }

    return array(true, "");
}

function get_database_connection($db_name = ""): array
{
    $post_values = handle_post();

    try
    {
        if ($db_name == "")
        {
            $db = new mysqli($post_values["db_host"], $post_values["db_user"], $post_values["db_password"]);
        }
        else
        {
            $db = new mysqli($post_values["db_host"], $post_values["db_user"], $post_values["db_password"], $post_values["db_name"]);
        }
    }
    catch (Exception $e)
    {
        return array(false, MESSAGES["DATABASE"]["CONNECTION_ERROR"]["MESSAGE"]);
    }

    return array(true, $db);
}

function install_system(): array
{

    if (!check_folder_permissions()[0])
    {
        return array(false, check_folder_permissions()[1]);
    }

    if (!create_database())
    {
        return array(false, MESSAGES["ERRORS"]["CREATE_DATABASE"]["MESSAGE"]);
    }

    if (!run_database_script())
    {
        return array(false, MESSAGES["ERRORS"]["RUN_SCRIPT"]["MESSAGE"]);
    }

    if (!create_institution())
    {
        return array(false, MESSAGES["ERRORS"]["CREATE_INSTITUTION"]["MESSAGE"]);
    }

    if (!create_admin())
    {
        return array(false, MESSAGES["ERRORS"]["CREATE_ADMIN"]["MESSAGE"]);
    }

    if (!create_custom_config())
    {
        return array(false, MESSAGES["ERRORS"]["CREATE_CONFIG_FILE"]["MESSAGE"]);
    }

    return array(true, "");
}

function create_database(): bool
{

    $post_values = handle_post();

    if ($post_values["db_create"] == 0)
    {
        return true;
    }

    $db = get_database_connection()[1];

    $sql = "CREATE DATABASE IF NOT EXISTS `" . $post_values["db_name"];

    $sql .= "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

    if (!$db->query($sql))
    {
        return false;
    }

    $db->commit();
    $db->close();

    return true;

}

function run_database_script(): bool
{

    $post_values = handle_post();

    $db = get_database_connection($post_values["db_name"])[1];

    if (!$db)
    {
        return false;
    }

    $sql = file_get_contents(DATABASE_PATH);

    if (!$sql)
    {
        return false;
    }

    if ($db->multi_query($sql))
    {
        do
        {
            if ($result = $db->store_result())
            {
                while ($row = $result->fetch_row())
                {
                    printf("%s", $row[0]);
                }
                $result->free();
            }
        } while ($db->next_result());
    }

    return true;
}

function create_institution(): bool
{
    $post_values = handle_post();

    $db = get_database_connection($post_values["db_name"])[1];

    if (!$db)
    {
        return false;
    }

    $words = explode(" ", $post_values["institution_name"]);
    $sigla = "";

    if (count($words) > 1)
    {
        foreach ($words as $word)
        {
            $sigla .= $word[0];
        }
    }
    else
    {
        $sigla = substr($post_values["institution_name"], 0, 3);
    }

    $sigla = strtoupper($sigla);

    $sql = "insert into instituicao (codigo, nome, entidade_codigo, admin, sigla) values (1, ?, null, 1, ?)";

    $stmt = $db->prepare($sql);

    $stmt->bind_param("ss", $post_values["institution_name"], $sigla);

    if (!$stmt->execute())
    {
        return false;
    }

    $stmt->close();

    return true;
}

function create_admin(): bool
{
    $post_values = handle_post();

    $db = get_database_connection($post_values["db_name"])[1];

    if (!$db)
    {
        return false;
    }

    $sql = "insert into usuario (codigo, instituicao_codigo, tipo_codigo, nome, login, senha, telefone, email, ativo, token, ultimo_login, senha_provisoria, expiracao_senha_provisoria) 
                values (1, 1, 2, 'Admin', ?, ?, null, ?, 1, null, null, null, null)";

    $stmt = $db->prepare($sql);

    $hashed_password = password_hash($post_values["admin_password"], PASSWORD_DEFAULT);

    $stmt->bind_param("sss", $post_values["admin_login"], $hashed_password, $post_values["admin_email"]);

    if (!$stmt->execute()) return false;

    $stmt->close();

    return true;
}

function create_custom_config(): bool
{
    $post_values = handle_post();

    $envs_example_file = file_get_contents(CONFIG_FOLDER . "/envs_example.php");
    $envs_example_file = str_replace("REPLACE_HOST", escape_chars($post_values["db_host"]), $envs_example_file);
    $envs_example_file = str_replace("REPLACE_NAME", escape_chars($post_values["db_name"]), $envs_example_file);
    $envs_example_file = str_replace("REPLACE_USER", escape_chars($post_values["db_user"]), $envs_example_file);
    $envs_example_file = str_replace("REPLACE_PASSWORD", escape_chars($post_values["db_password"]), $envs_example_file);
    $envs_file_path = CONFIG_FOLDER . "/custom/envs.php";

    if (!file_exists($envs_file_path))
    {
        $file = fopen($envs_file_path, "w");
        fwrite($file, $envs_example_file);
        fclose($file);
    }
    else
    {
        return false;
    }

    $settings_content = '<?php
        return [   
            "nome_instituicao" => "' . escape_chars($post_values["institution_name"]) . '" 
        ];
    ';

    $settings_file_path = CONFIG_FOLDER . "/custom/settings.php";

    if (!file_exists($settings_file_path))
    {
        $file = fopen($settings_file_path, "w");
        fwrite($file, $settings_content);
        fclose($file);
    }
    else
    {
        return false;
    }

    return true;
}


function escape_chars(string $string): string
{
    $string = addslashes($string);
    return str_replace('$', '\$', $string);
}

function get_input_field_parameters(): array
{

    $post_values = handle_post();

    return [
        "db_host" => [
            "label" => "Host do banco de dados",
            "placeholder" => "Ex: localhost",
            "value" => empty($post_values["db_host"]) ? "localhost" : htmlspecialchars($post_values["db_host"]),
            "required" => true,
            "type" => "text"
        ],

        "db_name" => [
            "label" => "Nome do banco de dados",
            "placeholder" => "Ex: paramo ou a123456_paramo",
            "value" => htmlspecialchars($post_values["db_name"]),
            "required" => true,
            "type" => "text"
        ],

        "db_user" => [
            "label" => "Usuário do banco de dados",
            "placeholder" => "Ex: paramo ou a123456_paramo",
            "value" => htmlspecialchars($post_values["db_user"]),
            "required" => true,
            "type" => "text"
        ],

        "db_password" => [
            "label" => "Senha do banco de dados",
            "placeholder" => "",
            "value" => htmlspecialchars($post_values["db_password"]),
            "required" => true,
            "type" => "password"
        ],

        "db_create" => [
            "label" => "Criar banco de dados:  ",
            "placeholder" => "",
            "value" => htmlspecialchars($post_values["db_create"]),
            "required" => false,
            "type" => "radio"
        ],

        "institution_name" => [
            "label" => "Nome da instituição",
            "placeholder" => "Ex: Páramo - Sistema de Gereciamento de Acervos",
            "value" => htmlspecialchars($post_values["institution_name"]),
            "required" => true,
            "type" => "text"
        ],

        "admin_login" => [
            "label" => "Login do usuário administrador",
            "placeholder" => "",
            "value" => htmlspecialchars($post_values["admin_login"]),
            "required" => true,
            "type" => "text"
        ],

        "admin_email" => [
            "label" => "Email do usuário administrador",
            "placeholder" => "Ex: email@email.com",
            "value" => htmlspecialchars($post_values["admin_email"]),
            "required" => true,
            "type" => "email"
        ],

        "admin_password" => [
            "label" => "Senha do usuário administrador",
            "placeholder" => MESSAGES["FORM"]["PASSWORD_PATTERN"]["MESSAGE"],
            "value" => htmlspecialchars($post_values["admin_password"]),
            "required" => true,
            "type" => "password"
        ]
    ];

}

function get_tab_parameters(): array
{
    return [
        "requirements" => "Verificação de requisitos mínimos",
        "database" => "Configuração do banco de dados",
        "configuration" => "Configuração inicial do sistema",
        "confirmation" => "Confirmação das informações",
        "finish" => "Instalação concluída",
    ];
}

function get_alert($message, string $type = "warning", bool $prepend_alert = false): string
{
    $prepend = [
        "warning" => "ATENÇÃO! ",
        "danger" => "IMPORTANTE! ",
        "success" => "SUCESSO! ",
    ];

    if (is_array($message))
    {
        $type = $message["TYPE"] ?? "";
        $message = $message["MESSAGE"] ?? "";
    }

    $html = '<div class="alert alert-' . $type . '">';
    if ($prepend_alert)
    {
        $html .= '<strong>' . $prepend[$type] . '</strong>';
    }
    $html .= $message;
    $html .= '</div>';

    return $html;
}

function get_nav_item($id, $title, $active = false): string
{

    $width = 100 / count(get_tab_parameters());

    $html = '<li class="nav-item text-center" style="width: ' . $width . '%;">';
    $html .= '<a class="nav-link ' . ($active ? 'active' : '') . ' ' . (!$active ? 'disabled' : '') . '" id="' . $id . '-tab" data-toggle="tab" href="#' . $id . '" role="tab" aria-controls="' . $id . '" aria-selected="' . ($active ? 'true' : 'false') . '">' . $title . '</a>';
    $html .= '</li>';

    return $html;
}

function get_nav_tabs($active_tab): string
{

    $tabs = get_tab_parameters();

    $html = '<ul class="nav nav-tabs" id="myTab" role="tablist">';

    foreach ($tabs as $id => $title)
    {
        $html .= get_nav_item($id, $title, $active_tab == $id);
    }

    $html .= '</ul>';

    $html .= get_progress_bar($active_tab);

    return $html;
}

function get_progress_bar(string $active_tab): string
{
    $width = 100 / count(get_tab_parameters());
    $progress_completed = INSTALLATION_STEPS[strtoupper($active_tab)] * $width;

    $html = '<div class="progress">';
    $html .= '<div class="progress-bar" role="progressbar" style="width: ' . $progress_completed . '%;" aria-valuenow="' . $progress_completed . '" aria-valuemin="0" aria-valuemax="100"></div>';
    $html .= '</div>';

    return $html;
}

function get_tab_content($active_tab, $content): string
{

    $tab = '<div class="tab-content" id=' . $active_tab . '">';
    $tab .= '<div class="tab-pane fade show active" id="' . $active_tab . '" role="tabpanel" aria-labelledby="' . $active_tab . '-tab">';
    $tab .= $content;
    $tab .= '</div>';
    $tab .= '</div>';

    return $tab;
}

function get_button($title, $type = 'submit', $class = 'btn btn-primary', $method = ""): string
{

    $html = '';

    if ($method != "")
    {
        $method = 'onclick="' . $method . '"';
    }

    $html .= '<button type="' . $type . '" class="' . $class . '" ' . $method . '>' . $title . '</button>';

    return $html;
}

function get_input_field($name, $label, $placeholder, $type = 'text', $value = '', $required = true): string
{

    $html = '<div class="form-group mb-3">';
    $html .= '<label for="' . $name . '">' . $label . '</label>';

    if ($type == "radio")
    {
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input" type="radio" name="' . $name . '" value="1">';
        $html .= '<label class="form-check-label" for="' . $name . '">Sim</label>';
        $html .= '</div>';
        $html .= '<div class="form-check">';
        $html .= '<input class="form-check-input" type="radio" name="' . $name . '" value="0" checked>';
        $html .= '<label class="form-check-label" for="' . $name . '">Não</label>';
        $html .= '</div>';
    }
    elseif ($type == 'password' && $name == 'admin_password')
    {
        $html .= '<input type="' . $type . '" class="form-control" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . ($required ? 'required' : '') . ' minlength="8"  autocomplete="new-password">';
    }
    else
    {
        $html .= '<input type="' . $type . '" class="form-control" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $value . '" ' . ($required ? 'required' : '') . '>';
    }

    $html .= '</div>';
    return $html;
}

function get_hidden_input($name, $value): array
{

    $html = '<input id="' . $name . '" type="hidden" name="' . $name . '" value="' . $value . '">';

    return array($html);
}

function get_form_fields(array $fields): array
{

    $form_fields = array();

    foreach ($fields as $name => $field)
    {
        $form_fields[] = get_input_field($name, $field["label"], $field["placeholder"], $field["type"], $field["value"], $field["required"]);
    }

    return $form_fields;
}

function get_form(array $inputs, string $button): string
{
    return '<form id="form" method="post" enctype="multipart/form-data">' . implode('', $inputs) . $button . '</form>';
}