<?php
// core
function core_loader($class_name) {
    $file = CLASS_ROOT.'/core/'.$class_name.'.php';
    if (file_exists($file)) {
        require $file;
    }
}
spl_autoload_register('core_loader');

// helper
function helper_loader($class_name) {
    $file = CLASS_ROOT.'/helper/'.$class_name.'.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('helper_loader');

// model
function model_loader($class_name) {
    $file = CLASS_ROOT.'/model/'.$class_name.'.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('model_loader');
?>