<?php

class setor_sistema extends objeto_base
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = $this->inicializar_tabela_banco();
        $this->chave_primaria = $this->inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = $this->inicializar_relacionamentos();

        $this->inicializar_visualizacoes();
    }

    public function inicializar_tabela_banco()
    {
        return "setor_sistema";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['setor_sistema_codigo'] = [
            'setor_sistema_codigo',
            'coluna_tabela' => 'codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['setor_sistema_nome'] = [
            'setor_sistema_nome',
            'coluna_tabela' => 'nome',
            'tipo_dado' => 's'
        ];

        $va_atributos['setor_sistema_recurso_sistema_padrao_codigo'] = [
            'setor_sistema_recurso_sistema_padrao_codigo',
            'coluna_tabela' => 'recurso_sistema_padrao_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'recurso_sistema'
        ];

        if (class_exists('setor_sistema_business'))
        {
            $vo_setor_sistema_business = new setor_sistema_business();
            $va_atributos = array_merge_recursive($va_atributos, $vo_setor_sistema_business->inicializar_atributos() ?? []);
        }

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['setor_sistema_recurso_sistema_codigo'] = [
            [
                'setor_sistema_recurso_sistema_codigo'
            ],
            'tabela_intermediaria' => 'setor_sistema_recurso_sistema',
            'chave_exportada' => 'setor_sistema_codigo',
            'campos_relacionamento' => [
                'setor_sistema_recurso_sistema_codigo' => 'recurso_sistema_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'recurso_sistema',
            'objeto' => 'recurso_sistema',
            'alias' => 'recursos de sistema'
        ];

        $va_relacionamentos['setor_sistema_usuario_codigo'] = [
            [
                'setor_sistema_usuario_codigo'
            ],
            'tabela_intermediaria' => 'usuario_setor_sistema',
            'chave_exportada' => 'setor_sistema_codigo',
            'campos_relacionamento' => [
                'setor_sistema_usuario_codigo' => 'usuario_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'usuario',
            'objeto' => 'usuario',
            'alias' => 'usuários'
        ];

        $va_relacionamentos['setor_sistema_acervo_codigo'] = [
            ['setor_sistema_acervo_codigo'],
            'tabela_intermediaria' => 'acervo',
            'chave_exportada' => 'setor_sistema_codigo',
            'campos_relacionamento' => [
                'setor_sistema_acervo_codigo' => [
                    ['codigo'],
                    "atributo" => "acervo_codigo"
                ]
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'acervo',
            'objeto' => 'acervo',
            'tipo' => '1n',
            'alias' => "acervos"
        ];

        if (class_exists('setor_sistema_business')) {
            $vo_setor_sistema_business = new setor_sistema_business();
            $va_relacionamentos = array_merge_recursive($va_relacionamentos, $vo_setor_sistema_business->inicializar_relacionamentos() ?? []);
        }

        return $va_relacionamentos;
    }

    public function inicializar_campos_edicao()
    {
        $va_campos_edicao = array();

        $va_campos_edicao["setor_sistema_nome"] = ["html_text_input", "nome" => "setor_sistema_nome", "label" => "Nome", "setor_sistema", "foco" => true];

        // "recurso_sistema_item_acervo" é um filtro escondido para listar no campo abaixo
        // somente os recursos de sistema que estão marcados como itens de acervo

        $va_campos_edicao["recurso_sistema_item_acervo"] = [
            "html_text_input",
            "nome" => "recurso_sistema_item_acervo",
            "label" => "Texto",
            "nao_exibir" => true,
            "valor_padrao" => 1
        ];

        $va_campos_edicao["setor_sistema_recurso_sistema_codigo"] = [
            "html_combo_input",
            "nome" => "setor_sistema_recurso_sistema_codigo",
            "label" => "Itens catalogáveis",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_plural"],
            "formato" => "multi_selecao"
        ];

        $va_campos_edicao["setor_sistema_recurso_sistema_padrao_codigo"] = [
            "html_combo_input",
            "nome" => "setor_sistema_recurso_sistema_padrao_codigo",
            "label" => "Item de acervo preferencial",
            "objeto" => "recurso_sistema",
            "atributos" => ["recurso_sistema_codigo", "recurso_sistema_nome_plural"],
            "atributo" => "recurso_sistema_codigo",
            "sem_valor" => false,
            "filtro" => [
                [
                    "atributo" => "recurso_sistema_item_acervo",
                    "valor" => 1
                ]
            ]
        ];

        if (class_exists('setor_sistema_business'))
        {
            $vo_setor_sistema_business = new setor_sistema_business();
            $va_campos_edicao = array_merge_recursive($va_campos_edicao, $vo_setor_sistema_business->inicializar_campos_edicao());
        }

        return $va_campos_edicao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao["setor_sistema_codigo"] = ["nome" => "setor_sistema_codigo", "exibir" => false];
        $va_campos_visualizacao["setor_sistema_nome"] = ["nome" => "setor_sistema_nome"];

        $va_campos_visualizacao["setor_sistema_recurso_sistema_padrao_codigo"] = ["nome" => "setor_sistema_recurso_sistema_padrao_codigo"];

        $va_campos_visualizacao_lista = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("lista"));

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["lista"]["order_by"] = ["setor_sistema_nome" => "Nome"];

        $va_campos_visualizacao["setor_sistema_recurso_sistema_codigo"] = ["nome" => "setor_sistema_recurso_sistema_codigo",
            "formato" => ["campo" => "recurso_sistema_nome_plural"]
        ];

        $this->visualizacoes["navegacao"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));
        $this->visualizacoes["navegacao"]["order_by"] = ["setor_sistema_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "setor_sistema_nome" => ["label" => "Título", "main_field" => true],
        ];

        $this->visualizacoes["ficha"]["campos"] = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "setor_sistema_nome" => ["label" => "Título", "main_field" => true],
        ];

        $this->visualizacoes["publica"]["campos"] = $va_campos_visualizacao_lista;
        $this->visualizacoes["publica"]["order_by"] = ["setor_sistema_nome" => ""];
        $this->visualizacoes["publica"]["ordem_campos"] = [
            "setor_sistema_nome" => ["label" => "Nome", "main_field" => true],
        ];

        if (class_exists('setor_sistema_business'))
        {
            $vo_setor_sistema_business = new setor_sistema_business();
            $this->visualizacoes = array_merge_recursive($this->visualizacoes, $vo_setor_sistema_business->inicializar_visualizacoes() ?? []);
        }

        return $this->visualizacoes;
    }

    public function salvar($pa_valores, $pb_logar_operacao = true, $pn_idioma_codigo = 1, $pb_salvar_objeto_pai = true, $ps_id_objeto_filho = '', $pb_sobrescrever = true)
    {
        if (class_exists('setor_sistema_business'))
        {
            $vo_setor_sistema_business = new setor_sistema_business();
            $vo_setor_sistema_business->salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo, $pb_salvar_objeto_pai, $ps_id_objeto_filho, $pb_sobrescrever);
        }

        return parent::salvar($pa_valores, $pb_logar_operacao, $pn_idioma_codigo, $pb_salvar_objeto_pai, $ps_id_objeto_filho, $pb_sobrescrever);
    }

}

?>