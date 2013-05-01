<?php
/**

*/

echo '<h1>Welcome to PHProf</h1>';
$stat = phprof::stat();
foreach ($stat as $k=>$v) {
    echo '<p>' . $v . '</p>';
}
?>

<p>
PHProf is a toolset and redesigned front-end for viewing xdebug and xhprof data.
The tool was heavily influenced by the existing user-interfaces for these tools.
</p>

<?php

