$(function(){
  // 팝업
  /* main.php 로 이동
  $(".open--popup").click(function(){
    var target = $(this).attr("data-pop");
    $(".popBasic").hide();
    $("#"+target).css("display", "flex");
    $("html").css("overflow", "hidden");
  });
*/
  $(".close--popup").click(function(){
    $(this).parents(".popBasic").hide();
    $("html").css("overflow", "auto");
  });

  $("#btnGnb").click(function(){
    if($(this).hasClass("is--open")){
      $(this).removeClass("is--open");
      $("#aside").removeClass("is--open");
      $("html").css("overflow", "auto");
    }else{
      $(this).addClass("is--open");
      $("#aside").addClass("is--open");
      $("html").css("overflow", "hidden");
    }
  });

  function gnbSet(){
    var win_w = $(window).width();
    
    if(767 < win_w){
      $("#btnGnb").removeClass("is--open");
      $("#aside").removeClass("is--open");
    }

    $("html").css("overflow", "auto");
  };
  
  gnbSet();

  $(window).resize(function(){
    gnbSet();
  });
});

/***** 지갑 관련 *****/
//지갑 연결
function metaConnectAccount(ethereum, mb_seq, successCallback, errorCallback=false) {
	  ethereum
		.request({ method: 'eth_requestAccounts' })
		.then(function(accounts) {
			if (accounts.length === 0) {
				alert('연결된 지갑이 없습니다. 지갑을 연결해 주세요.');
			} else {
				//연결된 지갑 확인 및 기존에 지갑 정보가 없다면 저장
				let currentAccount = accounts[0];

				$.ajax({
					method: 'POST',
					url: '/wallet/account/save',
					data: { 'method':'ajax', 'account': currentAccount, 'mb_seq': mb_seq},
					dataType:"json",
					success: function(data){
						let res = data[0];

						if( res.status == "success" ) {
							successCallback(data);
						} else if( res.status == "different" ) {
							alert('기존에 저장하신 지갑과 연결된 지갑이 다릅니다.');
						} else {
							alert('지갑 연결에 실패하였습니다.');
						}
					}, error: function(e){
						if( errorCallback===false ) {
							alert('connect account error!');
						} else {
							errorCallback(e);
						}
					}
				});
			}
		})
		.catch((error) => {
			if(error.code === 4001) {
				// EIP-1193 userRejectedRequest error
				alert('Please connect to MetaMask.');
			} else {
				alert(error.message);
				console.log(error);
			}
		});
}

//지갑 변경
function metaChangeAccount(ethereum, successCallback, errorCallback=false) {
	  ethereum
		.request({ method: 'wallet_requestPermissions', params: [{ eth_accounts: {} }] })
		.then(function(accounts) {
		  successCallback(accounts);
		})
		.catch((error) => {
			if(error.code === 4001) {
				// EIP-1193 userRejectedRequest error
				console('not changed account.');
			} else {
				alert(error.message);
				console.log(error);
			}

			errorCallback();
		});
}

function metaGetBalanc(ethereum, account, successCallback, errorCallback=false) {
	  ethereum
		.request({ method: 'eth_getBalance', params:[account, 'latest'] })
		.then(function(balanceData) {
			successCallback(balanceData);
		})
		.catch((error) => {
			if(error.code === 4001) {
				// EIP-1193 userRejectedRequest error
				alert('Please connect to MetaMask.');
			} else {
				alert(error.message);
				console.log(error);
			}
		});
}

function metaGetBlockByHash(ethereum, hash, successCallback, errorCallback=false) {
	  ethereum
		.request({ method: 'tokenURI', params:[hash, 'latest'] })
		.then(function(balanceData) {
			successCallback(balanceData);
		})
		.catch((error) => {
			if(error.code === 4001) {
				// EIP-1193 userRejectedRequest error
				alert('Please connect to MetaMask.');
			} else {
				alert(error.message);
				console.log(error);
			}
		});
}



