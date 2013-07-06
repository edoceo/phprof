/**

*/

var phprof = {};

phprof.list_load = function(x) {
    $('#phprof-list').html('<option>Loading...</option>');
    $('#phprof-list').load('ajax.php',{a:'load'});
}


phprof.page_view = function(x) {
    $('#xhprof-view').load('ajax.php',{a:'view',x:x});
}