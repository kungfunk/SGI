<?php
/**
 * Description of login
 *
 * @author Administrador
 */
namespace App\Controllers;

class login extends \Core\Libs\Controller
{
    public $permissions = array('index' => 'LOGIN');

    public function index() {
        $this->header->title = 'SGI â€º Login';
        $auth = \Core\Libs\Auth::getAuth();
        $login_form = new \Core\Libs\View();
        $login_form->error = null;
        $login_form->name = null;
        $login_form->password = null;
        if(!empty($this->input['post'])) {
            if(\App\Models\Usuario::find_by_username_and_status($this->input['post']['name'], 1) == true
                    && $auth->login($this->input['post']['name'], $this->input['post']['password']))
                home();
            $login_form->name = filter_var($this->input['post']['name'], FILTER_SANITIZE_STRING);
            $login_form->error = 'Usuario o contraseÃ±a incorrectos';
        }
        return $login_form->display(array('login', 'login_form'));
    }
}
?>