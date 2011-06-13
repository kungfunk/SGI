<?php
/**
 * Description of Empresa
 *
 * @author Victor
 */
namespace App\Models;

class Empresa extends \ActiveRecord\Model
{
    static $validates_presence_of = array(
        array('name', 'message' => 'Este campo no puede estar en blanco')
    );

    static $validates_uniqueness_of = array(
         array('name', 'message' => 'Ya existe otra empresa con ese nombre')
    );
}
?>