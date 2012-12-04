<?php
/**
    Bootstrapper for PHProf

*/

$_ENV['title'] = 'PHProf';

function dump($x) { return "\n<pre class='dump'>\n" . html(print_r($x,true)) . "\n</pre>\n"; }
function html($x) { return htmlentities($x,ENT_QUOTES,'utf-8',false); }

/**
    Example xhprof_save()
*/
function xhprof_save($data,$file=null)
{
    $path = ini_get('xhprof.output_dir');
    if (!is_writeable($path)) {
        return false;
    }

    if (empty($file)) {
        if (empty($_SERVER['UNIQUE_ID'])) {
            $_SERVER['UNIQUE_ID'] = uniqid();
        }
        $file = $_SERVER['UNIQUE_ID'];
    }

    $data['file'] = $_SERVER['SCRIPT_FILENAME'];
    // if (empty($ns)) $ns = 'xhprof';
    $file = sprintf('%s/%s.xhprof',$path,$file);

    return file_put_contents($file,serialize($data));

}
