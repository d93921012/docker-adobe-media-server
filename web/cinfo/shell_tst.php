<!DOCTYPE html>
<html>
<head>
<title>Current connections</title>
<link href='cinfo.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script language="JavaScript">

//var uid = new String;
function reload() {
   thetimer = setTimeout("load_again()", 60000);
}

function load_again(){
   location.reload();
}

</script>

</head>
<body onLoad="reload();">
<?php

include 'cinfo-func.php';
include 'http-func.php';

$now = strftime("%H:%M:%S",time());
/*
測試, 透過 shell 得到 TCP 的連線數
*/
?>
Refresh time: <?= $now ?><br><br>
<table>
  <tr>
    <td>
      <?php  getConnCnt(); ?>
    </td>
    <td  valign='top'>
      Open files:
      <?php getStreamStats(); ?>
    </td>
    <td  valign='top'>
      Http open files:
      <?php getHttpFiles(); ?>
    </td>  </tr>
</table>

<script type="text/javascript">

$(document).ready(function(){
 
$("#ip-list td a").hover(
 function() {
    var self = this;
    var mblk = $(self).closest( "td" ).find("em");
    var p_top = $(self).position().top;
    var ip = $(self).text();
    console.log(p_top);
    jQuery.ajax({
          dataType:'json', 
          url:'http://ipinfo.io/'+ip,
          success:function(info) {
            mblk.html("ip: "+info.ip+"<br>\n"+
                "hostname: "+info.hostname+"<br>\n"+
                "city: "+info.city+"<br>\n"+
                "region: "+info.region+"<br>\n"+
                "country: "+info.country+"<br>\n"+
                "org: "+info.org+"<br>\n")
                .animate({opacity: "show", top: p_top}, "slow");
          },
          error:function(XMLHttpRequest,textStatus,errorThrown){}
        });
 }, 
 function() {
    $(this).closest( "td" ).find("em")
           .animate({opacity: "hide", top: "-85"}, "fast");
 }
);

}); //ready
</script>
</body>
</html>