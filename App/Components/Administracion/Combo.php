<?php 
/**
 * Description of Combo
 *
 * @author Equipo101
 */
namespace App\Components\Administracion;

class Combo
{ 
    public $combo;
    
    public static function empresas($selected = null) {
        $empresas = \App\Models\Empresa::all();
        $options = array();
        foreach($empresas as $empresa)
            $options[$empresa->id] = $empresa->name;
        $select = new \Core\Utils\HtmlSelect('empresa_id', $options, $selected);
        return $select->generate();
    }

    public static function status($selected = null) {
        $options = array('1' => 'Activo', '0' => 'Inactivo');
        $select = new \Core\Utils\HtmlSelect('status', $options, $selected);
        return $select->generate();
    }

    public static function rol($selected = null) {
        $roles = \App\Models\Rol::all();
        $options = array();
        foreach($roles as $rol)
            $options[$rol->id] = $rol->name;
        $select = new \Core\Utils\HtmlSelect('rol_id', $options, $selected);
        return $select->generate();
    }

    public static function servicios($selected = null) {
        $servicios = \App\Models\Servicio::all();
        $options = array();
        foreach($servicios as $servicio)
            $options[$servicio->id] = $servicio->name;
        $select = new \Core\Utils\HtmlSelect('servicio_id', $options, $selected);
        return $select->generate();
    }
}
?>