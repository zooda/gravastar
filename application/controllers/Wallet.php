<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Wallet extends CI_Controller { 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Members_model');
		$this->load->model('Products_model');
	}

	public function index()
	{
		redirect('/' ,'refresh');
		//$this->load->view('login');
	}

	//지갑 관련
	public function account($action) {
		if( $action == "save" ) {
			//인젝션 처리
			$mb_seq = $this->input->post('mb_seq',true);
			$account = $this->input->post('account',true);

			$mb_account = $this->Members_model->getAccount( $mb_seq );

			if( $mb_account && strtoupper($account) != strtoupper($mb_account) ) {
				$arrResult[] = array(
					'status' =>'different',
					'account' => $account,
					'msg'=>'저장된 지갑과 연결된 지갑이 다릅니다.',
				);

				log_message("error", "[wallet/account/save] account :".$account.", mb_account :".$mb_account.", msg: 저장된 지갑과 연결된 지갑이 다릅니다.");
			} else {
				if( !$mb_account ) {
					//연결된 지갑이 없으므로 바로 저장
					$this->Members_model->setAccount( $mb_seq, $account );
				}
				$arrResult[] = array(
					'status' =>'success',
					'account' => $account,
					'msg'=>'지갑이 연결 되었습니다.',
				);
			}
		} else if($action == "change" ) {
			//인젝션 처리
			$mb_seq = $this->input->post('mb_seq',true);
			$account = $this->input->post('account',true);

			if( $mb_seq == "" || $account == "" ) {
				$arrResult[] = array(
					'status' =>'error',
					'account' => $account,
					'msg'=>'변경할 지갑 정보가 없습니다.',
				);
				log_message("error", "[wallet/account/change] account :".$account.", mb_seq :".$mb_seq.", msg: 변경할 지갑 정보가 없습니다.");
			} else {
				$this->Members_model->setAccount( $mb_seq, $account );

				$arrResult[] = array(
					'status' =>'success',
					'account' => $account,
					'msg'=>'지갑이 연결 되었습니다.',
				);
			}
		} else if($action == "order" ) {

		} else if($action == "buy" ) {

		}
		
		echo json_encode($arrResult);
	}//account End

	//지갑 연결 처리 (존재하면 
	/*
	public function connectAccount(){
		//인젝션 처리
		$uid = $this->input->post('uid',true);
		$email = $this->input->post('email',true);
		$name = $this->input->post('name',true);
		$provider = $this->input->post('provider',true);
		$method = $this->input->post('method',true);
	}
	*/
}