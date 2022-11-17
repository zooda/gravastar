<?php
class Products_model extends CI_Model {

	public function __construct()
	{
		//$this->load->database();
	}

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

	public function popItemsRand($p_seq, $contract, $item_count, $kind=1)
	{
		$query = $this->db->query("
			select gi.*
			from gra_product_items gpi
			left join gra_items gi on gpi.it_seq = gi.seq
			where gpi.pd_seq = ".$p_seq." and contract_address = '".$contract."' and gi.enabled = 'Y' and gi.used = 'N' and kind = '".$kind."'
			order by rand()
			limit 
		".$item_count);

		return $query->result_array();

		//$query = $this->db->get_where('products', $where);
		//return $query->row_array();
	}

	public function getItemInfoByOrderno($orderno)
	{
		$query = $this->db->query("
			select goi.it_seq, go.order_no, go.transaction_hash, go.account, goi.amount, gi.nft_token_id, gi.name, gi.symbol
			from gra_orders go
			left join gra_order_items goi on go.order_no = goi.order_no
			left join gra_items gi on gi.seq = goi.it_seq
			where go.order_no = '".$orderno."'
		");

		return $query->result_array();

		//$query = $this->db->get_where('products', $where);
		//return $query->row_array();
	}

	public function getItemInfoByTokenid($tokenid)
	{
		$query = $this->db->query("
			select gi.*, gp.title, gp.contents, gp.image_url, gp.marker
			from gra_items gi
			left join gra_product_items gpi on gpi.it_seq = gi.seq
			left join gra_products gp on gp.seq = gpi.pd_seq
			where gi.nft_token_id = ".$tokenid."
		");

		return $query->result_array();

		//$query = $this->db->get_where('products', $where);
		//return $query->row_array();
	}
}