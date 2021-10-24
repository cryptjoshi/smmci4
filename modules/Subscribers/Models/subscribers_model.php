<?php
namespace Modules\Subscribers\Models;
use App\Models\ExtendModel;
class subscribers_model extends ExtendModel {
	public $tb_users;
	public $tb_subscribers;
    public $pagination;
	public function __construct(){
		$this->tb_categories 			= CATEGORIES;
		$this->tb_services   			= SERVICES;
		$this->tb_users      			= USERS;
		$this->tb_subscribers           = SUBSCRIBERS;
		$this->pagination = \Config\Services::pager();
		parent::__construct();
	}

	function get_users_list($total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();
		$builder = $this->db->table($this->tb_subscribers);
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

	function get_users_by_search($k){
		$k = trim(htmlspecialchars($k));
		$builder = $this->db->table($this->tb_subscribers)->select('*');
		
		if ($k != "" && strlen($k) >= 2) {
			$builder->where("(`first_name` LIKE '%".$k."%' ESCAPE '!' OR `last_name` LIKE '%".$k."%' ESCAPE '!' OR `email` LIKE '%".$k."%' ESCAPE '!' OR `country` LIKE '%".$k."%' ESCAPE '!' OR `ip` LIKE '%".$k."%' ESCAPE '!')");
		}
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}
}
