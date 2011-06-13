<?php
/**
 * Configuraciones generales y espec�ficas a cada categor�a de servidores
 *
 * Permite definir varias categor�as de sevidores, con m�ltiples url en cada categor�a
 *
 * @author Victor Calzón <victor@victorcalzon.com>
 * @package App
 */
namespace App;

class Config
{
    private static $me;

    // Se definen las rutas que van a pertenecer a cada categor�a de servidor($_SERVER['HTTP_HOST'])
    private $productionServers = array('sgi.dominio.com', 'dominio.com');
    private $localServers = array('localhost');
    private $testServers = array('');

    // ajax mode
    public $ajax;

    // logs
    public $log;
    public $logType;
    public $logStore;
    public $logExceptions;

    // imagenes temporales
    public $tempStore;

    // clase auth
    public $authDomain;
    public $authSalt; 
    public $useHashedPasswords;
    public $cookieName;

    // clase database
    public $dbDriver;
    public $dbHost;
    public $dbName;
    public $dbUsername; 
    public $dbPassword;
    public $dbDieOnError;
    
    public $useDBSessions;

    private function __construct()
    {
        $this->everywhere();

        $i_am_here = $this->whereAmI();

        if('production' == $i_am_here)
            $this->production();
        elseif('test' == $i_am_here)
            $this->test();
        elseif('local' == $i_am_here)
            $this->local();
        else
            die('<h1>¿Quien se ha llevado mi queso?</h1> <p>Incluye el http_host en el config melon!</p>
                 <p><code>$_SERVER[\'HTTP_HOST\']</code> reported <code>' . $_SERVER['HTTP_HOST'] . '</code></p>');

        // ojito al .htaccess para entender esto
        if(isset($_SERVER['REDIRECT_SELF_URL'])) {
            define('REQUEST_URI', $_SERVER['REDIRECT_SELF_URL']);
        }
        else {
            $part = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            define('REQUEST_URI', str_replace(DOC_URL, '', $part));
        }

        define('SELF_URL', DOC_URL.REQUEST_URI);
    }

    /**
     * Standard singleton
     * @return Config
     */
    public static function getConfig()
    {
        if(is_null(self::$me))
            self::$me = new Config();
        return self::$me;
    }

    public static function get($key)
    {
        return self::$me->$key;
    }

    /**
     * Configuraciones que se cargar�n en todos los servidores
     */
    private function everywhere()
    {
        // el idioma lo primero
        setlocale(LC_ALL, 'es_ES.UTF-8');

        // Store sesions in the database?
        $this->useDBSessions = true;

        $this->authDomain = $_SERVER['HTTP_HOST'];
        $this->useHashedPasswords = true; //Indica si estamos utilizando claves encriptadas
        $this->authSalt = 'wh4t3v3r'; // Este texto se a�ada despu�s de las claves antes de encriptarlas
        $this->cookieName = 'sgi'; // Nombre del proyecto normalmente
        $this->sessionName = 'sgi';

        $this->tempStore = ROOT_PATH.'/public/temp/';
    }

    /**
     * Configuraciones que se cargar�n en todos los servidores de la categor�a "production"
     */
    private function production()
    {
        ini_set('display_errors', '0');

        define('DOC_URL', 'http://sgi.dominio.com');

        $this->dbDriver = 'mysql';
        $this->dbHost = 'localhost';
        $this->dbName = 'sgi';
        $this->dbUsername = 'root';
        $this->dbPassword = '';
        $this->dbDieOnError = false;
        
        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    /**
     * Configuraciones que se cargar�n en todos los servidores de la categor�a "local"
     */
    private function local()
    {
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL | E_STRICT);

        define('DOC_URL', 'http://localhost/sgi/public');

        $this->dbDriver = 'mysql';
        $this->dbHost = 'localhost';
        $this->dbName = 'sgi';
        $this->dbUsername = 'root';
        $this->dbPassword = '';
        $this->dbDieOnError = true;

        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    /**
     * Configuraciones que se cargar�n en todos los servidores de la categor�a "test"
     */
    private function test()
    {
        ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL | E_STRICT);

        define('DOC_URL', '');

        $this->dbDriver = '';
        $this->dbHost = '';
        $this->dbName = '';
        $this->dbUsername = '';
        $this->dbPassword = '';
        $this->dbDieOnError = true;

        $this->ajax = true;

        $this->log = true;
        $this->logType = 'plaintext';
        $this->logStore = ROOT_PATH.'/App/Logs/';
        $this->logExceptions = true;
    }

    /**
     * Configuraciones que se cargar�n en todos los servidores de la categor�a "production"
     */
    public function whereAmI()
    {
        if(in_array($_SERVER['HTTP_HOST'], $this->productionServers))
            return 'production';
        elseif(in_array($_SERVER['HTTP_HOST'], $this->testServers))
            return 'test';
        elseif(in_array($_SERVER['HTTP_HOST'], $this->localServers))
            return 'local';
        else
            return false;
    }
}
?>