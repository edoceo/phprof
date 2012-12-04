<?php
/**

*/

class xhprof
{
    private static $_stat;
    
    /**
    */
    static function stat()
    {
        return self::$_stat;
    }

    static function tree($f)
    {
        if (!is_file($f)) {
            return false;
        }

        $data = unserialize(file_get_contents($f));
        if (!empty($data['file'])) {
            self::$_stat['file'] = $data['file'];
            unset($data['file']);
        }

        self::$_stat['tfc'] = 0;
        self::$_stat['ufc'] = 0;

        $func_tree = array();

        foreach ($data as $func=>$prof) {

            $from = null; // Calling Function

            if (preg_match('/^(.+)==>(.+)$/',$func,$m)) {
                $from = $m[1];
                $func = $m[2];
            }
            if (empty($func)) $func = 'anonymous()';
            // if (substr($func,0,6)=='load::'); // $func = str_replace('load::','require_once';
            // if (substr($func,0,10)=='run_init::') $func = str_replace('run_init::','load::',$func);
            

            if (empty($func_tree[$func])) {
                self::$_stat['ufc']++;
            }
            // Add Information to Function
            // $func_tree[$func]['ict'] += $prof['ct'];
            // $func_tree[$func]['iwt'] += $prof['wt'];
            // $func_tree[$func]['imu'] += $prof['mu'];
            // $func_tree[$func]['imp'] += $prof['pmu'];
            $func_tree[$func]['xct'] += $prof['ct'];
            $func_tree[$func]['xwt'] += $prof['wt'];
            $func_tree[$func]['xmu'] += $prof['mu'];
            $func_tree[$func]['xmp'] += $prof['pmu'];
            $func_tree[$func]['icpu'] += $prof['cpu'];

            if (!empty($from)) {
                if (empty($func_tree[$from])) {
                    self::$_stat['ufc']++;
                }
                $func_tree[$from]['ict'] += $prof['ct'];
                $func_tree[$from]['iwt'] += $prof['wt'];
                $func_tree[$from]['imu'] += $prof['mu'];
                $func_tree[$from]['imp'] += $prof['pmu'];
                $func_tree[$from]['icpu'] += $prof['cpu'];
            }

            self::$_stat['tfc'] += $prof['ct']; // Total Function Count
            // self::$_stat['time'] += $prof['wt']; // Total Function Count

            continue;

            if (!empty($from)) {
                echo "Func:$func; From: $from\n";
                die(dump($prof));
                // Add current $func metrics to parent $from
                if (empty($func_tree[$from])) {
                    $func_tree[$from] = array(
                        'ict' => $prof['ct'],
                        'iwt' => $prof['wt'],
                        'imu' => $prof['mu'],
                        'imp' => 0,
                        'icpu' => 0,
                        'xct' => 0,
                        'xwt' => $prof['wt'],  // Exclusive Time of Current
                        'xmu' => $prof['mu'],  // Exclusive Memory of Current
                        'xmp' => $prof['pmu'], //
                        'xcpu' => $prof['cpu'],
                        'pfn' => array(),
                        'cfn' => array($func),
                    );
                    self::$_stat['ufc']++;
                } else {
                    $func_tree[$from]['iii']++;
                    $func_tree[$from]['ict'] += $prof['ct'];
                    $func_tree[$from]['iwt'] += $prof['wt'];
                    $func_tree[$from]['imu'] += $prof['mu'];
                    $func_tree[$from]['imp'] += $prof['pmu'];
                    // $func_tree[$from]['xcpu'] += $prof['cpu'];
                    $func_tree[$from]['cfn'][] = $func;
                }
            }

            // Initialise this Function Tree Item
            if (empty($func_tree[$func])) {
                $func_tree[$func] = array(
                    'ict' => $prof['ct'],
                    'iwt' => $prof['wt'],
                    'imu' => $prof['mu'],
                    'imp' => 0,
                    'icpu' => 0,
                    'xct' => $prof['ct'],  // Inclusive Call Count
                    'xwt' => $prof['wt'],  // Inclusive Wall Milli-Seconds
                    'xmu' => $prof['mu'],  // Inclusive Memory Usage
                    'xmp' => $prof['pmu'], // Inclusive Peak Memory Usage
                    'xcpu' => $prof['cpu'],
                    'pfn' => array($from),         // Parent Function
                    'cfn' => array(),      // Child Functions
                );
                self::$_stat['ufc']++;
            } else {
                $func_tree[$func]['xxx']++;
                $func_tree[$func]['xct'] += $prof['ct'];
                $func_tree[$func]['xwt'] += $prof['wt'];
                $func_tree[$func]['xmu'] += $prof['mu'];
                $func_tree[$func]['xmp'] += $prof['pmu'];
                $func_tree[$func]['xcpu'] += $prof['cpu'];
                $func_tree[$func]['pfn'][] = $from;
                // echo dump($func_tree);
                // echo dump($func);
                // echo dump($prof);
                // die("What to dp here?");
                // if (!empty($func_tree[$func]['pfn'])) {
                //     echo dump($func_tree);
                //     echo dump($func);
                //     echo dump($prof);
                //     die("Duplicate Parent? $from => {$func_tree[$func]['pfn']}\n");
                // }
                // $func_tree[$func]['ict'] = $prof['ct']; //  = array(
                // $func_tree[$func]['iwt'] = $prof['wt'];
                // $func_tree[$func]['imu'] = $prof['mu'];
                // $func_tree[$func]['imp'] = $prof['pmu'];
                // $func_tree[$func]['pfn'] = $from;
            }
        
            // if (!in_array($func,$func_tree[$from]['cfn'])) {
            //     $func_tree[$from]['cfn'][] = $func;
            // }
        
            // if (!in_array($from,$func_tree[$func]['cfn']))
            //     $func_tree[$func]['pfn'][] = $from;
            //     
            // }
            self::$_stat['tfc'] += $prof['ct']; // Total Function Count
            // if ($func == 'file') echo dump($prof);

        }

        // echo dump($func_info);
        return $func_tree;
    }
}