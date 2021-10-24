<?php
namespace Modules\Payments_bonuses\Models;
use App\Models\ExtendModel;

class payments_bonuses_model extends ExtendModel {
	public $tb_payments;
	
	public function __construct(){
		$this->tb_payments       = PAYMENTS_METHOD;
		parent::__construct();
	}

	function get_paymnets_lists(){
		$builder = $this->db->table($this->tb_api_providers)->select("*")->orderBy("id", 'ASC');
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

}
