<?php

class label_box extends label
{

    protected $vn_logo_width = 20;
    protected $vn_logo_height = 15;
    protected $vn_barcode_width = 30;
    protected $vn_barcode_height = 10;

    public function __construct($ps_file_path, $orientation = 'P', $unit = 'mm', $size = 'Letter')
    {
        parent::__construct($ps_file_path, $orientation, $unit, $size);
    }

    function add_content($x, $y)
    {
        $this->vn_label_internal_top_margin = 5;
        $this->vn_label_internal_left_margin = 3;
        $this->add_label_header($x, $y);
        $this->add_label_content($x, $y + $this->vn_label_height / 2);
    }


    function set_barcode_text()
    {
        $this->vs_barcode_text = $this->va_itens[$this->vn_current_item]["unidade_armazenamento_codigo"];
    }

    function process_attributes()
    {
        $this->vs_text_attributes = mb_strtoupper($this->va_itens[$this->vn_current_item]["main_field"]);
    }

    function add_label_header($x, $y)
    {
        $x += $this->vn_label_internal_left_margin + 1;
        $y += $this->vn_label_internal_top_margin;

        $this->SetXY($x, $y);

        if ($this->vb_has_barcode)
        {
            $this->add_barcode($this->vn_barcode_width, $this->vn_barcode_height);
        }

        $x += $this->vn_label_width - $this->vn_barcode_width;

        $this->SetXY($x, $y);
        $this->add_logo();
    }

    function add_label_content($x, $y)
    {
        $x = $x + $this->vn_label_internal_left_margin;
        $this->SetXY($x, $y);

        $vn_half_height = $this->vn_label_height / 2;
        $vn_font_size = $vn_half_height * 0.7;

        $this->SetFontSize($vn_font_size);
        $this->Write($vn_half_height, $this->vs_text_attributes);
    }

    function add_logo()
    {
        $vs_logo_path = config::get(["pasta_assets", "images"]) . explode('/', config::get(["logo"]))[2];
        $this->Image($vs_logo_path, null, null, $this->vn_logo_width, $this->vn_logo_height);
    }

}