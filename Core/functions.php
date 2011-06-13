<?php
function is_ajax() {
    return(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

function printr($var) {
    echo '<pre>' . htmlspecialchars(print_r($var, true)) . '</pre>';
}

function redirect($url = null) {
    if(is_null($url)) $url = $_SERVER['PHP_SELF'];
    header("Location: $url");
}

function home() {
    redirect(DOC_URL);
}

function css($file, $label = false) {
    return ($label) ? '<link rel="stylesheet" type="text/css" href="'.DOC_URL.'/static/css/'.$file.'" />' : DOC_URL.'/static/css/'.$file;
}

function js($file, $label = false) {
    return ($label) ? '<script type="text/javascript" src="'.DOC_URL.'/static/js/'.$file.'" /></script>' : DOC_URL.'/static/js/'.$file;
}

function image($file, $subdir = null) {
    return ($subdir) ? DOC_URL.'/static/images/'.$subdir.'/'.$file : DOC_URL.'/static/images/'.$file;
}

// wrapper para la traduccion de cadenas
function __($string, $language = null, $useCache = true) {
    $i18n = I18n::getI18n();
    $i18n->useCache = $useCache;
    if($language) $i18n->setLanguage($language);
    echo $i18n->translate($string);
}

function getFiles($directory, $exempt = array('.','..','.ds_store','.svn'),&$files = array()) {
    if(false == ($handle = @opendir($directory)))
        throw new \Core\Libs\Fail('Error al aceder al directorio '.$directory, 11);
    while(false !== ($resource = readdir($handle))) {
        if(!in_array(strtolower($resource),$exempt)) {
            if(is_dir($directory.$resource.'/'))
                array_merge($files,
                    $this->getFiles($directory.$resource.'/',$exempt,$files));
            else
                $files[] = array('real_path' => $directory.$resource, 'filename' => $resource, 'directory' => str_replace($this->path, '', $directory));
        }
    }
    closedir($handle);
    return $files;
}
?>
