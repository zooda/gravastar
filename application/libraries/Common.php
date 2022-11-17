<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common {

	public function getBuyContractAddress()
	{
		return "0x0b2C5ed4eE64492Fe11648cf5db040B611672b72";
	}

	public function createOrderno() {
		$now = date("YmdHis"); //오늘의 날짜 년월일시분초
		$rand = strtoupper(substr(md5(uniqid(time())),0,6)) ; //임의의난수발생 앞6자리
		return $now ."-". $rand;
	}
}