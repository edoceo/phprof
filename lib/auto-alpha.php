<?php
/**
    phprof Prepend Script
*/

$x = false;
if (!empty($_GET['XDEBUG_PROFILE'])) $x = true;
if (!empty($_POST['XDEBUG_PROFILE'])) $x = true;
if (!empty($_COOKIE['XDEBUG_PROFILE'])) $x = true;
if ($x) $_COOKIE['XDEBUG_PROFILE'] = true;

$x = false;
if (!empty($_GET['XHPROF_PROFILE'])) $x = true;
if (!empty($_POST['XHPROF_PROFILE'])) $x = true;
if (!empty($_COOKIE['XHPROF_PROFILE'])) $x = true;
if ($x) {
    if (function_exists('xhprof_enable')) {
        setcookie('XHPROF_PROFILE',true);
        // Base Datas
        // XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY
        xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    }
}

/**
    Define the Save Handler
*/
function xhprof_save($data=null,$name=null)
{
    if (null == $data) {
        $data = xhprof_disable();
    }

    $path = ini_get('xhprof.output_dir');
    if (!is_writeable($path)) {
        return false;
    }

    if (empty($name)) {
        if (empty($_SERVER['UNIQUE_ID'])) {
            $_SERVER['UNIQUE_ID'] = uniqid();
        }
        $name = $_SERVER['UNIQUE_ID'];
    }

    $data['file'] = $_SERVER['SCRIPT_FILENAME'];
    // if (empty($ns)) $ns = 'xhprof';
    // $file = sprintf('%s/xhprof.%R.',$path,$name);
    // @see http://xdebug.org/docs/all#trace_output_name for mapping
    $file = ini_get('xhprof.output_name'); // 'xhprof.%R';
    $file = ( $file ? $file : 'xhprof.%R.out' );
    $file = str_replace('%p',posix_getpid(),$file);
    $file = str_replace('%r',sprintf('%08x',mt_rand(0x00001000,mt_getrandmax())),$file);
    $file = str_replace('%t',$_SERVER['REQUEST_TIME'],$file);
    $file = str_replace('%u',str_replace('.','_',microtime(true)),$file);
    $file = str_replace('%R',strtolower(str_replace('/','_',$_SERVER['REQUEST_URI'])),$file);
    $file = str_replace('%S',session_id(),$file);
    $file = sprintf('%s/%s',$path,$file);

    return file_put_contents($file,serialize($data));

}
