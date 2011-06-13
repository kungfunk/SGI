<?php
/**
 * Description of incidencia
 *
 * @author Equipo101
 */
namespace App\Controllers;

class incidencias extends \Core\Libs\Controller
{
    public $permissions = array('activas' => 'INCIDENCIAS_ACTIVAS',
                                'historico' => 'INCIDENCIAS_HISTORICO',
                                'index' => 'INCIDENCIAS_INDEX');

    public $page;
    public $limit;

    public function __construct() {
        parent::__construct();
        $this->page = \Core\Libs\Url::getPage();
        $this->limit = 10;
    }

    public function index() {
        $this->header->title = 'SGI › Incidencias';
        $this->body = new \Core\Libs\View();
        $resource = \App\Models\Incidencia::all(array('order' => 'created_at desc'));
        $pagination = new \Core\Utils\Pagination($resource, $this->limit);
        $this->body->incidencias = $pagination->getCursor();
        $this->body->showing = $pagination->showing();
        $this->body->next = $pagination->getNextPage();
        $this->body->previous = $pagination->getPreviousPage();
        $this->body->accion = '';
        return $this->body->display(array('incidencias', 'index'));
    }

    public function historico() {
        $this->header->title = 'SGI › Historico de incidencias';
        $this->body = new \Core\Libs\View();
        $sql = "select * from incidencias where usuario_id in (select id from usuarios where empresa_id = '".\Core\Libs\Auth::getAuth()->user->empresa_id."') and status_id in ('4', '2') order by created_at desc";
        $resource = \App\Models\Incidencia::find_by_sql($sql);
        $pagination = new \Core\Utils\Pagination($resource, $this->limit);
        $this->body->incidencias = $pagination->getCursor();
        $this->body->showing = $pagination->showing();
        $this->body->next = $pagination->getNextPage();
        $this->body->previous = $pagination->getPreviousPage();
        $this->body->accion = 'historico/';
        return $this->body->display(array('incidencias', 'index'));
    }

    public function activas() {
        $this->header->title = 'SGI › Incidencias activas';
        $this->body = new \Core\Libs\View();
        $sql = "select * from incidencias where usuario_id in (select id from usuarios where empresa_id = '".\Core\Libs\Auth::getAuth()->user->empresa_id."') and status_id not in('4', '2') order by created_at desc";
        $resource = \App\Models\Incidencia::find_by_sql($sql);
        $pagination = new \Core\Utils\Pagination($resource, $this->limit);
        $this->body->incidencias = $pagination->getCursor();
        $this->body->showing = $pagination->showing();
        $this->body->next = $pagination->getNextPage();
        $this->body->previous = $pagination->getPreviousPage();
        $this->body->accion = 'activas/';
        return $this->body->display(array('incidencias', 'index'));
    }
}
?>
