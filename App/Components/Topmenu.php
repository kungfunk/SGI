<?php
/**
 * Description of Combo
 *
 * @author Equipo101
 */
namespace App\Components;

class Topmenu
{
    public static function getIncidenciasAbiertas($usuario = null) {
        if($usuario == null)
            $usuario = \Core\Libs\Auth::getAuth()->user;
        $incidencias = \App\Models\Incidencia::find('all',array('conditions' => array('status_id in (?) and usuario_id = ?', array(1, 3), $usuario->id)));
        return count($incidencias);
    }

    public static function getMensajesNuevos($usuario = null) {
        if($usuario == null)
            $usuario = \Core\Libs\Auth::getAuth()->user;
    }
}
?>