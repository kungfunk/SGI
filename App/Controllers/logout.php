<?php
/**
 * Description of logout
 *
 * @author Administrador
 */
namespace App\Controllers;

class logout extends \Core\Libs\Controller
{
    public $permissions = array('index' => 'LOGOUT');

    public function index() {
        $goodbye = new \Core\Libs\View();
        $auth = \Core\Libs\Auth::getAuth();
        $auth->logout();
        home();
    }
}
?>
