<?php

    require_once dirname(__FILE__) . "/components/entry_point.php";

    header("Content-Disposition:attachment; filename=". $vs_id_objeto_tela . ".txt");
    
    if (isset($_POST["modo"]))
        $vs_modo = $_POST["modo"];
    
    if ($vs_modo == "ficha")
        $vn_objeto_codigo = $_POST["cod"];
        
    $vs_output = "out";
    require_once dirname(__FILE__). "/functions/montar_listagem.php" ;

    $va_linhas = array();
    $out = fopen('php://output', 'w');

    foreach($va_itens_listagem as $va_item_listagem)
    {
        $va_atributos = array();

        foreach($va_item_listagem["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["valor"] != "" && $va_atributos_item_listagem["exibir"])
                $va_atributos[] = $va_atributos_item_listagem["valor"];
        }

        fputcsv($out, $va_atributos);
    }

    fclose($out);
?>