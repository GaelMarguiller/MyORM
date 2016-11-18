<?php

spl_autoload_register(function ($className) {
    $name = $className . '.php';
    $it = new RecursiveDirectoryIterator(__DIR__);
    $display = Array('php');
    foreach (new RecursiveIteratorIterator($it) as $file) {
        $array = explode('.', $file);
        $array2 = explode('/', $file);
        if (in_array(strtolower($array[count($array) - 1]), $display)) {
            $fileName = ucfirst($array2[count($array2) - 1]);
            if ($fileName == $name) require_once($file);
        }
    }
});