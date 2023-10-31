<?php

class session
{
    public static function start_session(): void
    {
        $vs_session_name = session::generate_cookie_name();

        if (session_status() == PHP_SESSION_NONE)
        {
            session_name($vs_session_name);
            session_start(['gc_maxlifetime' => 60 * 60 * 12]);
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

        $va_usuario = session::get_user(["usuario_token" => $vs_token]);

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
        try
        {
            return bin2hex(random_bytes(32));
        }
        catch (Exception $e)
        {
            return md5(uniqid(rand(), true));
        }
    }

    public static function generate_cookie_name($pb_token=false): string
    {
        return
            strtolower(preg_replace("/[^A-Za-z0-9]/", "", config::get(["nome_instituicao"])))
            . ($pb_token ? "_token" : "");
    }

    public static function refresh_session_cookie(): void
    {
        $vs_session_name = session::generate_cookie_name();

        $options = [
            'path' => '/',
            'expires' => time() + 60 * 60,
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

    public static function login($ps_usuario_login, $ps_usuario_senha): string
    {

        $vs_redirect_pagina = "";
        $va_usuario = session::get_user(["usuario_login" => $ps_usuario_login]);

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

                $_SESSION["usuario_token"] = $vs_token;

                session::set_token_cookie($vs_token);

                return $vs_redirect_pagina;
            }
        }

        return "";
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), "", time() - 3600, "/");
        setcookie(session::generate_cookie_name(true), "", time() - 3600, "/");
        header("Location: login.php");
        exit();
    }

}