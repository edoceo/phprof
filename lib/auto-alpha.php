<?php
/**
    phprof Prepend Script
*/

if (function_exists('xhprof_enable')) {
    // Base Datas
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
}