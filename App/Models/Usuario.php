<?php
/**
 * Description of User
 *
 * @author Administrador
 */
namespace App\Models;
 
class Usuario extends \ActiveRecord\Model 
{ 
    static $belongs_to = array(
        array('empresa', 'class_name' => 'App\Models\Empresa'),
        array('rol', 'class_name' => 'App\Models\Rol')
    );

    static $has_many = array(
        array('incidencias', 'class_name' => 'App\Models\Incidencia') 
    );

    static $validates_presence_of = array(
        array('username', 'message' => 'Este campo no puede estar en blanco'),
        array('password', 'message' => 'Este campo no puede estar en blanco'),
        array('email', 'message' => 'Este campo no puede estar en blanco'),
        array('fullname', 'message' => 'Este campo no puede estar en blanco'),
        array('rol_id', 'message' => 'Este campo no puede estar en blanco'),
        array('empresa_id', 'message' => 'Este campo no puede estar en blanco'),
        array('status', 'message' => 'Este campo no puede estar en blanco'),
    );

    static $validates_uniqueness_of = array(
         array('username', 'message' => 'Ya existe otro usuario con ese nombre')
    );


    public function set_password($plaintext) {
        if($plaintext != null)
            $this->assign_attribute('password', sha1($plaintext.\App\Config::getConfig()->authSalt));
    }

    public function set_username($plaintext) {
        $this->assign_attribute('username', strtolower($plaintext));
    }

    /**
     * Esta funcion asigna los permisos adecuados al rol correspondiente
     *
     * @param string $role
     * @return array 
     */
    public function assignPermissions($role) {
        switch($role) {
            case 'Invitado':
                return array('HOME_VIEW', 'LOGIN');
            break;
            case 'Cliente':
                return array('HOME_VIEW', 'LOGOUT', 'INCIDENCIA_ITEM', 'INCIDENCIA_ALTA',
                             'INCIDENCIAS_ACTIVAS', 'INCIDENCIAS_HISTORICO');
            break;
            case 'Gestor':
                return array('HOME_VIEW', 'LOGOUT', 'INCIDENCIA_ITEM', 'INCIDENCIA_ALTA',
                             'INCIDENCIAS_INDEX', 'INCIDENCIAS_ACTIVAS', 'INCIDENCIAS_HISTORICO',
                             'ADMIN_INDEX', 'ADMIN_USER', 'ADMIN_LOGS', 'ADMIN_EMPRESAS', 'ADMIN_SERVICIOS');
            break;
            default:
                return false;
            break;
        }
    }
}
?>