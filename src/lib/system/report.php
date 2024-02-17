<?php

require_once config::get(["pasta_vendors"]) . 'fpdf/easytable/exfpdf.php';
require_once config::get(["pasta_vendors"]) . 'fpdf/easytable/easyTable.php';

class report extends exFPDF
{
    protected $vs_to_encoding = 'windows-1252';
    protected $va_table = array();
    protected $vn_total = 0;

    public $row_data = null;

    public $vs_title = 'Relatório';
    public $va_subheadings = array();
    public $vs_from_encoding = 'UTF-8';

    public $va_itens = array();

    public $vs_num_cols = '';

    public $vn_margin_top = 20;
    public $vn_margin_left = 15;

    public $va_table_header = array();
    public $vs_sum_key = 'Q';
    public $vb_sum_values = true;
    public $vb_percentage = true;
    public $vb_alternate_row_color = true;
    public $va_itens_keys = array();
    public $vs_new_table_on = null;
    public $vs_group_on = null;

    function __construct($ps_file_path, $orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        $this->Open($ps_file_path);
        parent::__construct($orientation, $unit, $size);
    }

    function add_table()
    {
        $table = new easyTable($this, $this->vs_num_cols, 'border:1; border-color:#ccc; paddingX:2; paddingY:2;');

        foreach ($this->va_table as $va_row)
        {

            foreach ($va_row as $va_cell)
            {
                $table->easyCell($va_cell["data"], $va_cell["style"]);
            }

            $table->printRow();
        }

        $table->endTable(5);
    }

    function add_first_page_header()
    {
        $table = new easyTable($this, '%{80,20}', 'border:B; paddingY:2;');

        $vs_cell = '<s "font-size:16; font-style:B;">' . $this->encode($this->vs_title) . '</s>';
        $vs_cell .= "\n";

        foreach($this->va_subheadings as $vs_subtitle)
        {
            $vs_cell .= '<s "font-size:11; font-style:B;>' . $this->encode($vs_subtitle["label"]) . ': </s>';
            $vs_cell .= '<s "font-size:11;>' . $this->encode($vs_subtitle["value"]) . '</s>';
            $vs_cell .= "\n";
        }

        $table->easyCell($vs_cell, 'align:L; valign:M; line-height:1.2;');

        $vs_logo_path = config::get(["pasta_assets", "custom", "images"]) . 'logo.png';
        $table->easyCell('', 'img:' . $vs_logo_path . ', h20; align:R; valign:M;');

        $table->printRow();
        $table->endTable(5);
    }

    function encode($vs_string)
    {
        return iconv($this->vs_from_encoding, $this->vs_to_encoding . '//TRANSLIT//IGNORE', $vs_string);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFontSize(9);
        $this->Cell(0, 10, $this->encode(config::get(["nome_instituicao"]) . ' | Gerado em ' . date("d/m/Y") . ' às ' . date("H:i:s")), 'T', 0, 'L');
        $this->Cell(0, 10, $this->encode('Página ') . $this->PageNo() , 0, 0, 'R');
    }

    function get_cell($ps_data, $ps_style = ""): array
    {
        return [
            "data" => $this->encode($ps_data),
            "style" => $ps_style
        ];
    }

    function process()
    {
        $this->set_meta_tags();
        $this->set_margins();
        $this->set_font();
        $this->set_page();
        if ($this->page == 1)
        {
            $this->add_first_page_header();
        }
        $this->process_itens();
    }

    function process_itens()
    {
        $this->va_table = array();

        if ($this->vs_new_table_on)
        {
            $va_itens = array();

            foreach ($this->va_itens as $va_item)
            {
                $va_itens[$va_item[$this->vs_new_table_on]][] = $va_item;
            }

            foreach ($va_itens as $vs_key => $va_item)
            {
                $this->va_table = array();
                $this->va_itens = $va_item;

                if ($this->vb_sum_values || $this->vb_percentage)
                {
                    $this->vn_total = array_sum(array_column($this->va_itens, $this->vs_sum_key));
                }

                $this->va_table[] = [$this->get_cell(
                    $vs_key,
                    "align:L; valign:M; font-style:B; font-size:14; bgcolor:#aaa; colspan:" .
                    ($this->vb_percentage ? count($this->va_table_header) + 1 : count($this->va_table_header))
                )];

                $this->set_table_header();
                $this->set_table_rows();
                $this->set_table_sum();

                $this->add_table();
            }
        }
        else
        {

            if ($this->vb_sum_values || $this->vb_percentage)
            {
                $this->vn_total = array_sum(array_column($this->va_itens, $this->vs_sum_key));
            }

            $this->set_table_header();
            $this->set_table_rows();
            $this->set_table_sum();

            $this->add_table();
        }
    }

    function set_margins()
    {
        $this->SetMargins($this->vn_margin_left, $this->vn_margin_top);
    }

    function set_meta_tags()
    {
        $this->SetTitle($this->encode($this->vs_title));
        $this->SetAuthor($this->encode(config::get(["nome_instituicao"])));
        $this->SetCreator($this->encode("Páramo"));
        $this->SetSubject($this->encode($this->vs_title));
        $this->SetKeywords($this->encode($this->vs_title));
    }

    function set_font()
    {
        $this->SetFont('Arial', '', 12);
    }

    function set_page()
    {
        $this->AddPage();
    }

    function set_table_header()
    {
        $va_header = array();
        $vs_base_style = "valign:M; font-style:B; ";
        $vs_cell_style = $vs_base_style;

        $vn_i = 0;
        foreach ($this->va_table_header as $vs_header)
        {
            $vs_cell_style = $vn_i == 0 ? $vs_base_style . "align:L; " : $vs_base_style . "align:R; ";

            $va_header[] = $this->get_cell($vs_header, $vs_cell_style);
            $vn_i++;
        }

        if ($this->vb_percentage)
        {
            $va_header[] = $this->get_cell("%", $vs_cell_style);
        }

        $this->va_table[] = $va_header;
    }

    function set_table_rows()
    {
        $va_rows = array();
        $va_group = array();

        $vn_row = 0;
        $vs_base_style = "valign:M; ";

        foreach ($this->va_itens as $va_item)
        {
            $vs_cell_style = $this->vb_alternate_row_color && $vn_row % 2 == 0 ? $vs_base_style . "bgcolor:#eee; " : $vs_base_style;

            $va_row = array();

            if ($this->vs_group_on)
            {
                $vs_group_value = $va_item[$this->vs_group_on] == "" ? "Sem rótulo" : $va_item[$this->vs_group_on];

                if (!in_array($vs_group_value, $va_group))
                {
                    $vs_style = $vs_cell_style . "bgcolor:#eee; font-style:B; colspan:" .
                        ($this->vb_percentage ? count($this->va_table_header) + 1 : count($this->va_table_header));

                    $va_rows[] = [$this->get_cell($vs_group_value, $vs_style)];
                }

                $va_group[] = $vs_group_value;
            }


            foreach ($this->va_itens_keys as $vn_key => $vs_key)
            {

                $vs_cell_style .= $vn_key == 0 ? "align:L; " : "align:R; ";

                if ($vs_key == $this->vs_group_on)
                {
                    continue;
                }

                $vs_extra_style = $this->vs_group_on != '' && $vs_key == $this->va_itens_keys[0] ? "paddingX:5; " : "";

                $value = $va_item[$vs_key] ?? "Sem rótulo";
                $va_row[] = $this->get_cell($value, $vs_cell_style . $vs_extra_style);
            }

            if ($this->vb_percentage)
            {
                $vn_percent = round($va_item[$this->vs_sum_key] / $this->vn_total * 100, 2);
                $vn_percent = str_replace('.', ',', $vn_percent);
                $va_row[] = $this->get_cell($vn_percent, $vs_cell_style);
            }

            $va_rows[] = $va_row;
            $vn_row++;
        }

        $this->va_table = array_merge($this->va_table, $va_rows);
    }

    function set_table_sum()
    {
        if (!$this->vb_sum_values && !$this->vb_percentage)
        {
            return;
        }

        $va_sum = array();

        $vs_base_style = "align:R; valign:M; font-style:B; ";

        $va_sum[] = $this->get_cell("Total", $vs_base_style . "bgcolor:#ccc;");
        $va_sum[] = $this->get_cell($this->vn_total, $vs_base_style);

        if ($this->vb_percentage)
        {
            $va_sum[] = $this->get_cell("100", $vs_base_style);
        }

        $this->va_table[] = $va_sum;
    }


}