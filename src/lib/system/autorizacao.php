<?php

/**
 * Autorização de objetos e registros para os endpoints legados.
 *
 * A classe do objeto nunca vem livremente do request: o identificador precisa
 * corresponder a um recurso instalado (e registrado em recurso_sistema, quando
 * houver contexto de banco) antes de qualquer instanciação. Classes de apoio do
 * diretório lib/system não são objetos de conteúdo e por isso nunca são aceitas.
 */
class autorizacao
{
    private const PADRAO_ID_OBJETO = '/\A[a-z][a-z0-9_]{0,63}\z/';

    private const PERMISSOES = [
        "editar" => "vb_pode_editar",
        "excluir" => "vb_pode_excluir",
        "inserir" => "vb_pode_inserir",
        "substituir" => "vb_pode_substituir",
    ];

    public static function objeto_id_valido($ps_id): bool
    {
        return is_string($ps_id)
            && preg_match(self::PADRAO_ID_OBJETO, $ps_id) === 1;
    }

    /**
     * Diretórios que contêm classes de objetos de conteúdo. lib/system fica de
     * fora de propósito: são classes de infraestrutura, não recursos editáveis.
     */
    public static function diretorios_de_objetos(): array
    {
        $vs_lib = rtrim((string) config::get(["pasta_lib"]), "/\\") . DIRECTORY_SEPARATOR;
        $vs_business = (string) (config::get(["pasta_business"]) ?: "default");

        return array_values(array_unique([
            $vs_lib . $vs_business . DIRECTORY_SEPARATOR . "core",
            $vs_lib . $vs_business . DIRECTORY_SEPARATOR . "auxiliary",
            $vs_lib . "default" . DIRECTORY_SEPARATOR . "core",
            $vs_lib . "default" . DIRECTORY_SEPARATOR . "auxiliary",
        ]));
    }

    /**
     * Confirma que existe uma classe de objeto instalada na árvore permitida.
     * Não instancia nada e não consulta o banco.
     */
    public static function classe_objeto_instalada($ps_id): bool
    {
        if (!self::objeto_id_valido($ps_id))
            return false;

        foreach (self::diretorios_de_objetos() as $vs_diretorio)
        {
            if (is_file($vs_diretorio . DIRECTORY_SEPARATOR . $ps_id . ".php"))
                return true;
        }

        return false;
    }

    /**
     * Objetos editáveis cuja classe fica em lib/system (seleção, grupo de
     * usuário, fluxo etc.). Aqui a classe de infraestrutura sozinha não basta:
     * o identificador precisa estar registrado em recurso_sistema.
     */
    public static function classe_sistema_registrada($ps_id): bool
    {
        if (!self::objeto_id_valido($ps_id))
            return false;

        $vs_arquivo = rtrim((string) config::get(["pasta_lib"]), "/\\")
            . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . $ps_id . ".php";

        if (!is_file($vs_arquivo))
            return false;

        return self::recurso_instalado($ps_id);
    }

    /**
     * Resolve um identificador de objeto para uma instância de objeto_base.
     *
     * $pa_registros_adicionais permite que fluxos com allowlist própria (por
     * exemplo, submissão externa) aceitem objetos configurados explicitamente,
     * sem abrir a resolução para classes arbitrárias.
     */
    public static function resolver_objeto($ps_id, array $pa_registros_adicionais = []): ?objeto_base
    {
        if (!self::classe_objeto_instalada($ps_id) && !self::classe_sistema_registrada($ps_id))
            return null;

        if (!class_exists($ps_id))
            return null;

        try
        {
            $vo_reflexao = new ReflectionClass($ps_id);
        }
        catch (Throwable $e)
        {
            return null;
        }

        // A checagem acontece ANTES de instanciar: nenhuma classe de sistema
        // chega a ser construída só porque o nome veio do request.
        if ($vo_reflexao->isAbstract()
            || !$vo_reflexao->isSubclassOf("objeto_base"))
            return null;

        $vb_registro_adicional = in_array($ps_id, $pa_registros_adicionais, true);

        if (!$vb_registro_adicional && !self::recurso_instalado($ps_id))
            return null;

        try
        {
            $vo_objeto = $vo_reflexao->newInstance("");
        }
        catch (Throwable $e)
        {
            return null;
        }

        return ($vo_objeto instanceof objeto_base) ? $vo_objeto : null;
    }

    public static function resolver_objeto_por_recurso($pn_recurso_sistema_codigo): ?objeto_base
    {
        if (!is_numeric($pn_recurso_sistema_codigo) || (int) $pn_recurso_sistema_codigo <= 0)
            return null;

        try
        {
            $vo_recurso_sistema = new recurso_sistema();
            $va_recurso = $vo_recurso_sistema->ler_lista(
                ["recurso_sistema_codigo" => (int) $pn_recurso_sistema_codigo],
                "lista",
                0,
                1
            );
        }
        catch (Throwable $e)
        {
            return null;
        }

        if (!count($va_recurso))
            return null;

        $vs_id = $va_recurso[0]["recurso_sistema_id"] ?? "";

        return self::resolver_objeto($vs_id, [$vs_id]);
    }

    /**
     * O recurso precisa estar registrado em recurso_sistema. Sem banco (ou em
     * ambiente de teste) a resposta é negativa, nunca uma liberação implícita.
     */
    private static function recurso_instalado($ps_id): bool
    {
        if (!class_exists("recurso_sistema"))
            return false;

        try
        {
            $vo_recurso_sistema = new recurso_sistema();
            $va_recurso = $vo_recurso_sistema->ler_lista(
                ["recurso_sistema_id" => $ps_id],
                "lista",
                0,
                1
            );
        }
        catch (Throwable $e)
        {
            return false;
        }

        return count($va_recurso) > 0;
    }

    public static function usuario_externo(): bool
    {
        return !empty($GLOBALS["vb_usuario_externo"]);
    }

    public static function selecoes_compartilhadas(): array
    {
        $va_selecoes = $GLOBALS["va_selecoes_compartilhadas_codigos"] ?? [];

        return is_array($va_selecoes)
            ? array_values(array_filter($va_selecoes, "is_numeric"))
            : [];
    }

    /**
     * O contexto só é considerado autenticado quando autenticar_usuario
     * inicializou o usuário e os parâmetros de controle de acesso. Assim um
     * endpoint que esqueceu a autenticação não libera leitura por omissão.
     */
    public static function contexto_autenticado(): bool
    {
        if ((int) ($GLOBALS["vn_usuario_logado_codigo"] ?? 0) <= 0)
            return false;

        if (!isset($GLOBALS["va_parametros_controle_acesso"])
            || !is_array($GLOBALS["va_parametros_controle_acesso"]))
            return false;

        return array_key_exists("vb_usuario_externo", $GLOBALS);
    }

    private static function parametros_controle_acesso(): array
    {
        $va_parametros = $GLOBALS["va_parametros_controle_acesso"] ?? null;

        if (is_array($va_parametros) && count($va_parametros) > 1)
            return $va_parametros;

        // Fallback seguro para uma instalação sem _atributos_ configurados:
        // continua restringindo por instituição/acervo do usuário autenticado.
        return [
            "instituicao_codigo" => $GLOBALS["vn_usuario_logado_instituicao_codigo"] ?? "",
            "acervo_codigo" => $GLOBALS["vn_usuario_logado_acervo_codigo"] ?? "",
            "_combinacao_" => "AND",
        ];
    }

    /**
     * Leitura de recurso: exige permissão de leitura (ou edição) do recurso.
     * Administradores têm bypass explícito porque autenticar_usuario não marca
     * vb_pode_ler para eles.
     */
    private static function pode_ler_recurso(): bool
    {
        return !empty($GLOBALS["vb_pode_ler"])
            || !empty($GLOBALS["vb_pode_editar"])
            || !empty($GLOBALS["vb_usuario_administrador"]);
    }

    /**
     * Para usuários externos (tipo 3), o escopo de leitura é somente o conteúdo
     * das seleções compartilhadas com ele.
     */
    public static function externo_pode_ler_registro(objeto_base $po_objeto, $pn_codigo): bool
    {
        $va_selecoes = self::selecoes_compartilhadas();

        if (!count($va_selecoes))
            return false;

        $vs_chave_primaria = $po_objeto->get_chave_primaria()[0];

        $va_registros = $po_objeto->ler_lista(
            [
                "item_selecao_codigo" => implode("|", $va_selecoes),
                $vs_chave_primaria => (int) $pn_codigo,
            ],
            "lista",
            0,
            1
        );

        return count($va_registros) > 0;
    }

    public static function autorizar_leitura_registro(?objeto_base $po_objeto, $pn_codigo): bool
    {
        if (!($po_objeto instanceof objeto_base) || !is_numeric($pn_codigo) || (int) $pn_codigo <= 0)
            return false;

        if (!self::contexto_autenticado())
            return false;

        if (self::usuario_externo())
            return self::externo_pode_ler_registro($po_objeto, (int) $pn_codigo);

        if (!self::pode_ler_recurso())
            return false;

        return $po_objeto->validar_acesso_registro((int) $pn_codigo, self::parametros_controle_acesso());
    }

    public static function autorizar_edicao_registro(?objeto_base $po_objeto, $pn_codigo, string $ps_permissao = "editar"): bool
    {
        if (!($po_objeto instanceof objeto_base) || self::usuario_externo())
            return false;

        if (!self::contexto_autenticado())
            return false;

        $vs_variavel = self::PERMISSOES[$ps_permissao] ?? "vb_pode_editar";

        if (empty($GLOBALS[$vs_variavel]) && empty($GLOBALS["vb_usuario_administrador"]))
            return false;

        if (!is_numeric($pn_codigo) || (int) $pn_codigo <= 0)
            return false;

        return $po_objeto->validar_acesso_registro((int) $pn_codigo, self::parametros_controle_acesso());
    }

    /**
     * Somente o dono da seleção (ou administrador) pode alterar seu conteúdo.
     */
    public static function autorizar_mutacao_selecao($pn_selecao_codigo): bool
    {
        if (self::usuario_externo()
            || !self::contexto_autenticado()
            || !is_numeric($pn_selecao_codigo)
            || (int) $pn_selecao_codigo <= 0)
            return false;

        $vn_usuario_codigo = (int) ($GLOBALS["vn_usuario_logado_codigo"] ?? 0);

        if ($vn_usuario_codigo <= 0)
            return false;

        try
        {
            $vo_selecao = new selecao();
            $va_selecao = $vo_selecao->ler_lista(
                ["selecao_codigo" => (int) $pn_selecao_codigo],
                "lista",
                0,
                1
            );
        }
        catch (Throwable $e)
        {
            return false;
        }

        if (!count($va_selecao))
            return false;

        if (!empty($GLOBALS["vb_usuario_administrador"]))
            return true;

        $vn_dono = (int) ($va_selecao[0]["selecao_usuario_codigo"]["usuario_codigo"] ?? 0);

        return $vn_dono === $vn_usuario_codigo;
    }
}
