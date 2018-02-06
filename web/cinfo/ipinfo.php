<html>
<head>
<title>ipinfo</title>
</head>
<body>
<h3>ipinfo.php</h3>
<a href='shell_tst.php'>conn_cnt</a>
<?php
   $c_ip = $_GET['ip'];
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$c_ip}");
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   if( ! $result = curl_exec($ch)) {
      echo "Error: ".curl_error($ch); 
   }
   $info = json_decode($result, true);
   echo '<pre>'.print_r($info, true).'</pre>';
   curl_close($ch);
?>
</body>
</html>