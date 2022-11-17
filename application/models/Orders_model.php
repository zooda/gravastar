<?php
class Orders_model extends CI_Model {

	public function __construct()
	{
		//$this->load->database();
	}

	public function setOrderInfo($order_no, $p_seq, $mb_seq, $amount, $items)
	{
		$total_amount = $amount * count($items);

		$insert_data = array(
			'order_no' => $order_no,
			'p_seq' => $p_seq,
			'mb_seq' => $mb_seq,
			'total_amount' => $total_amount,
		);

		if( $this->db->insert('orders', $insert_data) ) {
			$order_seq = $this->db->insert_id();
			$arrItemSeq = array();

			//아이템 저장 후 사용 완료 처리
			$insert_item_data = array();
			for( $i=0; $i < count($items); $i++ ){
				$it_seq = $items[$i]["seq"];
				$insert_item_data = array(
					"order_no" => $order_no,
					"p_seq" => $p_seq,
					"it_seq" => $it_seq,
					"amount" => $amount
				);
				$this->db->insert('order_items', $insert_item_data);

				$arrItemSeq[] = $it_seq;
			}

			//아이템 사용완료 처리
			$this->db->where_in('seq', $arrItemSeq);
			$this->db->set('used', 'Y');
			return $this->db->update('items');

		} else {
			return FALSE;
		}
	}

	public function setOrderSuccess($order_no, $data, $hash, $account, $hex_amount )
	{
		//에러 상태 저장
		$this->db->where('order_no', $order_no);
		$this->db->set('status', '1');
		$this->db->set('response', $data);
		$this->db->set('transaction_hash', $hash);
		$this->db->set('account', $account);
		$this->db->set('hex_amount', $hex_amount);
		return $this->db->update('orders');
	}

	public function setOrderError($order_no, $data)
	{
		//에러 상태 저장
		$this->db->where('order_no', $order_no);
		$this->db->set('status', '2');
		$this->db->set('response', $data);
		$this->db->update('orders');

		//아이템 사용상태 복구
		$item_where = array();
		$item_where["order_no"] = $order_no;

		$query = $this->db->get_where('order_items', $item_where);

		$arrItemSeq = array();
		foreach( $query->result_array() as $item ) {
			$arrItemSeq[] = $item["it_seq"];
		}

		$this->db->where_in('seq', $arrItemSeq);
		$this->db->set('used', 'N');
		return $this->db->update('items');
	}

/*
	public function get_products($seq = FALSE)
	{
		$where = array();

		if ($seq === FALSE)
		{
			$where["show_yn"] = "Y";
		} else {
			$where["show_yn"] = "Y";
			$where["seq"] = $seq;
		}

		$query = $this->db->get_where('products', $where);
		return $query->result_array();

		//$query = $this->db->get_where('products', $where);
		//return $query->row_array();
	}
*/

}