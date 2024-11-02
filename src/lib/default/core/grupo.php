<?php

class grupo extends agrupamento
{

    function __construct()
    {
        $this->recurso_sistema_codigo = objeto_base::ler_recurso_sistema_codigo(get_class($this));

        $this->tabela_banco = parent::inicializar_tabela_banco();
        $this->chave_primaria = parent::inicializar_chave_primaria();

        $this->atributos = $this->inicializar_atributos();
        $this->relacionamentos = parent::inicializar_relacionamentos();

        $this->inicializar_visualizacoes();

        $this->hierarquico = true;
        $this->campo_hierarquico = "agrupamento_agrupamento_superior_codigo";

        $this->registros_filhos["subgrupo"] = [
            "atributo_relacionamento" => "agrupamento_agrupamento_superior_codigo",
            "pode_excluir_pai" => true
        ];

        $this->controlador_acesso = ["acervo_codigo" => "agrupamento_acervo_codigo"];
    }

    public function inicializar_atributos()
    {
        $va_atributos = array();

        $va_atributos = parent::inicializar_atributos();

        $va_atributos['agrupamento_agrupamento_superior_codigo'] = [
            'agrupamento_agrupamento_superior_codigo',
            'coluna_tabela' => 'agrupamento_superior_codigo',
            'tipo_dado' => 'i',
            'objeto' => 'agrupamento',
            'valor_padrao' => [null, "<=>"]
        ];

        return $va_atributos;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '')
    {
        $va_campos_edicao = parent::inicializar_campos_edicao();

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo = '')
    {
        $va_filtros_navegacao = parent::inicializar_filtros_navegacao();

        return $va_filtros_navegacao;
    }

    public function inicializar_visualizacoes()
    {
        $va_campos_visualizacao = array();        
        parent::inicializar_visualizacoes();

        $va_campos_visualizacao_navegacao = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("navegacao"));

        $this->visualizacoes["navegacao"]["campos"] = $va_campos_visualizacao_navegacao;
        $this->visualizacoes["navegacao"]["order_by"] = ["agrupamento_dados_textuais_0_agrupamento_nome" => "Nome"];
        $this->visualizacoes["navegacao"]["ordem_campos"] = [
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["label" => "Nome", "main_field" => true],
            "agrupamento_acervo_codigo" => "Fundo/Coleção"
        ];

        $va_campos_visualizacao_ficha = array_merge($va_campos_visualizacao, $this->get_campos_visualizacao("ficha"));

        $this->visualizacoes["ficha"]["campos"] = $va_campos_visualizacao_ficha;
        $this->visualizacoes["ficha"]["ordem_campos"] = [
            "agrupamento_dados_textuais_0_agrupamento_nome" => ["label" => "Nome", "main_field" => true],
            "agrupamento_agrupamento_inferior_codigo" => "Subgrupos"
        ];
    }

    public function ler($pn_codigo, $ps_visualizacao = 'lista', $pn_idioma_codigo = 1)
    {
        $va_resultado = parent::ler($pn_codigo, $ps_visualizacao, $pn_idioma_codigo);

        $va_resultado["grupo_codigo"] = $va_resultado["agrupamento_codigo"];

        return $va_resultado;
    }
    
    public function ler_lista($pa_filtros_busca = null, $ps_visualizacao = "lista", $pn_primeiro_registro = 0, $pn_numero_registros = 20, $pa_order_by = null, $ps_order = null, $pa_log_info = null, $pn_idioma_codigo = 1, $pb_retornar_ramos_inferiores = true)
    {
        $va_resultados = parent::ler_lista($pa_filtros_busca, $ps_visualizacao, $pn_primeiro_registro, $pn_numero_registros, $pa_order_by, $ps_order, $pa_log_info, $pn_idioma_codigo);

        foreach ($va_resultados as &$va_resultado) 
        {
            $va_resultado["grupo_codigo"] = $va_resultado["agrupamento_codigo"];
        }

        return $va_resultados;
    }

}

?>