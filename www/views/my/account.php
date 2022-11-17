<?php
	if( $this->session->userdata("mb_seq") == "" ) {
		alert_move("로그인 후 사용가능 합니다.","/");
	}
?>

		<div id="container">
			<div class="topArea">
				<h2 id="logo">SLIME MINER</h2>
			</div>
			<div class="boxMyBalance">
				<div class="titleArea">
					<div class="title txt--mybalance">My Balance</div>
				</div>
				<div class="topToken">
					<div class="iconMatic"><span>0</span>&nbsp;MARTIC</div>
					<!--<div class="iconFnf">25,000 FNF</div>-->
				</div>
				<div class="lineWallet connect-wallet-area">
					<p>Register your Metamask wallet NFT items are applied based on the registered wallet.</p>
					<a href="javascript:connectMetamask();" class="btnWallet"><span>CONNECT WALLET</span></a>
				</div>

				<!-- 지갑 연결 후 -->
				<div class="lineWallet is--connect chang-wallet-area" style="display:none;">
					<p>Wallet address : <span id="wallet_address"></span> <a href="#" class="btnShare copy-my-wallet">복사</a></p>
					<a class="btnWallet change-my-wallet"><span>CHANGE WALLET</span></a>
				</div>
				<!-- //지갑 연결 후 -->
			</div>
			<div class="boxReward">
				<div class="titleArea">
					<div class="title txt--reward">Reward</div>
				</div>
				<ul class="listReward">
					<li>
						<div class="linkArea">
							<div class="title">Invitation Link</div>
							<div class="link">www.eissnvoak.io</div>
							<a href="#" class="btnShare">복사</a>
						</div>
						<div class="contArea">
							<div class="row">Accept invitation / total purchase : <strong>00 / 120,000 MATIC</strong></div>
							<div class="row">Cumulative Rewards : <strong>00 FNF</strong></div>
							<div class="row imp">Reward Guide : 000 token or more is accumulated to be paid.</div>
							<a href="#" class="btnReward is--get"><span>GET REWARDED</span></a>
						</div>
					</li>
					<li>
						<div class="linkArea">
							<div class="title">Invitation Link</div>
							<div class="link">www.eissnvoak.io</div>
						<a href="#" class="btnShare">복사</a>
					</div>
					<div class="contArea">
						<div class="row">Accept invitation / total purchase : <strong>00 / 120,000 MATIC</strong></div>
						<div class="row">Cumulative Rewards : <strong>00 FNF</strong></div>
						<div class="row imp">Reward Guide : 000 token or more is accumulated to be paid.</div>
						<a href="#" class="btnReward"><span>REWARDED</span></a>
					</div>
					</li>
				</ul>
			</div>
		</div>


<script>	
	//지갑 연결
	function connectMetamask() {
		metaConnectAccount(ethereum, '<?=$this->session->userdata("mb_seq")?>', function(accountData) {
			var currentAccount = accountData[0]['account'];

			$('.connect-wallet-area').hide();
			$('#wallet_address').html(currentAccount);
			$('.chang-wallet-area').show();

			alert('지갑이 연결 되었습니다.');
		},function (e) {
			// MetaMask is locked or the user has not connected any accounts
			$('.chang-wallet-area').hide();
			$('.connect-wallet-area').show();
			console.log('연결된 지갑이 없습니다. 지갑을 연결해 주세요.');
		});
	}
	
	$(function(){
		var isChangeAccount = false;

		var web3 = new Web3(window.ethereum);
		const ethereum = window.ethereum;

		//페이지 호출시 지갑 연결 체크
		metaConnectAccount(ethereum, '<?=$this->session->userdata("mb_seq")?>', function(accountData) {
			var currentAccount = accountData[0]['account'];

			$('.connect-wallet-area').hide();
			$('#wallet_address').html(currentAccount);
			$('.chang-wallet-area').show();

			metaGetBalanc(ethereum, currentAccount, function (balanceData) {
				let martic = hexToMartic(balanceData);
				console.log(martic);
				$('.topToken .iconMatic span').html(martic);
			});
		});

		//지갑 바뀌었을 경우 처리
		ethereum.on('accountsChanged', function(arrChangedAccount) {
			console.log(isChangeAccount, arrChangedAccount);
			//change wallet 버튼을 이용하여 주소를 변경 하였을 경우에만 사용
			var changedAccount = arrChangedAccount[0];
			if( isChangeAccount && changedAccount != '' ) {
				if( confirm(changedAccount + " 주소로 변경 하시겠습니까?") ) {
					//console.log(changedAccount);
					//console.log('<?=$this->session->userdata("mb_seq")?>');
					$.ajax({
						method: 'POST',
						url: '/wallet/account/change',
						data: { 'account': changedAccount, 'mb_seq': '<?=$this->session->userdata("mb_seq")?>'},
						dataType:"json",
						async: false,
						success: function(data){
							let res = data[0];

							if( res.status == "success" ) {
								alert('지갑 주소가 변경되었습니다.');
								location.reload();
							} else {
								alert(res.msg);
							}
						}, error: function(e){
							console.log(e);
						}
					});
					
				} else {
					alert('지갑 변경을 취소 하였습니다.');
				}

				isChangeAccount = false;
			}
		});

		//지갑 변경 버튼
		$('.btnWallet.change-my-wallet').click(function(){
			isChangeAccount = true;

			metaChangeAccount(ethereum, function(accountData) {
				console.log(accountData);
			}, function() { //창을 닫았거나, 실패시
				isChangeAccount = false;
			});
		});

		//지갑 주소 복사
		$('.btnShare.copy-my-wallet').click(function(){
			var content = $('#wallet_address').html();
			copyText(content);
		});
	});

</script>