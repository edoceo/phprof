<?php
/**

*/

echo '<h1>Welcome to PHProf</h1>';

?>

<p>
PHProf is a toolset and redesigned front-end for viewing xdebug and xhprof data.
The tool was heavily influenced by the existing user-interfaces for these tools.
</p>

<?php

$file_list = phprof::listProfileOutputs();

// Debug Output Files
echo '<form>';
echo '<fieldset>';
echo '<legend>Select the debug file</legend>';
echo '<select id="phprof_file" name="x"><option value="">Select File</option>';
foreach ($file_list as $k=>$file) {

    $have = array();
    if (!empty($file['xdebug'])) $have[] = 'xdebug';
    if (!empty($file['xhprof'])) $have[] = 'xhprof';

    echo '<option';
    if ($k == $_GET['x']) echo ' selected="selected"';
    echo ' value="' . $k . '">';
    echo strtok($k,'.');
    if (count($have)) {
        echo ' (' . implode(', ',$have) . ')';
    }
    echo '</option>';
}
echo '</select>';
echo '</fieldset>';
echo '</form>';

// Auto Load Form
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#phprof_file').change(function() {
        $('form').submit();
    });
});
</script>