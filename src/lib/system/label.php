<?php

require_once config::get(["pasta_vendors"]) . "autoload.php";
require_once config::get(["pasta_vendors"]) . "fpdf/fpdf2file.php";

class label extends FPDF2File
{

    protected $vs_to_encoding = 'windows-1252';
    protected $vn_current_item = 0;
    protected $vn_current_label = 1;
    protected $vs_text_attributes = '';
    protected $vs_barcode_text = '';

    public $vs_title = 'Etiquetas';
    public $vs_from_encoding = 'UTF-8';
    public $vn_gap;
    public $vb_has_barcode = false;
    public $vn_initial_label;
    public $va_itens;
    public $vn_label_per_row;
    public $vn_label_per_page;
    public $vn_label_width;
    public $vn_label_height;
    public $vn_label_internal_left_margin;
    public $vn_label_internal_top_margin;
    public $vn_margin_top = 10;
    public $vn_margin_left = 4;
    public $vn_start_row;
    public $vn_start_col;

    function __construct($ps_file_path, $orientation = 'P', $unit = 'mm', $size = 'Letter')
    {
        $this->Open($ps_file_path);
        parent::__construct($orientation, $unit, $size);
    }

    function process()
    {

        $this->set_meta_tags();
        $this->set_font();
        $this->set_page();

        while ($this->vn_current_item < count($this->va_itens))
        {
            $vb_new_cell = false;
            $this->set_barcode_text();
            $this->set_text_attributes();

            $x_current_label = $this->GetX();
            $y_current_label = $this->GetY();

            if (!empty($this->vs_text_attributes))
            {
                $this->add_label();
                $vb_new_cell = true;
            }
            else
            {
                $this->vn_current_item++;
            }

            $x_next_label = $this->GetX();
            $y_next_label = $this->GetY();

            if (($this->vn_current_label >= $this->vn_initial_label) && $vb_new_cell)
            {
                $this->add_content($x_current_label, $y_current_label);
                $this->vn_current_item++;
            }


            if ($vb_new_cell)
            {
                $this->SetXY($x_next_label, $y_next_label);
                $this->add_gap();
                $this->vn_current_label++;
            }
        }
    }

    private function add_label()
    {
        $this->Cell($this->vn_label_width, $this->vn_label_height);
    }

    private function add_gap()
    {
        if ($this->vn_current_label % $this->vn_label_per_row == 0)
        {
            $this->Ln($this->vn_label_height);
        }
        else
        {
            $this->Cell($this->vn_gap, $this->vn_label_height);
        }

        if ($this->vn_current_label % $this->vn_label_per_page == 0)
        {
            $this->AddPage();
        }
    }

    protected function add_content($x, $y)
    {
        $vn_line_height = 4;
        $y_initial = $y;
        $w = 0;

        if ($this->vb_has_barcode)
        {
            $w = $this->vn_label_width / 3 - $this->vn_label_internal_left_margin;
            $h = $this->vn_label_height / 3 - $this->vn_label_internal_top_margin;
            $x = $x + $this->vn_label_internal_left_margin;
            $y = $y + ($this->vn_label_height / 2) - ($h / 2);

            $this->SetXY($x, $y);
            $this->add_barcode($w, $h);
        }

        $x = $x + $w + $this->vn_label_internal_left_margin;
        $vn_lines = count(explode("\n", $this->vs_text_attributes)) - 2;

        $vn_available_height = $this->vn_label_height - ($this->vn_label_internal_top_margin * 2);
        $y = (($vn_available_height - ($vn_lines * $vn_line_height)) / 2) + $y_initial;

        $this->SetXY($x, $y);
        $this->MultiCell($this->vn_label_width - $this->vn_label_internal_left_margin, $vn_line_height, $this->vs_text_attributes, 0, 'L');
    }

    protected function add_barcode($w, $h)
    {
        $vo_bar_code_generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $bar_code = $vo_bar_code_generator->getBarcode($this->vs_barcode_text, $vo_bar_code_generator::TYPE_CODE_128);
        $vs_barcode_path = config::get(["pasta_media", "temp"]) . "barcode" . time() . ".png";

        if ($bar_code)
        {
            file_put_contents($vs_barcode_path, $bar_code);
            $this->Image($vs_barcode_path, $this->GetX(), $this->GetY(), $w, $h, 'PNG');
        }
    }

    function encode($vs_string)
    {
        return iconv($this->vs_from_encoding, $this->vs_to_encoding . '//TRANSLIT//IGNORE', $vs_string);
    }

    protected function process_attributes()
    {
        $this->vs_text_attributes = '';
        foreach ($this->va_itens[$this->vn_current_item]["atributos"] as $va_attribute)
        {
            if (isset($va_attribute["valor"]) && $va_attribute["valor"] != "" && $va_attribute["exibir"])
            {
                $this->vs_text_attributes .= $va_attribute["valor"] . "\n";
            }
        }
    }

    protected function set_text_attributes()
    {
        $this->process_attributes();
        $this->vs_text_attributes = $this->encode($this->vs_text_attributes);
    }

    protected function set_barcode_text()
    {
        if (isset($this->va_itens[$this->vn_current_item][$this->va_itens[$this->vn_current_item]["_objeto"] . "_codigo"]))
        {
            $this->vs_barcode_text = str_pad($this->va_itens[$this->vn_current_item][$this->va_itens[$this->vn_current_item]["_objeto"] . "_codigo"], 12, "0", STR_PAD_LEFT);
        }
    }

    public function set_font()
    {
        $this->SetFont('Arial', '', 10);
    }

    function set_meta_tags()
    {
        $this->SetTitle($this->encode($this->vs_title));
        $this->SetAuthor($this->encode(config::get(["nome_instituicao"])));
        $this->SetCreator($this->encode("Páramo"));
        $this->SetSubject($this->encode($this->vs_title));
        $this->SetKeywords($this->encode($this->vs_title));
    }

    private function set_page()
    {
        $this->SetMargins($this->vn_margin_left, $this->vn_margin_top);
        $this->set_label_per_row();
        $this->set_label_per_page();
        $this->set_initial_label();
        $this->AddPage();
        $this->SetAutoPageBreak(false);
    }

    private function set_label_per_row()
    {
        $this->vn_label_per_row = intval($this->GetPageWidth() / ($this->vn_label_width + $this->vn_gap));
    }

    private function set_label_per_page()
    {
        $this->vn_label_per_page = $this->vn_label_per_row * intval(($this->GetPageHeight() - $this->vn_margin_top) / $this->vn_label_height);
    }

    private function set_initial_label()
    {
        $this->vn_initial_label = ($this->vn_start_row * $this->vn_label_per_row) - ($this->vn_label_per_row - $this->vn_start_col);
    }

}
