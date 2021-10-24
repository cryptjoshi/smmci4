<?php namespace Modules\Users\Models;

use App\Models\ExtendModel;

class UsersModel extends ExtendModel {
    public $tb_users;
	public $tb_users_price;
	public $tb_categories;
	public $tb_services;
	public $pagination;

    public function __construct()
    {
        $this->tb_users =USERS;
	$this->tb_users_price=USERS_PRICE;
	$this->tb_categories=CATEGORIES;
	$this->tb_services = SERVICES;
	$this->pagination = \Config\Services::pager();
	parent::__construct();
    }
  
    	public function get_users_list($total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();
        $builder = $this->db->table($this->tb_users);
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		$builder->select("*");
		
		$builder->orderBy("id", 'DESC');
		$query = $builder->get();

		if ($total_rows) {
			$result = $builder->countAllResults();
			return $result;
		}else{
			$result = $query->getResult();
			return $result;
		}
		return false;
	}

	// old V3.2
	public function get_users_by_search($k){
		$k = trim(htmlspecialchars($k));
        $builder = $this->db->table($this->tb_users);
		$builder->select('*');
		
		if ($k != "" && strlen($k) >= 2) {
			$builder->where("(`first_name` LIKE '%".$k."%' ESCAPE '!' OR `last_name` LIKE '%".$k."%' ESCAPE '!' OR `email` LIKE '%".$k."%' ESCAPE '!' OR `desc` LIKE '%".$k."%' ESCAPE '!')");
		}
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}
	
	// Get Count of orders by Search query
	public function get_count_users_by_search($search = []){
		$k = trim($search['k']);

		$where_like = "(`first_name` LIKE '%".$k."%' ESCAPE '!' OR `last_name` LIKE '%".$k."%' ESCAPE '!' OR `email` LIKE '%".$k."%' ESCAPE '!')";
        $builder = $this->db->table($this->tb_users);
		$builder->select('*');
		
		if ($where_like) $builder->where($where_like);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		$number_row = $$builder->countAllResults();
		return $number_row;
	}

	// Search Logs by keywork and search type
	public function search_logs_by_get_method($search, $limit = "", $start = ""){
		$k = trim($search['k']);
		$where_like = "(`first_name` LIKE '%".$k."%' ESCAPE '!' OR `last_name` LIKE '%".$k."%' ESCAPE '!' OR `email` LIKE '%".$k."%' ESCAPE '!')";
        $builder = $this->db->table($this->tb_users);
		$builder->select('*');
		
		if ($where_like) $builder->where($where_like);
		$builder->orderBy('id', 'DESC');
		$builder->limit($limit, $start);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	// Get custom rate list by user
	public function get_current_customrate_by($uid){
        
        $builder= $this->db->table($this->tb_services." s");
		$builder->select('up.*, s.name, s.original_price, s.price');
		
		$builder->join($this->tb_users_price." up", "s.id = up.service_id", 'left');
		$builder->where('up.uid', $uid);
		$builder->orderBy('up.service_id', 'ASC');
		$builder->orderBy('up.id', 'ASC');
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

	// Get all user price
	public function get_custom_rates(){

		//$custom_rates = $this->model->fetch('uid, service_id, service_price', $this->tb_users_price, ['uid' => session('uid')]);
		$builder = $this->db->table( $this->tb_users_price)->select('uid, service_id, service_price')->where(['uid' => session('uid')]);
		//$custom_rates = $this->model->fetch('uid, service_id, service_price', $this->tb_users_price, ['uid' => session('uid')]);
		$custom_rates =  $builder->get()->getResultArray();
		$exist_db_custom_rates = [];
		if (!empty($custom_rates)) {
			foreach ($custom_rates as $key => $row) {
				$exist_db_custom_rates[$row->service_id]['uid']           = $row->uid;
				$exist_db_custom_rates[$row->service_id]['service_id']    = $row->service_id;
				$exist_db_custom_rates[$row->service_id]['service_price'] = $row->service_price;
			}
		}
		return $exist_db_custom_rates;
	}
	
}