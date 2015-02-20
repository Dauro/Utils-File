<?php

ini_set("auto_detect_line_endings", "1");

spl_autoload_register(function ($class){

    $sRelativePath =  './src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if(file_exists($sRelativePath)) {
        include_once $sRelativePath;
        return true;
    }

    return false;
});

include './vendor/autoload.php';