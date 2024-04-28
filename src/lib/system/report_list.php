<?php

class report_list extends report
{

    protected $vn_image_rowspan = 0;
    protected $vs_image_path = '';
    protected $vn_rows_table = 0;
    public $vb_include_image = true;
    public $vs_image_position = 'left_side'; // first_row | left_side
    public $vb_break_row = false;


    public function __construct($ps_file_path, $orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($ps_file_path, $orientation, $unit, $size);
    }

    function add_table($pn_nivel = 0)
    {

        $vb_image_first_row = $this->vs_image_path && $this->vs_image_position == 'first_row';

        $this->vs_num_cols = $this->vs_image_path && $this->vs_image_position == 'left_side' ? '%{20, 20, 60}' : '%{20, 80}';

        $table = new easyTable($this, $this->vs_num_cols, 'width:' . (100-(5*$pn_nivel)) . '%; align:R; border:1; border-color:#ccc; paddingX:2; paddingY:2;' . ($this->vb_break_row ? ' split-row:true;' : ''));

        if ($this->vs_image_path)
        {
            $vn_image_rowspan = $this->vs_image_position == 'left_side' ? $this->vn_rows_table : ($this->vn_image_rowspan == 0 ? 1 : $this->vn_image_rowspan);
            $table->easyCell('', 'rowspan:' . $vn_image_rowspan  . '; img:' . $this->vs_image_path . ($vn_image_rowspan == 1 ? ', h20' : ''));
        }

        foreach ($this->va_table as $va_row)
        {
            if ($va_row["label"] == "" && !$vb_image_first_row)
            {
                $table->easyCell($va_row["value"], 'colspan:2; align:L');
            }
            elseif ($va_row["label"] == "" && $this->vs_image_path)
            {
                $table->easyCell($va_row["value"], 'align:L; valign:M');
            }
            else
            {
                $vb_remove_label = $this->vs_image_path && $this->vn_image_rowspan == 0;

                if (!$vb_remove_label)
                {
                    $table->easyCell($va_row["label"], 'align:L; valign:M; font-style:B; font-size:8');
                }

                $table->easyCell($va_row["value"], 'align:L; valign:M');
            }

            $table->printRow();
        }

        $table->endTable(5);
    }

    function process_itens()
    {
        foreach ($this->va_itens as $va_item)
        {
            $this->process_attributes($va_item);
            $this->add_table($va_item["_nivel"]);
        }
    }

    function process_attributes($pa_item)
    {

        $this->va_table = array();
        $this->vn_image_rowspan = 0;
        $this->vn_rows_table = 0;
        $this->vs_image_path = '';
        $va_attributes = array();

        if (isset($pa_item["id_field"]) && $pa_item["id_field"] != "")
        {
            $va_attributes[] = ["label" => "", "value" => $this->encode($this->remove_html_tags($pa_item["id_field"]))];
            $this->vn_image_rowspan++;
        }

        if (isset($pa_item["main_field"]) && $pa_item["main_field"] != "")
        {
            $va_attributes[] = ["label" => "", "value" => $this->encode($this->remove_html_tags($pa_item["main_field"]))];
            $this->vn_image_rowspan++;
        }

        if (isset($pa_item["descriptive_field"]) && $pa_item["descriptive_field"] != "")
        {
            $va_attributes[] = ["label" => "", "value" => $this->encode($this->remove_html_tags($pa_item["descriptive_field"]))];
            $this->vn_image_rowspan++;
        }

        if (isset($pa_item["representante_digital"]) && $pa_item["representante_digital"] != "")
        {
            $vs_image_path = config::get(["pasta_media", "images", "thumb"]) . $pa_item["representante_digital"];

            if (file_exists($vs_image_path) && $this->vb_include_image)
            {
                $this->vs_image_path = $vs_image_path;
            }
        }

        foreach ($pa_item["atributos"] as $va_atributos_item_listagem)
        {
            if ($va_atributos_item_listagem["valor"] != "" && $va_atributos_item_listagem["exibir"])
            {
                $va_attributes[] = ["label" => $this->encode($va_atributos_item_listagem["label"]), "value" => $this->encode($this->remove_html_tags($va_atributos_item_listagem["valor"]))];
                $this->vn_rows_table++;
            }
        }

        $this->vn_rows_table += $this->vn_image_rowspan;

        $this->va_table = $va_attributes;
    }


    function remove_html_tags($ps_text)
    {
        $ps_text = strip_tags($ps_text);
        return str_replace("&nbsp;", " ", $ps_text);
    }
}