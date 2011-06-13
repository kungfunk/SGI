<?php 
/**
 * Description of Rol
 *
 * @author Victor
 */
namespace App\Models;
 
class Modificacion extends \ActiveRecord\Model
{
    static $table_name = 'modificaciones';  
	
    static $belongs_to = array(
        array('incidencia', 'class_name' => 'App\Models\Incidencia'),
    	array('usuario', 'class_name' => 'App\Models\Usuario'),
        array('status', 'class_name' => 'App\Models\Status')
        );

    static $validates_presence_of = array(
        array('status_id', 'message' => 'Este campo no puede estar en blanco'),
        array('usuario_id', 'message' => 'Este campo no puede estar en blanco'),
        );

}
?>