<?php 
/**
 * Description of administracion
 *
 * @author Victor Calzón
 */
namespace App\Controllers;

class administracion extends \Core\Libs\Controller
{
	// Permisos necesarios para ver cada secci�n, Los permisos de cada usuario se pueden cambiar en Models\Usuario.php
    public $permissions = array('index' => 'ADMIN_INDEX',
                                'usuarios' => 'ADMIN_USER',
                                'logs' => 'ADMIN_LOGS',
                                'empresas' => 'ADMIN_EMPRESAS',
    							'servicios' => 'ADMIN_SERVICIOS');

    public function __construct() {
        parent::__construct();
        $this->header->menu = new \App\Components\Administracion\Menu();
        $this->header->js = null;
    }

    public function index() {
       $this->header->title = 'SGI › Administración';
       return 'portada de la administracion';
    }

    public function usuarios() {
       $this->header->title = 'SGI › Administración › Usuarios';
       // nuevo usuario
       if(isset($this->url[1]) && $this->url[1] == 'new') {
           if(!empty($this->input['post'])) {
               return $this->user_save();
           }
           return $this->user_new();
       }
       // edicion
       if(isset($this->url[1]) && $this->url[1] == 'edit') {
           if((\App\Models\Usuario::find_by_username($this->url[2])) == true) {
               if(!empty($this->input['post'])) {
                   return $this->user_save($this->url[2]);
               }
               return $this->user_edit($this->url[2]);
           }
           return 'Usuario no encontrado';
       }
       // borrar
       if(isset($this->url[1]) && $this->url[1] == 'delete') {
           if((\App\Models\Usuario::find_by_username($this->url[2])) == true) {
               return $this->user_delete($this->url[2]);
           }
           return 'Usuario no encontrado';
       }
       // lista
       return $this->user_list();
    }

    public function empresas() {
       $this->header->title = 'SGI › Administración › Empresas';
       // nuevo usuario
       if(isset($this->url[1]) && $this->url[1] == 'new') {
           if(!empty($this->input['post'])) {
               return $this->empresa_save();
           }
           return $this->empresa_new();
       }
       // edicion
       if(isset($this->url[1]) && $this->url[1] == 'edit') {
           if((\App\Models\Empresa::find($this->url[2])) == true) {
               if(!empty($this->input['post'])) {
                   return $this->empresa_save($this->url[2]);
               }
               return $this->empresa_edit($this->url[2]);
           }
           return 'Empresa no encontrada';
       }
       // borrar
       if(isset($this->url[1]) && $this->url[1] == 'delete') {
           if((\App\Models\Empresa::find($this->url[2])) == true) {
               return $this->empresa_delete($this->url[2]);
           }
           return 'Empresa no encontrada';
       }
       // lista
       return $this->empresa_list();
    }

    public function servicios() {
       $this->header->title = 'SGI › Administración › Servicios';
       // nuevo servicio
       if(isset($this->url[1]) && $this->url[1] == 'new') {
           if(!empty($this->input['post'])) {
               return $this->servicio_save();
           }
           return $this->servicio_new();
       }
       // edicion
       if(isset($this->url[1]) && $this->url[1] == 'edit') {
           if((\App\Models\Servicio::find($this->url[2])) == true) {
               if(!empty($this->input['post'])) {
                   return $this->servicio_save($this->url[2]);
               }
               return $this->servicio_edit($this->url[2]);
           }
           return 'Servicio no encontrado';
       }
       // borrar
       if(isset($this->url[1]) && $this->url[1] == 'delete') {
           if((\App\Models\Servicio::find($this->url[2])) == true) {
               return $this->servicio_delete($this->url[2]);
           }
           return 'Servicio no encontrado';
       }
       // lista
       return $this->servicio_list();
    }

    public function logs() {
        $this->header->title = 'SGI › Administración › Logs';
        if(isset($this->url[1])) {
            return nl2br(\Core\Libs\Log::load($this->url[1]));
        }
        $this->log_list = new \Core\Libs\View();
        $this->log_list->list = \Core\Libs\Log::getList();
        return $this->log_list->display(array('administracion', 'log_list'));
    }

    public function view($content) {
        echo $this->header->display(array('administracion', 'header'));
        echo $content;
        echo $this->footer->display(array('administracion', 'footer'));
    }

    private function user_list() {
        $this->user_list = new \Core\Libs\View();
        $this->user_list->users = \App\Models\Usuario::all();
        return $this->user_list->display(array('administracion', 'user_list'));
    }

    private function user_new($errors = null) {
        $this->user_new = new \Core\Libs\View();
        $this->user_new->errors = ($errors != null) ? $errors : null;
        $this->user_new->input = ($this->input['post'] != null) ? $this->input['post'] : null;
        $status = (isset($this->input['post']['status'])) ? $this->input['post']['status'] : null;
        $rol_id = (isset($this->input['post']['rol_id'])) ? $this->input['post']['rol_id'] : null;
        $empresa_id = (isset($this->input['post']['empresa_id'])) ? $this->input['post']['empresa_id'] : null;
        $this->user_new->status_select = \App\Components\Administracion\Combo::status($status);
        $this->user_new->rol_select = \App\Components\Administracion\Combo::rol($rol_id);
        $this->user_new->empresa_select = \App\Components\Administracion\Combo::empresas($empresa_id);
        return $this->user_new->display(array('administracion', 'user_new'));
    }

    private function user_edit($username, $errors = null) {
        $user = \App\Models\Usuario::find_by_username($username);
        $this->user_edit = new \Core\Libs\View();
        $this->user_edit->errors = ($errors != null) ? $errors : null;
        $this->user_edit->username = (isset($this->input['post']['username'])) ? $this->input['post']['username'] : $user->username;
        $this->user_edit->password = (isset($this->input['post']['password'])) ? $this->input['post']['password'] : $user->password;
        $this->user_edit->email = (isset($this->input['post']['email'])) ? $this->input['post']['email'] : $user->email;
        $this->user_edit->fullname = (isset($this->input['post']['fullname'])) ? $this->input['post']['fullname'] : $user->fullname;
        $status = (isset($this->input['post']['status'])) ? $this->input['post']['status'] : $user->status;
        $rol_id = (isset($this->input['post']['rol_id'])) ? $this->input['post']['rol_id'] : $user->rol_id;
        $empresa_id = (isset($this->input['post']['empresa_id'])) ? $this->input['post']['empresa_id'] : $user->empresa_id;
        $this->user_edit->status_select = \App\Components\Administracion\Combo::status($status);
        $this->user_edit->rol_select = \App\Components\Administracion\Combo::rol($rol_id);
        $this->user_edit->empresa_select = \App\Components\Administracion\Combo::empresas($empresa_id);
        return $this->user_edit->display(array('administracion', 'user_edit'));
    }

    private function user_save($username = null) {
        if($username == null) {
            $user = new \App\Models\Usuario($this->input['post']);
            if($user->save())
                return "Usuario creado con exito";
            else
                return $this->user_new($user->errors);
        }
        else {
            $user = \App\Models\Usuario::find_by_username($username);
            foreach($this->input['post'] as $key=>$value) {
                $user->$key = $value;
            }
            if($user->save())
                return "Usuario modificado con exito";
            else
                return $this->user_edit($username, $user->errors);
        }
    }

    private function user_delete($username) {
         $user = \App\Models\Usuario::find_by_username($username);
         $user->delete();
         return "Usuario eliminado con exito";
    }

    private function empresa_list() {
        $this->empresa_list = new \Core\Libs\View();
        $this->empresa_list->empresas = \App\Models\Empresa::all();
        return $this->empresa_list->display(array('administracion', 'empresa_list'));
    }

    private function empresa_new($errors = null) {
        $this->empresa_new = new \Core\Libs\View();
        $this->empresa_new->errors = ($errors != null) ? $errors : null;
        $this->empresa_new->input = ($this->input['post'] != null) ? $this->input['post'] : null;
        return $this->empresa_new->display(array('administracion', 'empresa_new'));
    }

    private function empresa_edit($id, $errors = null) {
        $empresa = \App\Models\Empresa::find($id);
        $this->empresa_edit = new \Core\Libs\View();
        $this->empresa_edit->errors = ($errors != null) ? $errors : null;
        $this->empresa_edit->name = (isset($this->input['post']['name'])) ? $this->input['post']['name'] : $empresa->name;
        return $this->empresa_edit->display(array('administracion', 'empresa_edit'));
    }

    private function empresa_save($id = null) {
        if($id == null) {
            $empresa = new \App\Models\Empresa($this->input['post']);
            if($empresa->save())
                return "Empresa creada con exito";
            else
                return $this->empresa_new($empresa->errors);
        }
        else {
            $empresa = \App\Models\Empresa::find($id);
            foreach($this->input['post'] as $key=>$value) {
                $empresa->$key = $value;
            }
            if($empresa->save())
                return "Empresa modificada con exito";
            else
                return $this->empresa_edit($id, $user->errors);
        }
    }

    private function empresa_delete($id) {
         $empresa = \App\Models\Empresa::find($id);
         $empresa->delete();
         return "Empresa eliminada con exito";
    }

/**
*	Procedimientos b�sicos para servicios
*    
*	@author Javi 
**/
    
    private function servicio_list() {
        $this->servicio_list = new \Core\Libs\View();
        $this->servicio_list->servicios = \App\Models\Servicio::all();
        return $this->servicio_list->display(array('administracion', 'servicio_list'));
    }

    private function servicio_new($errors = null) {
        $this->servicio_new = new \Core\Libs\View();
        $this->servicio_new->errors = ($errors != null) ? $errors : null;
        $this->servicio_new->input = ($this->input['post'] != null) ? $this->input['post'] : null;
        return $this->servicio_new->display(array('administracion', 'servicio_new'));
    }

    private function servicio_edit($id, $errors = null) {
        $servicio = \App\Models\Servicio::find($id);
        $this->servicio_edit = new \Core\Libs\View();
        $this->servicio_edit->errors = ($errors != null) ? $errors : null;
        $this->servicio_edit->name = (isset($this->input['post']['name'])) ? $this->input['post']['name'] : $servicio->name;
        return $this->servicio_edit->display(array('administracion', 'servicio_edit'));
    }

    private function servicio_save($id = null) {
        if($id == null) {
            $servicio = new \App\Models\Servicio($this->input['post']);
            if($servicio->save())
                return "Servicio creado con exito";
            else
                return $this->servicio_new($servicio->errors);
        }
        else {
            $servicio = \App\Models\Servicio::find($id);
            foreach($this->input['post'] as $key=>$value) {
                $servicio->$key = $value;
            }
            if($servicio->save())
                return "Servicio modificado con exito";
            else
                return $this->servicio_edit($id, $user->errors);
        }
    }

    private function servicio_delete($id) {
         $servicio = \App\Models\Servicio::find($id);
         $servicio->delete();
         return "Servicio eliminado con exito";
    }
}
?>