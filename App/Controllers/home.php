<?php
/**
 * Description of home
 *
 * @author Administrador
 */
namespace App\Controllers;

class home extends \Core\Libs\Controller
{
    public $permissions = array('index' => 'HOME_VIEW');

    public function index() {
        $auth = \Core\Libs\Auth::getAuth();
        if($auth->rol == 'Invitado')
            redirect(DOC_URL.'/login');

        $home = new \Core\Libs\View();
        $this->header->title = 'SGI â€º Principal';
        if(\Core\Libs\Auth::getAuth()->rol == 'Gestor')
            return $home->display(array('home', 'gestor'));
        else
            return $home->display(array('home', 'cliente'));
    }
}
?>