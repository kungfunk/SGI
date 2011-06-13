<?php
function loader($class) {
    $file = ROOT_PATH.'/'.str_replace('\\', '/', $class) . '.php';
    if(file_exists($file))
        require $file;
}

spl_autoload_register('loader');
?>
