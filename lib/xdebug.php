<?php
/**
    Reads and Parses an Xdebug file

    @todo would be more efficient using fscanf()
*/

class xdebug
{
    private static $_stat = array(
        'tfc' => 0, // Total Function Count
        'ufc' => 0, // Unique Function Count
        
    );

    public static function stat()
    {
        return self::$_stat;
    }

    public static function tree($file)
    {
        // echo "fopen($file,'r');";
        $fh = fopen($file,'r');

        self::$_stat['version'] = self::_fscanf($fh,'version: %d');
        self::$_stat['creator'] = self::_fscanf($fh,'creator: %s');
        self::$_stat['file'] = self::_fscanf($fh,'cmd: %s');
        self::$_stat['tfc'] = 0; // Total Function Count
        self::$_stat['ufc'] = 0; // Unique Function Count

        $fl = $fn = $cfl = $cfn = null;

        $xdebug_tree = array();

        while ($line = fgets($fh)) {

            //echo "$line\n";
            //echo "fl:$fl; fn:$fn; cfl:$cfl; cfn=$cfn;\n";
            if (preg_match('/^fl=(.+)/',$line,$m)) {
                // $xdebug_prof = array('fl' => $m[1]);
                $fl = $m[1];
                $fn = null;
                $cfl = null;
                $cfn = null;
                continue;
            }
            if (preg_match('/^fn=(.+)/',$line,$m)) {
                $fn = $m[1];

                // Mangle Names
                $fn = str_replace('include::','load::',$fn);
                $fn = str_replace('require::','load::',$fn);
                $fn = str_replace('include_once::','load::',$fn);
                $fn = str_replace('require_once::','load::',$fn);

                if (empty($xdebug_tree[$fn])) {
                    $xdebug_tree[$fn] = array(
                        'fl' => $fl,
                        'ln' => array(),
                        'xct' => 0,
                        'xwt' => 0,
                        'iwt' => 0,
                    );
                    self::$_stat['ufc']++;
                }
                $xdebug_tree[$fn]['xct']++;
                self::$_stat['tfc']++;
                // $xdebug_prof['fn'] = $m[1];
                // if (empty($xdebug_tree[ $xdebug_prof['fn'] ])) {
                //     $xdebug_tree[ $xdebug_prof['fn'] ] = array(
                //         'ct' => 1,
                //         'wt' => 0
                //     );
                // }
                if ($fn == '{main}') {
                    // Get Next Blank Line
                    $line = fgets($fh);
                    // Get Summary Line
                    self::$_stat['time'] = self::_fscanf($fh,'summary: %d');
                    // Get Next Blank Line
                    $line = fgets($fh);
                }

                continue;
            }
            // First Time Metrics Seen this Iteration - Add to FN
            if ( (empty($cfn)) && (preg_match('/^(\d+) (\d+)$/',$line,$m)) ) {
                // $xdebug_prof['line'] = $m[1];
                // $xdebug_prof['cost'] = $m[2];
                // $xdebug_tree[$fn]['ln'][] = $m[1];
                $xdebug_tree[$fn]['iwt'] += $m[2];
                continue;
            }

            // Child Routines/Stack
            // Got a New One!
            if (preg_match('/^cfl=(.+)/',$line,$m)) {
                // $xdebug_subf = array('fl' => $m[1]);
                $cfl = $m[1];
                continue;
            }
            if (preg_match('/^cfn=(.+)/',$line,$m)) {
                // $xdebug_subf['fn'] = $m[1];
                $cfn = $m[1];
                // echo "fl:$fl; fn:$fn; cfl:$cfl; cfn=$cfn;\n";
                continue;
            }

            if ( (!empty($cfn)) && (preg_match('/^(\d+) (\d+)$/',$line,$m)) ) {

                // Mangle Names
                $cfn = str_replace('include::','load::',$cfn);
                $cfn = str_replace('require::','load::',$cfn);
                $cfn = str_replace('include_once::','load::',$cfn);
                $cfn = str_replace('require_once::','load::',$cfn);

                // $xdebug_tree[$cfn]['iwt'] += $m[2];
                continue;
            }

            // if (preg_match('/calls=(\d+) (\d+) (\d+)/',$line,$m)) {
            //     // IDK What These Are
            //     $xdebug_subf['call'] = $m;
            // }
            // Last line of Child Info
            // if (preg_match('/(\d+) (\d+)/',$line,$m)) {
            //     $xdebug_subf['line'] = $m[1];
            //     $xdebug_subf['cost'] = $m[2];
            //     $xdebug_prof['subs'][] = $xdebug_subf;
            //     echo dump($xdebug_prof);
            //     echo dump($xdebug_subf);
            //     continue;
            // }

            if ($line == "\n") {
                $fl = $fn = null;
                $cfl = $cfn = null;
            }

        }

        return $xdebug_tree;
    }

    /**
        fscanf Wrapper
    */
    private static function _fscanf($h,$f)
    {
        $ret = fscanf($h,$f);
        if ( (is_array($ret)) && (count($ret)==1) ) {
            return $ret[0];
        }
        return $ret;
    }
}