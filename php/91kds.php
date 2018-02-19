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
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
	curl_setopt($ch, CURLOPT_COOKIE, 'UM_distinctid=161a89ffaff2f7-0ce766c1800224-5d4e211f-1d1e58-161a89ffb003f8; CNZZDATA5582619=cnzz_eid%3D789628614-1518948284-%26ntime%3D1518948284');
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
		$u = explode('@',$_GET['id']);
		$url = 'http://m.91kds.com/'.$u[0].'.html';
		$b = CURL($url);
		preg_match_all('|<li><a href="([^"]+).html" data-ajax="false">([^<]+)</a></li>|ims',$b,$c);
		foreach ($c[1] as $k => $v){
			$xml.='{"MZ":"'.$c[2][$k].'","HTML":"'.$fname.'?vid='.$v.'","PNG":"http://static.yingyonghui.com/icon/128/5079046.png"},';
		}
	}
	header("Content-type: application/jsonp; charset=UTF-8");
	echo 'success_jsonpCallback(['.$xml.'])';
}else if(isset ($_GET['vid'])){
	$url = 'http://m.91kds.com/'.$_GET['vid'].'.html';
	$b = CURL($url);
	preg_match_all('|<option value="([^"]+)">([^<]+)</option>|ims',$b,$c);
	foreach ($c[1] as $k => $v){
		//jsurl = "http://live.gslb.letv.com/gslb?stream_id=" + chid + "&tag=live&ext=m3u8&sign=live_photerne&p1=0&p2=00&p3=001&splatid=1004&ostype=andriod&hwtype=un&platid=10&playid=1&termid=2&pay=0&expect=3&format=1&" + token + "&jsonp=?";
        
		//$src = strtr($v,array('kds1://'=>'http://v.91kds.com/b7/','kds2://'=>'http://v.91kds.com/c7/'));
		$src = strtr($v,array('kds1://'=>"$fname?kid=kds1://",'kds2://'=>"$fname?kid=kds2://"));//,'@@'=>".m3u8?$lk")
		$xml.='{"type":"m4v","label":"'.$c[2][$k].'","src":"'.$src.'","image":"http://static.yingyonghui.com/icon/128/5079046.png"},';
	}
	header("Content-type: application/jsonp; charset=UTF-8");
	echo 'success_jsonpCallback(['.$xml.'])';
}else if(isset ($_GET['kid'])){
	$key = json_decode(CURL('http://m.91kds.com/auth3.php?t=0.'.TT().'&id='),true);//8729744682281145
	$lk = $key['livekey'];$lt = $key['token'];
	$src = strtr($_GET['kid'],array('kds1://'=>'http://v.91kds.com/b9/','kds2://'=>'http://v.91kds.com/c9/','@@'=>".m3u8?$lk"));
	//echo $src.$_GET['kid'];
	//header("Content-type: application/octet-stream");
	//header("Content-Disposition:attachment;filename='".$src."'");
	header("location:".$src);
}
function TT(){
	$a = range(0,9);
	for($i=0;$i<16;$i++){//16
		$b[] = array_rand($a);
	}
	return join("",$b);
}
?>
