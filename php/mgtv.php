<?php
error_reporting(0);
header("Content-Type:text/html;charset=utf-8");
$fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
function CURLS($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
	curl_setopt($c, CURLOPT_COOKIE, 'sessionid=1514525461731; __STKUUID=f624f94b-f0ab-43a7-89fa-5a8d1d978929; MQGUID=946613877276545024; __MQGUID=946613877276545024; search-history=%u56E0%u4E3A%u7231%2C%u6728%u4E43%u4F0A; lastActionTime=1514526310223');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$json = json_decode(CURLS('http://mobile.api.hunantv.com/v6/video/getSource?uid=&osVersion=7.8.9&appVersion=5.6.0.0&videoId='.$_GET['vid'].'&osType=android&fmt=4&pno=2010'),true);
$http = $json['data']['videoDomains'][0];
if(isset($_GET['hd'])){
	$hd = $_GET['hd'];
	$host = $json['data']['videoSources'][$hd]['url'];
}else{
	$host = $json['data']['videoSources'][0]['url'];
}
$mz = $json['data']['videoName'];
$png = $json['data']['imageUrl'];
$jsons = json_decode(CURLS($http.$host),true);
$uu = $jsons['info'];
if(strstr($uu,'/dianying/')){
	preg_match('|http://\w+.titan.mgtv.com/(.*)/dianying/lpe_(\d+)/(.*)_201(.*)_mp4/|ims',$uu,$c);
	$mp4 = 'http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid='.$c[3].'&file=/'.$c[1].'/dianying/lpe_'.$c[2].'/'.$c[3].'_201'.$c[4].'.mp4';
}else if(strstr($uu,'/c1/201')){
	preg_match('|http://\w+.titan.mgtv.com/(.*)_(\d+)/(.*)_201(.*)_mp4/|ims',$uu,$c);
	$mp4 = 'http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid='.$c[3].'&file=/'.$c[1].'_'.$c[2].'/'.$c[3].'_201'.$c[4].'.mp4';
}
echo $uu.$mp4;
/*
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename='QQ121027740_MGTV_".$_GET['vid'].".mp4'");
header("location:".$mp4);
*/
?>
