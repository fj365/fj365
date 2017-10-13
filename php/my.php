<?php
//JP播放器》my.php?p=1&t=zhongwenwuqu
//XML CMP列表》my.php?p=1&a=zhongwenwuqu  默认列表：my.php
//PHP Ajax酷狗搜索列表》my.php?kg=搜索内容
error_reporting(0);
$fname = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["SCRIPT_NAME"];
//开始编写
if(isset($_GET['a'])){
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
}elseif(isset($_GET['t']) || isset($_GET['kg'])){
	$b = CURL('http://www.dj520.com/html/'.$_GET['t'].'/index'.$_GET['p'].'.html');
	//资源列表
	preg_match_all('|<i><input name="check" type="checkbox" value="(\d+)" /></i>\s+<p><a title="([^"]+)"|ims',$b,$c);
	foreach($c[1] as $k => $v){
		$zy .= '<div class="list-group-item"><span class="badge"><a href="'.$fname.'?id='.$v.'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></span><a href="javascript:WNJP.play('.$k.');">'.stripslashes($c[2][$k]).'</a></div>'."\n";
		//JPLAYER jsonp列表
		$jsonp .='{mp3:"'.$fname.'?id='.$v.'",title:"'.stripslashes($c[2][$k]).'"},';
	}
	preg_match('|">下一页</a><a href="/html/\w+/index(\d+).html"|ims',$b,$pages);
	//分页
	$fy = '<li><a href="'.$fname.'?p=1&t='.$_GET['t'].'"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a></li><li><a href="'.$fname.'?p='.($_GET['p']-4).'&t='.$_GET['t'].'"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>';
	if(($_GET['p']+4)<=$pages[1]){
		for($k=($_GET['p']); $k <= ($_GET['p']+4); $k++){
			$fy .= '<li><a href="'.$fname.'?p='.$k.'&t='.$_GET['t'].'">'.$k.'</a></li>'."\n";
		}
	}
	$fy .= '<li><a href="'.$fname.'?p='.($_GET['p']+4).'&t='.$_GET['t'].'"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>';
	if($_GET['kg']){$fy = '';}
	//导航
	$dh = '<li><a href="'.$fname.'?p=1&t=manyaochuanshao">慢摇串烧</a></li><li><a href="'.$fname.'?p=1&t=yingwenwuqu">英文舞曲</a></li><li><a href="'.$fname.'?p=1&t=zhongwenwuqu">中文舞曲</a></li><li><a href="'.$fname.'?p=1&t=xianchangwuqu">现场舞曲</a></li><li><a href="'.$fname.'?p=1&t=jiubawuqu">酒吧舞曲</a></li><li><a href="'.$fname.'?p=1&t=yuenangu">越南鼓</a></li>';
	echo HTML($dh,$mbx,$zy,$fy,$jsonp);
}elseif(isset($_GET['id'])){
	$j = json_decode(CURL('http://www.dj520.com/uc.php?m=uc&a=playlistadd&songid='.$_GET['id']),true);
	if(strstr($j[0]['Dj_Url'],'m4a')){
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$j[0]['songname'].'.m4a"');
		header("location:http://music1.dj520.com/music20140219".$j[0]['Dj_Url']);
	}else{
		echo '暂无资源';
	}
}elseif(isset($_GET['kgid'])){
	$b = json_decode(CURL("http://m.kugou.com/app/i/getSongInfo.php?cmd=playInfo&hash=".$_GET['kgid']))->url;
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="kugou.m4a"');
	header("location:".stripslashes($b));
}else if(isset ($_GET['dyuid'])){
	$url = 'https://www.amemv.com/aweme/v1/aweme/post/?user_id='.$_GET['dyuid'].'&count=500&cursor=0&aid=1128';
	$json = json_decode(file_get_contents($url),true);
	$jsons = $json['aweme_list'];
	//print_r($jsons);
	foreach ($jsons as $k => $v){
		$xml.='{"type":"m4v","label":"'.$jsons[$k]['desc'].'","src":"'.$fname.'?t='.TH($jsons[$k]['video']['play_addr']['url_list'][0]).'","image":"'.$jsons[$k]['video']['cover']['url_list'][0].'"},';
	}
	header("Content-type: application/jsonp; charset=UTF-8");
	echo 'success_jsonpCallback(['.$xml.'])';
}else if(isset ($_GET['t'])){
	header("Content-type: video/mp4");
	header("Content-Disposition: filename=douyin.mp4");
	echo readfile($_GET['t']);
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
//
function TH($str){
	$strs=strtr($str,array('https:'=>'http:'));
	return URLencode($strs);
}
//JP页面
function HTML($dh,$mbx,$zy,$fy,$jsonp){
	header("content-type:text/html; charset=utf-8");
	echo '
<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<meta name="title" content="龒蜗牛D滚球影音">
	<meta name="description" content="龒蜗牛D滚球影音">
	<meta name="keywords" content="龒蜗牛D,滚球影音,龒蜗牛D滚球影音">
	<link href="//cdn.staticfile.org/twitter-bootstrap/3.0.1/css/bootstrap.min.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="//apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="//cdn.staticfile.org/jquery/2.0.0/jquery.min.js"></script>
	<script type="text/javascript" src="//cdn.staticfile.org/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="//cdn.staticfile.org/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>
	<script src="//cdn.bootcss.com/jplayer/2.9.2/add-on/jplayer.playlist.min.js"></script>
	<script src="//cdn.bootcss.com/jplayer/2.9.2/add-on/jquery.jplayer.inspector.min.js"></script>
	<script src="//cdn.bootcss.com/jplayer/2.9.2/jplayer/jquery.jplayer.min.js"></script>
	<link href="//cdn.bootcss.com/jplayer/2.9.2/skin/pink.flag/css/jplayer.pink.flag.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-top:-8px;margin-right: -14px;">
				<div class="navbar-header">
					<a class="navbar-brand" href="/"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
					<form class="navbar-header navbar-form navbar-left" role="请输入搜索内容">
						<div class="form-group">
							<div class="col-xs-6">
							<input type="text" name="kg" class="form-control"  placeholder="酷狗搜索"/>
							</div>
						</div>
						<!--<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></button>-->
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" style="margin-top:-15px;"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
					</form> 
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							 <a href="/">首页</a>
						</li>
						'.$dh.'
					</ul>
				</div>
				
			</nav>
		</div>
	</div>
	<div class="row clearfix" style="margin-bottom:100px;">
		<div class="col-md-12 column">
			<ul class="breadcrumb">
				<li class="active">
					 <a href="/">首页</a>
				</li>
				'.$mbx.'
			</ul>
			<div class="list-group" id="WNJP_List">
				 <a href="#" class="list-group-item active">资源列表</a>
				'.$zy.'
			</div>
			<ul class="pagination">
				'.$fy.'
			</ul>
		</div>
	</div>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">	
<div class="row clearfix">
	<div class="col-md-12 column">
		<div id="jp_container_1" class="jp-video jp-video-270p" role="application" aria-label="media player" style="width: 100%;">
			<div class="jp-type-playlist">
				<div id="jquery_jplayer_1" class="jp-jplayer"></div>
				<div class="jp-gui" id="jp-gui_hover">
					<div class="jp-video-play">
						<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
					</div>
					<div class="jp-interface">
						<div class="jp-progress">
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</div>
						<div class="jp-details">
							<div class="jp-title" aria-label="title">&nbsp;</div>
						</div>
						<div class="jp-controls-holder">
							<div class="jp-volume-controls">
								<button class="jp-mute" role="button" tabindex="0">mute</button>
								<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
								<div class="jp-volume-bar">
									<div class="jp-volume-bar-value"></div>
								</div>
							</div>
							<div class="jp-controls">
								<button class="jp-previous" role="button" tabindex="0">previous</button>
								<button class="jp-play" role="button" tabindex="0">play</button>
								<button class="jp-next" role="button" tabindex="0">next</button>
							</div>
							<div>
							<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
							<div class="jp-duration" role="timer" aria-label="duration" style="margin-left: -20px;">&nbsp;</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</nav>
<script type="text/javascript">
var WNJP;
$(document).ready(function(){
	if(location.search.substring(1).match(/kg=/)){
		KUGOU(location.search.substring(1).replace(/kg=(.+)/, "$1"));
	}
});
//<![CDATA[
$(document).ready(function(){
	WNJP = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	},[
	'.$jsonp.'
	], {
		ready: function (){$(this).jPlayer("load").jPlayer("play");},
		playlistOptions: {
			autoPlay: true,
			enableRemoveControls: false
		},
		solution: "html",
		supplied: "mp3,m4v",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		size: {width: "100%", height:"1px"},
		keyEnabled: true,
		play: function(event) {
			document.title = event.jPlayer.status.media.title;
		}
	});
	$("#jplayer_inspector_1").jPlayerInspector({jPlayer:$("#jquery_jplayer_1")});
	WNJP.play(0);
});
//]]>
//酷狗
function KUGOU(src){
	$.ajax({
		cache: true,
		async:false,
		url: "http://mobilecdn.kugou.com/api/v3/search/song?format=jsonp&keyword="+src+"&page=1&pagesize=500&showtype=1",
		type: "GET",
		dataType: "jsonp",
		jsonpCallback: \'callback\',
		contentType: "application/jsonp; charset=utf-8",
		timeout: 3e3,
		success: function(jdata) {
			kglist_success_jsonpCallback(jdata.data.info);
		}
	});
}
function kglist_success_jsonpCallback(data){
	var lkg_0 = "[";
	$.each(data, function(k, v) {
		lkg_0 += \'{mp3:"/php/my.php?kgid=\'+v.hash+\'",title:"\'+(v.filename).replace(\'\\\'\', \'\')+\'"},\';
	});
	lkg_0 += "]";
	eval("lkg_d0=" + lkg_0);
	WNJP.setPlaylist(lkg_d0);
	var lkg_01=\'<a href="#" class="list-group-item active">KuGou SoMusic List</a>\';
	$.each(data, function(k, v) {
		lkg_01+=\'<div class="list-group-item"><span class="badge"><a href="/php/my.php?kgid=\'+v.hash+\'"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></span><a href="javascript:WNJP.play(\'+k+\');">\'+v.filename+\'</a></div>\';
	});
	$(\'#WNJP_List\').html(lkg_01);
	WNJP.play(0);
}
</script>
</body>
</html>
';
}
?>
