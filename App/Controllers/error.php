<?php
/**
 * Description of error
 *
 * @author Administrador
 */
namespace App\Controllers;

class error extends \Core\Libs\Controller
{
    public $permissions = array('index' => 'HOME_VIEW');

    public function index() {
        $general = new \Core\Libs\View();
        $errors = \Core\Libs\Error::getError();
        // acceso restringido
        if($errors->check(403)) {
            header("HTTP/1.0 403 Forbidden");
            $general->message = $errors->msg(403, true);
        }
        // recurso no encontrado
        if($errors->check(404)) {
            header("HTTP/1.0 404 Not Found");
            $general->message = $errors->msg(404, true);
        }
        // fallo en la base de datos
        if($errors->check(80)) {
            $general->message = $errors->msg(80, true);
        }
        // metodo indefinido
        if($errors->check(50)) {
            $general->message = $errors->msg(50, true);
        }
        // error en el sistema de archivos
        if($errors->check(10)) {
            $general->message = $errors->msg(10, true);
        }
        // error en la subida de archivos
        if($errors->check(11)) {
            $general->message = $errors->msg(11, true);
        }
        // error al usar una aplicacion externa
        if($errors->check(60)) {
            $general->message = $errors->msg(60, true);
        }

        return $general->display(array('error', 'general'));
    }

    public function view($content) {
        echo $content;
    }
}
?>
