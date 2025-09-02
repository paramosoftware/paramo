<?php
function ler_valor1($ps_atributo, $pa_item, $pa_opcoes_campo=null, $pn_numero_itens_campo=null)
{
    $va_atributos = array();

    if (!isset($pn_numero_itens_campo))
    {   
        if (isset($pa_opcoes_campo["formato"]["numero_maximo_itens"]))
            $pn_numero_itens_campo = $pa_opcoes_campo["formato"]["numero_maximo_itens"];
    }

    if (!isset($ps_atributo))
    {
        return "";
    }

    if (isset($pa_opcoes_campo["formato"]["data"]))
    {
        $vo_data = new Periodo;

        if (isset($pa_item[$ps_atributo . "_data_inicial"]))
            $vo_data->set_data_inicial($pa_item[$ps_atributo . "_data_inicial"]);
        elseif (isset($pa_item[$ps_atributo]))
            $vo_data->set_data_inicial($pa_item[$ps_atributo]);

        if (isset($pa_item[$ps_atributo . "_data_final"]))
            $vo_data->set_data_final($pa_item[$ps_atributo . "_data_final"]);
        else
            $vo_data->set_data_final($vo_data->get_data_inicial());

        if (isset($pa_item[$ps_atributo . "_presumido"]))
            $vo_data->set_presumido($pa_item[$ps_atributo . "_presumido"]);

        if (isset($pa_item[$ps_atributo . "_sem_data"]))
            $vo_data->set_sem_data($pa_item[$ps_atributo . "_sem_data"]);

        if ($pa_opcoes_campo["formato"]["data"] == "ano")
            $va_data = $vo_data->get_data_exibicao();

        elseif ($pa_opcoes_campo["formato"]["data"] == "completo")
            $va_data = $vo_data->get_data_exibicao();

        return $va_data;
    }
    else
    {
        if (isset($pa_item[$ps_atributo]))
        {
            if (is_array($pa_item[$ps_atributo]))
                $va_atributos = $pa_item[$ps_atributo];
            else
                $va_atributos = $pa_item;
        }
        elseif (isset($pa_opcoes_campo["nao_busca_relacionamento"]) && $pa_opcoes_campo["nao_busca_relacionamento"])
        {
            return "";
        }
        else
        {
            // Tem que tratar o relacionamento que pode dar origem a vários campos

            $va_atributos[] = $pa_item;            
        }
    }

    $va_expressao_campo = array();
    $vs_valor_expressao = "";

    if (isset($pa_opcoes_campo["formato"]["expressao"]))
        $va_expressao_campo = $pa_opcoes_campo["formato"]["expressao"];
    elseif (isset($pa_opcoes_campo["formato"]["campo"]))
    {
        if (!isset($pa_item[$ps_atributo])) return "";
        
        $va_expressao_campo[] = $pa_opcoes_campo["formato"]["campo"];
    }
    else
        $va_expressao_campo[] = $ps_atributo;
    
    if (count($va_expressao_campo))
    {
        $va_item_montado = array();

        // Este loop acontece para o caso de haver diferentes itens escolhidos para um mesmo campo
        $contador = 0;

        foreach($va_atributos as $vs_key_atributo => $va_atributo)
        {
            // Prevendo mais um nível de array com o mesmo nome do relacionamento
            // que representa cada objeto relacionado

            if (isset($va_atributo[$ps_atributo]))
            {
                // Se a coluna do relacionamento é um objeto, ele vem como um outro elemento do array
                // Preciso juntar tudo num mesmo elemento para poder ter todos os valores num mesmo nível
                
                $va_atributo_reconstruido = $va_atributo[$ps_atributo];

                if (is_array($va_atributo_reconstruido))
                {
                    $contador_subatributo = 1;
                    foreach ($va_atributo as $vs_key_subatributo => $va_subatributo)
                    {
                        if ($contador_subatributo > 1)
                            $va_atributo_reconstruido[$vs_key_subatributo] = $va_subatributo;

                        $contador_subatributo++;
                    }
                }

                $va_atributo = $va_atributo_reconstruido;
            }

            //////////////////////////////////////////////////////////////////////

            $va_item_expressao = array();

            if (!is_array($va_atributo))
                $va_atributo_temp = $va_atributos;
            else
                $va_atributo_temp = $va_atributo;

            // $vb_busca_profundidade serve para controlar a busca hierárquica, se ela for acontecer

            $vb_busca_profundidade = true;
            $vn_contador_nivel = 0;
            $va_ramo = array();          

            if (isset($pa_opcoes_campo["formato"]["hierarquia"]) && isset($pa_opcoes_campo["formato"]["link"]))
            {
                $va_atributo_construir_ramo = $va_atributo_temp;

                while ($vb_busca_profundidade)
                {
                    $va_ramo[$vn_contador_nivel] = $va_atributo_construir_ramo[$pa_opcoes_campo["formato"]["link"]["codigo"]];
                    
                    if (isset($va_atributo_construir_ramo[$pa_opcoes_campo["formato"]["hierarquia"]]))
                        $va_atributo_construir_ramo = $va_atributo_construir_ramo[$pa_opcoes_campo["formato"]["hierarquia"]];
                    else
                        $vb_busca_profundidade = false;

                    $vn_contador_nivel--;
                }
            }

            $vb_busca_profundidade = true;

            while ($vb_busca_profundidade)
            {
                $va_partes_expressao = array();

                foreach($va_expressao_campo as $vs_parte_expressao)
                {
                    $vb_parte_constante = false;
                    $vb_pode_exibir = true;
                    $vb_data = false;

                    if (is_array($vs_parte_expressao))
                    {                     
                        $vs_valor_condicao = "";
                        $vs_valor_desejado_condicao = "";
                        
                        if (isset($vs_parte_expressao["condicao"]))
                        {
                            $vs_valor_condicao = ler_parte_expressao($vs_parte_expressao["condicao"][0], $va_atributo_temp);
                            $vs_valor_desejado_condicao = $vs_parte_expressao["condicao"][1];
                            
                            $vb_pode_exibir = false;
                        }
                        
                        if (isset($vs_parte_expressao["constante"]))
                            $vb_parte_constante = true;

                        if (isset($vs_parte_expressao[1]) && $vs_parte_expressao[1] == "_data_")
                            $vb_data = true;
                        
                        if ( ($vs_valor_desejado_condicao == "<>vazio") && (trim($vs_valor_condicao) != "") )
                            $vb_pode_exibir = true;
                        elseif ($vs_valor_condicao == $vs_valor_desejado_condicao)
                            $vb_pode_exibir = true;

                        $vs_parte_expressao = reset($vs_parte_expressao);
                    }
                    
                    if ($vb_pode_exibir)
                    {
                        if (!$vb_parte_constante)
                            $va_partes_expressao[] = ler_parte_expressao($vs_parte_expressao, $va_atributo_temp, $vb_data);
                        else
                            $va_partes_expressao[] = $vs_parte_expressao;
                    }
                }

                $vs_expressao_montada = join("", $va_partes_expressao);

                if (isset($pa_opcoes_campo["formato"]["link"]) && trim($vs_expressao_montada))
                {
                    if (isset($pa_opcoes_campo["formato"]["link"]["destino"]))
                    {
                        $vn_objeto_link_codigo = $va_atributo_temp[$pa_opcoes_campo["formato"]["link"]["codigo"]];
                        $vs_url = "navegar.php?obj=" . $pa_opcoes_campo["formato"]["link"]["objeto"] . "&cod=" . $vn_objeto_link_codigo;
                        $vs_expressao_montada = '<a href="'. $vs_url . '">' . $vs_expressao_montada . '</a>';
                    }

                    if (isset($pa_opcoes_campo["formato"]["link"]["url"]))
                    {
                        $vs_padrao_url = "padrao";
                        if (isset($pa_opcoes_campo["formato"]["link"]["padrao"]))
                            $vs_padrao_url = $pa_opcoes_campo["formato"]["link"]["padrao"];
                        
                        $vs_url = $pa_opcoes_campo["formato"]["link"]["url"];
                        $contador = 1;

                        foreach($pa_opcoes_campo["formato"]["link"]["parametros"] as $vs_parametro => $vs_campo_parametro)
                        {
                            if ($vs_padrao_url == "padrao")
                            {
                                if ($contador == 1)
                                    $vs_url .= "?" . $vs_parametro . "=" . (empty($va_atributo_temp[$vs_campo_parametro]) ? $vs_campo_parametro : $va_atributo_temp[$vs_campo_parametro]);
                                else
                                    $vs_url .= "&" . $vs_parametro . "=" . (empty($va_atributo_temp[$vs_campo_parametro]) ? $vs_campo_parametro : $va_atributo_temp[$vs_campo_parametro]);
                            }
                            else
                                $vs_url .= "/" . $va_atributo_temp[$vs_campo_parametro];

                             $contador++;
                        }

                        $vs_expressao_montada = '<a href="'. $vs_url . '">' . $vs_expressao_montada . '</a>';
                    }
                    elseif (isset($pa_opcoes_campo["formato"]["link"]["get_link"]))
                    {
                        $vo_objeto = new $pa_opcoes_campo["formato"]["link"]["objeto"];

                        $vn_objeto_link_codigo = $va_atributo_temp[$pa_opcoes_campo["formato"]["link"]["codigo"]];

                        $vs_expressao_montada = $vo_objeto->get_link($vn_objeto_link_codigo, $vs_expressao_montada, $va_ramo);
                    }
                    else
                    {
                        $vs_expressao_montada = '<a target="_blank" href="'. $vs_expressao_montada . '">' . $vs_expressao_montada . '</a>';
                    }
                }
                elseif (isset($pa_opcoes_campo["formato"]["booleano"]) && ($vs_expressao_montada != ""))
                {
                    if ($vs_expressao_montada)
                        $vs_expressao_montada = "Sim";
                    else
                        $vs_expressao_montada = "Não";
                }

                // Adiciona sempre no começo do array
                array_unshift($va_item_expressao, $vs_expressao_montada);

                if (isset($pa_opcoes_campo["formato"]["hierarquia"]))
                {
                    if (isset($va_atributo_temp[$pa_opcoes_campo["formato"]["hierarquia"]]))
                    {
                        $va_atributo_temp = $va_atributo_temp[$pa_opcoes_campo["formato"]["hierarquia"]];

                        // Adiciona o separador sempre no começo do array //
                        ////////////////////////////////////////////////////

                        array_unshift($va_item_expressao, $pa_opcoes_campo["formato"]["separador"]);
                    }
                    else
                        $vb_busca_profundidade = false;
                }
                else
                    $vb_busca_profundidade = false;

                $vn_contador_nivel++;
            }

            if (join("", $va_item_expressao))
                $va_item_montado[] = join("", $va_item_expressao);

            // Se $va_atributo não é array (isto é, o campo só pode ter 1 valor), só busca o valor uma única vez)
            if (!is_array($va_atributo))
                break;

            $contador++;

            if (isset($pn_numero_itens_campo))
            {
                if ($contador == $pn_numero_itens_campo)
                    break;
            }
        }
        
        if (isset($pa_opcoes_campo["separador"]))
            $vs_valor_expressao = join($pa_opcoes_campo["separador"], $va_item_montado);
        else
            $vs_valor_expressao = join(" | ", $va_item_montado);

        if (isset($pn_numero_itens_campo))
        {
            // Se houve truncamento...

            if ( (count($va_atributos) > $pn_numero_itens_campo) && isset($pa_opcoes_campo["formato"]["termo_complementar"]) )
                $vs_valor_expressao .= $pa_opcoes_campo["formato"]["termo_complementar"];
        }

        return $vs_valor_expressao;
    }
}

function ler_parte_expressao($ps_parte_expressao, $pa_atributo, $pb_data=false)
{   
    $va_campo_nome = explode("_0_", $ps_parte_expressao);
    $vs_valor_parte_expressao = $pa_atributo;

    foreach ($va_campo_nome as $vs_campo_nome)
    {
        if (isset($vs_valor_parte_expressao[$vs_campo_nome]))
        {
            $vs_valor_parte_expressao = $vs_valor_parte_expressao[$vs_campo_nome];

            if (isset($vs_valor_parte_expressao[0][$vs_campo_nome]))
                $vs_valor_parte_expressao = $vs_valor_parte_expressao[0][$vs_campo_nome];
        }
        elseif (isset($vs_valor_parte_expressao[0]))
        {
            $vs_valor_parte_expressao = $vs_valor_parte_expressao[0][$vs_campo_nome];
        }
        else
        {
            $vs_valor_parte_expressao = "";
            break;
        }
    }

    while (is_array($vs_valor_parte_expressao))
    {
        $vs_valor_temp = reset($vs_valor_parte_expressao);
        $vs_valor_parte_expressao = $vs_valor_temp;
    }

    if ((string) $vs_valor_parte_expressao != "")
    {
        if ($pb_data)
        {
            $vo_data = new Periodo;

            $vo_data->set_data_inicial($vs_valor_parte_expressao);
            $vo_data->set_data_final($vs_valor_parte_expressao);

            $vs_valor_parte_expressao = $vo_data->get_data_exibicao();

            $vs_hora_completa = $vo_data->get_hora_completa();
            if ($vs_hora_completa)
                $vs_valor_parte_expressao .=  " " . $vo_data->get_hora_completa();
        }

        return $vs_valor_parte_expressao;
    }
    else
        return "";
}

?>