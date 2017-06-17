<?php
error_reporting(0);
$fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
//构造CURL函数
function CURL($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($c, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
//开始编写
if(isset($_GET['p'])){
	$b = CURL('http://www.dj520.com/html/'.$_GET['a'].'/index'.$_GET['p'].'.html');
	preg_match_all('|<i><input name="check" type="checkbox" value="(\d+)" /></i>\s+<p><a title="([^"]+)"|ims',$b,$c);
	foreach($c[1] as $k => $v){
		$x_0 .= '<m src="'.$fname.'?id='.$v.'" label="'.stripslashes($c[2][$k]).'"/>'."\n";
	}
	if($_GET['p']==='1'){
		preg_match('|">下一页</a><a href="/html/\w+/index(\d+).html"|ims',$b,$pages);
		for($k=2; $k <= ($pages[1]); $k++){
			$x_0 .= '<m list_src="'.$fname.'?p='.$k.'&a='.$_GET['a'].'" label="'.$k.'"/>'."\n";
		}
	}
	header("content-type:text/xml; charset=utf-8");
	echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n<list>\n$x_0\n</list>";
}elseif(isset($_GET['id'])){
	$j = json_decode(CURL('http://www.dj520.com/uc.php?m=uc&a=playlistadd&songid='.$_GET['id']),true);
	if(strstr($j[0]['Dj_Url'],'m4a')){
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$j[0]['songname'].'.m4a"');
		header("location:http://music1.dj520.com/music20140219".$j[0]['Dj_Url']);
	}else{
		echo '暂无资源';
	}
}else{
	$x = '<list>
	<m list_src="'.$fname.'?p=1&a=manyaochuanshao" label="慢摇串烧"/>
	<m list_src="'.$fname.'?p=1&a=yingwenwuqu" label="英文舞曲"/>
	<m list_src="'.$fname.'?p=1&a=zhongwenwuqu" label="中文舞曲"/>
	<m list_src="'.$fname.'?p=1&a=xianchangwuqu" label="现场舞曲"/>
	<m list_src="'.$fname.'?p=1&a=jiubawuqu" label="酒吧舞曲"/>
	<m list_src="'.$fname.'?p=1&a=yuenangu" label="越南鼓"/>
	</list>';
	echo base64_encode($x);
}
//结束编写
?>
