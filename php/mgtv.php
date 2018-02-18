<?php
error_reporting(0);
header("Content-Type:text/html;charset=utf-8");
$fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
function CURL($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($c, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
	//curl_setopt($c, CURLOPT_COOKIE, 'sessionid=1505460780171_8kpqp21m4cm; __STKUUID=ec0e0e3c-115d-4365-88e9-492d9de20437; MQGUID=908594628054749184; __MQGUID=908594628054749184; lastActionTime=1505461768547');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
function CURLS($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
	curl_setopt($c, CURLOPT_COOKIE, 'sessionid=1514525461731; __STKUUID=f624f94b-f0ab-43a7-89fa-5a8d1d978929; MQGUID=946613877276545024; __MQGUID=946613877276545024; search-history=%u56E0%u4E3A%u7231%2C%u6728%u4E43%u4F0A; lastActionTime=1514526310223');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
//http://pcweb.api.mgtv.com/player/video?video_id=4038839&cid=308892&keepPlay=0&vid=4038839&watchTime=45
$json = json_decode(CURLS('http://mobile.api.hunantv.com/v6/video/getSource?uid=&osVersion=7.8.9&appVersion=5.6.0.0&videoId='.$_GET['vid'].'&osType=android&fmt=4&pno=2010'),true);
//printf($json);
//http://disp.titan.mgtv.com/vod.do?fmt=4&pno=1121&fid=54D082892DB03347532E30DD507E6513&file=/c1/2017/09/13_0/54D082892DB03347532E30DD507E6513_20170913_1_1_931.mp4
/*
if($_GET['type']){
	$type = $_GET['type'];
	$http = $json['data']['videoDomains'][$type];
}else{
	$http = $json['data']['videoDomains'][2];
}
if($_GET['hd']){
	$hd = $_GET['hd'];
	$host = $json['data']['videoSources'][$hd]['url'];
}else{
	$host = $json['data']['videoSources'][0]['url'];
}
*/
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
//$http_host = '/php/r.php?url='.$http.$host;
//header("location:$http$host");
//echo $http_host;
//$http_host_2 = str_replace('arange=300','arange=0',$http_host);
//$k = CUR($http_host);
//$m = json_decode(CURLS($http_host))->info;
//http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=4B5639E5A69A3DE9F6BD659E82F90A4B&file=/c1/2017/12/21_0/4B5639E5A69A3DE9F6BD659E82F90A4B_20171221_1_1_957.mp4
$uu = $jsons['info'];
echo ($uu);
if(strstr($uu,'/dianying/')){
	preg_match('|http://pcvideocmnet.titan.mgtv.com/(.*)/dianying/lpe_(\d+)/(.*)_201(.*)_mp4/|ims',$uu,$c);
	$mp4 = 'http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid='.$c[3].'&file=/'.$c[1].'/dianying/lpe_'.$c[2].'/'.$c[3].'_201'.$c[4].'.mp4';
}else if(strstr($uu,'/c1/201')){
	preg_match('|http://pcvideocmnet.titan.mgtv.com/(.*)_0/(.*)_201(.*)_mp4/|ims',$uu,$c);
	$mp4 = 'http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid='.$c[2].'&file=/'.$c[1].'_0/'.$c[2].'_201'.$c[3].'.mp4';
}
/*
$mp5 = '{"type":"m4v","src":"'.$mp4.'","label":"'.$mz.'","image":"'.$png.'"},';
echo $mp5;//$uu,
*/
/*
header("Content-type: application/octet-stream");
header("Content-Disposition:attachment;filename='QQ121027740_MGTV_".$_GET['vid'].".mp4'");
header("location:".$mp4);
//
*/
//
//
//
//
//
//
/*{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=0788C1B014366414168455682F003E74&file=/c1/2018/01/10_0/0788C1B014366414168455682F003E74_20180110_1_1_662.mp4","label":"《前任3》催泪片段 韩庚扮至尊宝告别爱情","image":"http://1img.hitv.com/preview/sp_images/2018/dianying/318237/4241569/20180110103823089.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=510AD2D62BEBDE4B3A47DF00DEF68B0D&file=/c1/2017/12/27_0/510AD2D62BEBDE4B3A47DF00DEF68B0D_20171227_1_1_1131.mp4","label":"《西游记女儿国》预告片","image":"http://2img.hitv.com/preview/sp_images/2017/dianying/308831/4226939/20171227094601410.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=AD764131441EEACD0F50498CB612FF9F&file=/c1/2017/12/14_0/AD764131441EEACD0F50498CB612FF9F_20171214_1_1_1245.mp4","label":"《奇门遁甲》终极预告片","image":"http://3img.hitv.com/preview/sp_images/2017/dianying/318877/4211318/20171214174406244.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=CD5F0985CA6F3DD2CA85E7A04F0AFCFE&file=/c1/2017/12/20_0/CD5F0985CA6F3DD2CA85E7A04F0AFCFE_20171220_1_1_1264.mp4","label":"《机器之血》国际版预告","image":"http://3img.hitv.com/preview/sp_images/2017/dianying/318154/4217461/20171220110300771.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=A1FAF449638AB8CAA0DBAFCB6F72A455&file=/c1/2017/12/15_0/A1FAF449638AB8CAA0DBAFCB6F72A455_20171215_1_1_880.mp4","label":"《妖猫传》终极预告","image":"http://3img.hitv.com/preview/sp_images/2017/dianying/300033/4211991/20171215104918091.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=3B9CD60DC59FB2515DECA684402DF90D&file=/c1/2017/12/13_0/3B9CD60DC59FB2515DECA684402DF90D_20171213_1_1_1237.mp4","label":"《捉妖记2》国际版预告","image":"http://3img.hitv.com/preview/sp_images/2017/dianying/308584/4209178/20171213095105785.jpg_220x123.jpg"},{"type":"m4v","src":"http://disp.titan.mgtv.com/vod.do?fmt=4&pno=2010&fid=C4047019B7696F4AB6BDEF62EEA07D4E&file=/c1/2018/01/05_0/C4047019B7696F4AB6BDEF62EEA07D4E_20180105_1_1_1238.mp4","label":"《二代妖精》预告片","image":"http://0img.hitv.com/preview/sp_images/2018/dianying/316999/4236217/20180105100057510.jpg_220x123.jpg"},*/
?>
