<?php
/**
 * Description of Controller
 *
 * @author Administrador
 */
namespace Core\Libs;

abstract class Controller
{
    public $config;
    public $input;
    public $url;
    public $header;
    public $footer;

    public $action;
    public $page;

    /**
     * Permisos: HOME_VIEW, FORUM_VIEW, PROFILE_VIEW, PROFILE_EDIT, 
     *
     * @var array
     */
    public $permissions;

    public function __construct() {
        $this->header = new \Core\Libs\View();
        $this->footer = new \Core\Libs\View();
    }

    abstract public function index();

    public function view($content) {
        echo $this->header->display(array('common', 'header'));
        echo $content;
        echo $this->footer->display(array('common', 'footer'));
    }

    public function ajax_view($content) {
        echo $content;
    }

    public function __call($name, $arguments) {
        if(!method_exists($this, $name)) {
            throw new \Core\Libs\Fail('Undefined method '.$name, 50);
        }
        else {
            call_user_func(array($this, $name), $arguments);
        }
    }
}
?>
