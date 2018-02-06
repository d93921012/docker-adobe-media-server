<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Animated Menu Hover 1</title>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
 
$(".menu td").hover(
 function() {
    var self = this;
    var mblk = $(self).find("em");
    var ip = $(self).find('a').text();
    console.log(ip);
    jQuery.ajax({
          dataType:'json', 
          url:'http://ipinfo.io/'+ip,
          success:function(info) {
            mblk.html("ip: "+info.ip+"<br>\n"+
                "hostname: "+info.hostname+"<br>\n"+
                "city: "+info.city+"<br>\n"+
                "region: "+info.region+"<br>\n"+
                "country: "+info.country+"<br>\n"+
                "org: "+info.org+"<br>\n");
          },
          error:function(XMLHttpRequest,textStatus,errorThrown){}
        });
    mblk.html("Hello test message")
        .animate({opacity: "show", top: "15"}, "slow");
 }, 
 function() {
    $(this).find("em").animate({opacity: "hide", top: "-85"}, "fast");
 }
);

}); //ready
</script>

<style type="text/css">
body {
 margin: 10px auto;
 width: 570px;
 font: 75%/120% Arial, Helvetica, sans-serif;
}
.menu {
 margin: 100px 0 0;
 padding: 0;
 list-style: none;
}
.menu td {
 padding: 0;
 margin: 0 2px;
 float: left;
 position: relative;
 text-align: center;
}
.menu a {
 padding: 14px 10px;
 display: block;
 color: #000000;
 width: 144px;
 text-decoration: none;
 font-weight: bold;
 background-color: #ccc;
 /*background: url('http://www.webdesignerwall.com/demo/jquery/images/button.gif') no-repeat center center;*/
}
.menu td em {
 /*background: url('http://www.webdesignerwall.com/demo/jquery/images/hover.png') no-repeat;*/
 position: absolute;
 padding-top: 8px;
 padding-left: 8px;
 top: -85px;
 left: 160px;
 width: 380px;
 height: 100px;
 z-index: 2;
 display: none;
 text-align: left;
 font-style: normal;
 background-color: white;
 border: solid 1px;
}
</style>
</head>

<body>

<table class="menu">
 <tr><td>
  <a href="http://ipinfo.io/111.250.78.238">111.250.78.238</a>  
  <em>A wall of design ideas, web trends, and tutorials</em>
 </td></tr>
 <tr><td>
  <a href="http://ipinfo.io/1.34.121.10">1.34.121.10</a>

  <em>Featuring the best CSS and Flash web sites</em>
 </td></tr>
 <tr><td>
  <a href="http://ipinfo.io/211.23.16.217">211.23.16.217</a>
  <em>Blog and design portfolio of WDW designer, Nick La</em>
 </td></tr>
</table>

</body>
</html>