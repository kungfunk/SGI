<?php 
/**
 * Description of Servicio
 *
 * @author Javi
 */
namespace App\Models;

class Servicio extends \ActiveRecord\Model
{
    static $validates_presence_of = array(
        array('name', 'message' => 'Este campo no puede estar en blanco')
    );

    static $validates_uniqueness_of = array(
         array('name', 'message' => 'Ya existe otro servicio con ese nombre')
    );
}
?>