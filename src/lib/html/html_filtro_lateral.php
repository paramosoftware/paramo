<?php

class html_filtro_lateral extends html_input
{

private $itens = array();
private $objects = [];

public function get_itens()
{
    if (!isset($this->itens))
        $this->itens = array();

    return $this->itens;
}

public function get_objects()
{
    return $this->objects;
}

public function preencher($pa_filtro_listagem, $pa_parametros_campo)
{
    $va_itens = array();

    $va_dependencias = array();
    $va_filtro = array();
    $va_filtros_pos_aplicacao = array();

    $vb_aplicar_filtro_banco_dados = true;

    if (
        isset($pa_filtro_listagem[$pa_parametros_campo["atributo"]]) 
        && 
        (is_array($pa_filtro_listagem[$pa_parametros_campo["atributo"]]) || $pa_filtro_listagem[$pa_parametros_campo["atributo"]] == 0)
    )
    {
        $this->adicionar_item("_NO_", "[Sem atribução]");
        return true;
    }

    if (isset($pa_parametros_campo["dependencia"]))
    {
        if (isset($pa_parametros_campo["dependencia"]["campo"]))
            $va_dependencias = array($pa_parametros_campo["dependencia"]);
        else
            $va_dependencias = $pa_parametros_campo["dependencia"];

        foreach ($va_dependencias as $va_dependencia)
        {
            if (isset($pa_filtro_listagem[$va_dependencia["campo"]]) && $pa_filtro_listagem[$va_dependencia["campo"]])
            {
                if (in_array($va_dependencia["campo"], $va_filtros_pos_aplicacao))
                    continue;

                $va_filtro[$va_dependencia["atributo"]] = $pa_filtro_listagem[$va_dependencia["campo"]];

                if (isset($va_dependencia["forcar_pos_aplicacao"]))
                {
                    $va_filtros_pos_aplicacao = array_merge($va_filtros_pos_aplicacao, $va_dependencia["forcar_pos_aplicacao"]);
                }
            }
            else
            {
                // Se a dependência "obrigatória" existe e nenhum valor é passado, não gera a lista

                if (isset($va_dependencia["obrigatoria"]) && $va_dependencia["obrigatoria"])
                    return false;
            }

            if (isset($va_dependencia["relacao_hierarquica"]))
            {
                $vs_atributo_hierarquico = $va_dependencia["relacao_hierarquica"];
                $vb_aplicar_filtro_banco_dados = false;
            }
        }
    }

    if (isset($pa_parametros_campo["filtro"]))
    {
        foreach ($pa_parametros_campo["filtro"] as $va_filtro_combo)
        {
            if (in_array($va_filtro_combo["atributo"], $va_filtros_pos_aplicacao))
                continue;

            if (isset($va_filtro_combo["operador"]))
                $va_filtro[$va_filtro_combo["atributo"]] = [$va_filtro_combo["valor"], $va_filtro_combo["operador"]];
            else
                $va_filtro[$va_filtro_combo["atributo"]] = $va_filtro_combo["valor"];
        }
    }
    
    if (isset($pa_parametros_campo["objeto"]))
    {
        if (isset($pa_parametros_campo["parametros_inicializacao"]))
        {
            $vo_objeto = new $pa_parametros_campo["objeto"]($pa_parametros_campo["parametros_inicializacao"]);
        }
        else
        {
            $vs_id_objeto = $pa_parametros_campo["objeto"];
            $vo_objeto = new $vs_id_objeto($vs_id_objeto);
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

//var_dump(get_class($vo_objeto), $va_filtro);
        
        $this->objects = $vo_objeto->ler_lista($va_filtro, $vs_visualizacao, $vn_primeiro_registro, $vn_numero_maximo_itens, ($pa_parametros_campo["ordenacao"] ?? null), null, null, 1, false);

        //var_dump($this->objects);

        if (count($va_filtros_pos_aplicacao))
        {
            foreach($this->objects as $va_item)
            {
                $va_items_hierarquia[] = $va_item;

                while (isset($va_item[$vs_atributo_hierarquico]))
                {
                    $va_items_hierarquia[] = $va_item[$vs_atributo_hierarquico];

                    $va_item = $va_item[$vs_atributo_hierarquico];
                }
            }

            $va_itens_lista = array();

            foreach ($va_items_hierarquia as $va_item)
            {
                $vb_adicionar_item_lista = false;
                $vb_verificou_filtros = false;

                foreach ($va_dependencias as $va_dependencia)
                {
                    if (!in_array($va_dependencia["campo"], $va_filtros_pos_aplicacao))
                        continue;

                    if (!isset($pa_filtro_listagem[$va_dependencia["campo"]]) && isset($va_dependencia["obrigatoria"]) && $va_dependencia["obrigatoria"])
                    {
                        $vb_verificou_filtros = true;
                        $vb_adicionar_item_lista = false;

                        break;
                    }

                    if (!isset($pa_filtro_listagem[$va_dependencia["campo"]]) && (!isset($va_dependencia["obrigatoria"]) || !$va_dependencia["obrigatoria"]))
                        continue;

                    $vs_atributo = $va_dependencia["atributo_pos_aplicacao"] ?? $va_dependencia["atributo"];
    
                    if (ler_valor1($vs_atributo, $va_item) == $pa_filtro_listagem[$va_dependencia["campo"]])
                        $vb_adicionar_item_lista = true;
                    else
                    {
                        $vb_adicionar_item_lista = false;
                    }

                    $vb_verificou_filtros = true;

                    if (!$vb_adicionar_item_lista) break;
                }

                if ($vb_verificou_filtros && !$vb_adicionar_item_lista) continue;
                
                foreach ($pa_parametros_campo["filtro"] as $va_filtro_combo)
                {
                    if (!in_array($pa_parametros_campo["atributo"], $va_filtros_pos_aplicacao))
                        continue;

                    if ($va_filtro_combo["operador"] == "<=>")
                    {
                        if (!isset($va_item[$va_filtro_combo["atributo"]]))
                            $vb_adicionar_item_lista = true;
                    }
                    else
                    {
                        if (ler_valor1($va_filtro_combo["atributo_pos_aplicacao"], $va_item) == $va_filtro_combo["valor"])
                            $vb_adicionar_item_lista = true;
                    }
                }

                if ($vb_adicionar_item_lista)
                    $va_itens_lista[] = $va_item;
            }

            $this->objects = $va_itens_lista;
        }
    }
    
    foreach($this->objects as $va_item)
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

            if (!is_array($pa_parametros_campo["atributos"][$va_keys_atributos[1]]))
                $vs_campo_item_lista_value = $pa_parametros_campo["atributos"][$va_keys_atributos[1]];
            else
            {
                $vs_campo_item_lista_value = $va_keys_atributos[1];
                $vs_campo_hierarquia = $pa_parametros_campo["atributos"][$va_keys_atributos[1]]["hierarquia"];
            }

            // Temos que generalizar essa montagem numa função
            //////////////////////////////////////////////////

            $va_item_lista_value = array();

            while ($vb_ler_hierarquia)
            {
                $vs_valor_item_lista = $this->ler_valor_textual($va_item, $vs_campo_item_lista_value);
                
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

            //////////////////////////////////////////////////
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

    if (count($this->itens))
        asort($this->itens);

    // Se atributo_inverso está configurado, vamos verificar
    // se existem relacionamentos não criados entre o filtro 
    // e o objeto que ele filtra
    ////////////////////////////////////////////////////////

    // if (isset($pa_parametros_campo["objeto_filtrado"]) && (!isset($pa_filtro_listagem[$pa_parametros_campo["atributo"]])) && count($this->objects))
    // {
    //     $vo_objeto_filtrado = new $pa_parametros_campo["objeto_filtrado"]('');

    //     $va_atributos = explode(",", $pa_parametros_campo["atributo"]);

    //     $pa_filtro_listagem[$va_atributos[0]] = ["0", "_EXISTS_"];

    //     $vn_numero_relacionamentos = $vo_objeto_filtrado->ler_numero_registros($pa_filtro_listagem);
    
    //     if ($vn_numero_relacionamentos)
    //         $this->adicionar_item("_NO_", "[Sem atribução]");
    // }
    
    ////////////////////////////////////////////////////////
}

public function adicionar_item($ps_key, $ps_valor)
{
    if (!isset($this->itens))
        $this->itens = array();

    $this->itens[$ps_key] = $ps_valor;
}

public function build(&$pa_valores_form=null, $pa_parametros_campo=array(), $ps_path_campo_filtro="")
{
    $vs_tela = $this->get_tela();
    $vs_ui_element = $this->get_ui_element();

    $vb_pode_exibir = $this->verificar_exibicao($pa_valores_form, $pa_parametros_campo);

    $this->preencher($pa_valores_form, $pa_parametros_campo);
    $va_itens_campo = $this->get_itens();

    $vs_valor_campo = "";
    if (isset($pa_valores_form[$pa_parametros_campo["atributo"]]))
    {
        if (is_array($pa_valores_form[$pa_parametros_campo["atributo"]]) || ($pa_valores_form[$pa_parametros_campo["atributo"]] == 0))
            $vs_valor_campo = "_NO_";
        else
            $vs_valor_campo = $pa_valores_form[$pa_parametros_campo["atributo"]];
    }
    
    if ($ps_path_campo_filtro != "")
    {
        require $ps_path_campo_filtro;
    }
    else
    {
        require dirname(__FILE__)."/../../../app/components/campo_filtro_lateral.php";
    }
    
}

}