<?php
define('ROOT', dirname(__DIR__));
define('TMP', ROOT . DS . 'tmp' . DS);
define('SRC', ROOT . DS . 'src' . DS);

if (!function_exists('dlog')) {
    function dlog($line)
    {
        file_put_contents(TMP . '/log.txt', $line . PHP_EOL, FILE_APPEND);
    }
}
