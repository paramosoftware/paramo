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

    private $objeto_importacao_is_item_acervo;
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
    public function get_existencia_registro($po_objeto_busca, $ps_valor_busca_registro, $pb_is_item_acervo)
    {
        $po_objeto_busca->inicializar_campos_importacao();
        $va_campos_importacao = $po_objeto_busca->get_campos_importacao();
        $va_resultado_busca = array();
        if ($pb_is_item_acervo)
        {
            $vs_campo_identificador_registro = $va_campos_importacao["identificador_registro"][0];
            $va_parametros_busca_registro[$vs_campo_identificador_registro] = [
                $ps_valor_busca_registro
            ];
            $va_resultado_busca = $po_objeto_busca->ler_lista($va_parametros_busca_registro);
        }

        return $va_resultado_busca;
    }

    public function get_index_identificador_objeto_importacao($pb_is_item_acervo): int|null {
        foreach(array_values($this->campos_destino_selecao) as $vn_posicao_campo_destino => $vs_identificacao_campo_destino) {
            if (str_contains($vs_identificacao_campo_destino, $pb_is_item_acervo ? "_identificador" : "_nome")) {
                return $vn_posicao_campo_destino;
            }
        }
        return null;
    }

    public function importar(): array {
        $this->objeto_importacao_is_item_acervo = $this->is_item_acervo($this->objeto_importacao);
        $vn_index_identificador_objeto_importacao = $this->get_index_identificador_objeto_importacao($this->is_item_acervo($this->objeto_importacao));

        foreach ($this->dados_origem as $va_linha_importacao) {
            $this->processar_linha($va_linha_importacao, $vn_index_identificador_objeto_importacao);

        }
        return $this->operacoes;
    }
    public function processar_linha($pa_linha_importacao, $pn_index_identificador_objeto_importacao) {
        $va_dados_insercao_linha = array();

        if (in_array($this->modo_importacao, ["upsert", "update", "create"])) {
            if ($this->objeto_importacao_is_item_acervo) {

                // TODO: Remover hardcoding aqui. Snippet de desenvolvimento
//                $va_dados_insercao_linha["texto_publicado_online"] = "1";
//                $va_dados_insercao_linha["texto_publicado_online_chk"] = "1";
//                $va_dados_insercao_linha["item_acervo_acervo_codigo"] = "1";
                //

            }

            if (isset($pn_index_identificador_objeto_importacao)) {
                if (!empty($pa_linha_importacao[$pn_index_identificador_objeto_importacao])) {
                    $vs_id_registro = $pa_linha_importacao[$pn_index_identificador_objeto_importacao];
                    $va_resultado_busca =  $this->get_existencia_registro($this->objeto_importacao, $vs_id_registro, $this->objeto_importacao_is_item_acervo);
                    unset($vs_id_registro);
                    
                    if (empty($va_resultado_busca) && in_array($this->modo_importacao, ["update"])) {
                        // registro inexistente em update
                        // [adicionar erro de operacao e skipar]
                    }

                    $va_dados_insercao_linha = array_merge($va_dados_insercao_linha, $va_resultado_busca[0]);

                } elseif (in_array($this->modo_importacao, ["update"])) {
                    // id não fornecido
                    // [adicionar erro de operacao e skipar]
                }
            }

            // Processar colunas
            $this->objeto_importacao->inicializar_relacionamentos();
            foreach($pa_linha_importacao as $vn_index_coluna_importacao => $vs_dado_coluna_importacao) {

                if (array_key_exists($vn_index_coluna_importacao, $this->campos_destino_selecao)) {
                    $vs_chave_campo_destino_atual = $this->campos_destino_selecao[$vn_index_coluna_importacao];
//                    $va_dados_insercao_linha[$vs_chave_campo_destino_atual] = $this->processar_coluna($vs_chave_campo_destino_atual, $vn_index_coluna_importacao, $vs_dado_coluna_importacao);
                    $va_dados_insercao_linha[$vs_chave_campo_destino_atual] = $vs_dado_coluna_importacao; // teste (ignorando todas as verificacoes)
                }
            }
// ...
            $this->objeto_importacao->iniciar_transacao();
            $this->objeto_importacao->salvar($va_dados_insercao_linha);
            $this->objeto_importacao->finalizar_transacao();
        }

    }
    public function validar_campo($ps_chave_campo_destino, $pn_index_linha_importacao) {
        $va_campo_destino = $this->objeto_importacao->campos_importacao["$ps_chave_campo_destino"];
        if (get_campo_tem_relacionamento($ps_chave_campo_destino)) {
            if (get_campo_tem_dependencia($ps_chave_campo_destino)) {
                
            }

        }

    }

    public function get_campo_tem_relacionamento($ps_chave_campo_destino): bool
    {
        return array_key_exists($ps_chave_campo_destino, $this->objeto_importacao->relacionamentos);

    }

    function get_campo_tem_dependencia($ps_campo): bool
    {
        return isset($ps_campo["dependencia"]);
    }
    public function processar_coluna($ps_chave_campo_destino, $ps_dado_campo_destino, $vn) {
        return validar_campo();
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
    public function get_campos_relacionamento_objeto_importacao(): array
    {
        return $this->objeto_importacao->campos_relacionamento;
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
