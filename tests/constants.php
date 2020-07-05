<?php
define('ROOT', dirname(__DIR__));
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('TMP', ROOT . DS . 'tmp' . DS);
define('SRC', ROOT . DS . 'src' . DS);

if (!function_exists('dlog')) {
    function dlog(string $line): void
    {
        file_put_contents(TMP . '/log.txt', $line . PHP_EOL, FILE_APPEND);
    }
}
if (!function_exists('dexport')) {
    /**
     * @param mixed $obj
     */
    function dexport($obj): string
    {
        ob_start();
        var_dump($obj);
        $content = ob_get_contents();
        ob_end_clean();

        return $content ?: '';
    }
}
