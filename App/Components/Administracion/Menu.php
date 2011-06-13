<?php 
/**
 * Description of menu
 *
 * @author Equipo101
 */
namespace App\Components\Administracion;

class Menu
{ 
    public $ul;

    public function __construct() {
        $this->generate();
    }

    public function generate() {
        $this->ul = "<li><h3>Gestion de usuarios</h3><ul>
                        <li><a href=\"".DOC_URL."/administracion/usuarios\">Listado</a></li>
                        <li><a href=\"".DOC_URL."/administracion/usuarios/new\">Alta de usuario</a></li>
                     </ul></li>
                     <li><h3>Gestion de empresas</h3><ul>
                        <li><a href=\"".DOC_URL."/administracion/empresas\">Listado</a></li>
                        <li><a href=\"".DOC_URL."/administracion/empresas/new\">Alta de empresa</a></li>
                     </ul></li>
                     <li><h3>Gestion de servicios</h3><ul>
                        <li><a href=\"".DOC_URL."/administracion/servicios\">Listado</a></li>
                        <li><a href=\"".DOC_URL."/administracion/servicios/new\">Alta de servicio</a></li>
                     </ul></li>
                     <li><a href=\"".DOC_URL."/administracion/logs\">Logs</a></li>";
    }

    public function __toString() {
        return $this->ul;
    }
}
?>
