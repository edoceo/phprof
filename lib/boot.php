<?php
/**
    Bootstrapper for PHProf

*/

function dump($x) { return "\n<pre class='dump'>\n" . html(print_r($x,true)) . "\n</pre>\n"; }
function html($x) { return htmlentities($x,ENT_QUOTES,'utf-8',false); }


function _show_stat($xd,$xh=null)
{
    $ret = array();

    if (trim($xd) == trim($xh)) {
        $ret = $xd;
    } else {
        if (!empty($xd)) {
            $ret[] = '<span title="xdebug">' . $xd . '</span>';
        }
        if (!empty($xh)) {
            $ret[] = '<span title="xhprof">' . $xh . '</span>';
        }
        $ret = implode('|',$ret);
    }
    // @todo output better hints
    // $ret = max($xd,$xh);
    return $ret;
}
