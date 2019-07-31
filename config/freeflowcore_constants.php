<?php
/**
 * Path to the FreeFlow Core directories.
 */
exec('echo  %FF_CORE_HOME%', $out, $ret);
if (!defined('FF_CORE_HOME')) {
    if ($ret == 0) {
        $dir = trim($out[0]);
        if ($dir != '%FF_CORE_HOME%') {
            define('FF_CORE_HOME', $dir);
        } else {
            define('FF_CORE_HOME', null);
        }
    } else {
        define('FF_CORE_HOME', null);
    }
}
unset($out);
unset($ret);

if (!defined('FF_CORE_URL')) {
    define('FF_CORE_URL', 'http://localhost/');
}

if (!defined('TZ')) {
    define('TZ', 'UTC');
}
if (!defined('TF')) {
    define('TF', 'HH:mm:ss');
}
if (!defined('DF')) {
    define('DF', 'yyyy-MM-dd');
}
if (!defined('DTF')) {
    define('DTF', 'yyyy-MM-dd HH:mm:ss');
}