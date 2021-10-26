<?php
namespace Modules\Payments\Controllers;

use App\Controllers\BaseController;
use Modules\Payments\Models\payments_model;

class payments extends BaseController {
	public $tb_payments;
	public $columns;
	public $module;
	public $module_name;
	public $module_icon;

	public function __construct(){
		//parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
		$this->model = new payments_model();
		$this->tb_payments       = PAYMENTS_METHOD;
		$this->module            = "payments";
		$this->columns = array(
			"method"           => lang("app.method"),
			"name"             => lang("app.name"),
			"min"              => lang("app.min"),
			"max"              => lang("app.max"),
			"new users"        => lang("app.new_users"),
			"status"           => lang("app.status"),
		);

	}

	public function index(){
		$payments = $this->model->fetch('*', $this->tb_payments);
		$data = array(
			"module"       => $this->module ,//get_class($this),
			"columns"      => $this->columns,
			"payments"    => $payments,
		);
		return view('Modules\Payments\Views\index', $data);
	}

	public function update($id = ""){
		$id = segment(3);
		$payment    = $this->model->get("*", $this->tb_payments, ['id' => $id]);
		$data = array(
			"module"   		=> $this->module ,//get_class($this),
			"payment" 	    => $payment,
		);
		
		return view('Modules\Payments\Views\integrations/'.$payment->type, $data);
	}

	public function ajax_update($id = ""){
		$id = segment(3);
		
		if (!get_role('admin')) _validation('error', "Permission Denied!");
		$payment        = $this->model->get("*", $this->tb_payments, ['id' => $id]);
		$payment_params = post('payment_params');
		if (!$payment) {
			ms(["status"  => "error", "message" => 'There was an error processing your request. Please try again later']);
		}

		if ($payment->type != $payment_params['type']) {
			ms(["status"  => "error", "message" => 'There was an error processing your request. Please try again later']);
		}
		if (!$payment_params['min']|| !$payment_params['name']) {
			ms(["status"  => "error", "message" => 'Please fill in required fields']);
		}

		if ($payment_params['min'] < 0) {
			ms(["status"  => "error", "message" => 'Minimal payment less than minimal: 1']);
		}

		if ($payment_params['max'] < $payment_params['min']) {
			ms(["status"  => "error", "message" => 'Maximal payment must be greater than "Minimal payment".']);
		}
		
		$data_payment = array(
			"name"         	  => $payment_params['name'],
			"min"         	  => $payment_params['min'],
			"max"         	  => $payment_params['max'],
			"status"          => (int)$payment_params['status'],
			"new_users"       => (int)$payment_params['new_users'],
			"params"          => json_encode($payment_params),
		);
		$this->model->common_update($this->tb_payments, $data_payment, ['id' => $id]);
		//echo $this->model->db->getLastQuery();
		ms(array(
			"status"  => "success",
			"message" => lang("app.update_successfully")
		));
	}
	
	public function ajax_toggle_item_status($id = ""){
		$id=segment(3);
		if (!get_role('admin')) _validation('error', "Permission Denied!");
		_is_ajax($this->module);
		$status  = post('status');
		$item  = $this->model->get("id", $this->tb_payments, ['id' => $id]);
		if ($item ) {
			$this->model->common_update($this->tb_payments, ['status' => (int)$status], ['id' => $id]);
			_validation('success', lang("app.update_successfully"));
		}
	}

	public function ajax_delete_item($ids = ""){
		$ids=segment(3);
		$this->model->delete($this->tb_payments, $ids, true);
	}

}