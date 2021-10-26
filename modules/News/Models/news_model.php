<?php
namespace Modules\News\Models;
use App\Models\ExtendModel;

class news_model extends ExtendModel {
	public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
	public $tb_news;
	 

	public function __construct(){
		parent::__construct();
		//Config Module
		$this->tb_users      = USERS;
		$this->tb_categories = CATEGORIES;
		$this->tb_services   = SERVICES;
		$this->tb_orders     = ORDER;
		$this->tb_news       = NEWS;
 

	}

	function get_news(){
		
		$builder = $this->db->table($this->tb_news)->select('*')->orderBy('created', 'DESC');
		$query = $builder->get();
		if($query->getResult()){
			return $data = $query->getResult();
		}else{
			false;
		}
	}

	function get_news_by_ajax(){
		$builder = $this->db->table($this->tb_news)->select('*');
		$builder->where("(expiry  > '".NOW."')");
		$builder->where("(created < '".NOW."')");
		$builder->where('status', 1);
		$builder->orderBy('created', 'DESC');
		$query = $builder->get();
		if($query->getResult()){
			return $data = $query->getResult();
		}else{
			false;
		}
	}

	function get_news_by_ids($ids = ""){
		$builder = $this->db->table($this->tb_news)->select('*')->where("ids", $ids);
		$query = $builder->get();
		$result = $query->getRow();
		if (!empty($result)) {
			return $result;
		}
		return false;
	}

}
