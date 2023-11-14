<?php

#[\AllowDynamicProperties]
class html_combo_input extends html_input
{

private $itens;

public function get_itens()
{
    if (!isset($this->itens))
        $this->itens = array();

    return $this->itens;
}

public function preencher($pa_filtro_listagem, $pa_parametros_campo)
{
    if (isset($pa_parametros_campo["valores"]))
    {
        foreach($pa_parametros_campo["valores"] as $vs_key_item => $vs_item)
        {
            $this->adicionar_item($vs_key_item, $vs_item);
        }
    }

    if (isset($pa_parametros_campo["objeto"]))
    {
        $va_itens = array();
        $va_filtro = array();

        if (isset($pa_parametros_campo["dependencia"]))
        {
            if (isset($pa_parametros_campo["dependencia"]["campo"]))
                $va_dependencias = array($pa_parametros_campo["dependencia"]);
            else
                $va_dependencias = $pa_parametros_campo["dependencia"];

            $vb_nao_achou_dependencia_obrigatoria = false;

            foreach ($va_dependencias as $va_dependencia)
            {
                // Vou verificar antes se eu posso aplicar a dependência
                // Não posso aplicar a dependência se o campo estiver sendo construído para uma busca combinada
                // e a dependência é originada de outro campo do mesmo conjunto de filtro

                $vb_busca_combinada = isset($pa_parametros_campo["busca_combinada"]) ? true : false;

                $vb_dependencia_campo_interface = false;
                if (!isset($va_dependencia["tipo"]) || (isset($va_dependencia["tipo"]) && $va_dependencia["tipo"] == "interface"))
                    $vb_dependencia_campo_interface = true;

                if (!$vb_busca_combinada || ($vb_busca_combinada && !$vb_dependencia_campo_interface))
                {
                    if (isset($pa_filtro_listagem[$va_dependencia["campo"]]) && $pa_filtro_listagem[$va_dependencia["campo"]] != "")
                    {
                        $va_filtro[$va_dependencia["atributo"]] = $pa_filtro_listagem[$va_dependencia["campo"]];
                    }
                    elseif (isset($pa_filtro_listagem[$va_dependencia["atributo"]]))
                    {
                        $va_filtro[$va_dependencia["atributo"]] = $pa_filtro_listagem[$va_dependencia["atributo"]];
                    }
                    else
                    {
                        // Se a dependência "obrigatória" existe e nenhum valor é passado, não gera a lista
                        ///////////////////////////////////////////////////////////////////////////////////

                        if (isset($va_dependencia["obrigatoria"]) && $va_dependencia["obrigatoria"])
                            return false;
                    }
                }
            }
        }

        if (isset($pa_parametros_campo["filtro"]))
        {
            foreach ($pa_parametros_campo["filtro"] as $va_filtro_combo)
            {
                if (isset($va_filtro_combo["operador"]))
                    $va_filtro[$va_filtro_combo["atributo"]] = [$va_filtro_combo["valor"], $va_filtro_combo["operador"]];
                else
                    $va_filtro[$va_filtro_combo["atributo"]] = $va_filtro_combo["valor"];
            }
        }

        if (isset($pa_parametros_campo["parametros_inicializacao"]))
        {
            $vo_objeto = new $pa_parametros_campo["objeto"]($pa_parametros_campo["parametros_inicializacao"]);
        }
        else
        {
            $vs_id_objeto = $pa_parametros_campo["objeto"];
            $vo_objeto = new $vs_id_objeto($vs_id_objeto);
        }
        
        if (isset($pa_parametros_campo["prevenir_circularidade"]) && $pa_parametros_campo["prevenir_circularidade"] != "")
        {
            $va_codigos_proibidos = $vo_objeto->ler_codigos_ramo_inferior($pa_parametros_campo["prevenir_circularidade"], $vo_objeto->get_campo_hierarquico());
            $va_codigos_proibidos[] = $pa_parametros_campo["prevenir_circularidade"];

            $va_filtro[$vo_objeto->get_chave_primaria()[0]] = [implode("|", $va_codigos_proibidos), "NOT IN"];
        }

        if (isset($pa_parametros_campo["visualizacao"]))
            $vs_visualizacao = $pa_parametros_campo["visualizacao"];
        else
            $vs_visualizacao = "lista";

        $va_visualizacao = $vo_objeto->get_visualizacao($vs_visualizacao);

        $vn_primeiro_registro = 0;
        $vn_numero_maximo_itens = 0;
        if (isset($pa_parametros_campo["numero_maximo_itens"]))
        {
            $vn_primeiro_registro = 1;
            $vn_numero_maximo_itens = $pa_parametros_campo["numero_maximo_itens"];
        }

        $va_itens = $vo_objeto->ler_lista($va_filtro, $vs_visualizacao, $vn_primeiro_registro, $vn_numero_maximo_itens);

        foreach($va_itens as $va_item)
        {
            if (isset($pa_parametros_campo["atributos"]))
            {
                $va_keys_atributos = array_keys($pa_parametros_campo["atributos"]);

                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[0]]))
                    $vn_item_lista_option = $va_item[$pa_parametros_campo["atributos"][$va_keys_atributos[0]]];
                else
                    $vn_item_lista_option = $va_item[$va_keys_atributos[0]];

                $vs_campo_hierarquia = "";
                $vb_ler_hierarquia = true;

                if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[1]]) || (!isset($pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"])) )
                    $vs_campo_item_lista_value = $pa_parametros_campo["atributos"][$va_keys_atributos[1]];
                else
                {
                    $vs_campo_item_lista_value = $va_keys_atributos[1];
                    $vs_campo_hierarquia = $pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"];
                }

                // Temos que generalizar essa montagem numa função
                $va_item_lista_value = array();

                while ($vb_ler_hierarquia)
                {
                    //$vs_valor_item_lista = $this->ler_valor_textual($va_item, $vs_campo_item_lista_value);

                    if (is_array($vs_campo_item_lista_value))
                        $vs_campo_item_lista_value = $va_keys_atributos[1];
                        
                    $vs_valor_item_lista = ler_valor1($vs_campo_item_lista_value, $va_item, $pa_parametros_campo["atributos"][$va_keys_atributos[1]]);

                    if (isset($pa_parametros_campo["atributos"][$va_keys_atributos[1]]["sentido"]))
                        $va_item_lista_value[] = $vs_valor_item_lista;
                    else
                        array_unshift($va_item_lista_value, $vs_valor_item_lista);

                    if (isset($va_item[$vs_campo_hierarquia]))
                    {
                        $va_item = $va_item[$vs_campo_hierarquia];
                        $vs_valor_item_lista = $va_item;
                    }
                    else
                        $vb_ler_hierarquia = false;
                }

                $vs_item_lista_value = join(" >  ", $va_item_lista_value);
                // Temos que generalizar essa montagem numa função
            }
            else
            {
                $contador = 1;
                foreach ($va_visualizacao["campos"] as $va_campos_visualizacao)
                {
                    if ($contador == 1)
                    {
                        // O primeiro campo da visualização é, por conveniência, o código do item
                        $vn_item_lista_option = $va_item[$va_campos_visualizacao["nome"]];
                    }
                    else
                    {
                        // O último campo da visualização será, por conveniência, o valor a ser exibido do item
                        $vs_item_lista_value = $va_item[$va_campos_visualizacao["nome"]];
                    }

                    $contador++;
                }
            }

            $this->adicionar_item($vn_item_lista_option, $vs_item_lista_value);
        }
    }
}

public function adicionar_item($ps_key, $ps_valor)
{
    if (!isset($this->itens))
        $this->itens = array();

    $this->itens[$ps_key] = $ps_valor;
}

public function build(&$pa_valores_form=null, $pa_parametros_campo=array())
{
    $vs_tela = $this->get_tela();
    $vs_modo = $this->get_modo_form();
    $vs_ui_element = $this->get_ui_element();

    $vb_marcar_sem_valor = false;
    $vb_marcar_com_valor = false;

    $vs_sufixo_nome_campo = "";
    if (isset($pa_parametros_campo["sufixo_nome"]))
        $vs_sufixo_nome_campo = $pa_parametros_campo["sufixo_nome"];

    $this->preencher($pa_valores_form, $pa_parametros_campo);

    $va_itens_campo = $this->get_itens();

    $vs_valor_campo = "";
    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
    {
        if (isset($pa_parametros_campo["atributo"]))
        {
            if (is_array($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]))
            {
                if ( count($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo]) && (isset($pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo][$pa_parametros_campo["atributo"]])) )
                    $vs_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo][$pa_parametros_campo["atributo"]];
            }
            else
                $vs_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo];
        }
        else
            $vs_valor_campo = $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo];
    }

    if ( isset($pa_parametros_campo["valor_padrao"]) && ($vs_valor_campo == "") )
        $vs_valor_campo = $pa_parametros_campo["valor_padrao"];

    if ( ($vs_valor_campo == "") && isset($pa_parametros_campo["sem_valor"]) && !$pa_parametros_campo["sem_valor"] && count($va_itens_campo))
        $vs_valor_campo = array_keys($va_itens_campo)[0];

    if (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_sem_valor"]))
        $vb_marcar_sem_valor = true;
    elseif (isset($pa_valores_form[$pa_parametros_campo["nome"] . "_com_valor"]))
        $vb_marcar_com_valor = true;
    
    if ($vb_marcar_sem_valor || $vb_marcar_com_valor)
        $pa_parametros_campo["desabilitar"] = true;
    
    $vb_pode_exibir = true;
    if (isset($pa_parametros_campo["nao_exibir"]) && $pa_parametros_campo["nao_exibir"])
        $vb_pode_exibir = !$pa_parametros_campo["nao_exibir"];

    $vb_pode_exibir = $vb_pode_exibir && $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    if (isset($pa_parametros_campo["exibir_quando_preenchido"]))
        $vb_pode_exibir = $vb_pode_exibir && ((trim($vs_valor_campo) != "") || $vb_marcar_sem_valor || $vb_marcar_com_valor) && $pa_parametros_campo["exibir_quando_preenchido"];

    if ($vs_ui_element == "linha")
        require dirname(__FILE__) . "/../../../app/components/campo_combo_linha.php";
    elseif ($vs_ui_element == "autocomplete")
        require dirname(__FILE__) . "/../../../app/components/campo_lista_selecao.php";
    elseif ($vs_ui_element == "radio")
        require dirname(__FILE__) . "/../../../app/components/campo_options.php";
    elseif ($vs_ui_element == "multi_selecao")
        require dirname(__FILE__) . "/../../../app/components/campo_multi_selecao.php";
    elseif ($vs_ui_element == "header")
        require dirname(__FILE__) . "/../../../app/components/campo_combo_header.php";
    else
    {
        $va_valor_campo = $pa_valores_form;
        require dirname(__FILE__) . "/../../../app/components/campo_combo_form.php";
    }

    if ($vs_valor_campo != "")
    {
        if (isset($pa_parametros_campo["conectar"]))
        {
            foreach($pa_parametros_campo["conectar"] as $v_conectar)
            {
                $pa_valores_form[$v_conectar["atributo"]] = [$vs_valor_campo, "="];
            }
        }

        $pa_valores_form[$pa_parametros_campo["nome"] . $vs_sufixo_nome_campo] = $vs_valor_campo;
    }

    return true;
}

}