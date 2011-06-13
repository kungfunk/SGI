<?php
/**
 * Description of Model
 *
 * @author Administrador
 */
namespace Core\Libs;

class Model extends \ActiveRecord\Model
{
    private $_db;
    private $_model;

    public function __construct() {
        try {
            parent::__construct();
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function __toString() {
        return 'Model: '.$this->_model;
    }
}
?>