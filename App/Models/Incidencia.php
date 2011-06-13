<?php 
/**
 * Description of Rol
 *
 * @author Victor
 */
namespace App\Models;
 
class Incidencia extends \ActiveRecord\Model 
{
    static $belongs_to = array(
        array('usuario', 'class_name' => 'App\Models\Usuario'),
        array('status', 'class_name' => 'App\Models\Status'),
        array('servicio', 'class_name' => 'App\Models\Servicio'),
        );

    static $has_many = array(
        array('modificaciones', 'class_name' => 'App\Models\Modificacion')
    	);
    
    static $validates_presence_of = array(
        array('descripcion', 'message' => 'Este campo no puede estar en blanco'),
        array('status_id', 'message' => 'Este campo no puede estar en blanco'),
        array('usuario_id', 'message' => 'Este campo no puede estar en blanco'),
        array('suplantado', 'message' => 'Este campo no puede estar en blanco'),
        array('servicio_id', 'message' => 'Este campo no puede estar en blanco')
        );

}
?>