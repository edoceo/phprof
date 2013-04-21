<?php
/**
    phprof Append Script & Example xhprof_save()
*/

function xhprof_save($data,$name=null)
{
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
    $file = sprintf('%s/xhprof.%s.',$path,$name);

    return file_put_contents($file,serialize($data));

}
