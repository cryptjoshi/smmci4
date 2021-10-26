<?php 
namespace Modules\Order\Models;

use App\Models\ExtendModel;

class OrderModel extends ExtendModel {
    public $tb_users;
	public $tb_order;
	public $tb_categories;
	public $tb_services;
	public $tb_api_providers;
	public $pagination;

	public function __construct(){
		parent::__construct();
		$this->tb_categories        = CATEGORIES;
		$this->tb_order             = ORDER;
		$this->tb_users             = USERS;
		$this->tb_services          = SERVICES;
		$this->tb_api_providers   	= API_PROVIDERS;
		$this->pagination = \Config\Services::pager();
	}

	function get_categories_list(){
		$data  = array();
		$builder = $this->db->table($this->tb_categories)->select("*")->from($this->tb_categories)->where("status", "1")->orderBy("sort", 'ASC');
		$query = $builder->get();

		$categories = $query->getResult();
		if(!empty($categories)){
			return $categories;
		}
		return false;
	}
	function get_categories_info($cate_id=""){
		$data  = array();
		$builder = $this->db->table($this->tb_categories)->select("*")->where("status", "1")->where("id",$cate_id)->orderBy("sort", 'ASC');
		$query = $builder->get();

		$categories = $query->getResult();
		if(!empty($categories)){
			return $categories;
		}
		return false;
	}
	function get_services_list_by_cate($id = ""){
		$data  = array();
		$builder = $this->db->table($this->tb_services)->select("*");

        if (!get_role("admin")) {
			$builder->where("status", "1");
		}
		$builder->where("cate_id", $id)->orderBy("price", "ASC");
		$query = $builder->get();
		$services = $query->getResult();
		if(!empty($services)){
			return $services;
		}
		return false;
	}

	function get_service_search($val = ""){
		$data  = array();
		if(is_null($val))$val="";
        $builder = $this->db->table($this->tb_services)->select("*");
		$builder->like("id",$val);
        $builder->orLike('name',$val);
		$builder->where("status", "1");
		$query = $builder->get();
		$services = $query->getResult();
		if(!empty($services)){
			return $services;
		}
		return false;
	}


	function get_service_item($id = ""){
		$data  = array();
        $builder = $this->db->table($this->tb_services)->select("*");
		$builder->where("id", $id);
		$builder->where("status", "1");
		$query = $builder->get();
		$service = $query->getRow();
		
		if(!empty($service)){
			return $service;
		}
		return false;
	}

	function get_service_item_byid($id = ""){
		$data  = array();
        $builder = $this->db->table($this->tb_services)->select("*");
		$builder->where("id",$id);
		$builder->where("status", "1");
		$query = $builder->get();
		$services = $query->getResult();
		if(!empty($services)){
			return $services;
		}
		return false;
	}
	function get_services_by_cate($id = ""){
		$data  = array();
        $builder = $this->db->table($this->tb_services)->select("*");
		$builder->where("cate_id", $id);
		$builder->where("status", "1");
		$builder->orderBy('price', 'ASC');
		$query = $builder->get();
		$services = $query->getResult();
		if(!empty($services)){
			return $services;
		}

		return false;
	}
	function get_order_logs_list($total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();

		$builder = $this->db->table($this->tb_order." o");
		
		if (get_role("user")) {
			$builder->where("o.uid", session("uid"));
		}
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		$builder->select('o.uid,o.ids as ids,o.refill_status,o.charge,o.link,o.comments,o.created,o.is_refill,o.status,o.is_drip_feed,o.id,o.quantity,o.service_type,o.api_provider_id,o.start_counter,o.remains,o.service_id,o.api_order_id,o.formal_charge,o.profit,u.email as user_email, s.name as service_name, api.name as api_name');
		$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
		$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');
		$builder->join($this->tb_api_providers." api", "api.id = o.api_provider_id", 'left');
		if($status != "all" && !empty($status)){
			$builder->where("o.status", $status);
		}
		$builder->where("o.service_type !=", "subscriptions");
		$builder->where("o.is_drip_feed !=", 1);
		$builder->orderBy("o.id", 'DESC');

		$query = $builder->get();
		if ($total_rows) {
			
			$result = $builder->countAll();
			return $result;
		}else{
			$result = $query->getResult();
			return $result;
		}
		return false;
	}
	function get_total_order_logs_list($total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();

		$builder = $this->db->table($this->tb_order." o");
		
		if (get_role("user")) {
			$builder->where("o.uid", session("uid"));
		}
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		$builder->select('o.uid');
		$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
		$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');
		$builder->join($this->tb_api_providers." api", "api.id = o.api_provider_id", 'left');
		if($status != "all" && !empty($status)){
			$builder->where("o.status", $status);
		}
		$builder->where("o.service_type !=", "subscriptions");
		$builder->where("o.is_drip_feed !=", 1);
		$builder->orderBy("o.id", 'DESC');

		$query = $builder->get();
		if ($total_rows) {
			
			$result = $builder->countAll();
			return $result;
		}else{
			$result = $query->getResult();
			return $result;
		}
		return false;
	}
	// Get Count of orders by status
	function get_count_orders($status = ""){
		$builder=$this->db->table($this->tb_order);
		if (get_role("user")) {
			$builder->where("uid", session("uid"));
		}
		$builder->select("id");
		//$this->db->from($this->tb_order);
		if($status != "all" && !empty($status)){
			$builder->where("status", $status);
		}
		$builder->where("service_type !=", "subscriptions");
		$builder->where("is_drip_feed !=", 1);
		//$query = $builder->get();
		return $builder->countAll();
	}

	function get_orders_logs_by_search($k){
		$k = trim(htmlspecialchars($k));
		$builder = $this->db->table($this->tb_order." o");
		if (get_role("user")) {
			$builder->select('o.*, u.email as user_email, s.name as service_name');
			//$this->db->from($this->tb_order." o");
			$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
			$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`o`.`id` LIKE '%".$k."%' ESCAPE '!' OR `o`.`link` LIKE '%".$k."%' ESCAPE '!' OR `o`.`status` LIKE '%".$k."%' ESCAPE '!' OR  `s`.`name` LIKE '%".$k."%' ESCAPE '!')");
			}
			$builder->where("o.service_type !=", "subscriptions");
			$builder->where("o.is_drip_feed !=", 1);
			$builder->where("u.id", session("uid"));
			$builder->orderBy("o.id", 'DESC');
			$query = $builder->get();
			$result = $query->getResult();

		}else{
			$builder->select('o.*, u.email as user_email, s.name as service_name, api.name as api_name');
			$builder->from($this->tb_order." o");
			$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
			$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');
			$builder->join($this->tb_api_providers." api", "api.id = o.api_provider_id", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`o`.`api_order_id` LIKE '%".$k."%' ESCAPE '!' OR `o`.`id` LIKE '%".$k."%' ESCAPE '!' OR `o`.`status` LIKE '%".$k."%' ESCAPE '!' OR `o`.`link` LIKE '%".$k."%' ESCAPE '!' OR  `u`.`email` LIKE '%".$k."%' ESCAPE '!' OR  `s`.`name` LIKE '%".$k."%' ESCAPE '!')");
			}
			$builder->where("o.service_type !=", "subscriptions");
			$builder->where("o.is_drip_feed !=", 1);
			$builder->orderBy("o.id", 'DESC');

			$query = $builder->get();
			$result = $query->getResult();
		}
		return $result;
	}

	// Get Count of orders by Search query
	public function get_count_orders_by_search($search = []){
		$k = htmlspecialchars($search['k']);
		$where_like = "";
		$builder= $this->db->table($this->tb_order." o");
		if (get_role("user")) {
			$builder->where("o.uid", session("uid"));
			$where_like = "(`o`.`id` LIKE '%".$k."%' ESCAPE '!' OR `o`.`link` LIKE '%".$k."%' ESCAPE '!')";
		}else{
			switch ($search['type']) {
				case 1:
					#order id
					$where_like = "`o`.`id` LIKE '%".$k."%' ESCAPE '!'";
					break;
				case 2:
					# API order id
					$where_like = "`o`.`api_order_id` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 3:
					# Link
					$where_like = "`o`.`link` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 4:
					# User Email
					$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
					break;
			}
		}
		$builder->select('o.id, u.email as user_email');
		$builder->from($this->tb_order." o");
		$builder->join($this->tb_users." u", "u.id = o.uid", 'left');

		if ($where_like) $builder->where($where_like);

		$builder->where("o.service_type !=", "subscriptions");
		$builder->where("o.is_drip_feed !=", 1);
		$query = $builder->get();
		$number_row = $builder->countAll();//$query->num_rows();
		
		return $number_row;
	}

	// Search Logs by keywork and search type
	public function search_logs_by_get_method($search, $limit = "", $start = ""){
		$k = htmlspecialchars($search['k']);
		$where_like = "";
		
		if (get_role("user")) {
			$builder=$this->db->table($this->tb_order." o");
			$builder->select('o.*, u.email as user_email, s.name as service_name');
			
			$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
			$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');

			$builder->where("(`o`.`id` LIKE '%".$k."%' ESCAPE '!' OR `o`.`link` LIKE '%".$k."%' ESCAPE '!')");

			$builder->where("o.service_type !=", "subscriptions");
			$builder->where("o.is_drip_feed !=", 1);
			$builder->where("o.uid", session("uid"));
			$builder->orderBy("o.id", 'DESC');
			$builder->limit($limit, $start);
			$query = $builder->get();
			$result = $query->getResult();

		}else{
			switch ($search['type']) {
				case 1:
					#order id
					$where_like = "`o`.`id` LIKE '%".$k."%' ESCAPE '!'";
					break;
				case 2:
					# API order id
					$where_like = "`o`.`api_order_id` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 3:
					# Link
					$where_like = "`o`.`link` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 4:
					# User Email
					$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
					break;
			}
			$builder=$this->db->table($this->tb_order." o");
			$builder->select('o.*, u.email as user_email, s.name as service_name, api.name as api_name');
			$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
			$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');
			$builder->join($this->tb_api_providers." api", "api.id = o.api_provider_id", 'left');

			if ($where_like) $builder->where($where_like);

			$builder->where("o.service_type !=", "subscriptions");
			$builder->where("o.is_drip_feed !=", 1);
			$builder->orderBy("o.id", 'DESC');
			$builder->limit($limit, $start);

			$query = $builder->get();
			$result = $query->getResult();
		}
		return $result;
	}

	function get_log_details($id){
		$builder=$this->db->table($this->tb_order." o");
		$builder->select('o.*, u.email as user_email, s.name as service_name, api.name as api_name');
		$builder->join($this->tb_users." u", "u.id = o.uid", 'left');
		$builder->join($this->tb_services." s", "s.id = o.service_id", 'left');
		$builder->join($this->tb_api_providers." api", "api.id = o.api_provider_id", 'left');
		$builder->where("o.main_order_id", $id);
		$builder->orderBy("o.id", 'DESC');
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	function get_top_bestsellers($limit = ""){
		if ($limit == "") {
			$limit = 10;
		}
		$query = "SELECT count(service_id) as total_orders, service_id FROM {$this->tb_order} GROUP BY service_id ORDER BY total_orders DESC LIMIT 30";
		$top_sellers =  $this->db->query($query)->getResult();
		$result = [];
		$i = 1;
		foreach ($top_sellers as $key => $row) {
			$builder=$this->db->table($this->tb_services." s");
			$builder->select('s.*, api.name as api_name');
			$builder->join($this->tb_api_providers." api", "s.api_provider_id = api.id", 'left');
			$builder->where("s.id", $row->service_id);
			$builder->where("s.status", 1);
			$builder->orderBy("s.price", 'ASC');
			$query = $builder->get();
			if(!empty($query->getRow()) && $i <= $limit ){
				$item = $query->getRow();
				$item->total_orders = $row->total_orders;
				$result[] = $item;
				$i++;
			}
		}
		return $result;
	}


}