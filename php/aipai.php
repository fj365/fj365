<?php
preg_match('|flashvars: "([^"]+)"|',file_get_contents('http://www.aipai.com/c39/OjkmJiImJiJqJWQrIw.html'),$b);
preg_match('|"url1080":"([^"]+)"|',urldecode($b[1]),$c);
echo(stripslashes($c[1]));
?>
