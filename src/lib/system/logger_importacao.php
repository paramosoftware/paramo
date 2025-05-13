<?php
class logger_importacao
{
    private $inicio;
    private $fim;
    private $duracao;
    private $id_objeto_importacao;
    private $operacoes;
    private $timezone;
    private $modo_importacao;

    private $debug;
    private $tolerancia_erros;

    public function __construct($ps_id_objeto_importacao, $ps_modo_importacao, $pb_debug, $pb_tolerancia_erros)
    {
        $this->timezone = new DateTimeZone('America/Sao_Paulo');
        $this->inicio = new DateTime('now', $this->timezone);
        $this->id_objeto_importacao = $ps_id_objeto_importacao;
        $this->modo_importacao = $ps_modo_importacao;
        $this->debug = $pb_debug;
        $this->tolerancia_erros = $pb_tolerancia_erros;
        $this->operacoes = array();

    }

    public function adicionar_operacao($ps_resultado, $ps_mensagem, $ps_tipo_operacao, $ps_codigo_registro = null): void
    {
        $this->operacoes[] = [
            "resultado" => $ps_resultado, "mensagens" => [$ps_mensagem], "tipo_operacao" => $ps_tipo_operacao, "codigo_registro" => $ps_codigo_registro
        ];
    }

    public function complementar_operacao_atual($ps_mensagem): void
    {
        $this->operacoes[array_key_last($this->operacoes)]["mensagens"][] = $ps_mensagem;
    }

    public function finalizar_relatorio(): array
    {
        $this->fim = new DateTime('now', $this->timezone);
        $this->duracao = $this->fim->diff($this->inicio);

        return [
            "objeto_importado" => $this->id_objeto_importacao,
            "operacoes" => $this->operacoes,
            "modo_import" => $this->modo_importacao,
            "duracao" => $this->duracao->format('%i minutos, %s segundos'),
            "debug" => $this->debug,
            "tolerancia_erros" => $this->tolerancia_erros,

        ];
    }

}
?>


