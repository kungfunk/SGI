<?php
/**
 * Description of Combo
 *
 * @author Equipo101
 */
namespace App\Components;

class Notificaciones
{
    public static $plantillas = array(
        '0' => array('titulo' => 'Nueva incidencia', 'mensaje' => 'El usuario "{nombreusuario}" ha introducido la incidencia ID"{id}"'),
    	'1' => array('titulo' => 'Cambio de estado en una de sus incidencias', 'mensaje' => 'La incidencia con el identificador "{id}" ha cambiado al estado {status}'),
        '2' => array('titulo' => 'Cambio de estado en una de sus incidencias', 'mensaje' => 'La incidencia con el identificador "{id}" ha cambiado al estado {status}'),
        '3' => array('titulo' => 'Cambio de estado en una de sus incidencias', 'mensaje' => 'La incidencia con el identificador "{id}" ha cambiado al estado {status}'),
        '4' => array('titulo' => 'Incidencia resuelta', 'mensaje' => 'La incidencia con el identificador "{id}" ha cambiado al estado {status}')
    );

    public static $sender = 'no-reply@dominio.com';
    public static $cc = 'sgi@dominio.com';
    public static $recepcion = 'sgi@dominio.com';
    
    public static function cambioStatus($usuario_id, $status_id, $incidencia_id) {
        $usuario = \App\Models\Usuario::find($usuario_id);
        $status = \App\Models\Status::find($status_id);
        $mail = new \Core\Libs\Mail_html();
        $mail->From(self::$sender);
        $mail->To($usuario->email);
        $mail->Subject(self::$plantillas[$status_id]['titulo']);
        if($status_id == 4) {
            $incidencia = \App\Models\Incidencia::find($incidencia_id);
            $mail->Body(nl2br(str_replace(array('{id}', '{status}'), array($incidencia_id, $status->name), self::$plantillas[$status_id]['mensaje']).'<br /><br />'.$incidencia->respuesta), 'utf-8', 'text/html');
        }
        else {
            $mail->Body(nl2br(str_replace(array('{id}', '{status}'), array($incidencia_id, $status->name), self::$plantillas[$status_id]['mensaje'])), 'utf-8', 'text/html');
        }
        $mail->Cc(self::$cc);
        $mail->Send();
    }

    public static function nuevaIncidencia($usuario_id, $incidencia_id) {
        $usuario = \App\Models\Usuario::find($usuario_id);
        $mail = new \Core\Libs\Mail_html();
        $mail->From(self::$sender);
        $mail->To(self::$recepcion);
        $mail->Subject(self::$plantillas['0']['titulo']);
        $incidencia = \App\Models\Incidencia::find($incidencia_id);
        $mail->Body(nl2br(str_replace(array('{id}', '{nombreusuario}'), array($incidencia_id, $usuario->fullname), self::$plantillas[0]['mensaje']).'<br /><br />'.$incidencia->descripcion), 'utf-8', 'text/html');
        $mail->Send();
    }
}
?>
