<?php
	$location = $_SERVER["REQUEST_URI"];

	$active_menu = "main";
	if( strpos($location, "item") > 0 ) {
		$active_menu = "my_item";
	} else if( strpos($location, "account") > 0 ) {
		$active_menu = "my_account";
	}

	//print_r($this->session->userdata());

	$ip = $_SERVER['REMOTE_ADDR']; 
	//echo $ip;
	if( !($ip == "203.226.142.11" || $ip == "218.145.140.124") ) {
		alert_back('접근이 허용되지 않은 IP입니다.');
	}
	
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
	<title>SLIME MINER</title>
  <link rel="stylesheet" href="/css/swiper.min.css" />
	<link rel="stylesheet" href="/css/style.css" />
<!--<script src="/js/jquery-3.5.1.min.js"></script>-->
<script src="/js/jquery-3.6.1.min.js"></script>
<script src="/js/swiper.min.js"></script>
<script src="/js/web3.min.js"></script>
<script src="/js/common.js"></script>
<script src="/js/function.js"></script>

</head>

<body>

	<section id="wrap">
		
		<button type="button" id="btnGnb">메뉴</button>
		
		
		<aside id="aside">
			<h1>SLIME MINER</h1>
			
			<div id="firebaseui-auth-container"></div>
			<div class="btnConnect">
			<?php if( $this->session->userdata("mb_seq") ) { ?>
				<!-- 연결 후 -->
				<span class="login-info"><?=$this->session->userdata("mb_email");?></span>
			<?php } else { ?>
				<!-- 연결 전 -->
				<a href="javascript:openFirebaseUI();" class="btn-login" >GAME CONNECT</a>
			<?php } ?>
			</div>
			
			
			<ul id="gnb">
				<li <?php if($active_menu == "main") {?>class="active"<?php }?>><a href="/main/main/">Shop</a></li>
				<li <?php if($active_menu == "my_item") {?>class="active"<?php }?>><a href="/my/item/">My Item</a></li>
				<li <?php if($active_menu == "my_account") {?>class="active"<?php }?>><a href="/my/account/">My Account</a></li>
			</ul>
		</aside>
		