<?php
/**
    Show the Configuration
*/

// echo dump($file_list);

/*
xdebug.auto_trace	Off	Off
xdebug.collect_includes	On	On
xdebug.collect_params	0	0
xdebug.collect_return	Off	Off
xdebug.collect_vars	Off	Off
xdebug.default_enable	Off	On
xdebug.dump.COOKIE	no value	no value
xdebug.dump.ENV	no value	no value
xdebug.dump.FILES	no value	no value
xdebug.dump.GET	no value	no value
xdebug.dump.POST	no value	no value
xdebug.dump.REQUEST	no value	no value
xdebug.dump.SERVER	no value	no value
xdebug.dump.SESSION	no value	no value
xdebug.dump_globals	On	On
xdebug.dump_once	On	On
xdebug.dump_undefined	Off	Off
xdebug.extended_info	On	On
xdebug.idekey	no value	no value
xdebug.manual_url	http://www.php.net	http://www.php.net
xdebug.max_nesting_level	100	100
xdebug.profiler_aggregate	Off	Off
xdebug.profiler_append	Off	Off
xdebug.profiler_enable	Off	Off
xdebug.profiler_enable_trigger	Off	Off
xdebug.profiler_output_dir	/tmp	/tmp
xdebug.profiler_output_name	%p.xdebug	cachegrind.out.%p
xdebug.remote_autostart	Off	Off
xdebug.remote_enable	Off	Off
xdebug.remote_handler	dbgp	dbgp
xdebug.remote_host	localhost	localhost
xdebug.remote_log	no value	no value
xdebug.remote_mode	req	req
xdebug.remote_port	9000	9000
xdebug.show_exception_trace	Off	Off
xdebug.show_local_vars	Off	Off
xdebug.show_mem_delta	Off	Off
xdebug.trace_format	0	0
xdebug.trace_options	0	0
xdebug.trace_output_dir	/tmp	/tmp
xdebug.trace_output_name	trace.%c	trace.%c
xdebug.var_display_max_children	128	128
xdebug.var_display_max_data	512	512
xdebug.var_display_max_depth	3	3
*/

$want_list = array(
    'auto_prepend_file' => APP_ROOT . '/lib/auto-alpha.php',
    'auto_append_file' => APP_ROOT . '/lib/auto-omega.php',
    'xdebug.default_enable' => '1',
    'xdebug.profiler_enable' => '1',
    // When this setting is set to 1, you can trigger the generation of profiler files by using the XDEBUG_PROFILE GET/POST parameter, or set a cookie with the name XDEBUG_PROFILE. This will then write the profiler data to defined directory. In order to prevent the profiler to generate profile files for each request, you need to set xdebug.profiler_enable to 0.
    'xdebug.profiler_enable_trigger' => '1',
    'xdebug.profiler_append' => '0',
    'xdebug.profiler_output_dir' => '/tmp/phprof',
    'xdebug.profiler_output_name' => 'xdebug.%u.out',
    'xdebug.trace_output_dir' =>  '/tmp/phprof',
    'xdebug.trace_output_name' => 'xtrace.%u.trace',

    'xhprof.output_dir'  => '/tmp/phprof',
    'xhprof.output_name' => 'xhprof.%u.out',
);


echo '<table border="1">';
echo '<th>Setting</th><th>Reccomended</th><th>You</th>';

foreach ($want_list as $name => $want) {

    // $want = '/tmp/phprof';
    $have = ini_get($name);
    echo '<tr>';
    echo '<td>' . $name . '</td>';
    echo '<td>' . $want . '</td>';
    echo '<td class="' . ($want==$have ? 'good' : 'fail') . '">' . $have . '</td>';
    echo '</tr>';
}

echo '</table>';

echo '<p>See <a href="http://xdebug.org/docs/all_settings">xdebug.org/docs/all_settings</a> for more details</p>';
echo '<p>To Install WebGrind Use</p>';
echo '<pre>git clone git://github.com/jokkedk/webgrind.git ' . $_SERVER['DOCUMENT_ROOT'] . '/webgrind</pre>';

echo '<p>To Install Xhprof use</p>';
echo '<pre>git clone git://github.com/facebook/xhprof.git ' . $_SERVER['DOCUMENT_ROOT'] . '/xhprof</pre>';

// echo '<pre>';
// print_r(phprof::dump());
// echo '</pre>';
