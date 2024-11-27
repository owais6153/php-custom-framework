<?php

function config($name = "app")
{
    static $configs = [];

    $keys = explode('.', $name);
    $type= $keys[0];
    $key= $keys[1] ?? '';


    if (!isset($configs[$type])) {
        $filePath = __DIR__ . "/../config/$type.php";
        $configs[$type] = file_exists($filePath) ? require($filePath) : [];
    }

    if ($key !== "") {
        if (isset($configs[$type][$key])) {
            return $configs[$type][$key];
        } else {
            throw new Error("$key key does not exist in $type configuration");
        }
    } else {
        return $configs[$type];
    }
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}