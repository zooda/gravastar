<?php 
	if ( ! defined("BASEPATH")) exit("No direct script access allowed"); 

class Members_model extends CI_Model { 
	public function __construct()
	{
			//$this->load->database();
	}

	public function get($option){
		if( isset($option["seq"]) ) {
			$this->db->where("seq",$option["seq"]);
		}
		if( isset($option["uid"]) ) {
			$this->db->where("f_uid",$option["uid"]);
		}
		if( isset($option["email"]) ) {
			$this->db->where("email",$option["email"]);
		}

		$this->db->where("is_active","Y");
		$this->db->order_by("created_date", "desc");
		$query = $this->db->get("members");

		if( $query->num_rows() > 0 ) 
			return $query->row_array();
		else
			return FALSE;

/*
		if($query->num_rows() > 0){
			//있다면 바로 로그인
			foreach ($query->result() as $row){
				$data = array(
					"mb_seq"=> $row->seq,
					"mb_email"=> $row->email,
					"logged_in"=>TRUE
				);
			}
			$this->session->set_userdata($data);
			return TRUE;
		} else{
			return FALSE;
		}
		*/
	}

	public function set($data){
		$insert_data = array(
			'f_uid' => $data['uid'],
			'email' => $data['email'],
			'name' => $data['name'],
			'provideid' => $data['provider']
		);

		if( $this->db->insert('members', $insert_data) ) {
			return array(
				'status' => 'success',
				'insert_id' => $this->db->insert_id()
			);
		} else {
			return array(
				'status' => 'error',
				'message' => $this->db->_error_message(),
				'num' => $this->db->_error_number()
			);
		}
	}

	public function setLoginDate($seq) {
		$this->db->where('seq', $seq);
		$this->db->set('logined_date', 'DATE_ADD(NOW(), INTERVAL 1 MINUTE)', FALSE);
		return $this->db->update('members');
	}

	public function getAccount($mb_seq){
		$query = $this->db->query("
			select *
			from gra_members gm
			join gra_accounts ga on gm.a_seq = ga.seq
			where gm.seq = ".$mb_seq
		);

		if(  $query->num_rows() > 0 ) {
			$row = $query->row_array();
			return $row['account'];
		} else {
			return FALSE;
		}
	}

	//지갑 정보 갱신
	public function setAccount($mb_seq, $account){
		$insert_data = array(
			'mb_seq' => $mb_seq,
			'account' => $account
		);

		if( $this->db->insert('accounts', $insert_data) ) {
			$a_seq = $this->db->insert_id();

			$this->db->where('seq', $mb_seq);
			$this->db->set('a_seq', $a_seq, FALSE);
			$this->db->set('modifed_date', 'DATE_ADD(NOW(), INTERVAL 1 MINUTE)', FALSE);
			return $this->db->update('members');
		} else {
			return FALSE;
		}
	}

//사용안함
	public function login($email, $uid){ 
		$email = $this->db->escape($email);
		$code = $this->db->escape($code);

		$this->db->where("email",$email);
		$this->db->where("f_uid",$uid);
		$this->db->where("is_active","Y");
		$query = $this->db->get("members");

		if($query->num_rows() > 0){
			//있다면 바로 로그인
			foreach ($query->result() as $row){
				$data = array(
					"mb_seq"=> $row->seq,
					"mb_email"=> $row->email,
					"logged_in"=>TRUE
				);
			}
			$this->session->set_userdata($data);
			return TRUE;
		} else{
			return FALSE;
		}
	}
//사용안함=======================
	public function join($email, $code){ 
		$email = $this->db->escape($email);
		$code = $this->db->escape($code);

		$this->db->where("email",$email);
		$this->db->where("f_uid",$code);
		$this->db->where("is_active","Y");
		$query = $this->db->get("members");

		if($query->num_rows() < 1){
			$data = array(
				'email' => $email,
				'f_uid' => $f_uid
			);

			return $this->db->insert('members', $data);
		}

		return FALSE;
	}

	public function isLoggedIn(){
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

		$is_logged_in = $this->session->userdata("logged_in");
		if(!isset($is_logged_in) || $is_logged_in!==TRUE)
		{
			return FALSE;
		}
		return TRUE;
	}
}