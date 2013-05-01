<?php
/**

http://www.cs.tut.fi/~jkorpela/html/characters.html
http://lachy.id.au/dev/markup/tests/html5/charref/


    Edoceo PHP Profiler
    
    @see http://www.php.net/manual/en/book.xhprof.php
    @see http://techportal.ibuildings.com/2009/12/01/profiling-with-xhprof/
    @see http://valgrind.org/docs/manual/cl-format.html
    @see http://www.flexigrid.info/
    @see http://www.datatables.net/examples/basic_init/scroll_y.html
    
Inclusive Time includes the time spent in the function itself and in all the descendant functions;
Exclusive Time only measures time spent in the function itself, without including the descendant calls.

Install:
  pecl install --force xdebug
  pecl install --force xhprof

*/

$_t0 = microtime(true);
error_reporting((E_ALL | E_STRICT) ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
// header('Content-Type: ');

define('APP_ROOT',dirname(__FILE__));
require_once(APP_ROOT . '/lib/boot.php');
require_once(APP_ROOT . '/lib/phprof.php');
require_once(APP_ROOT . '/lib/xdebug.php');
require_once(APP_ROOT . '/lib/xhprof.php');

$_ENV['title'] = 'PHProf';


echo <<<EOH
<!doctype html>
<html lang="en-us">
<head>
<title>{$_ENV['title']}</title>
<link href="//gcdn.org/radix/radix.css" rel="stylesheet" type="text/css">
<link href="css/base.css" rel="stylesheet" type="text/css">
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<script src="//gcdn.org/jquery/1.9.1/jquery.js" type="text/javascript"></script>
<script src="phprof.js" type="text/javascript"></script>
</head>
<body>
<header>
<ul id="menu">
EOH;
// $p = (Radix::$path == '/index' ? '/' : Radix::$path);
$list = array();
$list[''] = 'PHProf';
// $list['xdebug'] = 'xdebug';
$list['xhprof'] = 'xhprof';
$list['webgrind'] = 'WebGrind';
$list['config'] = 'config';

foreach ($list as $k=>$v) {
    echo '<li>';
    echo '<a';
    if ($k == $_GET['a']) echo ' class="h"';
    echo ' href="?a=' . $k . '">' . $v . '</a>';
    echo '</li>';
}
echo '<li><a href="test.php">test</a></li>';
echo '</ul>';
echo '<div style="float:right;margin:2px;">';
echo '<select id="phprof-list" style="max-width:480px;"><option value="">Loading...</option></select>';
echo '<button id="phprof-list-load" style="margin:2px;">&#x021BA;</button>';
// echo '<button id="phprof-view" style="margin:2px;">&#x021BA;</button>';
echo '<button id="phprof-view" style="margin:2px;">&#x021B5;</button>';
echo '</div>';
// <ul id="menu">
// <li><a class="h" href="/">Opus</a></li><li><a href="/groups">Groups</a></li><li><a href="/calendar">Calendar</a></li><li><a href="/tools">Tools</a></li><li><a href="/jobs">Jobs</a></li><li><a href="/contact">Contact</a></li></ul>
// <a href="?a=">Home</a> | <a href="?a=xdebug">xdebug</a> | <a href="?a=xhprof">xhprof</a> | <a href="?a=config">config</a>
echo '</header>';

echo '<section id="xhprof-view">';

switch ($_GET['a']) {
case 'xhprof':
    $uri = sprintf('http://%s/xhprof',$_SERVER['SERVER_NAME']);
    header('Location: ' . $uri);
    die('Location: <a href="' . $uri . '">' . $uri . '</a>');
    break;
case 'webgrind':
    $uri = sprintf('http://%s/webgrind',$_SERVER['SERVER_NAME']);
    header('Location: ' . $uri);
    die('Location: <a href="' . $uri . '">' . $uri . '</a>');
    break;
case 'config':
    // Show Config
    include(APP_ROOT . '/view/config.php');
    break;
default:
    include(APP_ROOT . '/view/index.php');
    // if (!empty($_GET['x'])) {
    //     if (phprof::load($_GET['x'])) {
    //         include(APP_ROOT . '/view/view.php');
    //     }
    // }
}

echo '</section>';

// Footer
echo '<footer>';
echo '<a href="http://radix.edoceo.com/performance">PHProf</a> | ';
// Log Time, Memory, Query Count
if (!empty($_t0)) {

    $set = array();

    // Time
    $set[] = ceil((microtime(true)-$_t0)*1000) . 'ms';

    // Memory Usage
    $set[] = ceil(memory_get_peak_usage(true) / 1024) . 'kb';

    // File Count
    // $set[] = count(get_included_files()) . 'f';

    echo implode(' | ',$set);
}
echo '</footer>';
?>
<script>
$(document).ready(function() {
    $('#phprof-list').on('change',function() {
        phprof.page_view($('#phprof-list').val());
    });

    $('#phprof-list-load').on('click',function() {
        phprof.list_load();
    });

    $('#phprof-view').on('click',function() {
        phprof.page_view($('#phprof-list').val());
    });

    $('#phprof-list-wipe').on('click',function() {
        phprof.list_wipe();
    });
    // $('#phprof-list').on();

    phprof.list_load();
});
</script>
<?php
echo '</body>';
echo '</html>';
