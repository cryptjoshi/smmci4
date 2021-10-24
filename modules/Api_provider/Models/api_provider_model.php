<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
namespace Modules\Api_provider\Models;
use App\Models\ExtendModel;
class api_provider_model extends ExtendModel {
	public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_api_providers;
	public $tb_orders;
	public $tb_refills;

	public function __construct(){
		$this->tb_categories 		= CATEGORIES;
		$this->tb_services   		= SERVICES;
		$this->tb_api_providers   	= API_PROVIDERS;
		$this->tb_orders     		= ORDER;
		$this->tb_refills			= REFILLS;
		parent::__construct();
	}

	function get_api_lists($status = false){
		$data  = array();
		$builder = $this->db->table($this->tb_api_providers);
		if ($status) {
			$builder->where("status", 1);
		}
		$builder->select("*");
		
		$builder->orderBy("id", 'ASC');

		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	function get_all_refill(){
		$data  = array();
		$where = "(`status` = 'pending' or `status` = 'inprogress')";
		$builder=$this->db->table($this->tb_refills);
		$builder->select("*");
		
		$builder->where($where);
		$builder->where("api_provider_id !=", 0);
		$builder->where("api_order_id =", -1);
		$builder->orderBy("id", 'ASC');
		$builder->limit(5, 0);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	function get_all_orders(){
		$data  = array();
		$where = "(`status` = 'pending' or `status` = 'inprogress')";
		$builder = $this->db->table($this->tb_orders);
		$builder->select("*");
		$builder->where($where);
		$builder->where("api_provider_id !=", 0);
		$builder->where("api_order_id =", -1);
		$builder->orderBy("id", 'ASC');
		$builder->limit(5, 0);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	function get_all_orders_status($limit = "", $start = ""){
		$where = "(`status` = 'active' or `status` = 'processing' or `status` = 'inprogress'  or `status` = 'pending' or `status` = '') AND `api_provider_id` != 0 AND `api_order_id` > 0 AND `changed` < '".NOW."' AND service_type != 'subscriptions'";
		$data  = array();
		$builder = $this->db->table($this->tb_orders);
		$builder->select("*");
		$builder->where($where);
		$builder->orderBy("id", 'ASC');
		$builder->limit($limit, $start);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	function get_all_subscriptions_status(){
		$where = "(`sub_status` = 'Active' or `sub_status` = 'Paused' OR `status` = '') AND `api_provider_id` != 0 AND `api_order_id` > 0 AND `changed` < '".NOW."' AND service_type = 'subscriptions'";
		$builder = $this->db->table($this->tb_orders);
		$builder->select("*");
		$builder->where($where);
		$builder->orderBy("id", 'ASC');
		$builder->limit(15,0);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}
}
