<?php
error_reporting(0);
function CURL($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
	curl_setopt($ch, CURLOPT_REFERER, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
//
if(isset ($_GET['id'])){
	if(($_GET['id'])=='all'){
		$url = 'http://m.91kds.com/index.html';
		$b = CURL($url);
		preg_match_all('|<li><a href="([^"]+).html" data-ajax="false">([^<]+)</a></li>|ims',$b,$c);
		foreach ($c[1] as $k => $v){
			$xml.='{"type":"list","label":"'.$c[2][$k].'","src":"'.$fname.'?id='.$v.'","image":"http://static.yingyonghui.com/icon/128/5079046.png"},';
		}
	}else{
		$url = 'http://m.91kds.com/'.$_GET['id'].'.html';
		$b = CURL($url);
		preg_match_all('|<li><a href="([^"]+).html" data-ajax="false">([^<]+)</a></li>|ims',$b,$c);
		foreach ($c[1] as $k => $v){
			$xml.='{"type":"lists","label":"'.$c[2][$k].'","src":"'.$fname.'?vid='.$v.'","image":"http://static.yingyonghui.com/icon/128/5079046.png"},';
		}
	}
	//header("Content-type: application/jsonp; charset=UTF-8");
	echo $url.'success_jsonpCallback(['.$xml.'])';
}else if(isset ($_GET['vid'])){
	$key = json_decode(CURL('http://m.91kds.com/auth1.php?t=0.'.TT().'&id='),true);
	$lk = $key['livekey'];$lt = $key['token'];
	$url = 'http://m.91kds.com/'.$_GET['vid'].'.html';
	$b = CURL($url);
	preg_match_all('|<option value="([^"]+)">([^<]+)</option>|ims',$b,$c);
	foreach ($c[1] as $k => $v){
		//jsurl = "http://live.gslb.letv.com/gslb?stream_id=" + chid + "&tag=live&ext=m3u8&sign=live_photerne&p1=0&p2=00&p3=001&splatid=1004&ostype=andriod&hwtype=un&platid=10&playid=1&termid=2&pay=0&expect=3&format=1&" + token + "&jsonp=?";
        
		$src = strtr($v,array('kds1://'=>'http://v.91kds.com/b7/','kds2://'=>'http://v.91kds.com/c7/','@@'=>".m3u8?$lk"));
		$xml.='{"type":"m4v","label":"'.$c[2][$k].'","src":"'.$src.'","image":"http://static.yingyonghui.com/icon/128/5079046.png"},';
	}
	//header("Content-type: application/jsonp; charset=UTF-8");
	echo $url.'success_jsonpCallback(['.$xml.'])';
}
function TT(){
	$a = range(0,9);
	for($i=0;$i<16;$i++){
		$b[] = array_rand($a);
	}
	return join("",$b);
}
?>
