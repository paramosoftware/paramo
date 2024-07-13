<?php

class importacao_refatorado
{
    // Fins de log
    private $timezone;
    private $inicio;
    private $fim;

    private $duracao;

    private $operacoes;

    // Parametros importacao

    private $modo_importacao;

    private $debug;

    private $tolerancia_erros;

    private $separador_hierarquia;

    private $caminho_arquivo_importacao;

    private $id_objeto_importacao;

    private $objeto_importacao;

    private $campos_edicao;

    private $campos_relacionamento;

    // Selecoes do usuário & Origem de dados
    private $header_campos_origem;
    private $selecao_campos_destino;

    private $dados_origem;

    function __construct($ps_id_objeto_importacao = null)
    {
        $this->id_objeto_importacao = $ps_id_objeto_importacao;
        if ($this->get_id_objeto_importacao()) {
            $this->inicializar_objeto_importacao();
        }

    }
    public function inicializar_objeto_importacao() {
        $this->objeto_importacao = new $this->id_objeto_importacao;
    }
    public function inicializar_campos_edicao_objeto_importacao() {
        if ($this->get_objeto_importacao()) {
            $this->set_campos_edicao($this->objeto_importacao->inicializar_campos_edicao());
        }

    }

    public function get_operacoes(): array
    {
        return $this->operacoes;
    }

    public function get_modo_importacao(): string
    {
        return $this->modo_importacao;
    }

    public function get_debug(): bool
    {
        return $this->debug;
    }

    public function get_tolerancia_erros(): bool
    {
        return $this->tolerancia_erros;
    }

    public function get_separador_hierarquia(): string
    {
        return $this->separador_hierarquia;
    }

    public function get_id_objeto_importacao(): string
    {
        return $this->id_objeto_importacao;
    }

    public function get_objeto_importacao(): object
    {
        return $this->objeto_importacao;
    }

    public function get_campos_edicao(): array
    {
        return $this->campos_edicao;
    }

    public function get_campos_relacionamento(): array
    {
        return $this->campos_relacionamento;
    }

    public function get_header_campos_origem(): array
    {
        return $this->header_campos_origem;
    }

    public function get_selecao_campos_destino(): array
    {
        return $this->selecao_campos_destino;
    }

    public function get_dados_origem(): array
    {
        return $this->dados_origem;
    }
    public function get_caminho_arquivo_importacao(): string
    {
        return $this->caminho_arquivo_importacao;
    }
    public function set_dados_origem($dados_origem): void
    {
        $this->dados_origem = $dados_origem;
    }

    public function set_selecao_campos_destino($selecao_campos_destino): void
    {
        $this->selecao_campos_destino = $selecao_campos_destino;
    }

    public function set_header_campos_origem($header_campos_origem): void
    {
        $this->header_campos_origem = $header_campos_origem;
    }

    public function set_campos_relacionamento($campos_relacionamento): void
    {
        $this->campos_relacionamento = $campos_relacionamento;
    }

    public function set_campos_edicao($campos_edicao): void
    {
        $this->campos_edicao = $campos_edicao;
    }

    public function set_objeto_importacao($objeto_importacao): void
    {
        $this->objeto_importacao = $objeto_importacao;
    }

    public function set_id_objeto_importacao(mixed $id_objeto_importacao): void
    {
        $this->id_objeto_importacao = $id_objeto_importacao;
    }

    public function set_separador_hierarquia($separador_hierarquia): void
    {
        $this->separador_hierarquia = $separador_hierarquia;
    }

    public function set_tolerancia_erros($tolerancia_erros): void
    {
        $this->tolerancia_erros = $tolerancia_erros;
    }

    public function set_debug($debug): void
    {
        $this->debug = $debug;
    }

    public function set_modo_importacao($modo_importacao): void
    {
        $this->modo_importacao = $modo_importacao;
    }

    public function set_operacoes($operacoes): void
    {
        $this->operacoes = $operacoes;
    }

    public function set_caminho_arquivo_importacao($caminho_arquivo_importacao): void
    {
        $this->caminho_arquivo_importacao = $caminho_arquivo_importacao;
    }



}
