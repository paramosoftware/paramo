<?php

class google_drive
{
    public static function save_token(string $token, string $service, int $user_id): void
    {
        $vo_auth = new oauth();

        $va_oauth = $vo_auth->ler_lista([
            "oauth_usuario_codigo" => $user_id,
            "oauth_servico" => $service
        ]);

        if (count($va_oauth) > 0)
        {
            $vo_auth->atualizar([
                "oauth_codigo" => $va_oauth[0]["oauth_codigo"],
                "oauth_token" => $token,
                "oauth_servico" => $service,
                "oauth_usuario_codigo" => $user_id
            ]);
        } else
        {
            $vo_auth->salvar([
                "oauth_token" => $token,
                "oauth_servico" => $service,
                "oauth_usuario_codigo" => $user_id
            ], false);
        }
    }

    public static function refresh_token($client, string $service, $user_id): \Google\Client
    {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        $token = $client->getAccessToken();
        self::save_token(json_encode($token), $service, $user_id);
        $client->setAccessToken($token);
        return $client;
    }

    public static function get_auth_url($user_id, $service): string
    {
        $authUrl = "";
        $client = google_drive::get_client();
        $token = google_drive::get_token($user_id, $service);

        if (!empty($token))
        {
            $client->setAccessToken($token);
            if ($client->isAccessTokenExpired())
            {
                $client = google_drive::refresh_token($client, $service, $user_id);

                if ($client->isAccessTokenExpired())
                {
                    $authUrl = $client->createAuthUrl();
                }
            }
        } else
        {
            $authUrl = $client->createAuthUrl();
        }

        return $authUrl;
    }

    public static function get_token(int $user_id, string $service): string
    {
        $vo_auth = new oauth();
        $va_oauth = $vo_auth->ler_lista([
            "oauth_usuario_codigo" => $user_id,
            "oauth_servico" => $service
        ]);

        return $va_oauth[0]["oauth_token"] ?? "";
    }

    public static function get_client(): \Google\Client
    {
        require_once dirname(__FILE__) . '/../vendors/google-api-client/vendor/autoload.php';

        $client_id = config::get(["drive_client_id"]);
        $client_secret = config::get(["drive_client_secret"]);
        $redirect_uri = config::get(["drive_redirect_uri"]);
        $scopes = config::get(["drive_scopes"]);

        $client = new Google\Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope($scopes);
        $client->setPrompt('consent');
        $client->setAccessType('offline');

        return $client;
    }

    public static function get_service($client): \Google\Service\Drive
    {
        require_once dirname(__FILE__) . '/../vendors/google-api-client/vendor/autoload.php';
        return new Google\Service\Drive($client);
    }
}