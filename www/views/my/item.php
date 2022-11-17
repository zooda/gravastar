<?php
	if( $this->session->userdata("mb_seq") == "" ) {
		alert_move("로그인 후 사용가능 합니다.","/");
	}
?>

		<div id="container">
			<div class="topArea">
				<h2 id="logo">SLIME MINER</h2>
			</div>
			<div class="boxMyItem">
				<div class="titleArea">
					<div class="title txt--myitem">My Item</div>
					<div class="pos">Total <span>0</span> item</div>
				</div>
				<ul class="listMyItem">
				<!--
					<li>
						<div class="thumb"><img src="/images/thumbMyItem1.png" /></div>
						<div class="cont">
							<div class="title">Slime power pack</div>
							<div class="txt">Mine faster mining speed increase</div>
						</div>
						<div class="token">Token Id #1234</div>
					</li>
					<li>
						<div class="thumb"><img src="/images/thumbMyItem2.png" /></div>
						<div class="cont">
							<div class="title with--bedge is--sale">Slime power pack</div>
							<div class="txt">Mine faster mining speed increase</div>
						</div>
						<div class="token">Token Id #1234</div>
					</li>
					<li>
						<div class="thumb"><img src="/images/thumbMyItem1.png" /></div>
						<div class="cont">
							<div class="title with--bedge is--limit">Slime power pack</div>
							<div class="txt">Mine faster mining speed increase</div>
						</div>
						<div class="token">Token Id #1234</div>
					</li>
					<li>
						<div class="thumb"><img src="/images/thumbMyItem2.png" /></div>
						<div class="cont">
							<div class="title with--bedge is--pick">Slime power pack</div>
							<div class="txt">Mine faster mining speed increase</div>
						</div>
						<div class="token">Token Id #1234</div>
					</li>
					-->
				</ul>
			</div>
		</div>

<script src="https://cdn.ethers.io/scripts/ethers-v4.min.js"></script>

<script type="text/javascript">
	$(function(){
		//로딩바 호출
		$(".boxLoading").css("display", "flex");
		$("html").css("overflow", "auto");

		var tmpLodingTime = 0;
		$(".boxProgress .bar").css("width", "0%");
		$(".boxProgress .icon").css("left", "0%");
		var loadingInterval = setInterval(function(){
			if( tmpLodingTime > 100 ) {
				if(loadingInterval) clearInterval(loadingInterval);
			} else {
				$(".boxProgress .bar").css("width", tmpLodingTime+"%");
				$(".boxProgress .icon").css("left", tmpLodingTime+"%");
			}
			tmpLodingTime++;
		}, 100);

		var web3 = new Web3(window.ethereum);
		const ethereum = window.ethereum;

		//페이지 호출시 지갑 연결 체크
		metaConnectAccount(ethereum, '<?=$this->session->userdata("mb_seq")?>', function(accountData) {
			var currentAccount = accountData[0]['account'];

			getMyNftList(web3, currentAccount);
		});
	});

async function getMyNftList(web3, currentAccount) {
	//추후 값을 넘겨야함
	var contractAddress = '<?=$buycontract?>';
	var abi = [{"constant": false,"inputs": [{"internalType": "address","name": "","type": "address"}],"name": "_ownedTokenIdCount","outputs": [{"internalType": "uint256","name": "","type": "uint256"}],"stateMutability": "view","type": "function"}];

	var web3Contract = new web3.eth.Contract(abi,contractAddress);
	//보유 토큰 갯수
	var tokenCnt = await web3Contract.methods._ownedTokenIdCount(currentAccount).call();
	var tokens = []; //토큰iD 리스트

	for( let i = 0; i < tokenCnt; i++ ) {
		let abi_token = [{"constant": false,"inputs": [{"internalType": "address","name": "owner","type": "address"},{"internalType": "uint256","name": "index","type": "uint256"}],"name": "tokenOfOwnerByIndex","outputs": [{"internalType": "uint256","name": "","type": "uint256"}],"stateMutability": "view","type": "function"}];
		let web3Contract_token = new web3.eth.Contract(abi_token,contractAddress);
		let tokenid = await web3Contract_token.methods.tokenOfOwnerByIndex(currentAccount, i).call();

		if( tokenid ) tokens.push(tokenid);

		console.log('tokenid',tokenid);
	}

	//console.log('tokens',tokens);
	

	var itemHtml = '';

	for( let i = 0; i < tokens.length; i++ ) {
		console.log('tokenCnt for start');
		let abi_uri = [{"constant": false,"inputs": [{"internalType": "uint256","name": "tokenId","type": "uint256"}],"name": "uri","outputs": [{"internalType": "string","name": "","type": "string"}],"stateMutability": "view","type": "function","constant":true}];
		let web3Contract_uri = new web3.eth.Contract(abi_uri,contractAddress);
		await web3Contract_uri.methods.uri(tokens[i]).call(function(err,result){
			//구매 토큰 정보
			$.ajax({
				method: 'POST',
				url: '/my/itemInfoByTokenid/',
				data: { 'tokenid':tokens[i]},
				dataType:"json",
				async: false,
				success: function(data){
					data = data[0];
					console.log("item info",data.items[0]['nft_token_id']);
					
					if( data.status == 'success' ) {
						var iteminfo = data.items[0];
						drawNftList(result,function(nftData){
							//console.log('drawNftList', nftData);
							itemHtml += '<li>';
							itemHtml += '<div class="thumb"><img src="'+nftData.image+'" /></div>';
							itemHtml += '<div class="cont">';
							switch(iteminfo.marker) {
								case 'S': itemHtml += '<div class="title with--bedge is--sale">'+iteminfo.title+'</div>'; break;
								case 'L': itemHtml += '<div class="title with--bedge is--limit">'+iteminfo.title+'</div>'; break;
								case 'P': itemHtml += '<div class="title with--bedge is--pick">'+iteminfo.title+'</div>'; break;
								default: itemHtml += '<div class="title with--bedge">'+iteminfo.title+'</div>'; break;
							}
							itemHtml += '<div class="txt">'+iteminfo.contents+'</div>';
							itemHtml += '</div>';
							itemHtml += '<div class="token">Token Id '+nftData.name+'</div>';
							itemHtml += '</li>';
						});
					} else {
						console.log(data.msg);
					}
					
				},
				error: function(e){
					console.log("item info error",e);
				}
			});

			//console.log('++++++++++++++++');
			//console.log(err,result);

		});
		//console.log(token_uri);
	}

	//화면 표시
	$('.boxMyItem .titleArea .pos span').html(tokens.length);
	$('.boxMyItem .listMyItem').append(itemHtml);
	//로딩바 닫기
	$(".boxLoading").hide();
}

async function drawNftList(uri,callback) {
	$.ajax({
		method: 'GET',
		url: uri,
		dataType:"json",
		async: false,
		success: function(data){
			//data = data[0];
			//console.log('iteminfo',iteminfo);
			//console.log('drawNftList',data);
			callback(data);
		},
		error: function(e){
			console.log("error",e);
		}
	});
}
</script>