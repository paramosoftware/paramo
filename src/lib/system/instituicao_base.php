<?php 

class instituicao_base extends objeto_base
{
    private $name;
    private $is_admin;

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($ps_name)
    {
        $this->name = $ps_name;
    }

    public function get_is_admin()
    {
        return $this->is_admin;
    }

    public function set_is_admin($ps_is_admin)
    {
        $this->is_admin = $ps_is_admin;
    }
}
