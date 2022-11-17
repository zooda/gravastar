<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('common');
		$this->load->model('Products_model');
		$this->load->model('Members_model');
		$this->load->model('Orders_model');
	}

	public function item()
	{
		$data['buycontract'] = $this->common->getBuyContractAddress();

		$this->load->view('layout/header');
		$this->load->view('my/item',$data);
		$this->load->view('layout/footer');
	}

	public function itemInfoByTokenid()
	{
		//인젝션 처리
		$tokenid = $this->input->post('tokenid',true);
		
		$arrItems = $this->Products_model->getItemInfoByTokenid($tokenid);

		if( count($arrItems) > 0 ) {
			$arrResult[] = array(
				"status" =>"success",
				"items" => $arrItems,
				"msg"=>"아이템 리스트"
			);
		} else {
			$arrResult[] = array(
				"status" =>"error",
				"msg"=>"해당 아이템이 존재하지 않습니다."
			);
			log_message("error", "[my/itemInfoByTokenid] tokenid :".$tokenid.", msg: 해당 아이템이 존재하지 않습니다.");
		}

		echo json_encode($arrResult);
	}

	public function account()
	{
		$data['buycontract'] = $this->common->getBuyContractAddress();

		$this->load->view('layout/header');
		$this->load->view('my/account',$data);
		$this->load->view('layout/footer');
	}

}
