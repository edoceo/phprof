<?php
/**
    Edoceo PHP Profiler
    $Id$
    
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

define('APP_ROOT',dirname(__FILE__));
require_once(APP_ROOT . '/lib/boot.php');
require_once(APP_ROOT . '/lib/phprof.php');
require_once(APP_ROOT . '/lib/xdebug.php');
require_once(APP_ROOT . '/lib/xhprof.php');

echo <<<EOH
<!doctype html>
<html lang="en-us">
<head>
<title>{$_ENV['title']}</title>
<link href="//gcdn.org/radix/radix.css" rel="stylesheet" type="text/css">
<link href="css/base.css" rel="stylesheet" type="text/css">
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<script src="//gcdn.org/jquery/1.8.3/jquery.js" type="text/javascript"></script>
</head>
<body>
<header>
<ul id="menu">
EOH;
// $p = (Radix::$path == '/index' ? '/' : Radix::$path);
$list = array();
$list[''] = 'PHProf';
// $list['xdebug'] = 'xdebug';
// $list['xhprof'] = 'xhprof';
// $list['webgrind'] = 'webgrind';
// $list['xhprof'] = 'xhprof';
$list['config'] = 'config';

foreach ($list as $k=>$v) {
    echo '<li>';
    echo '<a';
    if ($k == $_GET['a']) echo ' class="h"';
    echo ' href="?a=' . $k . '">' . $v . '</a>';
    echo '</li>';
}
// echo '<li style="padding:4px 0px 0px 16px;"><div class="g-plusone"></div></li>';
// echo '<li><a href="/webgrind/?run=' . $_GET['x'] . '">webgrind</a></li>';
// echo '<li><a href="/xhprof/xhprof_html/?run=' . $_GET['x'] . '">xhprof</a></li>';
echo '</ul>';
// <ul id="menu">
// <li><a class="h" href="/">Opus</a></li><li><a href="/groups">Groups</a></li><li><a href="/calendar">Calendar</a></li><li><a href="/tools">Tools</a></li><li><a href="/jobs">Jobs</a></li><li><a href="/contact">Contact</a></li></ul>
// <a href="?a=">Home</a> | <a href="?a=xdebug">xdebug</a> | <a href="?a=xhprof">xhprof</a> | <a href="?a=config">config</a>
echo '</header>';

echo '<section>';

switch ($_GET['a']) {
case 'config':

    include(APP_ROOT . '/view/config.php');

    // echo dump($_SERVER);
    ob_start();
    phpinfo();
    $html = ob_get_clean();
    if (preg_match('/<body>(.+)<\/body>/ms',$html,$m)) {
        $html = $m[1];
    }
    echo $html;
    
    // Show Config
    break;
default:
    include(APP_ROOT . '/view/index.php');
    if (!empty($_GET['x'])) {
        if (phprof::load($_GET['x'])) {
            include(APP_ROOT . '/view/view.php');
        }
    }
}

echo '</section>';

// Footer
echo '<footer><address>';
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
echo '</address></footer>';

echo '</body>';
echo '</html>';
