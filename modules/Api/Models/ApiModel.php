<?php 
namespace Modules\Api\Models;

use App\Models\ExtendModel;
class ApiModel extends ExtendModel {

    public $tb_users;
	public $tb_users_price;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
	public $tb_transaction_logs;
	public $tb_refill;

	public function __construct(){
		$this->tb_categories 	= CATEGORIES;
		$this->tb_services   	= SERVICES;
		$this->tb_orders     	= ORDER;
		$this->tb_users_price   = USERS_PRICE;
		$this->tb_transaction_logs   = TRANSACTION_LOGS;
		$this->tb_refill = REFILLS;
		parent::__construct();
	}

	function get_services_list($uid = ""){
		$data  = array();
		$this->db->select("s.id as service, s.name, c.name as category, s.price as rate, s.min, s.max, s.type, s.desc, s.dripfeed");
		$this->db->from($this->tb_services ." s");
		$this->db->join($this->tb_categories." c", "s.cate_id = c.id", 'left');
		$this->db->where("s.status", "1");
		$this->db->where("c.status", "1");
		$this->db->order_by("c.sort", "ASC");
		$this->db->order_by("s.price", "ASC");
		$query = $this->db->get();
		if($query->result()){
			$data = $query->result();
			$custom_rates = $this->get_custom_rates($uid);
			if ($custom_rates && $uid) {
				foreach ($data as $key => $row) {
					if (isset($custom_rates[$row->service])) {
						$data[$key]->rate = $custom_rates[$row->service]['service_price'];
					}
				}
			}
			return $data;
		}else{
			false;
		}
	}

	// Get all user price
	private function get_custom_rates($uid = ""){
		$custom_rates = $this->model->fetch('uid, service_id, service_price', $this->tb_users_price, ['uid' =>$uid ] );
		$exist_db_custom_rates = [];
		if (!empty($custom_rates)) {
			foreach ($custom_rates as $key => $row) {
				$exist_db_custom_rates[$row->service_id]['uid']           = $row->uid;
				$exist_db_custom_rates[$row->service_id]['service_price'] = $row->service_price;
			}
		}
		return $exist_db_custom_rates;
	}

	function get_order_id($id, $uid){
		$this->db->select("id as order, status, charge, start_counter as start_count, remains");
		$this->db->from($this->tb_orders);
		$this->db->where("id", $id);
		$this->db->where("uid", $uid);
		$query = $this->db->get();

		$result = $query->row();
		if(!empty($result)){
			switch ($result->status) {

				case 'completed':
					$result->status = 'Completed';
					break;
					
				case 'completed':
					$result->status = 'Completed';
					break;

				case 'processing':
					$result->status = 'Processing';
					break;

				case 'pending':
					$result->status = 'Pending';
					break;

				case 'inprogress':
					$result->status = 'In progress';
					break;

				case 'partial':
					$result->status = 'Partial';
					break;

				case 'cancelled':
					$result->status = 'Cancelled';
					break;

				case 'refunded':
					$result->status = 'Refunded';
					break;

			}
			return $result;
		}
		return false;
	}
	function get_order_all_id($id, $uid){
		$this->db->select("*");
		$this->db->from($this->tb_orders);
		$this->db->where("id", $id);
		$this->db->where("uid", $uid);
		$query = $this->db->get();

		$result = $query->row();
		if(!empty($result)){
			switch ($result->status) {

				case 'completed':
					$result->status = 'Completed';
					break;
					
				case 'completed':
					$result->status = 'Completed';
					break;

				case 'processing':
					$result->status = 'Processing';
					break;

				case 'pending':
					$result->status = 'Pending';
					break;

				case 'inprogress':
					$result->status = 'In progress';
					break;

				case 'partial':
					$result->status = 'Partial';
					break;

				case 'cancelled':
					$result->status = 'Cancelled';
					break;

				case 'refunded':
					$result->status = 'Refunded';
					break;

			}
			return $result;
		}
		return false;
	}
	function get_all_transaction_byid($id,$total_rows=false){
		$this->db->select("*");
		$this->db->from($this->tb_transaction_logs);
		$this->db->where('uid', $id);
		$this->db->order_by("id", 'ASC');
		$query = $this->db->get();
		if ($total_rows) {
			$result = $query->num_rows();
			return $result;
		}else{
			$result = $query->result();
		}

	}

	function get_transaction_byid($id,$total_rows=false,$limit = "", $start = ""){
		$this->db->select("*");
		$this->db->from($this->tb_transaction_logs);
		$this->db->where('uid', $id);
		if ($limit != "" && $start >= 0) {
			$this->db->limit($limit, $start);
		}
		$this->db->order_by("id", 'ASC');

		$query = $this->db->get();
		if ($total_rows) {
			$result = $query->num_rows();
			return $result;
		}else{
			$result = $query->result();
			$total=$this->get_all_transaction_byid($id,true);
			return array(
				"data"=>$result,
				"total"=>$total,
				"page"=>$start,
				"size"=>$limit,
				"endPage"=>ceil($total/$limit)
			);
			//return $result;
		}
 
	}

	function get_refill($uid,$id){
		$this->db->select("*");
		$this->db->from($this->tb_refill);
		$this->db->where('uid', $uid);
		$this->db->where('id', $id);
		$this->db->order_by("id", 'ASC');
		$query = $this->db->get();
		$refill = (object)$query->result();
		return $refill->status;

	}

}