<?php
/**
 * Functions placed in this file MUST NOT break the MVC patterns defined by CakePHP
 */

/**
 * Check if running in CLI mode. Slightly more reliable than the CakePHP defined constant
 *
 * @return bool
 */
function is_cli()
{
    if (defined('STDIN')) {
        return true;
    }

    if (php_sapi_name() === 'cli') {
        return true;
    }

    if (array_key_exists('SHELL', $_ENV)) {
        return true;
    }

    if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
        return true;
    }

    if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
        return true;
    }

    return false;
}

function prc($var)
{
    if (is_cli()) {
        print($var . "\r\n");
    }
}

/**
 *
 * @param array $list
 * @param int $p
 * @return array
 * @link http://www.php.net/manual/en/function.array-chunk.php#75022
 */
function partition(Array $list, $p)
{
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice($list, $mark, $incr);
        $mark += $incr;
    }
    return $partition;
}


/**
 * Check if an array is numerically indexed at the first level
 *
 * @param array $arr
 * @return bool
 */
function isSeqArr(array $arr)
{
    //empty array
    if ([] === $arr) {
        return false;
    }

    //check keys
    if (array_keys($arr) == range(0, count($arr) - 1)) {
        $return = true;
    } else {
        $return = false;
    }

    return $return;
}


/**
 * Recursively implode an array
 *
 * @param string $glue
 * @param array $array
 * @param bool $include_keys
 * @param bool $trim_all
 * @return string
 */
function recursive_implode($glue = ',', array $array, $include_keys = false, $trim_all = false)
{
    $glued_string = '';

    // Recursively iterates array and adds key/value to glued string
    array_walk_recursive($array, function ($value, $key) use ($glue, $include_keys, &$glued_string) {
        $include_keys and $glued_string .= $key . $glue;
        $glued_string .= $value . $glue;
    });

    // Removes last $glue from string
    strlen($glue) > 0 and $glued_string = substr($glued_string, 0, -strlen($glue));

    // Trim ALL whitespace
    $trim_all and $glued_string = preg_replace("/(\s)/ixsm", '', $glued_string);
    return (string)$glued_string;
}


/**
 * Get the current HOSTNAME
 *
 * @return string
 */
function getHID()
{
    return strtolower(substr(sha1(gethostname()), 0, 8));
}


/**
 * @deprecated move to Text Helper class
 * @param $haystack
 * @param $needle
 * @return bool
 */
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}


/**
 * @deprecated move to Text Helper class
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


/**
 * Generate a recursively nested UL
 *
 * @deprecated Move this function to a TEXT helper class
 * @param $element
 * @param bool $html
 * @return string
 */
function recursive_list($element, $html = false)
{
    if ($html == true) {
        $uls = "<ul>";
        $ule = "</ul>";
        $lis = "<li>";
        $lie = "</li>";
    } else {
        $uls = "";
        $ule = "";
        $lis = "";
        $lie = "";
    }

    $ret = "$uls\r\n";
    foreach ($element as $k => $value) {
        if (!is_array($value)) {
            $ret .= "$lis\r\n";
            $ret .= ucwords($k) . ": " . $value . "\r\n";
        } else {
            $ret .= "$uls\r\n";
            $ret .= recursive_list($value) . "\r\n";
            $ret .= "$lie\r\n";
            $ret .= "$ule\r\n";
        }
    }
    $ret .= "$ule\r\n";

    return trim($ret);
}

/**
 * Convert value to boolean.
 *
 * @param $val
 * @return bool
 */
function asBool($val)
{
    if (is_string($val)) {
        $val = strtolower($val);
    }

    $true = [true, 'true', 1, '1', 't', 'yes', 'on'];
    $false = [false, 'false', 0, '0', 'f', 'no', 'off', null];

    if (in_array($val, $true, true)) {
        return true;
    }

    if (in_array($val, $false, true)) {
        return false;
    }

    return boolval($val);
}


function isJson($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function decodeJsonOrSerialized($data)
{
    if (is_numeric($data)) {
        return $data;
    }

    if ($data === null || $data === 'null') {
        return null;
    }

    if ($data === true || $data === 'true') {
        return true;
    }

    if ($data === false || $data === 'false') {
        return false;
    }

    $jsonDecodeResult = @json_decode($data, true);
    if ($jsonDecodeResult) {
        return $jsonDecodeResult;
    }

    $unserializeResult = @unserialize($data);
    if ($unserializeResult) {
        return $unserializeResult;
    }

    return $data;
}

/**
 * Push-Header-Down
 */
function phd()
{
    echo "<div id='push-header-down' style='height: 50px;'></div>";
}

/**
 * Bare bones debug
 *
 * @param $msg
 */
function bbd($msg = null)
{
    if (is_object($msg) || is_array($msg) || is_bool($msg)) {
        //$msg = json_encode($msg, JSON_PRETTY_PRINT);
        $msg = \App\Utility\Feedback\DebugCapture::captureDump($msg) . "\r\n\r\n";
    } else {
        $msg = date('Ymd_His') . " " . $msg . "\r\n\r\n";
    }

    file_put_contents(LOGS . "bbd.txt", $msg, FILE_APPEND);
}

/**
 * Limit the number of replaces
 *
 * @param $from
 * @param $to
 * @param $content
 * @param $times
 * @return null|string|string[]
 */
function str_replace_x_times($from, $to, $content, $times = 1)
{
    $from = '/' . preg_quote($from, '/') . '/';
    return preg_replace($from, $to, $content, $times);
}
