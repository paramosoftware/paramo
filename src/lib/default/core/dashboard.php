<?php

class dashboard
{

    function __construct()
    {
    }

    public function get_objeto_item_acervo_nome()
    {
        return "item_acervo";
    }

    public function get_atributo_instituicao_objeto_item_acervo()
    {
        return "item_acervo_instituicao_codigo";
    }

    public function get_atributo_acervo_objeto_item_acervo(): string
    {
        return "item_acervo_acervo_codigo";
    }

    public function get_filtro_busca_geral(): string
    {
        return "item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_titulo,item_acervo_codigo_0_item_acervo_dados_textuais_0_item_acervo_descricao,item_acervo_codigo_0_item_acervo_entidade_codigo_0_entidade_nome";
    }

    public function get_parametros_card_acervo($pn_setor_sistema_codigo)
    {
        $va_cards_acervo = array();

        $va_cards_acervo[1] = 
        [
            'objeto_acervo' => 'conjunto_documental',
            'recurso_sistema_padrao' => 'documento',
            'atributos' => [
                'conjunto_documental_periodo',
                'conjunto_documental_localidade_codigo_0_localidade_nome',
                'acervo_instituicao_codigo_0_instituicao_nome'
            ],
            "link" => "listar.php?obj=documento&" . $this->get_objeto_item_acervo_nome() . "_codigo_0_" . $this->get_atributo_acervo_objeto_item_acervo() . "="
        ];

        $va_cards_acervo[2] = 
        [
            'objeto_acervo' => 'biblioteca',
            'recurso_sistema_padrao' => 'livro',
            'atributos' => [
                'acervo_instituicao_codigo_0_instituicao_nome'
            ],
            "link" => "listar.php?obj=livro&" . $this->get_objeto_item_acervo_nome() . "_codigo_0_" . $this->get_atributo_acervo_objeto_item_acervo() . "="
        ];

        return $va_cards_acervo[$pn_setor_sistema_codigo] ?? array();
    }


    public function get_regras_exibicao($pb_exibir_insituicoes=false): array
    {

        $va_regras_exibicao = array();

        $va_regras_exibicao[] = [
            1 => [
                "exibir" => $pb_exibir_insituicoes,
                "titulo" => "Instituicões",
                "descricao" => "Veja os cadastros de outras instituições clicando em um dos itens de acervo",
                "card" => "instituicao",
                "link" => "ficha.php?obj=instituicao&cod=",
                "regras" => [
                    "numero_itens" => [
                        "valores" => 1,
                        "operador" => ">=",
                    ]
                ]
            ],
            2 => [
                "exibir" => true,
                "titulo" => "Acervos",
                "descricao" => "Clique no nome de uma instituição acima para visualizar os acervos",
                "card" => "acervo",
                "link" => "ficha.php?obj=acervo&cod=",
                "regras" => [
                    "numero_itens" => [
                        "valores" => 1,
                        "operador" => ">=",
                    ]
                ]
            ]
        ];

        return $va_regras_exibicao;
    }

    public function get_colunas_resultado_busca($ps_recurso_sistema_id)
    {
        $va_colunas_resultado_busca = array();

        $va_default = [
            "Identificador" => "item_acervo_identificador",
            "Titulo" => "item_acervo_dados_textuais_0_item_acervo_titulo"
        ];

        $va_colunas_resultado_busca["entrevista"] = [
            "Identificador" => "item_acervo_identificador",
            "Nome" => "item_acervo_entidade_entrevistado_codigo_0_entidade_nome"
        ];

        return $va_colunas_resultado_busca[$ps_recurso_sistema_id] ?? $va_default;
    }
}

?>