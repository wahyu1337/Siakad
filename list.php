<?php
function listFiles($dir, $prefix = '') {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        echo $prefix . $file . (is_dir($path) ? '/' : '') . "<br>";
        if (is_dir($path)) {
            listFiles($path, $prefix . '&nbsp;&nbsp;&nbsp;');
        }
    }
}

listFiles('.');
?>