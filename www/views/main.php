<?php
/*
Array ( [seq] => 1 [title] => 영광의 슬라임 소환 [contents] => 고급 등급 이상의 슬라임 매니저를 획득. [type] => 0 [amount] => 2000 [sell_start_date] => [sell_end_date] => [image_url] => /images/products/groupA.jpg [max_buy] => 10 [area] => [marker] => [show_yn] => Y [created_date] => 2022-10-06 08:31:38 [modifed_date] => )*/
?>
		<div id="container">
			<div class="topArea">
				<h2 id="logo">SLIME MINER</h2>
				<div class="txtCheckitems">
					<img src="./images/txtCheckSlime.png" alt="" class="pc" />
					<img src="./images/txtCheckSlime_mo.png" alt="" class="mo" />
				</div>
			</div>
			<div class="boxNotice">
				<div class="title">NOTICE</div>
				<div class="cont"><a href="#">Please register your wallet for game connection!</a></div>
				<a href="#" class="more">MORE</a>
			</div>
			<div class="boxMainSlime">
				<div class="titleArea">
					<div class="title txt--slime">Slime miner items</div>
				</div>
				<div class="swiper-container">
					<div class="swiper-wrapper">

					<?php
						foreach( $products as $prod ) {

							switch($prod['marker']){
								case "S": 
									$marker = '<div class="bedge is--sale">for sale</div>';
									break;
								case "L": 
									$marker = '<div class="bedge is--limit">limited</div>';
									break;
								case "P": 
									$marker = '<div class="bedge is--pick">slime\'s pick</div>';
									break;
								default: 
									$marker = '';
									break;
							}
					?>
						<div class="swiper-slide" data-seq=<?=$prod['seq']?>>
							<div class="thumbArea">
								<div class="thumb">
									<div class="img"><img src="<?=$prod['image_url']?>" /></div>
									<div class="time">Time for sale : <strong>-24 day 12:59:33</strong></div>
								</div>
								<?=$marker;?>
							</div>
							<div class="cont">
								<div class="title"><?=$prod['title']?></div>
								<div class="subject"><?=$prod['contents']?></div>
							</div>
							<div class="bottom">
								<div class="num" data-max-buy=<?=$prod['max_buy']?>>
									<a href="#" class="minus">-</a>
									<div class="val">1</div>
									<a href="#" class="plus">+</a>
								</div>
								<div class="total"><span><?=number_format($prod['amount'])?></span> MATIC</div>
							</div>
							<div class="btn">
								<button type="button" class="open--popup btn-buy-product-open" data-pop="popBuy">BUY</button>
							</div>
						</div>
					<?php
						}
					?>
<!--
						<div class="swiper-slide">
							<div class="thumbArea">
								<div class="thumb">
									<div class="img"><img src="./images/thumbSlime2.png" /></div>
									<div class="time">Time for sale : <strong>-24 day 12:59:33</strong></div>
								</div>
								<div class="bedge is--sale">for sale</div>
							</div>
							<div class="cont">
								<div class="title">빛나는 골드 부스트 소환</div>
								<div class="subject">x5배 ~ x20배 골드를 획득할 수 있는 부스트아이템을 획득.</div>
							</div>
							<div class="bottom">
								<div class="num">
									<a href="#" class="minus">-</a>
									<div class="val">1</div>
									<a href="#" class="plus">+</a>
								</div>
								<div class="total"><span>2,000</span> MATIC</div>
							</div>
							<div class="btn">
								<button type="button" class="open--popup" data-pop="popBuy">BUY</button>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="thumbArea">
								<div class="thumb">
									<div class="img"><img src="./images/thumbSlime3.png" /></div>
									<div class="time">Time for sale : <strong>-24 day 12:59:33</strong></div>
								</div>
								<div class="bedge is--limit">limited</div>
							</div>
							<div class="cont">
								<div class="title">x2 골드 15일 패스권</div>
								<div class="subject">x2배 골드 획득을 15일간 획득.</div>
							</div>
							<div class="bottom">
								<div class="num">
									<a href="#" class="minus">-</a>
									<div class="val">1</div>
									<a href="#" class="plus">+</a>
								</div>
								<div class="total"><span>2,000</span> MATIC</div>
							</div>
							<div class="btn">
								<button type="button" class="open--popup" data-pop="popBuy">BUY</button>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="thumbArea">
								<div class="thumb">
									<div class="img"><img src="./images/thumbSlime4.png" /></div>
									<div class="time">Time for sale : <strong>-24 day 12:59:33</strong></div>
								</div>
								<div class="bedge is--pick">slime's pick</div>
							</div>
							<div class="cont">
								<div class="title">x5 골드 3시간권</div>
								<div class="subject">x5배 골드를 3시간동안 획득.</div>
							</div>
							<div class="bottom">
								<div class="num">
									<a href="#" class="minus">-</a>
									<div class="val">1</div>
									<a href="#" class="plus">+</a>
								</div>
								<div class="total"><span>2,000</span> MATIC</div>
							</div>
							<div class="btn">
								<button type="button" class="open--popup" data-pop="popBuy">BUY</button>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="thumbArea">
								<div class="thumb">
									<div class="img"><img src="./images/thumbSlime1.png" /></div>
									<div class="time">Time for sale : <strong>-24 day 12:59:33</strong></div>
								</div>
							</div>
							<div class="cont">
								<div class="title">영광의 슬라임 소환</div>
								<div class="subject">고급 등급 이상의 슬라임 매니저를 획득.</div>
							</div>
							<div class="bottom">
								<div class="num">
									<a href="#" class="minus">-</a>
									<div class="val">1</div>
									<a href="#" class="plus">+</a>
								</div>
								<div class="total"><span>2,000</span> MATIC</div>
							</div>
							<div class="btn">
								<button type="button" class="open--popup" data-pop="popBuy">BUY</button>
							</div>
						</div>
					-->
					</div>
				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>
		</div>


<script src="https://cdn.ethers.io/scripts/ethers-v4.min.js"></script>

<script type="text/javascript">

//console.log(ui);
$(function(){
	/*
      // FirebaseUI config.
      var uiConfig = {
        signInSuccessUrl: '<url-to-redirect-to-on-success>',
        signInOptions: [
          // Leave the lines as is for the providers you want to offer your users.
          firebase.auth.GoogleAuthProvider.PROVIDER_ID,
          firebase.auth.FacebookAuthProvider.PROVIDER_ID,
          firebase.auth.TwitterAuthProvider.PROVIDER_ID,
          firebase.auth.GithubAuthProvider.PROVIDER_ID,
          firebase.auth.EmailAuthProvider.PROVIDER_ID,
          firebase.auth.PhoneAuthProvider.PROVIDER_ID,
          firebaseui.auth.AnonymousAuthProvider.PROVIDER_ID
        ],
        // tosUrl and privacyPolicyUrl accept either url string or a callback
        // function.
        // Terms of service url/callback.
        tosUrl: '<your-tos-url>',
        // Privacy policy url/callback.
        privacyPolicyUrl: function() {
          window.location.assign('<your-privacy-policy-url>');
        }
      };

      // Initialize the FirebaseUI Widget using Firebase.
      var ui = new firebaseui.auth.AuthUI(firebase.auth());
      // The start method will wait until the DOM is loaded.
      ui.start('#firebaseui-auth-container', uiConfig);
*/
	//상품 수량 변경
	$('.plus').click(function(e){
		 e.preventDefault();

		var $num = $(this).closest('.num');
		var maxBuy = $num.data('max-buy');
		var curBuy = $num.find('.val').html();
		
		if( curBuy < maxBuy ) curBuy++;
		$num.find('.val').html(curBuy);
	});
	$('.minus').click(function(e){
		 e.preventDefault();

		var $num = $(this).closest('.num');
		var curBuy = $num.find('.val').html();
		
		if( curBuy > 1 ) curBuy--;
		$num.find('.val').html(curBuy);
	});
	//상품 수량 변경 End
});
</script>

<?php if( $this->session->userdata("mb_seq") == "" ) { //로그인 전 ?>
<script type="text/javascript">
$(function(){
	$(".btn-buy-product-open").click(function(){
		alert('로그인(GAME CONNECT) 후 사용가능 합니다.');
	});
});
</script>
<?php } else { //로그인 후 ?>
<script type="text/javascript">
$(function(){
	var web3 = new Web3(window.ethereum);
	const ethereum = window.ethereum;
	var loadingInterval = null; //로딩바

	//결제 팝업 띄우기
	$(".btn-buy-product-open").click(function(){
		let $this = $(this);

		//지갑 연결
		metaConnectAccount(ethereum, '<?=$this->session->userdata("mb_seq")?>', function(accountData) {
			//console.log(accountData);
			var $wrapSlide = $this.closest('.swiper-slide');
			let p_seq = $wrapSlide.data('seq');

			//상품 정보 연결 및 결제창 오픈
			$.ajax({
				method: 'POST',
				url: '/main/shop/buy',
				data: { 'p_seq':p_seq, 'item_count': $wrapSlide.find('.val').html()  },
				dataType:"json",
				success: function(data){
					data = data[0];
					//console.log(data);
					var $targetPopup = $('#popBuy');
					var product = data['product'];

					//가격, 수량 셋팅
					$targetPopup.data('seq',p_seq);
					$targetPopup.find('.thumb img').attr('src',product['image_url']);
					$targetPopup.find('.thumb .bedge').removeClass('is--sale is--limit is--pick');
					//console.log(product['marker']);
					if( product['marker'] == 'S' ) {
						$targetPopup.find('.thumb .bedge').addClass('is--sale');
					} else if( product['marker'] == 'L' ) {
						$targetPopup.find('.thumb .bedge').addClass('is--limit');
					} else if( product['marker'] == 'P' ) {
						$targetPopup.find('.thumb .bedge').addClass('is--pick');
					}
					$targetPopup.find('.cont .title strong').html(product['title']);
					$targetPopup.find('.cont .subject').html(product['contents']);
					$targetPopup.find('.cont .item-val strong').html(product['item_count']);
					$targetPopup.find('.cont .price span').html(product['str_total_amount']);
					
					$(".popBasic").hide();
					$targetPopup.css("display", "flex");
					$("html").css("overflow", "hidden");
				},
				error: function(e){
					console.log("error",e);
				}
			});
		}); //connect End
	}); //Buy 버튼 End

	//구매하기 버튼
	$(".btn-buy-product").click(function() {
		var $targetPopup = $('#popBuy');

		//로딩바 호출
		$(".boxLoading").css("display", "flex");
		$targetPopup.hide();
		$("html").css("overflow", "auto");


		var tmpLodingTime = 0;
		$(".boxProgress .bar").css("width", "0%");
		$(".boxProgress .icon").css("left", "0%");
		loadingInterval = setInterval(function(){
			if( tmpLodingTime > 100 ) {
				if(loadingInterval) clearInterval(loadingInterval);
			} else {
				$(".boxProgress .bar").css("width", tmpLodingTime+"%");
				$(".boxProgress .icon").css("left", tmpLodingTime+"%");
			}
			tmpLodingTime++;
		}, 100);

		//지갑 연결
		metaConnectAccount(ethereum, '<?=$this->session->userdata("mb_seq")?>', function(accountData) {
			//console.log(accountData);
			var currentAccount = accountData[0]['account'];
			let p_seq = $targetPopup.data('seq');
			let item_count = $targetPopup.find('.item-val strong').html();

			//상품 정보 연결 및 결제창 오픈
			$.ajax({
				method: 'POST',
				url: '/main/shop/buyok',
				data: { 'method':'ajax', 'account': currentAccount, 'mb_seq': '<?=$this->session->userdata("mb_seq")?>','p_seq':p_seq, 'item_count': parseInt(item_count)  },
				dataType:"json",
				success: function(data){
					data = data[0];
					console.log(data);

					if( data.status == "success" ) {
						//실제 구매 처리
						buyProcess(data);

					} else {
						alert(data.msg);
						$(".boxLoading").hide();
					}
				},
				error: function(e){
					console.log("error",e);
				}
			});
		}); //connect End

	}); //구매하기버튼 End

}); //function End

//실제 구매 프로세스
async function buyProcess(data) {
	var tokens   =   data.tokens;

	//추후 값을 넘겨야함
	var price   =   '0.1';//data.total_amount;
	var order_no   =   data.order_no;
	var contractAddress = '<?=$buycontract?>';
	var abi = [{"constant": false,"inputs": [{"internalType": "uint256[]","name": "ids","type": "uint256[]"}],"name": "buy","outputs": [],"stateMutability": "payable","type": "function"}];

	const provider = new ethers.providers.Web3Provider(window.ethereum);
	const signer = provider.getSigner();
	const contract = new ethers.Contract(contractAddress, abi, signer);

	//const tx = await contract.connect(signer).buy(tokens, {value:ethers.utils.parseUnits(price, 'ether').toHexString()}).then(async (v) => {
	const tx = await contract.connect(signer).buy(tokens, {value:ethers.utils.parseUnits(price, 'ether').toHexString()}).then(async (v) => {
		alert("결제 성공.");
		console.log("+++++++++++++++++++++");
		console.log(v);
		
		//완료창 호출
		$(".boxLoading").hide();
		$("#popComplete").css("display", "flex");
		
		//구매 로그 저장
		//console.log("로그저장start");
		$.ajax({
			method: 'POST',
			url: '/main/shop/result',
			data: { 'status':'success', 'order_no': order_no, 'val': JSON.stringify(v), 'hash':v.hash, 'account':v.from, 'hex':v.value._hex },
			dataType:"json",
			success: function(data){
				console.log("save log",data);
			},
			error: function(e){
				console.log("save system log",e);
			}
		});
	}).catch((err) => {
		console.log('error',err);

		let err_message = '';

		if(err.code === -32603) {
			err_message = '이미 전송된 NFT 입니다.';
		} else {
			if( err.data ) {
				err_message = err.data.message;
			} else {
				err_message = err.message;
			}
		}

		//컨트렉트 호출에 의한 에러일 경우에만 로그 저장 및 NFT 복원
		if( typeof err.code !== 'undefined' ) {
			//에러 로그 저장
			$.ajax({
				method: 'POST',
				url: '/main/shop/result',
				data: { 'status':'error', 'order_no': order_no, 'val': JSON.stringify(err) },
				dataType:"json",
				success: function(data){
					console.log("save error log",data);
				},
				error: function(e){
					console.log("save system error log",e);
				}
			});

			err_message += '\n구매 실패 하였습니다.';
			alert(err_message);
		}
		
		$(".boxLoading").hide();
	});
}
</script>
<?php } ?>