<?php
namespace Modules\Blocks\Controllers;
use App\Controllers\BaseController;
use Modules\Langueage\Controllers;
use Modules\Langueage\Controllers\Language;

use function PHPUnit\Framework\isNull;

require_once (APPPATH.'/libraries/unirest-php/src/Unirest.php');
class Blocks extends BaseController  {
	public $model;
	public function __construct(){
		//parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
		//Config Module
		helper('language');
		$this->tb_tickets    		= TICKETS;
		$this->tb_users    		    = USERS;
		$this->tb_ticket_message    = TICKET_MESSAGES;
		$this->model = new   \Modules\blocks\models\BlocksModel();
	}
	
	
	public function set_language(){
		$lang = new Language();
		$lang->set_language(post("id"));
		ms(array("status" => "success"));
	}

	public function header(){
		$unread_tickets = 0;
		if (get_role('admin') || get_role('supporter')) {
			$total_unread_tickets = $this->model->fetch('id', $this->tb_tickets, ['admin_read' => 1]);
		}else{
			$total_unread_tickets = $this->model->fetch('id', $this->tb_tickets, ['user_read' => 1, 'uid' => session('uid')]);
		}
		if (!empty($total_unread_tickets)) {
			$unread_tickets = count($total_unread_tickets);
		}
		$data = array(
			'total_unread_tickets' => $unread_tickets,
		);
		$data['lang_current'] =get_lang_code_defaut();
		$data['languages']    = $this->model->fetch('*', LANGUAGE_LIST,'status = 1');
		$data['module'] = $this;
		$data['locale'] = isNull(session('langCurrent'))?"en":session('langCurrent')->code;
		return view('Modules\Blocks\Views\header', $data);
	}
	
	public function sidebar(){
		$data = array();
		return view('Modules\Blocks\Views\sidebar', $data);
	}	

	public function footer(){
		$data = array(
        	'lang_current' =>get_lang_code_defaut(),
			'module'=>$this,
        	'languages'    => $this->model->fetch('*', LANGUAGE_LIST,'status = 1'),
        );
		return view('Modules\Blocks\Views\footer', $data);
	}	
	
	public function empty_data(){
		$data = array();
		return view('Modules\Blocks\Views\empty_data', $data);
	}	


	public function search_box(){

		if (in_array(segment(1), ['services'])) {
			$requests = [
				'method' => 'POST',
				'action' => cn(segment(1)."/ajax_search"),
				'class'  => 'ajaxSearchItemsKeyUp',
			];
		}else{
			$requests = [
				'method' => 'GET',
				'action' => cn(segment(1)."/search"),
				'class'  => '',
			];
		}

		$data_search = '';
		/*----------  Order and Dripfeed  ----------*/
		if (segment(2) == 'log' || (segment(1) == 'order' && segment(2) == 'search') || segment(1) == 'dripfeed') {
			$data_search = [
				1 => 'Order ID',
				2 => 'API Order ID',
				3 => 'Order Link',
				4 => 'User Email',
			];
		}
		/*----------  subscriptions ----------*/
		if (segment(1) == 'subscriptions') {
			$data_search = [
				1 => 'Order ID',
				2 => 'API Order ID',
				3 => 'Username',
				4 => 'User Email',
			];
		}
		/*----------  Transactions ----------*/
		if (segment(1) == 'transactions') {
			$data_search = [
				2 => 'Transaction ID',
				1 => 'User Email',
			];
		}
		/*----------  Tickets ----------*/
		if (segment(1) == 'tickets') {
			$data_search = [
				1 => 'Ticket ID',
				2 => 'User Email',
				3 => 'Subject',
			];
		}
		view('Modules\Blocks\Views\search_box', ['data_search' => $data_search, 'requests' => $requests]);
	}

	public function back_to_admin(){
		$user = $this->model->get("id, ids", $this->tb_users, ['id' => session('uid')]);
		if (empty($user)) {
			ms(array(
				'status'  => 'error',
				'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
			));
		}
		unset_session("uid_tmp");
		unset_session("user_current_info");
		if (!session('uid_tmp')) {
			ms(array(
				'status'  => 'success',
				'message' => lang("app.processing_"),
			));
		}
	}
}