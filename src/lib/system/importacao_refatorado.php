<?php

class importacao_refatorado
{
    // Fins de log
    private $timezone;
    private $inicio;
    private $fim;

    private $duracao;

    private $operacoes = array();

    // Parametros importacao

    private $modo_importacao;

    private $debug;

    private $tolerancia_erros;

    private $separador_hierarquia;

    private $caminho_arquivo_importacao;

    private $id_objeto_importacao;

    private $objeto_importacao;

    private $campos_edicao = array();

    private $campos_relacionamento = array();

    // Selecoes do usuário & Origem de dados
    private $campos_origem_label = array();
    private $campos_destino_selecao = array();

    private $dados_origem = array();
    private $campos_variantes_importacao = array();

    function __construct($ps_id_objeto_importacao = null)
    {
        $this->id_objeto_importacao = $ps_id_objeto_importacao;
        if ($this->get_id_objeto_importacao()) {
            $this->inicializar_objeto_importacao();
        }

    }
    public function inicializar_timezone_importacao() {
        $this->timezone = new DateTimeZone('America/Sao_Paulo');
    }
    public function inicializar_data_inicio_importacao() {
        $this->inicio = new DateTime('now', $this->timezone);
    }
    public function get_usuario_relacionou_identificador($pb_is_item_acervo): int|null {
        foreach(array_keys($this->campos_destino_selecao) as $vn_posicao_campo_destino => $vs_identificacao_campo_destino) {
            if (str_contains($vs_identificacao_campo_destino, $pb_is_item_acervo ? "_identificador" : "_nome")) {
                return $vn_posicao_campo_destino;
            }
        }
        return null;
    }
    public function importar(): array {
        foreach ($this->dados_origem as $va_linha_importacao) {
            $this->processar_linha($va_linha_importacao);
        }
        return $this->operacoes;
    }
    public function processar_linha($pa_linha_importacao) {
        $va_dados_insercao_linha = array();

        if (in_array($this->modo_importacao, ["upsert", "update", "create"])) {
            if ($this->is_item_acervo($this->objeto_importacao)) {

            }

            $vn_index_identificador_objeto_importacao = $this->get_usuario_relacionou_identificador($this->is_item_acervo($this->objeto_importacao));
            // ...
        }

    }
    public function processar_coluna() {

    }
    public function is_item_acervo($pa_id_objeto) {
        return is_subclass_of(new $pa_id_objeto, "texto");
    }
    public function inicializar_objeto_importacao() {
        $this->objeto_importacao = new $this->id_objeto_importacao;
    }
    public function inicializar_campos_edicao_objeto_importacao() {
        if ($this->get_objeto_importacao()) {
            $this->set_campos_edicao($this->objeto_importacao->inicializar_campos_edicao());
        }

    }
    public function inicializar_variantes_campos_importacao($pa_valores_padrao, $pa_criar_itens_relacionados, $pa_separadores_valores, $pa_separadores_subcampo,): void {
        foreach (array_keys($this->campos_destino_selecao) as $vn_posicao_campo_destino) {
            $this->campos_variantes_importacao[$vn_posicao_campo_destino]  = [
                "valor_padrao" => $pa_valores_padrao[$vn_posicao_campo_destino],
                "separador_subcampos" => $pa_separadores_subcampo[$vn_posicao_campo_destino],
                "separador_valores" => $pa_separadores_valores[$vn_posicao_campo_destino],
                "criar_itens_relacionados" => isset($pa_criar_itens_relacionados[$vn_posicao_campo_destino])
                // [tipo de relacao padrao]

            ];
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
    public function get_campo_edicao($ps_label): array
    {
        return array_key_exists($ps_label, $this->campos_edicao) ? $this->campos_edicao[$ps_label] : [];

    }
    public function get_campos_relacionamento(): array
    {
        return $this->campos_relacionamento;
    }

    public function get_campos_origem_label(): array
    {
        return $this->campos_origem_label;
    }
    public function get_campo_origem_label($pn_index_selecao):  string {
        return $pn_index_selecao <= count($this->campos_origem_label) ? $this->campos_origem_label[$pn_index_selecao] : "";
    }
    public function get_campos_destino_selecao(): array
    {
        return $this->campos_destino_selecao;
    }
    public function get_campo_destino_selecao($pn_index_selecao): string {
        return $pn_index_selecao <= count($this->campos_destino_selecao) ? $this->campos_destino_selecao[$pn_index_selecao] : "";

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

    public function set_campos_destino_selecao($pa_campos_destino_selecao): void
    {
        $this->campos_destino_selecao = array_filter($pa_campos_destino_selecao, function($vs_campo) {
            return !empty($vs_campo);
        });
    }

    public function set_campos_origem_label($campos_origem_label): void
    {
        $this->campos_origem_label = $campos_origem_label;
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
