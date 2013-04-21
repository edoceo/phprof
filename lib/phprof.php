<?php
/**

*/

class phprof
{
    private static $_sql;
    private static $_xdebug_tree;
    private static $_xhprof_tree;

    /**
        
    */
    static function dump()
    {
        return self::$_cfg;
    }
    static function stat()
    {
        $stat = array();
        $path = ini_get('xdebug.profiler_output_dir');
        if (!is_dir($path)) {
            $stat['xdebug'] = 'xDebug Output Directory Missing';
        }
        $path = ini_get('xhprof.output_dir');
        if (!is_dir($path)) {
            $stat['xhprof'] = 'XHProf Output Directory Missing';
        }

        return $stat;
    }
    /**
        List of Executed Scripts
    */
    static function listProfileOutputs()
    {
        $file_list = array();

        // Load xdebug
        $path = ini_get('xdebug.profiler_output_dir');
        if (!is_dir($path)) {
            return $file_list;
        }
        // File Name Pattern
        $name = ini_get('xdebug.profiler_output_name');
        $name = '/^'.preg_replace('/(%[^%])+/', '.+', $name_re).'$/';

        $list = scandir($path);
        foreach ($list as $file) {
            if (preg_match($name,$file,$m)) {
                $file_list[$m[1]]['xdebug'] = sprintf('%s/%s',$path,$file);
            }
        }
        // echo dump($list);
        // Load xhprof Files
        $path = ini_get('xhprof.output_dir');
        if (!is_dir($path)) {
            return $file_list;
        }
        // File Name Pattern
        $name = ini_get('xhprof.output_name');
        $name = '/^'.preg_replace('/(%[^%])+/', '.+', $name_re).'$/';

        $list = scandir($path);
        foreach ($list as $file) {
            if (preg_match('/(.+)\.xhprof$/',$file,$m)) {
                $file_list[$m[1]]['xhprof'] = sprintf('%s/%s',$path,$file);
            }
        }

        // $name = phprof::config('xdebug_name');
        // $path = phprof::config('xdebug_path');
        // foreach ($list as $file) {
        //     // print_r($file);
        //     if (fnmatch($name,$file)) $file_list[] = $file;
        // }
        // 
        // $name = phprof::config('xhprof_name');
        // $path = phprof::config('xhprof_path');
        // $list = scandir($path);
        // $file_list = array();
        // foreach ($list as $file) {
        //     // print_r($file);
        //     if (fnmatch($name,$file)) $file_list[] = $file;
        // }
        ksort($file_list);
        return $file_list;
    }
    /**
    */
    static function load($uid)
    {
        $sqlite_file = sprintf('%s/%s.sqlite',ini_get('xhprof.output_dir'),$uid);
        $xdebug_file = sprintf('%s/%s.xdebug',ini_get('xdebug.profiler_output_dir'),$uid);
        $xhprof_file = sprintf('%s/%s.xhprof',ini_get('xhprof.output_dir'),$uid);

        if (is_file($sqlite_file)) {
            return;
        }

        // self::$_sql = sqlite_open($sqlite_file,0666,$esz);
        // $sql = 'CREATE TABLE tree (id INT AUTOINCREMENT, fl TEXT, fn TEXT, ct INT, wt INT)';
        // sqlite_exec($sql,self::$_sql);
        // $sql.= 'CREATE TABLE info (k TEXT, v TEXT)';
        // sqlite_exec($sql,self::$_sql);

        self::$_xdebug_tree = xdebug::tree($xdebug_file);
        self::$_xhprof_tree = xhprof::tree($xhprof_file);

        // Loop Each, Merging to Master Function Tree
        // echo dump(array_keys(self::$_xdebug_tree));
        // echo dump(array_keys(self::$_xhprof_tree));

        return true;

    }
    /**
    */
    /**
    */
    static function tree()
    {

        $tree = array();

        // Spin xdebug
        $i = 0;
        foreach (self::$_xdebug_tree as $k=>$v) {

            // Mangle Name
            $k = str_replace('php::',null,$k);
            $k = str_replace('->','::',$k);

            $tree[$k]['xdebug'] = $v;
            $tree[$k]['xdebug_tick'] = $i;

            $i++;
        }

        // Spin xhprof
        $i = 0;
        if ( (!empty(self::$_xhprof_tree)) && (is_array(self::$_xhprof_tree)) ) {
            foreach (self::$_xhprof_tree as $k=>$v) {
    
                // Mangle Name
                if ($k=='main()') $k = '{main}'; // xhprof name to xdebug name
                // if (substr($k,0,6)=='load::') continue;
                // if (substr($k,0,10)=='run_init::') continue;
    
                // $tree[$k]['tick']++;
                $tree[$k]['xhprof'] = $v;
                $tree[$k]['xhprof_tick'] = $i;
    
                $i++;
            }
        }

        return $tree;
    }

}
