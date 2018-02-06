http-tst.php
<?php
/*
必需要在 /etc/sudoers 加入下面兩行
Defaults:apache !requiretty
apache ALL=(ALL) NOPASSWD: /opt/adobe/ams/webroot/ajtest/cinfo/bin/get_open_vod_files.php
*/
function get_open_vod_files($pid)
{
    $cmd =  "sudo ".__DIR__."/bin/get_open_vod_files.php {$pid}";
	// $cmd = __DIR__."/bin/get_open_vod_files.php {$pid}";
    // echo "cmd: {$cmd} <br>\n";

    $descriptorspec = [
                0 => array("pipe", "r"),  // stdin
                1 => array("pipe", "w"),  // stdout
                2 => array("pipe", "w"),  // stderr
            ];
    $env = array('TERM' => 'xterm');

    $fd = proc_open($cmd, $descriptorspec, $pipes, null, $env);

    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    // $ret = proc_close($process);
	$ret = proc_close($fd);

    // echo "stdout: <br>".nl2br($stdout)."<br>\n";
    // echo "stderr: ".nl2br($stderr)."<br>\n";
    // echo "ret: $ret<br>\n";

    if ($stderr) {
       // throw new Exception("Failed to list open files: ".$stderr, 1);
	   echo "Failed to list open files: ".$stderr;
    }

    return $stdout;
}

function getHttpFiles() 
{
    $rr = shell_exec("ps ax | grep -v 'grep' | grep -e '/bin/httpd'");
     // echo "<br>result: ".nl2br($rr);
    $aa = preg_split("/\n/", $rr);
    // echo '<pre>'.print_r($aa, true).'</pre>';

    $files = [];

    foreach ($aa as $r) {
		$r = trim($r);
		// echo "$r <br>";
        $bb = preg_split("/\s+/", $r);
        // echo '<pre>'.print_r($bb, true).'</pre>';
		$pid = $bb[0];
		// echo "pid: {$pid}<br>\n";
        if (intval($pid) >0 ) { 
            $ff = get_open_vod_files($pid);
            // echo $ff;

            preg_match_all(
                "|/([^\/]*\.mp4)|U",
                $ff, $out, PREG_PATTERN_ORDER
            );

            // echo '<pre>'.print_r($out, true).'</pre>';
            $files = array_merge($files, $out[1]);
        }
    }

    // echo "tt ---";

    asort($files);
    // echo '<pre>'.print_r($out[1], true).'</pre>';
    $file_cnt = array_count_values($files);
    // echo 'files: <pre>'.print_r($file_cnt, true).'</pre>';
    echo "<table border='0'><tr><td>Filename</td><td>(cnt)</td></tr>\n";

    foreach($file_cnt as $fpath=>$c_cnt) {
        if (strlen($fpath)>20) {
            $v_url = "http://140.112.161.158/vod/get-mp4/".$fpath;
            echo "<tr><td><a href='{$v_url}' target='blank'>{$fpath}</a> </td><td> ({$c_cnt}) </td></tr>\n";
        } else {
            echo "<tr><td>{$fpath} </td><td> ({$c_cnt}) </td></tr>\n";
        }
    }

    echo "</table>";
}