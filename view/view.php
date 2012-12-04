<?php
/**
    @file
    @brief View some Data

*/

$stat = phprof::stat();
$tree = phprof::tree();

// echo dump($stat);

echo '<h2>Viewing: ' . $_GET['x'] . '</h2>';

// echo dump($func_tree);
echo '<h2>Script: ' . _show_stat($stat['xdebug']['file'],$stat['xhprof']['file']) . '</h2>';
echo '<p>Summary: ' . _show_stat($stat['xdebug']['tfc'],$stat['xhprof']['tfc']) . ' functions (' . _show_stat($stat['xdebug']['ufc'],$stat['xhprof']['ufc']) . ' unique)</p>';
echo '<p>Timing: ' . _show_stat($stat['xdebug']['cost'], $stat['xhprof']['time']) . '</p>';
// echo '<p>Using: ' . $func_tree['main()']['icpu'] . ' CPU &#956;s; ' . $func_tree['main()']['imu'] . ' (' . $func_tree['main()']['imp'] . ') MEM.</p>';

// echo dump($func_tree['main()']);

echo '<table border="1" id="list">';
echo '<thead>';
echo '<tr>';
echo '<th width="64">Idx</th>';
echo '<th width="256">Function</th>';
echo '<th width="64">Called</th>';
echo '<th width="64">Called%</th>';
echo '<th width="64">Caller</th>';
echo '<th width="64">Ex &#956;s</th>';
echo '<th width="64">Ex Mem</th>';
echo '<th width="64">Ex &#x2308;Mem&#x2309;</th>';
echo '<th width="64">Ex CPU</th>';
// echo '<th>In Ct</th>';  // Inclusive Count? - WOrthless /djb
echo '<th width="64">In &#956;s</th>';
echo '<th width="64">In Mem</th>';
echo '<th width="64">In &#x2308;Mem&#x2309;</th>';
// echo '<th width="64">I-Memory</th><th>I-Memory%</th>';
echo '</tr>';
echo '</thead>';

echo '<tbody>';
foreach ($tree as $func=>$perf) {
    echo '<tr>';
    echo '<td>' . _show_stat($perf['xdebug_tick'],$perf['xhprof_tick']) . '</td>';
    echo '<td><a href="?' . http_build_query(array('x'=>$_GET['x'],'f'=>$func)) . '">' . html($func) . '</a></td>';
    echo '<td class="r">' . _show_stat($perf['xdebug']['xct'],$perf['xhprof']['xct']) . '</td>';
    echo '<td class="r">&mdash;</td>';
    echo '<td class="r">' . _show_stat($perf['xdebug']['ict'],$perf['xhprof']['ict']) . '</td>'; // Inclusive Call Count - Direct Sub Calls

    // Inclusive Wall Time

    // echo '<td class="r">' . _show_stat($perf['xdebug']['xwt'], $perf['xhprof']['iwt'] - $perf['xhprof']['xwt']) . '</td>'; // Exclusive Wall Time MS
    echo '<td class="r">' . _show_stat($perf['xdebug']['xwt'], $perf['xhprof']['xwt']) . '</td>';
    // echo '<td class="r">' . ($perf['xhprof']['imu'] - $perf['xhprof']['xmu']) . '</td>'; // <td class="r">&mdash;</td>'; // Exclusive Memory Usage (imu - smu)
    echo '<td class="r">' . ($perf['xhprof']['xmu']) . '</td>';
    // echo '<td class="r">' . ($perf['xhprof']['imp'] - $perf['xhprof']['xmp']) . '</td>'; // <td class="r">&mdash;</td>'; // Exclusive Memory Peak
    echo '<td class="r">' . ($perf['xhprof']['xmp']) . '</td>';
    echo '<td class="r">' . ($perf['xhprof']['xcpu']) . '</td>';

    // Inclusive WT
    echo '<td class="r">' . _show_stat($perf['xdebug']['iwt'], $perf['xhprof']['xwt'] - $perf['xhprof']['iwt']) . '</td>';

    // Inclusive Memory
    echo '<td class="r">' . _show_stat($perf['xdebug']['imu'], $perf['xhprof']['xmu'] - $perf['xhprof']['imu']) . '</td>';

    // Inclusive Memory Peak
    echo '<td class="r">' . _show_stat($perf['xdebug']['imp'], $perf['xhprof']['xmp'] - $perf['xhprof']['imp']) . '</td>';

    // echo '<td class="r">' . ($perf['xhprof']['icpu'] - $perf['xhprof']['xcpu']) . '</td>';
    // echo '<td class="r">' . $prof['ict'] . '</td>'; // Inclusive Count? - WOrthless /djb
    // echo '<td class="r">' . $perf['iwt'] . '</td><td class="r">&mdash;</td>'; // Inclusive Wall Time MS
    // echo '<td class="r">' . $prof['imu'] . '</td><td class="r">&mdash;</td>'; // 
    // echo '<td class="r">' . $prof['imp'] . '</td><td class="r">&mdash;</td>'; // Inclusive Peak Memory Usage
    if ($func == '{main}') {
        unset($perf['xhprof']['cfn']);
        unset($perf['xhprof']['pfn']);
        // echo '<td>' . dump($perf) . '</td>';
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

// echo dump($perf);

function _show_stat($xd,$xh)
{
    $ret = array();

    if ($xd == $xh) {
        $ret = array($xd);
    } else {
        if (!empty($xd)) {
            $ret[] = '<span title="xdebug">' . $xd . '</span>';
        }
        if (!empty($xh)) {
            $ret[] = '<span title="xhprof">' . $xh . '</span>';
        }
    }
    return implode('|',$ret);
}

?>

<script type="text/javascript">
$(document).ready(function() {
    $('#list').dataTable({
        bPaginate: false,
        sScrollY:'500px'
    });
});


// $(document).ready(function() {
//     $('#list').indrid();
// });

// $(document).ready(function() {
//     $('#list').flexigrid({
//         colModel: [
//         { display:'IDX', name:'idx', width: 64, sortable: true },
//         { display:'Function', name:'func', width:384, sortable: true },
//         { sortable: true },
//         { sortable: true },
//         { sortable: true }
//         ],
//         height: 'auto'
//     });
// });
</script>