<?php
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

define('ECOS_START', microtime(true));

define('WEB_MODE', true);

function pr($str){
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

function prd($str){
    echo "<pre>";
    var_dump($str);
    echo "</pre>";
}

require __DIR__.'/../bootstrap/start.php';

kernel::boot();
