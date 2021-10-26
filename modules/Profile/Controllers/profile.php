<?php
namespace Modules\Profile\Controllers;
use App\Controllers\BaseController;

class Profile extends BaseController {
	public $tb_users;
	public $module_name;
	public $module_icon;
	private $model;
	public function __construct(){
		
		$this->model = new \Modules\Profile\Models\profile_model();
		$this->tb_users = USERS;
	}

	public function index(){
		$data = array(
			"module"       => "profile",
			"user"         => $this->model->get('*', $this->tb_users, "id = '".session('uid')."'"),
		);
	 
		return view('Modules\Profile\Views\index', $data);
	}

	public function ajax_update($ids = ''){
		$id                 = session('uid');
		$first_name         = post('first_name');
		$last_name          = post('last_name');
		
		$email              = post('email');
		$password           = post('password');
		$re_password        = post('re_password');
		$timezone           = post('timezone');

		if($first_name == '' || $last_name == ''){
			ms(array(
				'status'  => 'error',
				'message' => lang("app.please_fill_in_the_required_fields"),
			));
		}

		$data = array(
			"first_name"              => $first_name,
			"last_name"               => $last_name,
			"timezone"                => $timezone,
			"changed"                 => NOW,
		);

		if($password != ''){
			if($password == ''){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.password_is_required"),
				));
			}

			if(strlen($password) < 6){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.password_must_be_at_least_6_characters_long"),
				));
			}

			if($re_password!= $password){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.password_does_not_match_the_confirm_password"),
				));
			}
			
			$data['password'] = $this->model->app_password_hash($password);
		}
		
		if($id != ''){
			$checkUser = $this->model->get('id,ids,email',$this->tb_users,"`id` = '{$id}'");

			if(empty($checkUser)){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
				));
			}

			// check email

			if($email == ''){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.email_is_required"),
				));
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		      	ms(array(
					'status'  => 'error',
					'message' => lang("app.invalid_email_format"),
				));
		    }

			$checkUserEmail = $this->model->get('email, ids', $this->tb_users,"email='{$email}' AND `id` != '{$id}'");

			if(!empty($checkUserEmail)){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.an_account_for_the_specified_email_address_already_exists_Try_another_email_address"),
				));
			}

			$data['email']   = $email;

			if($this->model->common_update($this->tb_users, $data,array("id" =>$id))){
				ms(array(
					'status'  => 'success',
					'message' => lang('Update_successfully'),
				));
			}
		}else{
			ms(array(
				'status'  => 'error',
				'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
			));
		}
	}

	public function ajax_update_more_infors($ids = ''){
		$id                 = session('uid');
		$website            = post('website');
		$phone              = post('phone');
		$skype_id           = post('skype_id');
		$what_asap          = post('what_asap');
		$address            = post('address');
		$account_number     = post('account_number');

		$more_information = array(
			"website"         => $website,
			"phone"        	  => $phone,
			"what_asap"       => $what_asap,
			"skype_id"        => $skype_id,
			"address"         => $address,
			"account_number"  => $account_number
		);

		$data = array(
			"more_information"        => json_encode($more_information),
			"changed"                 => NOW,
		);

		if($id != ''){
			$checkUser = $this->model->get('id,ids,email',$this->tb_users,"`id` = '{$id}'");

			if(empty($checkUser)){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
				));
			}

			if($this->model->common_update($this->tb_users, $data,array("id"=>$id))){
				ms(array(
					'status'  => 'success',
					'message' => lang('Updated_successfully'),
				));
			}
		}else{
			ms(array(
				'status'  => 'error',
				'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
			));
		}
	}

	public function ajax_update_api($ids = ''){
		$id  = session('uid');
		//$data = $this->request->getPost();
		$api_key = create_random_string_key(32);
		
		$data = array(
			"api_key"         => $api_key,
			"changed"         => NOW
		);
		
		 if($id != ''){

		 	$checkUser = $this->model->get('id,ids,api_key', $this->tb_users,"`id` = '{$id}'");
		 	$checkApi_key = $this->model->get('id,ids,api_key', $this->tb_users,"`api_key` = '{$api_key}'");
		 	if(empty($checkUser) || !empty($checkApi_key)){
		 		ms(array(
		 			'status'  => 'error',
					'message' => lang("app.there_was_an_error_processing_your_request_Please_try_again_later"),
		 		));
		 	}

			if($this->model->common_update($this->tb_users, $data,array("id"=>$id))){
				ms(array(
					'status'  => 'success',
					'message' => lang('Update_successfully')
				));
			}
		}else{
			ms(array(
				'status'  => 'error',
				'message' => lang("app.no Id There_was_an_error_processing_your_request_Please_try_again_later"),
			));
		}
	}
}