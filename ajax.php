<?php
/**

*/

error_reporting((E_ALL | E_STRICT) ^ E_NOTICE);

header('Content-Type: text/html; charset=utf-8');

define('APP_ROOT',dirname(__FILE__));
require_once(APP_ROOT . '/lib/boot.php');
require_once(APP_ROOT . '/lib/phprof.php');
require_once(APP_ROOT . '/lib/xdebug.php');
require_once(APP_ROOT . '/lib/xhprof.php');

// print_r($_POST);
switch ($_POST['a']) {
case 'load':

    $file_list = phprof::listProfileOutputs();
    foreach ($file_list as $k=>$file) {

        $have = array();
        if (!empty($file['xdebug'])) $have[] = 'xdebug';
        if (!empty($file['xhprof'])) $have[] = 'xhprof';

        echo '<option';
        if ($k == $_GET['x']) echo ' selected="selected"';
        echo ' value="' . $k . '">';
        echo strftime('%d %H:%M',$file['time']);
        echo strtok($k,'.');
        if (count($have)) {
            echo ' (' . implode(', ',$have) . ')';
        }
        echo '</option>';
    }
    break;

case 'view':

    include(APP_ROOT . '/view/view.php');

    break;
}
