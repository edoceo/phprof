<?php
/**
    Bootstrapper for PHProf

*/

$_ENV['title'] = 'PHProf';

function dump($x) { return "\n<pre class='dump'>\n" . html(print_r($x,true)) . "\n</pre>\n"; }
function html($x) { return htmlentities($x,ENT_QUOTES,'utf-8',false); }
