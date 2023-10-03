<?php

class paginador
{

    private $numero_ultima_pagina;
    private $numero_registros;
    private $numero_registros_por_pagina;

    protected $visualizacoes = array();

    function __construct($pn_numero_registros = null, $pn_numero_registros_por_pagina = 20)
    {
        $this->numero_registros = $pn_numero_registros;
        $this->numero_registros_por_pagina = $pn_numero_registros_por_pagina;

        $va_campos_visualizacao = array();
        $va_campos_visualizacao["codigo"] = ["nome" => "codigo", "exibir" => false];
        $va_campos_visualizacao["valor"] = ["nome" => "valor"];

        $this->visualizacoes["lista"]["campos"] = $va_campos_visualizacao;
    }

    public function get_visualizacao($ps_visualizacao)
    {
        return $this->visualizacoes[$ps_visualizacao];
    }

    public function set_numero_registros($pn_numero_registros)
    {
        $this->numero_registros = $pn_numero_registros;
    }

    public function get_numero_registros()
    {
        return $this->numero_registros;
    }

    public function get_numero_ultima_pagina()
    {
        $vn_numero_paginas_temp = intval($this->numero_registros / $this->numero_registros_por_pagina);

        if (($this->numero_registros % $this->numero_registros_por_pagina) > 0)
            $vn_numero_paginas_temp = $vn_numero_paginas_temp + 1;

        return $vn_numero_paginas_temp;
    }

    public function ler_lista()
    {
        $va_valores = array();

        if ($this->get_numero_ultima_pagina() > 1) {
            $contador = 1;
            while ($contador <= $this->get_numero_ultima_pagina()) {
                $va_valores[] = ["codigo" => $contador, "valor" => $contador];
                $contador++;
            }
        }

        return $va_valores;
    }

}