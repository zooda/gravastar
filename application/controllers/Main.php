<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('common');
		$this->load->model('Products_model');
		$this->load->model('Members_model');
		$this->load->model('Orders_model');
	}

	public function main()
	{
		$data = array();
		$data['products'] = $this->Products_model->get_products();

		$data['buycontract'] = $this->common->getBuyContractAddress();

		$this->load->view('layout/header');
		$this->load->view('main', $data);
		$this->load->view('layout/footer');
	}

	public function shop($action) {
		if( $action == "buy" ) {
			//인젝션 처리
			$p_seq = $this->input->post('p_seq',true);
			$item_count = $this->input->post('item_count',true);

			//상품 결제 정보 리턴
			$arr_product = $this->Products_model->get_products($p_seq);

			if( count($arr_product) > 0 ) {
				$total_amount = $arr_product[0]['amount'] * $item_count;
				$arrResult[] = array(
					'status' =>'success',
					'product' => array(
						'p_seq' => $arr_product[0]['seq'],
						'title' => $arr_product[0]['title'],
						'contents' => $arr_product[0]['contents'],
						'image_url' => $arr_product[0]['image_url'],
						'amount' => $arr_product[0]['amount'],
						'item_count' => $item_count,
						'total_amount' => $total_amount,
						'marker' => $arr_product[0]['marker'],
						'str_total_amount' => number_format($total_amount),
					),
					'msg'=>'상품 결제 정보.',
				);
			} else {
				$arrResult[] = array(
					'stauts' => 'error',
					'msg'=>'상품정보가 없습니다.',
				);
				log_message("error", "[main/shop/buy] p_seq :".$p_seq.", item_cocunt :".$item_count.", msg: 상품정보가 없습니다.");
			}
		} //buy End
		else if( $action == "buyok" ) { //실제 구매
			//인젝션 처리
			$mb_seq = $this->input->post('mb_seq',true);
			$account = $this->input->post('account',true);
			$p_seq = $this->input->post('p_seq',true);
			$item_count = $this->input->post('item_count',true);

			$mb_account = $this->Members_model->getAccount( $mb_seq );

			if( $mb_account && strtoupper($account) != strtoupper($mb_account) ) {
				$arrResult[] = array(
					'status' =>'different',
					'account' => $account,
					'msg'=>'저장된 지갑과 연결된 지갑이 다릅니다.',
				);
			} else {
				if( !$mb_account ) {
					//연결된 지갑이 없으므로 바로 저장
					$this->Members_model->setAccount( $mb_seq, $account );
				}

				//상품 수량 체크
				$arr_item = $this->Products_model->popItemsRand($p_seq, $this->common->getBuyContractAddress(), $item_count);

				if( count($arr_item) < $item_count ) {
					$arrResult[] = array(
						'stauts' => 'error',
						'msg'=>'상품 수량이 부족합니다.',
					);
					log_message("error", "[main/shop/buyok] mb_seq :".$mb_seq.", account :".$account.", p_seq :".$p_seq.", item_count :".$item_count.", msg: 상품 수량이 부족합니다.");
				} else {
					$arr_product = $this->Products_model->get_products($p_seq);
					$total_amount = $arr_product[0]['amount'] * $item_count;

					$order_no = $this->common->createOrderno();
					if( $this->Orders_model->setOrderInfo($order_no, $p_seq, $mb_seq, $arr_product[0]['amount'], $arr_item) ) {
						foreach( $arr_item as $item ) {
							$tokens[] = $item["nft_token_id"];
						}
						$arrResult[] = array(
							'status' =>'success',
							'order_no' => $order_no,
							'total_amount' => $total_amount,
							'tokens' => $tokens,
							'msg'=>'주문정보 임시저장.'
						);
					} else {
						$arrResult[] = array(
							'status' =>'error',
							'msg'=>'주문정보 생성 실패.'
						);
						log_message("error", "[main/shop/buyok] mb_seq :".$mb_seq.", account :".$account.", p_seq :".$p_seq.", item_count :".$item_count.", msg: 주문정보 생성 실패.");
					}
				}
			}
		} //buyok End
		else if( $action == "result" ) {
			//인젝션 처리
			$status = $this->input->post('status',true);
			$order_no = $this->input->post('order_no',true);
			$val = $this->input->post('val',true);
			$hash = $this->input->post('hash',true);
			$account = $this->input->post('account',true);
			$hex_amount = $this->input->post('hex',true);

			if( $status == "error" ) {
				$this->Orders_model->setOrderError($order_no,$val);

				$arrResult[] = array(
					'status' =>'error',
					'msg'=>'주문 실패 처리 완료'
				);
				log_message("error", "[main/shop/result] order_no :".$order_no.", account :".$account.", val :".$val.", hash :".$hash.", msg: 주문 실패 처리.");

			} else if( $status =="success" ) {
				$this->Orders_model->setOrderSuccess($order_no,json_encode($val), $hash, $account, $hex_amount );

				
				$arrItems = $this->Products_model->getItemInfoByOrderno($order_no);

				$tokens = array();
				foreach( $arrItems as $tmpItem ) {
					$tokens[] = (object)[
						"tokenId"=>$tmpItem["nft_token_id"],
						"price"=>$tmpItem["amount"]
					];
				}
				//구매 후 서버에 기록
				$this->saveBuyHistoryToServer($tokens, $hash, $arrItems[0]["name"], $arrItems[0]["symbol"]);

				$arrResult[] = array(
					'status' =>'success',
					'msg'=>'주문 처리 완료'
				);
			}

		} //result End

		echo json_encode($arrResult);
	} //shop End


	public function saveBuyHistoryToServer($tokens, $hash, $contractName, $contractSymbol)
	{
		$api_vars = array(
			"tokens"=> $tokens,
			"txHash"=> $hash,
			"contractName"=> $contractName,
			"contractSymbol"=> $contractSymbol
			);
//Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiJhZG1pbiIsImlhdCI6MTY2NzIxMDA5OX0.wLO1Dko6PBpAfX21ISkWc4QF8LChLBu8Yfaf6nk02UU
		$ch = curl_init();
		//echo $this->API_HOST.$api;
		//개발: 13.125.166.164:3001
		curl_setopt($ch, CURLOPT_URL, "https://dev-admin-api.v2.pentasquare.io/v1/misc/buy-polygon-erc1155");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_HEADER, 1); // 쿠키를 가져오려면 써줘야.
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json','Authorization:Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyaWQiOiJhZG1pbiIsImlhdCI6MTY2NzIxMDA5OX0.wLO1Dko6PBpAfX21ISkWc4QF8LChLBu8Yfaf6nk02UU'));
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

		curl_setopt($ch, CURLOPT_POST, 1);
		$json_vars = json_encode($api_vars);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_vars);

		$resp = curl_exec($ch);

		if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
			echo $error_msg;
		}

		//echo $resp;
		//$body=mb_substr($resp, curl_getinfo($ch,CURLINFO_HEADER_SIZE));
		curl_close ($ch);

		$res = json_decode($resp, true);

		print_r($res);
		//$res = $resp; //디코딩 안함
	}

	public function firebasetest()
	{
		$tokens = array();

		$tokens[] = (object)[
			"tokenId"=>35,
			"price"=>1
		];

		$this->saveBuyHistoryToServer($tokens, "0x7835267ef4ee0efe1321aaf737babeb5b62ed09ed8269cf3dabcf3b1263ab08f", "Slime Contract", "SLIME");
	}
}
