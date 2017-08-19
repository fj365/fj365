<?php
preg_match('|"ticket":\s+"([^"]+)",|ims', file_get_contents("http://5sing.kugou.com/fc/14840042.html"), $c);
$d=json_decode(base64_decode($c[1]),true);
echo $d['file'];
?>
