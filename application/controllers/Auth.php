<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth extends CI_Controller { 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Members_model');
		//$this->load->model('Products_model');
	}

	public function index()
	{
		redirect('/' ,'refresh');
		//$this->load->view('login');
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('/' ,'refresh');
		exit;
	}

	public function login(){
		//인젝션 처리
		$uid = $this->input->post('uid',true);
		$email = $this->input->post('email',true);
		$name = $this->input->post('name',true);
		$provider = $this->input->post('provider',true);
		$method = $this->input->post('method',true);
		$mb_seq = "";

		//회원 가입 유무 체크
		$member = $this->Members_model->get( array("uid"=>$uid) );

		if( !$member ) { //회원이 아니라면 가입
			$res = $this->Members_model->set(array(
				"uid"=>$uid,
				"email"=>$email,
				"name"=>$name,
				"provider"=>$provider
			));

			if( $res['status'] == 'success' ) {
				$mb_seq = $res['insert_id'];
			} else {
				echo $res['message'].' ['.$res['num'].']';
				log_message("error", $res['message']." [".$res["num"]."]");
				return FALSE;
			}
		} else {
			$mb_seq = $member["seq"];
			$uid = $member["f_uid"];
			$email = $member["email"];
		}

		//로그인 일시 저장
		$this->Members_model->setLoginDate($mb_seq);

		//지갑 정보
		$account = $this->Members_model->getAccount( $mb_seq );

		//세션처리
		$session_data = array(
			"mb_seq"=> $mb_seq,
			"mb_email"=> $email,
			"mb_uid"=> $uid
		);
		$this->session->set_userdata($session_data);

		if( $method == 'ajax' ) {
			if( $account ) {
				$arrResult[] = array(
					'status' =>'success',
					'mb_seq' => $mb_seq,
					'uid' => $uid,
					'email' => $email,
					'name' => $name,
					'provider' => $provider,
					'account' => $account,
					'msg'=>'로그인 되었습니다.',
				);
			} else {
				$arrResult[] = array(
					'status' =>'noaccount',
					'mb_seq' => $mb_seq,
					'uid' => $uid,
					'email' => $email,
					'name' => $name,
					'provider' => $provider,
					'account' => $account,
					'msg'=>'로그인 되었습니다.',
				);
			}

			echo json_encode($arrResult);
		}

	} //login End
}