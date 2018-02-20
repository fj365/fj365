<?php
error_reporting(0);
if(isset ($_GET['vid'])){
$b = C_F('http://proxy.zhuangla.com.cn/OdParse/?url='.$_GET['vid'].'&type=mgtv');
preg_match('|"time":"(.*?)", "key": "(.*?)", "url": "(.*?)","type": "(.*?)"|ims',$b,$c);
//echo $c[1].$c[2].$c[3].$c[4];
$d = C_F('http://proxy.zhuangla.com.cn/OdParse/api.php?referer=http://www.f8dy.tv/vodplayhtml/'.$_GET['vid'].'-3-1.html&time='.$c[1].'&key='.$c[2].'&url='.$c[3].'&type=mgtv');
$e = json_decode($d,true);
//echo $e['url'];
header("location:".$e['url']);
}
function C_F($url){
	$ch=curl_init();
  	curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
  	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt ($ch, CURLOPT_REFERER, $url);
  	$data=curl_exec($ch);
  	curl_close($ch);
  	return $data;
}
?>
