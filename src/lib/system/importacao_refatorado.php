<?php

class importacao_refatorado
{
    // Fins de log
    private $timezone;
    private $inicio;
    private $fim;

    private $duracao;
    private $logger;
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
    private $campos_importacao;
    // Selecoes do usuário & Origem de dados
    private $campos_origem_label = array();
    private $campos_destino_selecao = array();

    private $dados_origem = array();
    private $campos_variantes_importacao = array();

    private $usuario_logado_instituicao_codigo;

    private $usuario_logado_codigo;
    private $objeto_importacao_is_item_acervo;
    private $objeto_importacao_chave_primaria;

    private $index_linha_operacao_atual;
    private $index_col_operacao_atual;

    function __construct($pn_usuario_logado_instituicao_codigo, $ps_usuario_codigo, $ps_id_objeto_importacao = null)
    {
        $this->id_objeto_importacao = $ps_id_objeto_importacao;
        $this->usuario_logado_instituicao_codigo = $pn_usuario_logado_instituicao_codigo;
        $this->usuario_logado_codigo = $ps_usuario_codigo;

        if ($this->get_id_objeto_importacao()) {
            $this->inicializar_objeto_importacao();
        }

    }

    public function complementar_item_acervo() {
        return [
            "texto_publicado_online" => "1",
            "texto_publicado_online_chk" => "1",
            "item_acervo_acervo_codigo" => "1",
            "item_acervo_instituicao_codigo" => 1
        ];
    }

    public function is_item_acervo($pa_id_objeto) {
        return is_subclass_of(new $pa_id_objeto, "texto");

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
    function get_codigo_objeto_from_nome($ps_id_objeto_busca, $ps_atributo_retorno, $ps_atributo_busca, $ps_valor_busca)
    {
        $vo_objeto_de_busca = new $ps_id_objeto_busca;
        $va_campo_busca_atual = $this->campos_edicao[$this->campos_destino_selecao[$this->index_col_operacao_atual]];
        $ps_atributo_busca = isset($va_campo_busca_atual["procurar_por"]) ? $va_campo_busca_atual["procurar_por"] : $ps_atributo_busca;

        $va_parametro_busca[$ps_atributo_busca] = [$ps_valor_busca];
        $va_retorno_busca = $vo_objeto_de_busca->ler_lista($va_parametro_busca);
        return  isset($va_retorno_busca[0][$ps_atributo_retorno])? $va_retorno_busca[0][$ps_atributo_retorno] : false;
    }

    public function importar(): array {

        $this->logger = new logger_importacao($this->id_objeto_importacao, $this->modo_importacao, $this->debug, $this->tolerancia_erros);

        $this->objeto_importacao_is_item_acervo = $this->is_item_acervo($this->objeto_importacao);
        $vn_index_identificador_objeto_importacao = $this->get_index_identificador_objeto_importacao($this->is_item_acervo($this->objeto_importacao));

        $this->objeto_importacao->iniciar_transacao();
        foreach ($this->dados_origem as $vn_linha_importacao => $va_dados_linha_importacao)
        {
            $this->linha_operacao_atual = $vn_linha_importacao;
            $this->processar_linha($va_dados_linha_importacao, $vn_index_identificador_objeto_importacao);
        }

        $this->objeto_importacao->finalizar_transacao();

        return $this->logger->finalizar_relatorio();

    }
    public function processar_linha($pa_linha_importacao, $pn_index_identificador_objeto_importacao) {
        $va_dados_insercao_linha = array();

        if (in_array($this->modo_importacao, ["upsert", "update", "create"])) {

            if (isset($pn_index_identificador_objeto_importacao)) {
                if (!empty($pa_linha_importacao[$pn_index_identificador_objeto_importacao])) {
                    $vs_id_registro = $pa_linha_importacao[$pn_index_identificador_objeto_importacao];
                    $va_resultado_busca =  $this->get_existencia_registro($this->objeto_importacao, $vs_id_registro, $this->objeto_importacao_is_item_acervo);
                    unset($vs_id_registro);
                    
                    if (empty($va_resultado_busca) && in_array($this->modo_importacao, ["update"])) {
                        $this->logger->adicionar_operacao("Negativo", "Objeto não encontrado em operação de atualização.", "Atualização");
                        return;
                    }

                    $va_dados_insercao_linha = array_intersect_key($va_dados_insercao_linha, $va_resultado_busca[0]);
                    $vs_chave_primaria_objeto_importacao = $this->objeto_importacao->get_chave_primaria()[0];
                    $va_dados_insercao_linha[$vs_chave_primaria_objeto_importacao] = $va_resultado_busca[0][$vs_chave_primaria_objeto_importacao];

                    if (!empty($this->objeto_importacao->get_campo_relacionamento_pai)) {
                        $va_dados_insercao_linha[$this->objeto_importacao->get_campo_relacionamento_pai] = $va_resultado_busca[0][$this->objeto_importacao->get_campo_relacionamento_pai];
                    }

                } elseif (in_array($this->modo_importacao, ["update"])) {
                    // id não fornecido
                    $this->logger->adicionar_operacao("Negativo", "Objeto sem identificador em operação de atualização.", "Atualização");

                }
            }


            $this->inicializar_campos_objeto_importacao();
            // Processar colunas
            foreach($pa_linha_importacao as $vn_index_coluna_importacao => $vs_dado_coluna_importacao) {
                $this->index_col_operacao_atual = $vn_index_coluna_importacao;
                if (array_key_exists($vn_index_coluna_importacao, $this->campos_destino_selecao)) {
                    $vs_chave_campo_destino_atual = $this->campos_destino_selecao[$vn_index_coluna_importacao];
                    if (strpos($this->campos_destino_selecao[$vn_index_coluna_importacao], "data")) {
                        $va_dados_insercao_linha = array_merge($va_dados_insercao_linha, $this->processar_data($vs_dado_coluna_importacao, ""));
                    }
                    $va_dados_insercao_linha[$vs_chave_campo_destino_atual] = $this->processar_coluna($vs_chave_campo_destino_atual, $vs_dado_coluna_importacao);
                }
            }

            //TODO: Converter isso em funcao, e considerar tolerancia de erros. chave de código sem atributo presente = falha

            if (!isset($va_dados_insercao_linha["item_acervo_identificador"]) && $this->objeto_importacao_is_item_acervo)
            {
                $va_dados_insercao_linha["item_acervo_identificador"] = "";
            }


            $va_dados_insercao_linha["instituicao_codigo"] = $this->usuario_logado_instituicao_codigo;
            $va_dados_insercao_linha["usuario_logado_codigo"] = $this->usuario_logado_codigo;
            if ($this->objeto_importacao_is_item_acervo) {
                $va_dados_insercao_linha = array_merge($va_dados_insercao_linha, $this->complementar_item_acervo());

            }


            $this->logger->adicionar_operacao(
                "Positivo",
                "Objeto manipulado com sucesso. ",
                "Main",
                $this->objeto_importacao->salvar($va_dados_insercao_linha));
        }

    }

    public function processar_coluna($ps_chave_campo_destino, $ps_dado_campo_destino) {

        if ($this->get_campo_tem_relacionamento($ps_chave_campo_destino)) {
            $va_campo_destino = $this->campos_edicao[$ps_chave_campo_destino];
            if ($this->campo_is_lista_controlada($va_campo_destino)) {
                $vs_chave_primeiro_subcampo_destino_atual = array_key_first($va_campo_destino["subcampos"]);
                $va_campo_destino = $va_campo_destino["subcampos"][$vs_chave_primeiro_subcampo_destino_atual];

            }

            $vs_codigo_atributo_campo = $this->get_codigo_objeto_from_nome(
                $va_campo_destino["objeto"], // id objeto
                $va_campo_destino["atributos"][0], // atributo de busca
                $va_campo_destino["atributos"][1], // atributo de retorno
                $ps_dado_campo_destino); // valor de busca



            if (empty($vs_codigo_atributo_campo))
            {
                // [criar entrada em lista já que não existe]
                $vo_item_relacionado = new $va_campo_destino["objeto"];
                $vo_item_relacionado->inicializar_campos_edicao();
                // TODO: Tratar subcampos aqui, se existirem...
                // Acesso de separadores neste ponto em em $this->campos_variantes_importacao[$this->index_col_operacao_atual]
                $va_insercao_item_relacionado = array_fill_keys(array_keys($vo_item_relacionado->get_campos_edicao()), "");
                $va_insercao_item_relacionado[$va_campo_destino["atributos"][1]] = $ps_dado_campo_destino;

//              $va_insercao_item_relacionado["instituicao_codigo"] = $this->usuario_logado_instituicao_codigo;
                $va_insercao_item_relacionado["usuario_logado_codigo"] = $this->usuario_logado_codigo;
                return $vo_item_relacionado->salvar($va_insercao_item_relacionado);

            }

            if (strpos($ps_chave_campo_destino, "_codigo")) {
                return $vs_codigo_atributo_campo;

            } else {
                return $ps_dado_campo_destino;
            }
        }

        return $ps_dado_campo_destino;

    }

    function processar_data($ps_value, $ps_id)
    {

        $ps_id = "texto_data";
        
        $vo_periodo = new Periodo();
        if (count(explode("-", $ps_value)) >= 3)
        {
            $ps_value = str_replace("-", "/", $ps_value);
        }

        $vo_periodo->tratar_string($ps_value);

        if ($vo_periodo->validar())
        {

            return
                [$ps_id => "_data_",
                    $ps_id . "_sem_data" => $vo_periodo->get_sem_data(),
                    $ps_id . "_presumido" => $vo_periodo->get_presumido(),
                    $ps_id . "_dia_inicial" => $vo_periodo->get_dia_inicial(),
                    $ps_id . "_mes_inicial" => $vo_periodo->get_mes_inicial(),
                    $ps_id . "_ano_inicial" => $vo_periodo->get_ano_inicial(),
                    $ps_id . "_dia_final" => $vo_periodo->get_dia_final(),
                    $ps_id . "_mes_final" => $vo_periodo->get_mes_final(),
                    $ps_id . "_ano_final" => $vo_periodo->get_ano_final()];
        }
        else
        {
            $this->add_error("Valor não é uma data válida: " . $ps_value);
        }
    }
    public function get_campo_tem_relacionamento($ps_chave_campo_destino): bool
    {
        return array_key_exists($ps_chave_campo_destino, $this->campos_relacionamento);

    }

    public function campo_is_lista_controlada($pa_campo) {
        return $this->is_item_acervo($pa_campo["objeto"]);

    }

    function get_campo_tem_subcampo($pa_campo)
    {
        return (isset($pa_campo["subcampos"]));

    }

    public function inicializar_objeto_importacao() {
        $this->objeto_importacao = new $this->id_objeto_importacao;
        $this->objeto_importacao_chave_primaria = $this->objeto_importacao->get_chave_primaria();

    }
    public function inicializar_campos_edicao_objeto_importacao() {
        if ($this->objeto_importacao) {
            $this->campos_edicao = $this->objeto_importacao->inicializar_campos_edicao();
            return true;
        }
        return false;
    }

    public function inicializar_campos_objeto_importacao() {
        if ($this->objeto_importacao) {
            // separados pois não dá pra criar objeto por retorno de funcao em runtime
            $vs_id_objeto_pai = $this->objeto_importacao->get_objeto_pai();
            $vo_objeto_pai = new $vs_id_objeto_pai;

            $this->objeto_importacao->inicializar_campos_importacao();
            $this->campos_importacao = $this->objeto_importacao->get_campos_importacao();
            $this->campos_relacionamento = array_merge($this->objeto_importacao->inicializar_relacionamentos(), $vo_objeto_pai->inicializar_relacionamentos());
            return true;
        }
        return false;
    }
    public function inicializar_variantes_campos_importacao($pa_valores_padrao, $pa_criar_itens_relacionados, $pa_separadores_valores, $pa_separadores_subcampo,): void {
        foreach (array_keys($this->campos_destino_selecao) as $vn_posicao_campo_destino) {
            $this->campos_variantes_importacao[$vn_posicao_campo_destino]  = [
                "valor_padrao" => $pa_valores_padrao[$vn_posicao_campo_destino],
                "separador_subcampos" => $pa_separadores_subcampo[$vn_posicao_campo_destino]  ?? false,
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
        return $this->campos_destino_selecao[$pn_index_selecao];

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
