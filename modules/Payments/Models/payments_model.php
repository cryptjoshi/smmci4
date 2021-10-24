<?php
namespace Modules\Payments\Models;

use App\Models\ExtendModel;

class payments_model extends ExtendModel {
	public $tb_payments;
	
	public function __construct(){
		$this->tb_payments       = PAYMENTS_METHOD;
		parent::__construct();
	}

	public function get_paymnets_lists(){
		
		$query = $this->db->table($this->tb_api_providers)->select("*")->db->orderBy("id", 'ASC')->get();
		$result = $query->getResult();
		return $result;
	}

	public function get_payments_list_for_new_user(){
		$result = [];
		$payments_defaut = $this->model->fetch('id, new_users, type, name, status', $this->tb_payments, ['status' => 1]);
		if ($payments_defaut) {
			foreach ($payments_defaut as $key => $row) {
				$result[$row->type] = $row->new_users;
			}
		}
		return $result;
	}

}
