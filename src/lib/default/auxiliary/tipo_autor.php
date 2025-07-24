<?php

class tipo_autor extends objeto_base
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
        return "tipo_autor";
    }

    public function inicializar_chave_primaria()
    {
        return $va_chave_primaria['tipo_autor_codigo'] = [
            'tipo_autor_codigo',
            'coluna_tabela' => 'Codigo',
            'tipo_dado' => 'i'
        ];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos['tipo_autor_nome'] = [
            'tipo_autor_nome',
            'coluna_tabela' => 'Nome',
            'tipo_dado' => 's'
        ];

        return $va_atributos;
    }

    public function inicializar_relacionamentos($pn_recurso_sistema_codigo = null)
    {
        $va_relacionamentos = array();

        $va_relacionamentos['tipo_autor_item_acervo_entidade_codigo'] = [
            [
                'tipo_autor_item_acervo_entidade_codigo'
            ],
            'tabela_intermediaria' => 'item_acervo_entidade',
            'chave_exportada' => 'tipo_autor_codigo',
            'campos_relacionamento' => [
                'tipo_autor_item_acervo_entidade_codigo' => 'item_acervo_codigo',
            ],
            'tipos_campos_relacionamento' => ['i'],
            'tabela_relacionamento' => 'item_acervo',
            'objeto' => 'item_acervo',
            'alias' => 'itens do acervo',
            'impede_exclusao' => true
        ];

        return $va_relacionamentos;
    }
    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["tipo_autor_nome"] = [
            "html_text_input",
            "nome" => "tipo_autor_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE"
        ];

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();
        $va_campos_visualizacao["tipo_autor_codigo"] = ["nome" => "tipo_autor_codigo", "exibir" => false];
        $va_campos_visualizacao["tipo_autor_nome"] = ["nome" => "tipo_autor_nome"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["lista"]["order_by"] = ["tipo_autor_nome" => "Nome"];

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["tipo_autor_nome" => "Nome"];

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao;
    }


}

?>