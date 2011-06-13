<?php  
/**
 * Description of Combo
 *
 * @author Equipo101
 */
namespace App\Components;

class Combo
{
    public $combo;

    public static function usuarios($selected = null) {
        $usuarios = \App\Models\Usuario::all();
        $options = array();  
        foreach($usuarios as $usuario)
            $options[$usuario->id] = $usuario->fullname;
        $select = new \Core\Utils\HtmlSelect('usuario_id', $options, $selected);
        return $select->generate();
    }

    public static function status($selected = null) {
        $status = \App\Models\Status::all();
        $options = array();
        foreach($status as $statu)
            $options[$statu->id] = $statu->name;
        $select = new \Core\Utils\HtmlSelect('status_id', $options, $selected);
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

    public static function horas($selected = null) {
        for($contador = 0;$contador<=50;$contador++){
            $options[$contador] = $contador;
        }
        $select = new \Core\Utils\HtmlSelect('horas_id', $options, $selected);
        return $select->generate();
    }

    public static function minutos($selected = null) {
        for($contador = 0;$contador<=45;$contador+=15){
            $options[$contador] = $contador;
        }
        $select = new \Core\Utils\HtmlSelect('minutos_id', $options, $selected);
        return $select->generate();
    }
}
?>