<?php
namespace Modules\Payments_bonuses\Controllers;
use App\Controllers\BaseController;
use Modules\Payments_bonuses\Models\payments_bonuses_model;
class Payments_bonuses extends BaseController {
	public $tb_payments;
	public $tb_payments_bonuses;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		//parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
		$this->model = new payments_bonuses_model();
		$this->tb_payments               = PAYMENTS_METHOD;
		$this->tb_payments_bonuses       = PAYMENTS_BONUSES;
		$this->columns = array(
			"method"           => lang("app.method"),
			"bonus"            => lang("app.bonus_percentage"),
			"from"             => lang("app.bonus_from"),
			"status"           => lang("app.status"),
		);
	}

	public function index(){
		$payments_bonuses = $this->model->fetch('*', $this->tb_payments_bonuses);
		$data = array(
			"module"              => "Payments_bonuses",
			"model"				=> $this->model,
			"columns"             => $this->columns,
			"payments_bonuses"    => $payments_bonuses,
		);
		return view('Modules\Payments_bonuses\Views\index', $data);
	}

	public function update($ids = ""){
		$payments_bonus    = $this->model->get("*", $this->tb_payments_bonuses, ['ids' => $ids]);
		$data = array(
			"module"   		 => "Payments_bonuses",
			"payments_bonus" => $payments_bonus,
			"payments"       => $this->model->fetch('id, name', $this->tb_payments),
		);
		return view('Modules\Payments_bonuses\Views\index\update', $data);
	}

	public function ajax_update($ids = ""){
		if (!get_role('admin')) _validation('error', "Permission Denied!");
		$params                = post('editbonus');
		if (!$params['method'] || !$params['percentage']) {
			_validation('error', 'Please fill in required fields');
		}

		$payment              = $this->model->get("id", $this->tb_payments, ['id' => $params['method']]);
		if (!$payment) {
			_validation('error', 'Please fill in required fields');
		} 

		if ((int)$params['bonus_from'] < 0) {
			_validation('error', 'Bonus From is less than 0');
		} 	

		if ((int)$params['percentage'] < 0) {
			_validation('error', 'Bonus Percentage is less than 0');
		} 		

		$data_bonus = array(
			"percentage"      => (int)$params['percentage'],
			"bonus_from"      => (int)$params['bonus_from'],
			"status"          => (int)$params['status'],
		);

		$item  = $this->model->get("id", $this->tb_payments_bonuses, ['ids' => $ids, 'status' => 1]);
		if ($item) {
			$this->model->common_update($this->tb_payments_bonuses, $data_bonus, ['ids' => $ids]);
		}else{
			$data_bonus['ids']        = ids();
			$data_bonus['payment_id'] = $payment->id;
			$this->model->common_insert($this->tb_payments_bonuses, $data_bonus);
		}
		_validation('success', lang("app.update_successfully"));

	}
	
	public function ajax_toggle_item_status($id = ""){
		if (!get_role('admin')) _validation('error', "Permission Denied!");
		$status  = post('status');
		$item  = $this->model->get("id", $this->tb_payments_bonuses, ['id' => $id]);
		if ($item ) {
			$this->model->common_update($this->tb_payments_bonuses, ['status' => (int)$status], ['id' => $id]);
			_validation('success', lang("app.update_successfully"));
		}
	}
	
	public function ajax_delete_item($ids = ""){
		$this->model->delete($this->tb_payments, $ids, true);
	}

}