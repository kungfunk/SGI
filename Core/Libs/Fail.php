<?php
/**
 * Clase que extiende a Exception, 
 * @author Victor Calzón <victor@victorcalzon.com>
 * @package Core
 * @subpackage Libs
 */
namespace Core\Libs;

class Fail extends \Exception
{
    /**
     *
     * @param string $message
     * @param int $code
     * @param Exception $previous 
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->toError($code, $message);
        $this->toLog($code, $message);
    }

    /**
     *
     * @param int $code
     * @param string $message 
     */
    public function toError($code, $message) {
        \Core\Libs\Error::getError()->add($code, $message);
    }

    public function toLog($code, $message) {
        if(\App\Config::get('logExceptions')) {
            $user = $this->getUser($code);
            \Core\Libs\Log::write($user, $code, $message);
        }
    }

    private function getUser($code) {
        if($code == 80) {
            return 'SYSTEM';
        }
        $auth = \Core\Libs\Auth::getAuth();
        return $auth->username;
    }
}
?>
