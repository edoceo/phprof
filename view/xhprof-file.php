<?php
/**
    @file
    @brief Show a PHP Profile
*/

// include("$base/view/xhprof-list.php");

echo '<h2>Viewing: ' . $_GET['x'] . '</h2>';

// echo dump($func_tree);
echo '<h2>Script: ' . $exec_file . '</h2>';
echo '<p>Summary: ' . $func_info['ct'] . ' functions (' . count($func_tree) . ' unique) in ' . $func_tree['main()']['iwt'] . ' &#956;s</p>';
echo '<p>Using: ' . $func_tree['main()']['icpu'] . ' CPU &#956;s; ' . $func_tree['main()']['imu'] . ' (' . $func_tree['main()']['imp'] . ') MEM.</p>';

// echo dump($func_tree['main()']);

echo '<table border="1">';
echo '<tr>';
echo '<th>Function</th>';
echo '<th>Called</th><th>Called%</th><th>Caller</th>';
echo '<th>Ex &#956;s</th>';
echo '<th>Ex Mem</th>';
echo '<th>Ex CPU</th>';
// echo '<th>In Ct</th>';  // Inclusive Count? - WOrthless /djb
echo '<th>In &#956;s</th><th>In &#956;s%</th>';
echo '<th>I-Memory</th><th>I-Memory%</th>';
foreach ($func_tree as $func=>$prof) {
    echo '<tr>';
    echo '<td><a href="?' . http_build_query(array('a'=>$_GET['a'],'x'=>$_GET['x'],'f'=>$func)) . '">' . html($func) . '</a></td>';
    echo '<td class="r">' . $prof['ict'] . '</td>'; // Inclusive Calls
    echo '<td class="r">&mdash;</td>';
    echo '<td class="r">' . $prof['xct'] . '</td>'; // <td class="r">&mdash;</td>'; // Direct Sub-Funtion Calls
    echo '<td class="r">' . ($prof['iwt'] - $prof['xwt']) . '</td>'; // Exclusive Wall Time MS
    echo '<td class="r">' . ($prof['imu'] - $prof['xmu']) . '</td>'; // <td class="r">&mdash;</td>'; // Exclusive Memory Usage (imu - smu)
    // echo '<td class="r">' . $prof['xmp'] . '</td><td class="r">&mdash;</td>'; // Exclusive Peak Memory Usage
    echo '<td class="r">' . ($prof['icpu'] - $prof['xcpu']) . '</td>';

    // echo '<td class="r">' . $prof['ict'] . '</td>'; // Inclusive Count? - WOrthless /djb
    echo '<td class="r">' . $prof['iwt'] . '</td><td class="r">&mdash;</td>'; // Inclusive Wall Time MS
    // echo '<td class="r">' . $prof['imu'] . '</td><td class="r">&mdash;</td>'; // 
    // echo '<td class="r">' . $prof['imp'] . '</td><td class="r">&mdash;</td>'; // Inclusive Peak Memory Usage
    if ($func == 'PDO::__construct') {
        unset($prof['cfn']);
        unset($prof['pfn']);
        echo '<td>' . dump($prof) . '</td>';
    }
    echo '</tr>';
}
echo '</table>';

// echo dump(array_keys($func_tree));