<?php
/**
 * Clase factory devuelve un objeto a partir de la variable
 * dbDriver de la clase de configuracion.
 * @author Victor Calzón <victor@victorcalzon.com>
 * @package Core
 * @subpackage Database
 */
namespace Core\Database;

class Factory
{
    /**
     * Funcion estatica que hace el trabajo.
     * @return 
     */
    public static function getDB() {
        require_once ROOT_PATH.'/Core/Database/ActiveRecord/ActiveRecord.php';
        \ActiveRecord\Config::initialize(function($cfg)
        {
            $conn_token = \App\Config::get('dbDriver').'://'.\App\Config::get('dbUsername').':'.\App\Config::get('dbPassword').'@'.\App\Config::get('dbHost').'/'.\App\Config::get('dbName');
            $cfg->set_model_directory(ROOT_PATH.'/App/Models');
            $cfg->set_connections(array(
                'development' => $conn_token));
        });
    }
}
?>
