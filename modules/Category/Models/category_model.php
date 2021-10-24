<?php
namespace Modules\Category\Models;
use App\Models\ExtendModel;

class category_model extends ExtendModel {
	public $tb_users;
	public $tb_categories;
	public $tb_services;

	public function __construct(){
		$this->tb_categories = CATEGORIES;
		$this->pagination = \Config\Services::pager();
		parent::__construct();
	}



	function get_category_lists($total_rows = false, $status = "", $limit = "", $start = ""){
		$builder=$this->db->table($this->tb_categories)->select('*');
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		
		$builder->orderBy('sort', 'ASC');

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

	function get_category_lists_by_search($k = ""){
		$k = trim(htmlspecialchars($k));
		$builder=$this->db->table($this->tb_categories)->select('*');
		if (get_role("user")) {
			$builder->where("status", "1");
		}
		
		if ($k != "" && strlen($k) >= 2) {
			$builder->like("name", $k, 'both');
			$builder->orLike("desc", $k, 'both');
		}
		$builder->orderBy('sort', 'ASC');

		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}
}
