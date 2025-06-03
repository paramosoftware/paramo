<?php

class usuario_base extends objeto_base
{

function __construct() 
{
    $this->tabela_banco = "usuario";
    $this->chave_primaria = $this->inicializar_chave_primaria();

    $this->atributos = $this->inicializar_atributos();
    $this->relacionamentos = $this->inicializar_relacionamentos();

    $this->inicializar_visualizacoes();

    $this->registros_filhos["selecao"] = [
        "atributo_relacionamento" => "selecao_usuario_codigo",
        "pode_excluir_pai" => true
    ];

    $this->registros_filhos["oauth"] = [
        "atributo_relacionamento" => "oauth_usuario_codigo",
        "pode_excluir_pai" => true
    ];
}

public function inicializar_chave_primaria()
{
    return [
        'usuario_codigo', 
        'coluna_tabela' => 'Codigo', 
        'tipo_dado' => 'i'
    ];
}

public function inicializar_atributos()
{
    $va_atributos = array();

    $va_atributos['usuario_instituicao_codigo'] = [
        'usuario_instituicao_codigo', 
        'coluna_tabela' => 'instituicao_codigo', 
        'tipo_dado' => 'i',
        'objeto' => "instituicao"
    ];
    
    $va_atributos['usuario_setor_sistema_codigo'] = [
        'usuario_setor_sistema_codigo', 
        'coluna_tabela' => 'setor_sistema_codigo', 
        'tipo_dado' => 'i', 
        'objeto' => "setor_sistema"
    ];

    $va_atributos['usuario_tipo_codigo'] = [
        'usuario_tipo_codigo', 
        'coluna_tabela' => 'tipo_codigo', 
        'tipo_dado' => 'i', 
        'objeto' => 'tipo_usuario'
    ];
    
    $va_atributos['usuario_nome'] = [
        'usuario_nome', 
        'coluna_tabela' => 'nome', 
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_login'] = [
        'usuario_login', 
        'coluna_tabela' => 'login', 
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_senha'] = [
        'usuario_senha', 
        'coluna_tabela' => 'senha', 
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_email'] = [
        'usuario_email',
        'coluna_tabela' => 'email',
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_telefone'] = [
         'usuario_telefone',
         'coluna_tabela' => 'telefone',
         'tipo_dado' => 's'
     ];

    $va_atributos['usuario_token'] = [
        'usuario_token',
        'coluna_tabela' => 'token',
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_ultimo_login'] = [
        'usuario_ultimo_login',
        'coluna_tabela' => 'ultimo_login',
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_senha_provisoria'] = [
        'usuario_senha_provisoria',
        'coluna_tabela' => 'senha_provisoria',
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_data_expiracao_senha_provisoria'] = [
        'usuario_data_expiracao_senha_provisoria',
        'coluna_tabela' => 'expiracao_senha_provisoria',
        'tipo_dado' => 's'
    ];

    $va_atributos['usuario_ativo'] = [
        'usuario_ativo',
        'coluna_tabela' => 'ativo',
        'tipo_dado' => 'b'
    ];

    return $va_atributos;
}

public function inicializar_relacionamentos($pn_recurso_sistema_codigo=null)
{
    $va_relacionamentos = array();

    $va_relacionamentos['usuario_grupo_usuario_codigo'] = [
        'usuario_grupo_usuario_codigo',
        'tabela_intermediaria' => 'usuario_grupo_usuario',
        'chave_exportada' => 'usuario_codigo',
        'campos_relacionamento' => ['usuario_grupo_usuario_codigo' => 'grupo_usuario_codigo'],
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'grupo_usuario',
        'objeto' => 'grupo_usuario',
        'alias' => 'grupos de usuários'
    ];

    $va_relacionamentos['usuario_selecao_codigo'] = [ 
        ['usuario_selecao_codigo'], 
        'tabela_intermediaria' => 'selecao', 
        'chave_exportada' => 'usuario_codigo', 
        'campos_relacionamento' => [
            'usuario_selecao_codigo' => [
                ['codigo'], 
                "atributo" => "selecao_codigo"
            ]
        ], 
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'selecao', 
        'objeto' => 'selecao',
        'tipo' => '1n',
        'alias' => "seleções"
    ];

    $va_relacionamentos['usuario_oauth_codigo'] = [
        ['usuario_oauth_codigo'],
        'tabela_intermediaria' => 'oauth',
        'chave_exportada' => 'usuario_codigo',
        'campos_relacionamento' => [
            'usuario_oauth_codigo' => [
                ['codigo'],
                "atributo" => "oauth_codigo"
            ]
        ],
        'tipos_campos_relacionamento' => ['i'],
        'tabela_relacionamento' => 'oauth',
        'objeto' => 'oauth',
        'tipo' => '1n',
        'alias' => "oauth"
    ];


    $va_relacionamentos['usuario_setor_sistema_codigo'] = [
        'usuario_setor_sistema_codigo', 
        'tabela_intermediaria' => 'usuario_setor_sistema', 
        'chave_exportada' => 'usuario_codigo', 
        'campos_relacionamento' => ['usuario_setor_sistema_codigo' => 'setor_sistema_codigo'], 
        'tipos_campos_relacionamento' => ['i'], 
        'tabela_relacionamento' => 'setor_sistema', 
        'objeto' => 'setor_sistema',
        'alias' => 'setores do sistema'
    ];

    $va_relacionamentos['usuario_selecao_compartilhada_codigo'] = [
        'usuario_selecao_compartilhada_codigo', 
        'tabela_intermediaria' => 'selecao_usuario', 
        'chave_exportada' => 'usuario_codigo', 
        'campos_relacionamento' => ['usuario_selecao_compartilhada_codigo' => 'selecao_codigo'], 
        'tipos_campos_relacionamento' => ['i'], 
        'tabela_relacionamento' => 'selecao', 
        'objeto' => 'selecao',
        'alias' => 'seleções compartilhadas'
    ];

    return $va_relacionamentos;
}

public function inicializar_visualizacoes()
{
    $this->visualizacoes["senha"]["campos"]["usuario_codigo"] = ["nome" => "usuario_codigo"];
    $this->visualizacoes["senha"]["campos"]["usuario_senha"] = ["nome" => "usuario_senha"];
    $this->visualizacoes["senha"]["campos"]["usuario_token"] = ["nome" => "usuario_token"];
    $this->visualizacoes["senha"]["campos"]["usuario_ultimo_login"] = ["nome" => "usuario_ultimo_login"];
    $this->visualizacoes["senha"]["campos"]["usuario_senha_provisoria"] = ["nome" => "usuario_senha_provisoria"];
    $this->visualizacoes["senha"]["campos"]["usuario_data_expiracao_senha_provisoria"] = ["nome" => "usuario_data_expiracao_senha_provisoria"];
}

public function salvar($pa_valores, $pb_logar_operacao = true, $pn_idioma_codigo = 1, $pb_salvar_objeto_pai = true, $ps_id_objeto_filho = '', $pb_sobrescrever = true)
{
    if (isset($pa_valores["usuario_alterar_senha"]) && ($pa_valores["usuario_alterar_senha"]))
        $pa_valores["usuario_senha"] = password_hash($pa_valores["usuario_senha"], PASSWORD_DEFAULT);

    if (!isset($pa_valores["usuario_ativo"]))
        $pa_valores["usuario_ativo"] = 1;

    return parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo, true, "", $pb_sobrescrever);
}

public function ler_numero_registros($pa_filtros_busca = null, $pa_log_info = null, $pb_retornar_ramos_inferiores = true)
{
    if (isset($pa_filtros_busca["instituicao_codigo"]))
        $pa_filtros_busca["usuario_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

    return parent::ler_numero_registros($pa_filtros_busca, $pa_log_info);
}

public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
{
    if (isset($pa_filtros_busca["instituicao_codigo"]))
        $pa_filtros_busca["usuario_instituicao_codigo"] = $pa_filtros_busca["instituicao_codigo"];

    return parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info, $pn_idioma_codigo);
}

public function recuperar_senha($pn_usuario_codigo, $ps_usuario_email, $ps_usuario_nome)
{
    $vs_nova_senha = substr(md5(uniqid(rand(), true)), 0, 12);

    $vd_data_expiracao_nova_senha = date_create();
    date_add($vd_data_expiracao_nova_senha, date_interval_create_from_date_string("5 minutes"));

    $va_usuario["usuario_codigo"] = $pn_usuario_codigo;
    $va_usuario["usuario_logado_codigo"] = $pn_usuario_codigo;
    $va_usuario["usuario_senha_provisoria"] = password_hash($vs_nova_senha, PASSWORD_DEFAULT);
    $va_usuario["usuario_data_expiracao_senha_provisoria"] = date_format($vd_data_expiracao_nova_senha, 'Y-m-d H:i:s');

    $this->salvar($va_usuario, false);

    $vs_message = "<p>Olá, " . $ps_usuario_nome . "</p>";

    $vs_message .= "<p>Uma solicitação para redefinir sua senha de acesso ao sistema Páramo foi recebida.</p>";
    $vs_message .= "<p>Para acessar o sistema, utilize a senha provisória abaixo:</p>";
    $vs_message .= "<p><b>" . $vs_nova_senha . "</b></p>";

    $vs_message .= "<br>";

    $vs_message .= "<p><b>IMPORTANTE:</b> Esta senha é válida por 5 minutos. Ao acessar o sistema, você deverá alterar sua senha.</p>";

    return utils::send_email(
        $ps_usuario_email,
        $ps_usuario_nome,
        "[Páramo] Recuperar senha",
        $vs_message
    );

}


}

?>