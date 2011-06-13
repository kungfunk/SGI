<?php
/**
 * Description of Url
 *
 * @author Administrador
 */
namespace Core\Libs;

class Url
{
    public static $url;

    public static function parseUrl($uri) {
        // le quitamos los slashes del principio y final si los tuviera
        if(substr($uri, -1) == '/')
           $uri = substr($uri, 0, -1);

        if(substr($uri, 0, 1) == '/')
           $uri = substr($uri, 1);
        
        if($uri && $uri != 'home/')
            $parts = explode('/', $uri);
        else
            $parts = array('home');

        $array = array('controller' => array_shift($parts), 'vars' => $parts, 'page' => 1);
        // seteamos la pagina y la sacamos de vars
        if(array_search('pagina', $array['vars']) !== false) {
            $key = array_search('pagina', $array['vars']);
            if(isset($array['vars'][$key+1]) && is_numeric($array['vars'][$key+1]) && $array['vars'][$key+1] > 0) {
                $array['page'] = $array['vars'][$key+1];
                unset($array['vars'][$key]);
                unset($array['vars'][$key+1]);
            }
        }
        self::$url = $array;
        return $array;
    }

    public static function getUrl() {
        return self::$url;
    }

    public static function getPage() {
        return self::$url['page'];
    }
    /**
     *
     * @param string $str
     * @param string $delimiter
     * @return string
     */
    public static function slugify($str, $delimiter='-') {
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = str_replace('#39', '', $clean);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        return $clean;
    }
}
?>