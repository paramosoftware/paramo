<?php

class session
{
    /**
     * Mesma duração usada no gc_maxlifetime e no cookie da sessão.
     *
     * Cookie e sessão precisam durar o mesmo: com cookie de 1h, uma aba
     * aberta por mais tempo recebia uma sessão nova (sem csrf_token) e
     * qualquer formulário enviado por ela era recusado.
     */
    public const DURACAO_SESSAO_SEGUNDOS = 60 * 60 * 12;

    public static function start_session(): void
    {
        $vs_session_name = session::generate_cookie_name();

        if (session_status() == PHP_SESSION_NONE)
        {
            session_name($vs_session_name);
            session_start(['gc_maxlifetime' => self::DURACAO_SESSAO_SEGUNDOS]);
            session::refresh_session_cookie();
        }
    }

    public static function is_same_site(): bool
    {
        $vs_session_name = session::generate_cookie_name(true);
        return isset($_COOKIE[$vs_session_name]);
    }

    public static function get_logged_user()
    {
        session::start_session();
        $vs_cookie = session::generate_cookie_name(true);
        $vs_token = $_SESSION["usuario_token"] ?? $_COOKIE[$vs_cookie] ?? null;

        if (empty($vs_token))
        {
            return false;
        }

        $va_usuario = session::get_user(["usuario_token" => $vs_token, "usuario_ativo" => 1]);

        if (!empty($va_usuario))
        {
            if (strtotime($va_usuario["usuario_ultimo_login"]) > strtotime("-12 hours"))
            {
                $_SESSION["usuario_logado_codigo"] = $va_usuario["usuario_codigo"];
                $_SESSION["usuario_token"] = $vs_token;
                return $va_usuario["usuario_codigo"];
            }
        }

        return false;
    }

    public static function get_user($pa_parametros): array
    {
        $vo_usuario = new usuario;
        $va_usuario = $vo_usuario->ler_lista($pa_parametros, "senha", 0, 1, null, null, null, 1, true);

        if (!empty($va_usuario))
        {
            return $va_usuario[0];
        }

        return [];
    }

    public static function generate_token(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function generate_cookie_name($pb_token=false): string
    {
        return
            strtolower(preg_replace("/[^A-Za-z0-9]/", "", config::get(["nome_instituicao"])))
            . ($pb_token ? "_token" : "");
    }

    public static function csrf_token(): string
    {
        self::start_session();

        if (empty($_SESSION["csrf_token"]) || !is_string($_SESSION["csrf_token"]))
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

        return $_SESSION["csrf_token"];
    }

    public static function csrf_input(): string
    {
        return '<input type="hidden" name="csrf_token" value="'
            . htmlspecialchars(self::csrf_token(), ENT_QUOTES, "UTF-8")
            . '">';
    }

    public static function origem_permitida(): bool
    {
        $vs_host = $_SERVER["HTTP_HOST"] ?? "";

        if ($vs_host === "")
            return true;

        $vs_host_sem_porta = strtolower(explode(":", $vs_host)[0]);

        foreach (["HTTP_ORIGIN", "HTTP_REFERER"] as $vs_variavel)
        {
            if (empty($_SERVER[$vs_variavel]))
                continue;

            $vs_origem = parse_url($_SERVER[$vs_variavel], PHP_URL_HOST);

            if (!is_string($vs_origem) || $vs_origem === "")
                return false;

            $vs_origem_sem_porta = strtolower(explode(":", $vs_origem)[0]);

            if (!hash_equals($vs_host_sem_porta, $vs_origem_sem_porta))
                return false;
        }

        return true;
    }

    public static function validar_csrf(bool $pb_exigir_post = true): void
    {
        if ($pb_exigir_post && ($_SERVER["REQUEST_METHOD"] ?? "") !== "POST")
        {
            self::responder_falha_csrf(405, "Este formulário precisa ser enviado pelo método POST.");
        }

        if (!self::origem_permitida())
        {
            self::responder_falha_csrf(403, "A origem da requisição não é permitida.");
        }

        $vs_token = $_POST["csrf_token"] ?? ($_SERVER["HTTP_X_CSRF_TOKEN"] ?? "");
        $vs_esperado = $_SESSION["csrf_token"] ?? "";

        if (!is_string($vs_token)
            || !is_string($vs_esperado)
            || $vs_esperado === ""
            || !hash_equals($vs_esperado, $vs_token))
        {
            utils::log(
                "Requisição rejeitada por ausência de token CSRF.",
                utils::sanitizar_requisicao_para_log()
                    . " | metodo=" . ($_SERVER["REQUEST_METHOD"] ?? "")
                    . " | token_enviado=" . self::descrever_token($vs_token)
                    . " | token_na_sessao=" . (is_string($vs_esperado) && $vs_esperado !== "" ? "presente" : "ausente")
                    . " | cabecalho_x_csrf=" . (isset($_SERVER["HTTP_X_CSRF_TOKEN"]) ? "presente" : "ausente")
            );

            self::responder_falha_csrf(403, "O formulário expirou ou não pôde ser confirmado. Recarregue a página e tente novamente.");
        }
    }

    /**
     * Descreve o token recebido sem registrá-lo em log.
     */
    private static function descrever_token($ps_token): string
    {
        if (!is_string($ps_token) || $ps_token === "")
            return "ausente";

        return "presente(" . strlen($ps_token) . " bytes)";
    }

    /**
     * Falha de CSRF nunca pode terminar em resposta vazia: o navegador do
     * usuário mostraria uma tela em branco sem explicação.
     */
    private static function responder_falha_csrf(int $pn_status, string $ps_mensagem): void
    {
        if (!headers_sent())
        {
            http_response_code($pn_status);
            header("Content-Type: text/html; charset=UTF-8");
        }

        $vb_ajax = (strtolower((string) ($_SERVER["HTTP_X_REQUESTED_WITH"] ?? "")) === "xmlhttprequest")
            || (stripos((string) ($_SERVER["HTTP_ACCEPT"] ?? ""), "application/json") !== false);

        if ($vb_ajax)
        {
            echo $ps_mensagem;
            exit();
        }

        $vs_destino = self::recover_redirect_url("index.php");

        echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8">'
            . '<title>Sessão expirada</title></head><body>'
            . '<h1>Sessão expirada</h1>'
            . '<p>' . htmlspecialchars($ps_mensagem, ENT_QUOTES, "UTF-8") . '</p>'
            . '<p><a href="' . htmlspecialchars($vs_destino, ENT_QUOTES, "UTF-8") . '">Voltar para a página inicial</a></p>'
            . '</body></html>';

        exit();
    }

    public static function tarefa_id_valido($ps_tarefa): bool
    {
        if (!is_string($ps_tarefa) || strlen($ps_tarefa) < 1 || strlen($ps_tarefa) > 64)
            return false;

        if (strpos($ps_tarefa, "..") !== false || $ps_tarefa[0] === ".")
            return false;

        return preg_match('/\A[A-Za-z0-9][A-Za-z0-9._-]*\z/', $ps_tarefa) === 1;
    }

    public static function registrar_tarefa_progresso(string $ps_tarefa): void
    {
        if (!self::tarefa_id_valido($ps_tarefa) || session_status() !== PHP_SESSION_ACTIVE)
            return;

        $vn_usuario_codigo = (int) ($_SESSION["usuario_logado_codigo"] ?? 0);

        if ($vn_usuario_codigo > 0)
            $_SESSION["tarefas_progresso"][$ps_tarefa] = $vn_usuario_codigo;
    }

    public static function autorizar_tarefa_progresso($ps_tarefa): bool
    {
        if (!self::tarefa_id_valido($ps_tarefa) || session_status() !== PHP_SESSION_ACTIVE)
            return false;

        $vn_usuario_codigo = (int) ($_SESSION["usuario_logado_codigo"] ?? 0);

        if ($vn_usuario_codigo <= 0)
            return false;

        $va_tarefas = $_SESSION["tarefas_progresso"] ?? [];

        return is_array($va_tarefas)
            && ((int) ($va_tarefas[$ps_tarefa] ?? 0) === $vn_usuario_codigo);
    }

    /**
     * Arquivos em app/media/temp só podem ser servidos para o dono da tarefa
     * que os gerou (nome exato ou partes prefixadas pelo nome-base da tarefa).
     */
    public static function autorizar_arquivo_temporario($ps_nome): bool
    {
        if (!self::tarefa_id_valido($ps_nome) || session_status() !== PHP_SESSION_ACTIVE)
            return false;

        $vn_usuario_codigo = (int) ($_SESSION["usuario_logado_codigo"] ?? 0);

        if ($vn_usuario_codigo <= 0)
            return false;

        $va_tarefas = $_SESSION["tarefas_progresso"] ?? [];

        if (!is_array($va_tarefas))
            return false;

        foreach ($va_tarefas as $vs_tarefa => $vn_dono)
        {
            if ((int) $vn_dono !== $vn_usuario_codigo)
                continue;

            if ($ps_nome === $vs_tarefa
                || strpos($ps_nome, $vs_tarefa . ".") === 0
                || strpos($ps_nome, $vs_tarefa . "-parte-") === 0
                || strpos($ps_nome, $vs_tarefa . "-thumb-") === 0)
                return true;
        }

        return false;
    }

    public static function log_and_redirect_error($ps_summary, $stacktrace=null, $pb_append_http_variables=false): void
    {
        if (empty($stacktrace))
        {
            $stacktrace = var_export(debug_backtrace(), true);
        }

        if ($pb_append_http_variables)
        {

            unset(
                $_POST["usuario_senha"],
                $_POST["repetir_senha"],
                $_POST["usuario_senha_provisoria"],
                $_POST["new_password"],
                $_POST["usuario_token"],
                $_POST["token"],
                $_POST["csrf_token"],
                $_POST["signature"]
            );

            $stacktrace .= " - \$_GET: " . var_export($_GET, true);
            $stacktrace .= " - \$_POST: " . var_export($_POST, true);
        }

        $vs_codigo = utils::log($ps_summary, $stacktrace);
        session::redirect("erro.php?codigo=" . $vs_codigo);
    }


    /*
     *  $ps_script - precisa ser um script dentro da pasta app
     */
    public static function redirect($ps_script="index.php", $vb_absolute=false): void
    {

        $vs_redirect_url = "";

        if ($vb_absolute)
        {
            $vs_redirect_url = self::caminho_de_pagina_valido($ps_script);
        }
        elseif (!empty($_SESSION["redirect_url"]))
        {
            $vs_redirect_url = $_SESSION["redirect_url"] . $ps_script;
        }
        else
        {
            $vs_redirect_url = self::recover_redirect_url($ps_script);
        }

        // Nunca envie um cabeçalho Location vazio: isso deixa a tela em branco.
        if ($vs_redirect_url === "")
            $vs_redirect_url = "index.php";

        if (!headers_sent())
        {
            header("Location: " . $vs_redirect_url);
        }
        else
        {
            echo '<script>window.location.href = "' . $vs_redirect_url . '";</script>';
        }

        exit();
    }

    public static function refresh_session_cookie(): void
    {
        $vs_session_name = session::generate_cookie_name();

        $options = [
            'path' => '/',
            'expires' => time() + self::DURACAO_SESSAO_SEGUNDOS,
            'secure' => ($_SERVER["HTTPS"] ?? false),
            'httponly' => true,
            'samesite' => 'lax',
        ];

        setcookie($vs_session_name, session_id(), $options);
    }

    public static function set_token_cookie($ps_token): void
    {
        $vs_session_name = session::generate_cookie_name(true);

        $options = [
            'path' => '/',
            'expires' => time() + 60 * 60 * 24,
            'secure' => ($_SERVER["HTTPS"] ?? false),
            'httponly' => true,
            'samesite' => 'strict',
        ];

        setcookie($vs_session_name, $ps_token, $options);
    }

    public static function login($ps_usuario_login, $ps_usuario_senha): bool
    {

        $vs_redirect_pagina = "";
        $va_usuario = session::get_user(["usuario_login" => $ps_usuario_login, "usuario_ativo" => 1]);

        if (!empty($va_usuario))
        {
            $vb_validou_senha = false;

            $vn_usuario_codigo = $va_usuario["usuario_codigo"];
            $vs_hash_senha = $va_usuario["usuario_senha"];
            $vs_senha_provisoria = $va_usuario["usuario_senha_provisoria"] ?? null;
            $vd_expiracao_senha_provisoria = $va_usuario["usuario_data_expiracao_senha_provisoria"] ?? null;

            if (password_verify($ps_usuario_senha, $vs_hash_senha))
            {
                $vb_validou_senha = true;
                $vs_redirect_pagina = "index.php";
            }
            elseif (!empty($vs_senha_provisoria) && ($vd_expiracao_senha_provisoria > date("Y-m-d H:i:s")) )
            {
                $vb_validou_senha = password_verify($ps_usuario_senha, $vs_senha_provisoria);
                $vs_redirect_pagina = "editar_senha.php";
            }

            if ($vb_validou_senha)
            {
                $vs_token = session::generate_token();

                $va_parametros = [
                    "usuario_token" => $vs_token,
                    "usuario_ultimo_login" => date("Y-m-d H:i:s"),
                    "usuario_codigo" => $vn_usuario_codigo,
                    "usuario_senha_provisoria" => '',
                ];

                $vo_usuario = new usuario;
                $vo_usuario->salvar($va_parametros, false);

                // Evita fixação de sessão no momento da autenticação.
                if (session_status() === PHP_SESSION_ACTIVE)
                    session_regenerate_id(true);

                $_SESSION["usuario_token"] = $vs_token;
                $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                session::set_token_cookie($vs_token);

                if (isset($_POST["redirect"]) && $_POST["redirect"] != "")
                {
                    session::redirect($_POST["redirect"], true);
                }
                else
                {
                    session::redirect($vs_redirect_pagina);
                }
            }
        }

        return false;
    }

    /**
     * Aceita apenas o caminho de uma página real da aplicação. Endpoints de
     * functions/, destinos externos e arquivos inexistentes retornam vazio.
     */
    public static function caminho_de_pagina_valido($ps_url): string
    {
        if (!is_string($ps_url) || $ps_url === "" || strpos($ps_url, "\0") !== false)
            return "";

        $va_url = parse_url($ps_url);

        if (!is_array($va_url) || empty($va_url["path"]))
            return "";

        if (isset($va_url["host"]))
        {
            $vs_host_atual = strtolower(explode(":", (string) ($_SERVER["HTTP_HOST"] ?? ""))[0]);

            if ($vs_host_atual === "" || strtolower($va_url["host"]) !== $vs_host_atual)
                return "";
        }

        $vs_path = $va_url["path"];

        if (strpos($vs_path, "..") !== false)
            return "";

        $vs_script = basename($vs_path);

        if (preg_match('/\A[A-Za-z0-9_-]+\.php\z/', $vs_script) !== 1)
            return "";

        $vs_base = realpath(config::get(["pasta_app"]));
        $vs_real = realpath($vs_base . DIRECTORY_SEPARATOR . $vs_script);

        if ($vs_base === false || $vs_real === false || dirname($vs_real) !== $vs_base)
            return "";

        return $vs_path . (isset($va_url["query"]) ? "?" . $va_url["query"] : "");
    }

    public static function revogar_token_atual(): void
    {
        $vs_token = $_SESSION["usuario_token"] ?? null;
        $vn_usuario_codigo = (int) ($_SESSION["usuario_logado_codigo"] ?? 0);

        if (!empty($vs_token) && $vn_usuario_codigo > 0)
        {
            $vo_usuario = new usuario;
            $vo_usuario->salvar([
                "usuario_codigo" => $vn_usuario_codigo,
                "usuario_token" => "",
            ], false);
        }

        unset($_SESSION["usuario_token"]);
    }

    /**
     * Mantém a sessão atual após a troca de senha: rotaciona o token
     * persistido (invalidando os demais dispositivos) e o id da sessão.
     */
    public static function renovar_token_sessao(): bool
    {
        $vn_usuario_codigo = (int) ($_SESSION["usuario_logado_codigo"] ?? 0);

        if ($vn_usuario_codigo <= 0)
            return false;

        $vs_token = self::generate_token();

        $vo_usuario = new usuario;
        $vo_usuario->salvar([
            "usuario_codigo" => $vn_usuario_codigo,
            "usuario_token" => $vs_token,
        ], false);

        if (session_status() === PHP_SESSION_ACTIVE)
            session_regenerate_id(true);

        $_SESSION["usuario_token"] = $vs_token;
        self::set_token_cookie($vs_token);

        return true;
    }

    public static function logout($vb_redirect=true): void
    {
        session::revogar_token_atual();

        session_unset();
        session_destroy();
        setcookie(session_name(), "", time() - 3600, "/");
        setcookie(session::generate_cookie_name(true), "", time() - 3600, "/");

        $vs_destino = self::caminho_de_pagina_valido($_SERVER["REQUEST_URI"] ?? "");
        $vs_login = "login.php" . (($vb_redirect && $vs_destino !== "") ? "?redirect=" . urlencode($vs_destino) : "");
        session::redirect($vs_login);
        exit();
    }

    /*
     *  Pode ser chamada num script na pasta app para definir a página de redirecionamento.
     *  Útil para quando $_SERVER não está disponível
     */
    public static function set_redirect_url()
    {
        if (isset($_SERVER["REQUEST_URI"]))
        {
            $vs_sheme = $_SERVER["REQUEST_SCHEME"] ?? "https";
            $vs_base_url = $vs_sheme . "://" . $_SERVER["HTTP_HOST"];
            $va_redirect = explode("/", $_SERVER["REQUEST_URI"]);
            array_pop($va_redirect);

            $_SESSION["redirect_url"] = $vs_base_url . implode("/", $va_redirect) . "/";
        }
    }

    public static function recover_redirect_url($ps_script): string
    {
        $vs_scheme = $_SERVER["REQUEST_SCHEME"] ?? "https";
        $vs_host = $_SERVER["HTTP_HOST"] ?? "";
        $vs_request_uri = $_SERVER["REQUEST_URI"] ?? "";

        if (empty($vs_host) || empty($vs_request_uri))
        {
            return $ps_script;
        }

        $vs_redirect_url = $vs_scheme . "://" . $vs_host . "/";

        $va_path = explode("/", $vs_request_uri);
        $va_redirect_path = [];

        foreach ($va_path as $vn_key => $vs_path)
        {
            if (empty($vs_path))
            {
                continue;
            }

            if ($vs_path == "functions")
            {
                if (isset($va_path[$vn_key + 1]) && strpos($va_path[$vn_key + 1], ".php") !== false)
                {
                    $va_redirect_path[] = $ps_script;
                    break;
                }
            }

            if (strpos($vs_path, ".php") !== false)
            {
                $va_redirect_path[] = $ps_script;
                break;
            }
            else
            {
                $va_redirect_path[] = $vs_path;
            }

            if ($vs_path == "app")
            {
                $va_redirect_path[] = $ps_script;
                break;
            }
        }

        $vs_redirect_url .= implode("/", $va_redirect_path);

        return $vs_redirect_url;
    }

    public static function validate_data(array &$pa_data, array $pa_rules, array|null $pa_messages = null)
    {
        // REGRAS DE VALIDAÇÃO DISPONÍVEIS:
        $validation_rules = [
            "string" => function (&$value)
            {
                if (!filter_var($value, FILTER_FLAG_EMPTY_STRING_NULL))
                {
                    return false;
                }
                $value = filter_var(trim(strip_tags($value)), FILTER_DEFAULT);
                return true;
            },
            "integer" => function (&$value)
            {
                if (is_array($value))
                {
                    $value = filter_var_array($value, FILTER_SANITIZE_NUMBER_INT);
                    return filter_var_array($value, FILTER_VALIDATE_INT) !== false;
                }

                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                return filter_var($value, FILTER_VALIDATE_INT) !== false;
            },
            "array" => function(&$value)
            {
                return filter_var($value, FILTER_DEFAULT, ["flags" => FILTER_REQUIRE_ARRAY]) !== false;
            },
            "email" => function(&$value)
            {
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            },
            "required" => function (&$value)
            {
                return !empty($value);
            },
            "max" => function(&$value, $max_value = 255)
            {
                if (!$value)
                {
                    return true;
                }

                $length = is_array($value) ? count($value) : strlen($value);
                return $length <= $max_value;
            },
            "min" => function(&$value, $min_value = 5)
            {
                if (!$value)
                {
                    return true;
                }
                $length = is_array($value) ? count($value) : strlen($value);
                return $length >= $min_value;
            }
        ];

        // MENSAGENS DE ERRO PADRÃO PARA CADA REGRA DE VALIDAÇÃO
        $error_messages = [
            "string" => "O campo deve conter apenas texto válido",
            "required" => "O campo é obrigatório",
            "email" => "O campo deve conter um endereço e-mail válido",
            "array" => "O campo deve conter um conjunto válido de elementos",
            "integer" => "O campo deve conter um valor numérico inteiro válido",
            "max" => fn($value, $max = null) =>
                is_array($value)
                  ? "O campo não pode conter mais de $max itens"
                  : "O campo não pode conter mais de $max caracteres",
            "min" => fn($value, $min = null) =>
                is_array($value)
                    ? "O campo deve conter no mínimo $min itens"
                    : "O campo deve conter no mínimo $min caracteres"
        ];

        $validation_rule_names = array_keys($validation_rules);
        $collected_errors = [];

        foreach ($pa_rules as $field_name => $field_rules)
        {
            $field_value = $pa_data[$field_name] ?? null;
            $field_errors = [];

            // Se tem somente uma regra: "nome" => "required"
            if (is_string($field_rules))
            {
                $rule_name = $field_rules;

                if (isset($validation_rules[$rule_name]) && !$validation_rules[$rule_name]($field_value))
                {
                    $error_message = $pa_messages[$field_name][$rule_name] ?? null;

                    if (empty($error_message))
                    {
                        $default_message = $error_messages[$rule_name];
                        $error_message = is_callable($default_message) ? $default_message($field_value, null) : $default_message;
                    }

                    $field_errors[] = $error_message;
                }
            }
            // Se tem múltiplas regras: "nome" => ["required", "max" => 100]
            elseif (is_array($field_rules))
            {
                foreach ($field_rules as $rule_key => $rule_value)
                {
                    $rule_name = $rule_value;
                    $rule_parameter = null;

                    // se parametrizado: "max" => 100
                    if (is_string($rule_key) && in_array($rule_key, $validation_rule_names))
                    {
                        $rule_name = $rule_key;
                        $rule_parameter = $rule_value;
                    }

                    if (!in_array($rule_name, $validation_rule_names))
                    {
                        continue;
                    }

                    if (!$validation_rules[$rule_name]($field_value, $rule_parameter))
                    {
                        $error_message = $pa_messages[$field_name][$rule_name] ?? null;

                        if (empty($error_message))
                        {
                            $default_message = $error_messages[$rule_name];
                            $error_message = is_callable($default_message) ? $default_message($field_value, $rule_parameter) : $default_message;
                        }

                        $field_errors[] = $error_message;
                    }
                }
            }

            if (!empty($field_errors))
            {
                $collected_errors[$field_name] = $field_errors;
            }

            // Salva o valor sanitizado
            $pa_data[$field_name] = $field_value;
        }

        if (!empty($collected_errors))
        {
            return ["error" => true, "errors" => $collected_errors];
        }

        return null;
    }

    public static function send_response(
      mixed $p_response = null,
      int $pi_http_code = 200,
      string $ps_header_content_type = "application/json"
    ): void
    {
        http_response_code($pi_http_code);

        if ($ps_header_content_type)
        {
            header("Content-Type: $ps_header_content_type");

            if (str_contains($ps_header_content_type, "json"))
            {
                $p_response = json_encode($p_response);
            }
        }

        if (!empty($p_response) && gettype($p_response) == "string")
        {
            echo $p_response;
        }

        exit();
    }

    public static function get_body(): array
    {
        $body = (string) file_get_contents("php://input");

        # O formato do corpo decide como ler, não a presença de "=": um JSON com
        # URL que tem query string ("...?id=x") era interpretado como formulário,
        # e aí a assinatura não casava mais.
        $vs_inicio = ltrim($body);

        if ($vs_inicio !== "" && ($vs_inicio[0] === "{" || $vs_inicio[0] === "["))
        {
            $va_json = json_decode($vs_inicio, true);

            if (is_array($va_json))
                return $va_json;
        }

        if (str_contains($body, "="))
        {
            parse_str($body, $output);
            return $output;
        }

        if (str_contains($body, ":"))
        {
            $va_json = json_decode($body, true);

            if (is_array($va_json))
                return $va_json;
        }

        if (!empty($_POST))
            return $_POST;

        return [];
    }
}