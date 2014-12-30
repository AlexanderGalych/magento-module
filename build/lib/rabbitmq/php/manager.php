<?php
/**
 * Created by PhpStorm.
 * User: olg
 * Date: 24.12.14
 * Time: 18:24
 */

$cwd = '';
$env = array('some_option' => 'aeiou');
$i = 5;
while($i-- > 0) {
    $process = proc_open('php processor.php', array(), $pipes, $cwd, $env);
    if (is_resource($process)) {
        $processOption = proc_get_status($process);
        var_dump($processOption['pid']);
         sleep(10);
        //posix_kill($processOption['pid'], 9);
    }
}