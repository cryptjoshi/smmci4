<?php
namespace Modules\Api\Controllers;

use App\Controllers\BaseController;

class Api extends BaseController
{
	public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
	public $api_key;
	public $uid;
	public $tb_transaction_logs;
	public $pusher;
	public $cluster;
	public $key;
	public $secret;
	public $superkey;
	public $id;
	public $tb_user_logs;
	public $tb_user_block_ip;

    public function __construct()
    {

        
	//	$this->load->model(get_class($this) . '_model', 'model');
//		$this->load->model('transaction_model', 'model');
		//$this->pushing = $this->load->module('pusher');
		//Config Module
		$this->tb_users      = USERS;
		$this->tb_categories = CATEGORIES;
		$this->tb_services   = SERVICES;
		$this->tb_orders     = ORDER;
		$this->tb_transaction_logs   = TRANSACTION_LOGS;
		$this->tb_user_logs   		= USER_LOGS;
		$this->tb_user_block_ip   	= USER_BLOCK_IP;
		$this->superkey             = SUPER_KEY;
    }

    public function index()
	{
		redirect()->to(cn('api/docs'));
	}


	public function  rapidload()
	{
		$json = file_get_contents('php://input');
		$json_ob = json_decode($json);
		$key = $json_ob->key;
		$data = $json_ob->data;
		echo_json_string(array(
			'key' => $key,
			'data' => $data
		));
	}


	public function webhooks()
	{
		$params = [];


		$params = $_POST;


		$json = file_get_contents('php://input');
		$json_ob = json_decode($json);
		$key = $json_ob->key;
		$charge = $json_ob->data;
		
		if($key == 'refund.create'){

		} else 
		if ($key == 'charge.complete') {
		if($charge->card==null){
			$transaction_id = $charge->source->id;
		}else 
		{
			$transaction_id = $charge->id;
		}
		//$dbtr = $this->model->get_transaction_byid($transaction_id);
	 
		
			$data = array(
				"status"            => $charge->status == 'failed' ? 2 : 1,
				"transaction_id"    => $transaction_id,
				"payment_type"		=> $charge->source->type,
				"token"				=> $this->security->get_csrf_hash()
			);
			 
			push_message($data);
		 
		}
	}

	public function docs()
	{
		$api_key = null;
		$api_key = get_field(USERS, ['id' => session('uid')], "api_key");

		$signup = array(
			//"key"            => lang("your_api_key"),
			"action"         => "signup",
			"method"		=> "Post",
			"user"          => "{<br>`first_name`:``,<br>`last_name`:``,<br>`password`:``,<br>`email`:``<br>}",
		);
		

		$status_order = array(
			"key"            => lang("your_api_key"),
			"action"         => "status",
			"order"          => lang("order_id"),
		);

		$status_orders = array(
			"key"            => lang("your_api_key"),
			"action"         => "status",
			"orders"         => lang("order_ids_separated_by_comma_array_data"),
		);

		$services = array(
			"key"            => lang("your_api_key"),
			"action"         => "services",
		);

		$balance = array(
			"key"            => lang("your_api_key"),
			"action"         => "balance",
		);
		$transaction = array(
			"key"            => lang("your_api_key"),
			"action"         => "transaction"
		);
		$addfunds = array(
			"key"            => lang("your_api_key"),
			"action"         => "addfunds",
			"data"         => "{<br>
				'type': 'truewallet', # offline,qrcode,creditcard,truewallet<br>
				'transaction_id':'xxxxxxxxxxx',<br>
				'txn_fee':1.2,<br>
				'amount':30,<br>
				'status':'1' ##### 1:complete 2:cancel 0:wait<br>
			}"
		);
		$data = array(
			"module"        => get_class($this),
			"api_key"       => $api_key,
			"api_url"       => BASE . "api/v1",
			"status_order"  => $status_order,
			"status_orders" => $status_orders,
			"services"      => $services,
			"balance"       => $balance,
			"signup"		=> $signup,
			"transaction"	=> $transaction,
			"addfunds"		=> $addfunds
		);

		if (!session('uid')) {
			//$this->template->set_layout('general_page');
			return view("Modules\Api\Views\index", $data);
            //$this->template->build("index", $data);
		}

		return view("Modules\Api\Views\index", $data);
	}

	public function v1()
	{
		$params = [];
		$this->api_key = post('key');//(isset($_REQUEST["key"])) ? strip_tags(urldecode($_REQUEST["key"])) : '';
		$action        = post('action');//(isset($_REQUEST["action"])) ? strip_tags(urldecode($_REQUEST["action"])) : '';
		$order_id      = post('order');//(isset($_REQUEST["order"])) ? strip_tags(urldecode($_REQUEST["order"])) : '';
		$order_ids     = post('orders');//(isset($_REQUEST["orders"])) ? strip_tags(urldecode($_REQUEST["orders"])) : '';

		// Build parameters and call appropriate sub function
		$params = post();//$_REQUEST;
		$uid_exists = get_field($this->tb_users, ["api_key" => $this->api_key, "status" => 1], "id");
		if (($this->api_key == "" || empty($uid_exists)) && $action!="signup") {
			echo_json_string(array(
				'error' => lang("api_is_disable_for_this_user_or_user_not_found_contact_the_support"),
			));
		}
		$this->uid = $uid_exists;

		$action_allowed = array('add', 'status', 'services', 'balance','signup','transaction','addfunds');
		if ($action == "" || !in_array($action, $action_allowed)) {
			echo_json_string(array(
				'error' => lang("this_action_is_invalid"),
			));
		}

		switch ($action) {
			case 'services':
				$services = $this->model->get_services_list($this->uid);
				if (!empty($services)) {
					echo_json_string($services);
				} else {
					echo_json_string(array(
						'status' => "success",
						'data'   => "Empty Data",
					));
				}
				break;

			case 'add':
				$this->add($_REQUEST);
				break;

			case 'status':

				if (isset($order_id) && $order_id != "") {
					$this->single_status($order_id);
				}

				if (isset($order_ids) && $order_ids != "") {
					$this->multi_status($order_ids);
				}
				break;

			case 'balance':
				$this->balance();
				break;

			default:
				echo_json_string(array(
					'error' => lang("this_action_is_invalid"),
				));
				break;
			case "signup":
				$this->signup();
				break;
			case "transaction":
				$this->transaction();
				break;
			case "addfunds":
				$this->addfunds();
				break;
			case "refill":
				$this->refill();
				break;
			case "refill_status":
				$this->refill_status();
				break;
		}
	}

	private function signup(){

			$json = file_get_contents('php://input');
			$json_ob = json_decode($json);
			//print_r($json_ob);
			$post = (object)post();
			//$terms              = post('terms');
			$superkey			= $post->superkey;
			
			$first_name         = $post->first_name;
			$last_name          = $post->last_name;
			$email              = $post->email;
			$password           = $post->password;
			$re_password        = $post->re_password;
			$timezone           = "Asia/Bangkok";//get_option("default_timezone", 'UTC');
			if($first_name == '' || $last_name == '' || $password == ''|| $email == ''){
				echo_json_string(array(
					'status'  => 'error',
					'message' => lang("please_fill_in_the_required_fields"),
				));
			}
	
			if (!preg_match("/^[a-zA-Z ]*$/", $first_name)) {
				echo_json_string(array(
					'status'  => 'error',
					'message' => lang("only_letters_and_white_space_allowed"),
				));
			}
	
			if (!preg_match("/^[a-zA-Z ]*$/", $last_name)) {
				echo_json_string(array(
					'status'  => 'error',
					'message' => lang("only_letters_and_white_space_allowed"),
				));
			}
	
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo_json_string(array(
					'status'  => 'error',
					'message' => lang("invalid_email_format"),
				));
			}
	
			if($password != ''){
				if(strlen($password) < 6){
					echo_json_string(array(
						'status'  => 'error',
						'message' => lang("Password_must_be_at_least_6_characters_long"),
					));
				}
	
				if($re_password!= $password){
					echo_json_string(array(
						'status'  => 'error',
						'message' => lang("Password_must_be_at_least_6_characters_long"),
					));
				}
			}
	
			// if (!$terms) {
			// 	ms(array(
			// 		'status'  => 'error',
			// 		'message' => lang("oops_you_must_agree_with_the_terms_of_services_or_privacy_policy"),
			// 	));
			// }
	
			if ($this->is_banned_ip_address()) {
				echo_json_string(array(
					"status"  => "error",
					"message" => "Access from your IP address has been blocked for security reasons. Please contact the administrator!"
				));
			}
	
			// if (isset($_POST['g-recaptcha-response']) && get_option("enable_goolge_recapcha", '')  &&  get_option('google_capcha_site_key') != "" && get_option('google_capcha_secret_key') != "") {
			// 	$resp = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
			// 			  ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			// 	if (!$resp->isSuccess()) {
			// 		ms(array(
			// 			'status'  => 'error',
			// 			'message' => lang("please_verify_recaptcha"),
			// 		));
			// 	}
			// }
	
			// Get Settings (Limit payments) for new user
			$this->load->model('payments/payments_model', 'payments_model');
			$limit_payments = $this->payments_model->get_payments_list_for_new_user();
			$settings = [
				'limit_payments' => $limit_payments
			];
	
			$data = array(
				"ids"					  => ids(),
				"first_name"              => $first_name,
				"last_name"               => $last_name,
				"password"                => $this->model->app_password_hash($password),
				"timezone"                => $timezone,
				"status"                  => get_option('is_verification_new_account', 0) ? 0 : 1,
				"api_key"                 => create_random_string_key(32),
				"settings"                => json_encode($settings),
				//'history_ip'              => get_client_ip(),
				"reset_key"               => create_random_string_key(32),
				"activation_key"          => create_random_string_key(32),
				"changed"                 => NOW,
			);
 
	
			if($email != ''){
				// check email
				$checkUserEmail = $this->model->get('email, ids', $this->tb_users,"email='{$email}'");
				if(!empty($checkUserEmail)){
					ms(array(
						'status'  => 'error',
						'message' => lang("An_account_for_the_specified_email_address_already_exists_Try_another_email_address"),
					));
				}
	
				$data['created'] = NOW;
				$data['email']   = $email;
				$data['login_type']  = 'Sign_up_page';
	
				if($superkey==$this->superkey){
				if($this->db->insert($this->tb_users, $data)){
					$uid = $this->db->insert_id();
					if (get_option('is_verification_new_account', 0)) {
						$check_send_email_issue = $this->model->send_email(get_option('verification_email_subject', ''), get_option('verification_email_content', 0), $uid);
						if($check_send_email_issue){
							ms(array(
								"status" => "error",
								"message" => $check_send_email_issue,
							));
						}
	
						ms(array(
							"status"  => "success",
							"message" => lang('thank_you_for_signing_up_please_check_your_email_to_complete_the_account_verification_process')
						));
					}else{
						//set_session('uid', $uid);
						// $data_session = array(
						// 	'role'       => 'user',
						// 	'email'      => $email,
						// 	'first_name' => $first_name,
						// 	'last_name'  => $last_name,
						// 	'timezone'   => $timezone,
						// );
						//set_session('user_current_info', $data_session);
						/*----------  Insert User logs  ----------*/
						//$this->insert_user_activity_logs('logout');
	
						/*----------  Check is send welcome email or not  ----------*/
						if (get_option("is_welcome_email", '')) {
							$check_send_email_issue = $this->model->send_email(get_option('email_welcome_email_subject', ''), get_option('email_welcome_email_content', 0), $uid);
							if($check_send_email_issue){
								echo_json_string(array(
									"status" => "error",
									"message" => $check_send_email_issue,
								));
							}
						}
	
						/*----------  Send email notificaltion for Admin  ----------*/
						if (get_option("is_new_user_email", '')) {
							$subject = get_option('email_new_registration_subject', '');
							$subject = str_replace("{{website_name}}", get_option("website_name", "SmartPanel"), $subject);
	
							$email_content = get_option('email_new_registration_content', '');
							$email_content = str_replace("{{user_firstname}}", $first_name, $email_content);
							$email_content = str_replace("{{user_lastname}}", $last_name, $email_content);
							$email_content = str_replace("{{website_name}}", get_option("website_name", "SmartPanel"), $email_content);
							$email_content = str_replace("{{user_timezone}}", $timezone, $email_content);
							$email_content = str_replace("{{user_email}}", $email, $email_content);
	
							$admin_id = $this->model->get("id", $this->tb_users, "role = 'admin'","id","ASC")->id;
							if ($admin_id == "") {
								$admin_id = 1;
							}
							
							$check_send_email_issue = $this->model->send_email( $subject, $email_content, $admin_id, false);
							if($check_send_email_issue){
								echo_json_string(array(
									"status" => "error",
									"message" => $check_send_email_issue,
								));
							}
						}
					}
					$api_key = $this->model->get("api_key", $this->tb_users, "id = '{$uid}'","id","ASC")->api_key;
					echo_json_string(array(
						'status'  => 'success',
						'data' => array(
							"uid" =>$uid,
							"api_key"=>$api_key
						),
					));
				}else{
					echo_json_string(array(
						"status"  => "Failed",
						"message" => lang("There_was_an_error_processing_your_request_Please_try_again_later")
					));
				}
			}else {
				echo_json_string(array(
					"status"  => "Failed",
					"message" => lang("superkey is invalid")
				));
			}
			}
		
	}
	private function is_banned_ip_address(){
		if (!$this->db->table_exists($this->tb_user_block_ip)) {
			return false;
		}
		$ip_address = get_client_ip();
		$check_item = $this->model->get('ip', $this->tb_user_block_ip, ["ip" => $ip_address]);
		if (!empty($check_item)) {
			return true;
		}
		return false;
	}
	private function insert_user_activity_logs($type = ''){
		if (!$this->db->table_exists($this->tb_user_logs)) {
			return false;
		}
		if (session('uid')) {
			$ip_address = get_client_ip();
			$data_user_logs = array(
				"ids"		=> ids(),
				"uid"		=> session('uid'),
				"ip"		=> $ip_address,
				"type"		=> ($type == 'logout') ? 0 : 1,
				"created"   => NOW,
			);
			$location = get_location_info_by_ip($ip_address);
			if ($location->country != 'Unknown' && $location->country != '') {
				$data_user_logs['country'] = $location->country;
			}else{
				$data_user_logs['country'] = 'Unknown';
			}
			$this->db->insert($this->tb_user_logs, $data_user_logs);
		}
	}
	private function transaction()
	{
	
		$json = file_get_contents('php://input');
		
		$json_ob = json_decode($json);
	   
		$post = $json_ob;
		 
		$size = post('size');
		$page = post('page');
		 
		//$this->model->get_order_logs_list(false, $order_status, $limit_per_page, $page * $limit_per_page);

		$transactions = $this->model->get_transaction_byid($this->uid,false,$size, $page * $size);
		echo_json_string($transactions);
		// echo_json_string(array(
		// 	"status"   => "success",
		// 	"transactions"   => $transactions
		// ));
	}

	private function refill_status()
	{

		$json = file_get_contents('php://input');
		
		$json_ob = json_decode($json);
	   
		$post = $json_ob;

		$refill_status = $this->model->get_refill($this->uid,$post->refill);
		echo_json_string(array(
			"status"   => $refill_status
		));
	}
 
	private function refill()
	{
		$json = file_get_contents('php://input');
		
		$json_ob = json_decode($json);
	   
		$post = $json_ob;
		//$transactions = $this->model->get_transaction_byid($this->uid,false);
		$services =  $this->model->get_order_all_id($post->order, $this->uid);
		$check_service  = $this->model->check_record("*", $this->tb_services, $services->id, false, true);
		$link = "";
		$service_type ="";
		$data = array(
			"ids" 	            => ids(),
			"uid" 	            => $this->uid,
			"type" 	            => 'api',
			"cate_id" 	        => $check_service->cate_id,
			"service_id" 	    => $check_service->id,
			"link" 	            => $link,
			"api_provider_id"  	=> $check_service->api_provider_id,
			"api_service_id"  	=> $check_service->api_service_id,
			"api_order_id"  	=> (!empty($check_service->api_provider_id) && !empty($check_service->api_service_id)) ? -1 : 0,
			"status"			=> 'pending',
			"service_type"	    => $service_type,
			"changed" 	        => NOW,
			"created" 	        => NOW,
		);

		$refill_id = $this->db->insert($this->tb_refill, $data);
		
		echo_json_string(array(
			"refill"   => $refill_id
		));
	}

	private function addfunds(){

		$json = file_get_contents('php://input');
		$json_ob = json_decode($json);
		 
		$charge = (object)post(); //$json_ob;
		
		$superkey =  $charge->superkey;
		$data = array(
			"ids" 				=> ids(),
			"uid" 				=> $this->uid,
			"type" 				=> $charge->type,
			"transaction_id" 	=> $charge->transaction_id,
			"amount" 	        => $charge->amount+$charge->txn_fee,
			"txn_fee"           => $charge->txn_fee,
			"status"            => $charge->status,
			"created" 			=> NOW,
		);
		if($superkey == $this->superkey){
		$this->db->insert($this->tb_transaction_logs, $data);
		//$transaction_id = $this->db->insert_id();
		$user_balance = $this->model->get("balance", $this->tb_users, ['id' => $this->uid])->balance;
		$new_balance = $user_balance + ($charge->amount);
		$this->db->update($this->tb_users, ["balance" => $new_balance], ["id" => $this->uid]);		
		 $get_balance = $this->model->check_record("balance", $this->tb_users, $this->uid, false, true);
		 if (!empty($get_balance)) {
		 	echo_json_string(array(
		 		"status"   => "success",
		 		"balance"  => $get_balance
		 	));
		 } else {
		 	echo_json_string(array(
		 		"error"  => lang("the_account_does_not_exists"),
		 	));
		 }
		}else  {
			echo_json_string(array(
				"status"  => "Failed",
				"message" => lang("superkey is invalid")
			));
		}
	}

	private function add($params)
	{
		$service_id = post('service');//(isset($params["service"])) ? strip_tags(urldecode($params["service"])) : '';
		$link       = post('link');//(isset($params["link"])) ? strip_tags(urldecode($params["link"])) : '';

		if (!$service_id) {
			echo_json_string(array(
				'error' => lang("there_are_missing_required_parameters_please_check_your_api_manual"),
			));
		}

		$check_service  = $this->model->check_record("*", $this->tb_services, $service_id, false, true);
		if (empty($check_service)) {
			echo_json_string(array(
				"error" => lang("service_id_does_not_exists")
			));
		}
		$service_type = $check_service->type;
		switch ($service_type) {

			case 'custom_comments':
				$comments     = post('comments');//(isset($params["comments"])) ? strip_tags(urldecode($params["comments"])) : '';
				if ($comments == "") {
					echo_json_string(array(
						"error" => lang("comments_field_is_required")
					));
				}
				$comments     = str_replace("\r\n", ",", $comments);
				$comments     = str_replace("\n", ",", $comments);
				$comments_arr = explode(",", $comments);
				$quantity     = count($comments_arr);
				$is_custom_comments = 1;
				break;

			default:
				$quantity     = post('quantity');//(isset($params["quantity"])) ? strip_tags(urldecode($params["quantity"])) : '';
				$interval     = post('interval');//(isset($params["interval"])) ? strip_tags(urldecode($params["interval"])) : '';
				$runs         = post('runs');//(isset($params["runs"])) ? strip_tags(urldecode($params["runs"])) : '';
				$interval     = $interval;
				$runs         = $runs;
				if ($runs != '' || $interval != '') {

					if (!$check_service->dripfeed) {
						echo_json_string(array(
							'error' => "This services does not support Dripfeed feature!",
						));
					}

					if ($runs != '' && $interval == '') {
						echo_json_string(array(
							'error' => lang("interval_time_is_required"),
						));
					}

					if ($runs == '' && $interval != '') {
						echo_json_string(array(
							'error' => lang("runs_is_required"),
						));
					}
					$interval     = (int)$interval;
					$runs         = (int)$runs;
					if (!in_array($interval, [0, 5, 10, 15, 30, 60, 90])) {
						ms(array(
							"status"  => "error",
							"message" => 'Invalid Interval in minutes'
						));
					}

					if ($quantity == '') {
						echo_json_string(array(
							'error' => lang("quantity_is_required"),
						));
					}
					$is_drip_feed      = 1;
					$dripfeed_quantity = post('quantity');//$params['quantity'];
					$quantity          = $runs * $dripfeed_quantity;
				} else {
					$quantity          = $quantity;
				}
				break;
		}

		if ($link == "") {
			echo_json_string(array(
				'error' => 'Bad Link',
			));
		}

		if ($quantity == '') {
			echo_json_string(array(
				'error' => lang("quantity_is_required"),
			));
		}

		$min   = $check_service->min;
		$max   = $check_service->max;
		$price = get_user_price($this->uid, $check_service);

		if ($quantity <= 0 || $quantity < $min) {
			echo_json_string(array(
				"error" => lang("quantity_must_to_be_greater_than_or_equal_to_minimum_amount") . ' ' . $min
			));
		}

		if ($quantity > $max) {
			echo_json_string(array(
				"error" => lang("quantity_must_to_be_less_than_or_equal_to_maximum_amount") . ' ' . $max
			));
		}

		/*----------  Get user's balance and custom_rate info  ----------*/
		$user = $this->model->get("balance", $this->tb_users, ['id' => $this->uid]);
		/*----------  Set custom rate for each user  ----------*/
		if ($service_type == "package" || $service_type == "custom_comments_package") {
			$total_charge = $price;
		} else {
			$total_charge = $price * ($quantity / 1000);
		}

		if ((!empty($user->balance) && $user->balance < $total_charge) || empty($user->balance)) {
			echo_json_string(array(
				"error" => lang("not_enough_funds_on_balance")
			));
		}

		$data = array(
			"ids" 	            => ids(),
			"uid" 	            => $this->uid,
			"type" 	            => 'api',
			"cate_id" 	        => $check_service->cate_id,
			"service_id" 	    => $check_service->id,
			"link" 	            => $link,
			"quantity" 	        => $quantity,
			"charge" 	        => $total_charge,
			"api_provider_id"  	=> $check_service->api_provider_id,
			"api_service_id"  	=> $check_service->api_service_id,
			"api_order_id"  	=> (!empty($check_service->api_provider_id) && !empty($check_service->api_service_id)) ? -1 : 0,
			"status"			=> 'pending',
			"service_type"	    => $service_type,
			"changed" 	        => NOW,
			"created" 	        => NOW,
		);

		/*----------  Get Formal Charge and profit  ----------*/
		$data['formal_charge'] = ($check_service->original_price * $total_charge) / $check_service->price;
		$data['profit']        = $total_charge - $data['formal_charge'];
		/*----------  insert more params  ----------*/
		switch ($service_type) {
			case 'custom_comments':
				$data["comments"]  = json_encode(str_replace(",", "\n", $comments));
				break;

			default:
				// add params for Dripfeed Order type
				if (isset($is_drip_feed) && $is_drip_feed) {
					$data['is_drip_feed']      = 1;
					$data['runs']              = $runs;
					$data['interval']          = $interval;
					$data['dripfeed_quantity'] = $dripfeed_quantity;
					$data['status']            = 'inprogress';
				}
				break;
		}

		$this->db->insert($this->tb_orders, $data);
		if ($this->db->affected_rows() > 0) {
			$insert_order_id = $this->db->insert_id();
			$new_balance = $user->balance - $total_charge;
			$this->db->update($this->tb_users, ["balance" => $new_balance], ["id" => $this->uid]);
			if ($this->db->affected_rows() > 0) {
				echo_json_string(array(
					'status' => "success",
					"order"  => $insert_order_id,
				));
			}
		} else {
			echo_json_string(array(
				"error" => lang("There_was_an_error_processing_your_request_Please_try_again_later")
			));
		}
	}

	private function balance()
	{
		$get_balance = $this->model->check_record("balance", $this->tb_users, $this->uid, false, true);
		if (!empty($get_balance)) {
			echo_json_string(array(
				"status"   => "success",
				"balance"  => $get_balance->balance,
				"currency" => "USD"
			));
		} else {
			echo_json_string(array(
				"error"  => lang("the_account_does_not_exists"),
			));
		}
	}

	private function single_status($order_id)
	{
		if ($order_id == "") {
			echo_json_string(array(
				'error' => lang("order_id_is_required_parameter_please_check_your_api_manual")
			));
		}

		if (!is_numeric($order_id)) {
			echo_json_string(array(
				'error' => lang("incorrect_order_id"),
			));
		}

		$exists_order = $this->model->get('id, service_type ,status, charge, start_counter, remains, runs, is_drip_feed, sub_response_orders, sub_expiry, sub_posts', $this->tb_orders, ['id' => $order_id, 'uid' => $this->uid]);

		if (empty($exists_order)) {
			echo_json_string(array(
				'error' => lang("incorrect_order_id"),
			));
		} else {

			switch ($exists_order->service_type) {
				case 'subscriptions':
					$orders = [];
					$related_orders = $this->model->fetch("id, status", $this->tb_orders, ['main_order_id' => $exists_order->id]);
					if (!empty($related_orders)) {
						foreach ($related_orders as $key => $order) {
							$orders[] = $order->id;
						}
					}
					$result = array(
						'status'      => $this->order_title_status($exists_order->status),
						'expiry'      => (strtotime($exists_order->sub_expiry) < strtotime(NOW)) ? false : true,
						'posts'       => $exists_order->sub_posts,
						'orders'      => $orders,
					);
					echo_json_string($result);
					break;

				default:
					if ($exists_order->is_drip_feed) {
						$orders = [];
						$related_orders = $this->model->fetch("id, status", $this->tb_orders, ['main_order_id' => $exists_order->id]);
						if (!empty($related_orders)) {
							foreach ($related_orders as $key => $order) {
								$orders[] = $order->id;
							}
						}
						$result = array(
							'status'      => $this->order_title_status($exists_order->status),
							'runs'        => $exists_order->runs,
							'orders'      => $orders,
						);
					} else {
						$result = array(
							'order'       => $exists_order->id,
							'status'      => $this->order_title_status($exists_order->status),
							'charge'      => $exists_order->charge,
							'start_count' => $exists_order->start_counter,
							'remains'     => $exists_order->remains,
							'currency'    => 'USD',
						);
					}
					echo_json_string($result);
					break;
			}
		}
	}

	private function multi_status($order_ids)
	{

		if ($order_ids == "") {
			echo_json_string(array(
				'error' => lang("order_id_is_required_parameter_please_check_your_api_manual"),
			));
		}
		if (is_string($order_ids)) {
			$order_ids = explode(',', $order_ids);
		}

		if (is_array($order_ids)) {
			$data = [];
			foreach ($order_ids as $order_id) {
				$exists_order = $this->model->get('id, service_type ,status, charge, start_counter, remains, runs, is_drip_feed, sub_response_orders, sub_expiry, sub_posts', $this->tb_orders, ['id' => $order_id, 'uid' => $this->uid]);
				if (empty($exists_order)) {
					$data[$order_id] = "Incorrect order ID";
				} else {
					switch ($exists_order->service_type) {
						case 'subscriptions':
							$orders = [];
							$related_orders = $this->model->fetch("id, status", $this->tb_orders, ['main_order_id' => $exists_order->id]);
							if (!empty($related_orders)) {
								foreach ($related_orders as $key => $order) {
									$orders[] = $order->id;
								}
							}
							$result = array(
								'status'      => $this->order_title_status($exists_order->status),
								'expiry'      => (strtotime($exists_order->sub_expiry) < strtotime(NOW)) ? false : true,
								'posts'       => $exists_order->sub_posts,
								'orders'      => $orders,
							);
							break;

						default:
							if ($exists_order->is_drip_feed) {
								$orders = [];
								$related_orders = $this->model->fetch("id, status", $this->tb_orders, ['main_order_id' => $exists_order->id]);
								if (!empty($related_orders)) {
									foreach ($related_orders as $key => $order) {
										$orders[] = $order->id;
									}
								}
								$result = array(
									'status'      => $this->order_title_status($exists_order->status),
									'runs'        => $exists_order->runs,
									'orders'      => $orders,
								);
							} else {
								$result = array(
									'order'       => $exists_order->id,
									'status'      => $this->order_title_status($exists_order->status),
									'charge'      => $exists_order->charge,
									'start_count' => $exists_order->start_counter,
									'remains'     => $exists_order->remains,
									'currency'    => 'USD',
								);
							}
							break;
					}
					$data[$order_id] = $result;
				}
			}
			echo_json_string($data);
		}

		echo_json_string(array(
			'error' => lang("incorrect_order_id"),
		));
	}

	private function order_title_status($status)
	{
		switch ($status) {

			case 'active':
				$result = 'Active';
				break;

			case 'completed':
				$result = 'Completed';
				break;

			case 'processing':
				$result = 'Processing';
				break;

			case 'pending':
				$result = 'Pending';
				break;

			case 'inprogress':
				$result = 'In progress';
				break;

			case 'partial':
				$result = 'Partial';
				break;

			case 'canceled':
				$result = 'Canceled';
				break;

			case 'refunded':
				$result = 'Refunded';
				break;

			default:
				$result = 'Pending';
				break;
		}

		return $result;
	}

}