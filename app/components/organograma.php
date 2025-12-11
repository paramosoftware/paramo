
<?php
define("NUMERO_ITENS_PAGINA_LISTAGEM", 1000);
$vb_retornar_ramos_inferiores = true;
$vb_expandir_niveis_hierarquicos = true;

$va_filtros = $vo_objeto->inicializar_filtros_navegacao();
$va_organograma_filtros = null;
foreach ($va_filtros as $vs_campo => $va_filtro)
{
    if (!empty($va_filtro['usar_organograma']))
    {
        $va_filtro['sem_valor'] = false;
        $va_organograma_filtros = $va_filtro;
        break;
    }
}

$vn_selected = 0;
$va_organograma['main_field'] = htmlspecialchars($vs_recurso_sistema_nome_plural);
$va_organograma['children'] = [];
$vs_filtro_nome = $va_organograma_filtros["nome"] ?? '';

if (!empty($va_organograma_filtros)) 
{
    $vn_selected = $_GET[$va_organograma_filtros["nome"]] ?? '';
    $vo_filtro_obj = new html_combo_input($vs_id_objeto_tela, $va_organograma_filtros["nome"]);
    $vo_filtro_obj->preencher($_GET, $va_organograma_filtros);
    $vb_found = false;
    $va_itens_select = $vo_filtro_obj->get_itens();

    foreach ($va_itens_select as $vn_chave => $vs_valor) 
    {
        if ($vn_chave == $vn_selected) 
        {
            $vb_found = true;
            $va_organograma['main_field'] = $vs_valor;
            break;
        }
    }

    if (!$vb_found) 
    {
        $va_organograma['main_field'] = $va_itens_select[array_key_first($va_itens_select)];
        $vn_selected = array_key_first($va_itens_select);
    }

    $va_parametros_filtros_consulta[$va_organograma_filtros["nome"]] = $vn_selected;
}


require dirname(__FILE__)."/../functions/montar_listagem.php";
$vn_level_count = [];
foreach ($va_itens_listagem as $va_item)
{
    $vn_nivel = $va_item['_nivel'] ?? 0;
    $va_item['className'] = 'orgchart-level-' . $vn_nivel;

    if (!isset($vn_level_count[$vn_nivel]))
    {
        $vn_level_count[$vn_nivel] = 0;
    }

    $vn_level_count[$vn_nivel]++;

    if ($vn_nivel == 0)
    {
        $va_organograma['children'][] = $va_item;
    }
    else
    {
        $va_pai = &$va_organograma;
        for ($i = 0; $i < $vn_nivel; $i++)
        {
            $vn_ultimo_indice = count($va_pai['children']) - 1;
            $va_pai = &$va_pai['children'][$vn_ultimo_indice];
        }
        if (!isset($va_pai['children']))
        {
            $va_pai['children'] = [];
        }
        $va_pai['children'][] = $va_item;
    }
}

$vn_vertical_level = 0;
foreach ($vn_level_count as $vn_level => $vn_count)
{
    if ($vn_count > 10)
    {
        $vn_vertical_level = $vn_level + 2;
        break;
    }
}
?>

<link rel="stylesheet" type="text/css" href="assets/libraries/orgchart/orgchart.min.css">
<style type="text/css">
    #chart-container {
        background: #fff;
        height: 70vh;
        margin: 0.5rem;
        text-align: center;
    }
    .orgchart .node .title {
        min-width: 155px;
        min-height: 60px; 
        white-space: normal;
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem;
        background-color: #C63D2F;
        color: #fff;
    }
    .orgchart .orgchart-level-0 .title {
        background-color: #E25E3E;
    }
    .orgchart .orgchart-level-1 .title {
        background-color: #FF9B50;
    }
    .orgchart .orgchart-level-2 .title {
        background-color: #ffbb6d;
    }
    .orgchart .orgchart-level-3 .title {
        background-color: #ffbd4cc5;
    }

</style>


<div class="body flex-grow-1 px-3">
    <div class="container">
        <div class="row mb-3">
            <div class="col-6 d-flex gap-3 align-items-start">
                <?php if (!empty($vo_filtro_obj)) $vo_filtro_obj->build($_GET, $va_organograma_filtros); ?>
            </div>

            <div class="col-6 mb-3 text-end align-items-start">
                <button type="button" class="btn btn-outline-primary" id="btn_organograma">
                    Voltar para a lista
                </button>
                <button type="button" class="btn btn-primary" id="btn_exportar">
                    Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="chart-container"></div>

<script type="text/javascript" src="assets/libraries/orgchart/orgchart.min.js"></script>
<script type="text/javascript" src="assets/libraries/orgchart/html2canvas.min.js"></script>
<script type="text/javascript">
    $(function() {
        const datasource = <?= json_encode($va_organograma); ?>;

        const orgChart = $('#chart-container').orgchart({
            'data' : datasource,
            'nodeTitle': 'main_field',
            'pan': true,
            'zoom': true,
            'verticalLevel': <?= $vn_vertical_level; ?>,
        });

        $('#<?= $vs_filtro_nome; ?>').change(function() {
            $("#form_lista").submit();
        });

        $('#btn_organograma').click(function() {
            let vs_url_organograma = "<?= $vs_url_base; ?><?= $vs_id_objeto_tela; ?>";
            window.location.href = vs_url_organograma;
        });

        $('#btn_exportar').click(function() {
            if (orgChart) {
                orgChart.export('<?= htmlspecialchars($vs_recurso_sistema_nome_plural); ?>-organograma');
            }
        });
    });
</script>
