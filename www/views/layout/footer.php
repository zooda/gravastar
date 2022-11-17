		<footer id="footer">
			<div class="inner">
				<h3>PENTASQUARE</h3>
				<div class="partner">GRAVASTAR</div>
				<div class="copyright">Pentasquare Inc & Gravastar Inc.<br class="mo"/> All Rights Reserved.</div>
			</div>
		</footer>
	</section>

	<!-- 팝업 :: 구매하기 -->
	<div id="popBuy" class="popBasic popItemBuy">
		<div class="inner">
			<div class="title">아이템을 구매하시겠습니까?</div>
			<div class="thumb">
				<div class="img"><img src="./images/thumbPopItem.png" /></div>
				<div class="bedge is--limit">limited</div>
			</div>
			<div class="cont">
				<dl>
					<dt>아이템 명</dt>
					<dd class="title"><strong>x2 골드 15일 패스권</strong></dd>
					<dt>아이템 설명</dt>
					<dd class="subject">x2배 골드 획득을 15일간 획득.</dd>
					<dt>수량</dt>
					<dd class="item-val"><strong>1</strong></dd>
					<dt>가격</dt>
					<dd class="price"><span>2,000</span> MATIC</dd>
				</dl>
				<p>
					지갑 내 코인이 부족할 경우 구매가 최소될 수 있습니다.<br />
					구매 후 환불이 불가합니다.<br />
					아이템 구매에 동의하신다면 구매하기 버튼을 클릭해 주세요.
				</p>
			</div>
			<div class="btn">
				<a href="#" class="btnPop open--popup btn-buy-product" data-pop="popComplete"><span>구매하기</span></a>
			</div>
			<button type="button" class="close close--popup">닫기</button>
		</div>
	</div>

	<!-- 팝업 :: 구매 완료 -->
	<div id="popComplete" class="popBasic popComplete">
		<div class="inner">
			<div class="title">아이템 구매가 완료되었습니다.</div>
			<div class="btn">
				<a href="#" class="btnPop close--popup"><span>확인</span></a>
			</div>
		</div>
	</div>

	<!-- 로딩 팝업 -->
	<div class="boxLoading" style="display:none;">
		<div class="inner">
			<div class="boxProgress">
				<div class="icon"></div>
				<div class="progress_bg">
					<div class="bar"></div>
				</div>
			</div>
			<div class="title">Loading...</div>
			<div class="desc">잠시만 기다려주세요.</div>
		</div>
	</div>

</body>

<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.1.3/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.js"></script>
<link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.css" />

<script type="text/javascript">
	//개발용
	var firebaseConfig = {
	  apiKey: "AIzaSyBCZmxrKPXEGfYQvfecHZWo_UqLQkCTep8",
	  authDomain: "slime-idleminer-dev.firebaseapp.com",
	  databaseURL: "https://slime-idleminer-dev-default-rtdb.firebaseio.com",
	  projectId: "slime-idleminer-dev",
	  storageBucket: "slime-idleminer-dev.appspot.com",
	  messagingSenderId: "335868299295",
	  appId: "1:335868299295:web:6192fc2c3b6104c3f6ed2a",
		  measurementId: "G-6QB3R46PRX"
	};
	firebase.initializeApp(firebaseConfig);

	var firebase_ui = new firebaseui.auth.AuthUI(firebase.auth());

	var uiConfig = {
		callbacks: {
			signInSuccessWithAuthResult: function(authResult, redirectUrl) {
				//로그인 성공
				//console.log(authResult);

				var uid = (typeof authResult.user.uid !== 'undefined') ? authResult.user.uid : '';
				var name = (typeof authResult.additionalUserInfo.profile.name !== 'undefined') ? authResult.additionalUserInfo.profile.name : '';
				var email = (typeof authResult.additionalUserInfo.profile.email !== 'undefined') ? authResult.additionalUserInfo.profile.email : '';
				var provider = (typeof authResult.additionalUserInfo.providerId !== 'undefined') ? authResult.additionalUserInfo.providerId : '';

				//DB 및 Session 저장
				$.ajax({
					method: 'POST',
					url: '/auth/login',
					data: { 'method':'ajax', 'uid': uid, 'name': name, 'email':email, 'provider':provider },
					dataType:"json",
					success: function(data){
						data = data[0];
						//새로고침(메인으로)
						console.log(data);

						if( data.status == "success" ) {
							document.location.href= "/main/main";
						} else if( data.status == "noaccount" ) {
							alert('지갑 정보가 없습니다. 지갑을 연결해 주세요.');
							document.location.href= "/my/account/";
						} else {
							alert('로그인에 실패하였습니다.');
						}
					},
					error: function(e){
						console.log("error",e);
					}
				});

				//option : signInSuccessUrl 실행하지 않도록 false 반환
				return false;
			},
			uiShown: function() {
			},
			signInFailure: function(error) {
			}
		},
		signInFlow: 'popup',
		//signInSuccessUrl: '/auth/login',
		signInOptions: [
			firebase.auth.GoogleAuthProvider.PROVIDER_ID,
		],
	};

	//firebaseUI open
	function openFirebaseUI() {
		$('.btnConnect').hide();
		firebase_ui.start('#firebaseui-auth-container', uiConfig);
	}

	//메인페이지 슬라이드
	var swiper = new Swiper('.boxMainSlime .swiper-container', {
		slidesPerView: "auto",
		loop: false,
		spaceBetween: 20,
		navigation: {
			nextEl: ".boxMainSlime .swiper-button-next",
			prevEl: ".boxMainSlime .swiper-button-prev",
		},
	});

	$(function(){
		/* 로딩바 관련
		setTimeout(function(){
			$(".boxProgress .bar").css("width", "100%");
			$(".boxProgress .icon").css("left", "100%");
		}, 1000);

		setTimeout(function(){
			$(".boxLoading").hide();
		}, 2000);
		*/

		/*
		if( ethereum.isMetaMask ) {
			function handleChangAccount(accounts) {
				console.log('accountsChanged',accounts);

				let currentAccount = '<?=$this->session->userdata("mb_account");?>':

				if (accounts.length === 0) {
					// MetaMask is locked or the user has not connected any accounts
					console.log('[footer] 연결된 지갑이 없습니다. 지갑을 연결해 주세요.');
				} else if ( currentAccount != '' && accounts[0] !== currentAccount) { //연결된 지갑이 기존과 다를 경우만 갱신 여부 체크
					if( 
					if( currentAccount == '' ) {
					}
					//연결된 지갑 비교
					currentAccount = accounts[0];

					$('.connect-wallet-area').hide();
					$('#wallet_address').html(currentAccount);
					$('.chang-wallet-area').show();
				}
			}

			<?php if( $this->session->userdata("mb_seq") != "" ) { //로그인 중에만 이벤트 on?>
			//MetaMask Event
			ethereum.on('accountsChanged', handleChangAccount);
			<?php } ?>
			
		} else {
			alert('Metamask is not installed.');
		}
		*/
	});
</script>
</html>
