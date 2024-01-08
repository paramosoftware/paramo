<?php

class consulta_acervo extends selecao
{

    function __construct()
    {
        parent::__construct();

        $this->filtros_selecao["selecao_tipo_codigo"] = 2;
    }

    public function inicializar_campos_edicao($pn_objeto_codigo = '', $pn_bibliografia_codigo = '', $pa_valores_objeto = array())
    {
        $va_campos_edicao = array();

        $va_campos_edicao["selecao_codigo"] = [
            "html_text_input",
            "nome" => "selecao_codigo",
            "label" => "Código",
            "nao_exibir" => true
        ];

        $va_campos_edicao["selecao_usuario_codigo"] = [
            "html_autocomplete",
            "nome" => ['selecao_usuario', 'selecao_usuario_codigo'],
            "label" => "Consulente",
            "objeto" => "usuario",
            "atributos" => ["usuario_codigo", "usuario_nome"],
            "multiplos_valores" => false,
            "procurar_por" => "usuario_nome",
            "visualizacao" => "lista",
            "foco" => true
        ];

        $va_campos_edicao["selecao_data"] = [
            "html_date_input",
            "nome" => "selecao_data",
            "label" => "Data",
            "formato" => "dia"
        ];

        $va_campos_edicao["selecao_item_acervo_codigo"] = [
            "html_autocomplete",
            "nome" => ["selecao_item_acervo", "selecao_item_acervo_codigo"],
            "label" => "Itens do acervo selecionados",
            "objeto" => "texto",
            "atributos" => ["item_acervo_codigo", "item_acervo_identificador"],
            "multiplos_valores" => true,
            "procurar_por" => "item_acervo_identificador",
            "visualizacao" => "lista"
        ];

        return $va_campos_edicao;
    }

    public function inicializar_filtros_navegacao($pn_bibliografia_codigo='')
    {
        $va_filtros_navegacao = array();

        $va_filtros_navegacao["selecao_nome"] = [
            "html_text_input",
            "nome" => "selecao_nome",
            "label" => "Nome",
            "operador_filtro" => "LIKE",
            "foco" => true,
            "filtro" => [
                "atributo" => "selecao_tipo_codigo",
                "operador_filtro" => "=",
                "valor" => 2
            ]
        ];

        return $va_filtros_navegacao;
    }

}

?>