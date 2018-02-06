<?php
// get the connection information of the media server.
function getConnCnt() 
{
    // $dl_cnt = shell_exec("ps aux | grep -v 'grep' | grep -c -e 'php-cgi'");
    // echo "Connections: $dl_cnt <br><br>\n";
    $rr = shell_exec("netstat -an | grep -e 'ESTABLISHED'");
    //  echo "result: $rr";
    $aa = preg_split("/\s+/", $rr);
    // echo '<pre>'.print_r($aa, true)."</pre>\n";

    $all_ip = array();
    
    foreach($aa as $row) {
        $row = str_replace('::ffff:','',$row);

        if (strpos($row,':')>0 && strpos($row,'140.112.161.1xx')===FALSE
            && strpos($row,'127.0.0.1')===FALSE
        ) {
            // echo "$row<br />\n";
            $all_ip[] = substr($row, 0, strpos($row,':'));
        }
    }

    $ip_cnt = array_count_values($all_ip);
    arsort($ip_cnt);
    echo "connected ip: ".count($ip_cnt);
    // echo '<pre>'.print_r($ip_cnt, true).'</pre>';
  
    echo "<table id='ip-list' border='1'><tr><td>IP</td><td>Connections</td></tr>\n";
    foreach($ip_cnt as $c_ip=>$c_cnt) {
        echo "<tr><td><a href='http://ipinfo.io/{$c_ip}' target='_blank'>{$c_ip}</a>  <em></em>".
            " </td><td> {$c_cnt}</td></tr>\n";
    }
    echo "</table>";
}

/*
必須修改 [fms]/conf/Users.xml
You need to make changes to Users.xml file in order for HTTP commands to work (meaning admin commands via http).
 
<Root>
  <AdminServer>
    <HTTPCommands>
      <Allow>All</Allow>   // by default this would be "ping"
      <Deny></Deny>       //by default this would be "All"

*/
function getStreamStats() 
{
 //   $url = 'http://localhost:1111/admin/getNetStreamStats?auser=chkcgi&apswd=cgi208@)*&appInst=vod/_definst_&streamids=-1';
    $url = 'http://140.112.161.119:1111/admin/getNetStreamStats?auser=chkcgi&apswd=cgi208@)*&appInst=vod/_definst_&streamids=-1';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if( ! $result = curl_exec($ch)) {
        echo "Error: ".curl_error($ch); 
    }

    // echo '<pre>'.$result.'</pre>';
    preg_match_all(
        "|<name>mp4:.*/(.*)</name>|U",
        $result, $out, PREG_PATTERN_ORDER
    );

    asort($out[1]);
    // echo '<pre>'.print_r($out[1], true).'</pre>';
    $file_cnt = array_count_values($out[1]);
    // asort($file_cnt);
    // echo '<pre>'.print_r($file_cnt, true).'</pre>';
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
    
    curl_close($ch);
}