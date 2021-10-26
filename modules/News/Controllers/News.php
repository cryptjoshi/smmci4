<?php
namespace Modules\News\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\Debug\Toolbar\Collectors\Views;
use Modules\News\Models\news_model;

class News extends BaseController {
	public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
	public $tb_news;
	public $api_key;
	public $uid;
	public $columns;

	public function __construct(){
		//parent::__construct();
		$this->model = new news_model();//("news".'_model', 'model');
		//Config Module
		$this->tb_users      = USERS;
		$this->tb_categories = CATEGORIES;
		$this->tb_services   = SERVICES;
		$this->tb_orders     = ORDER;
		$this->tb_news       = NEWS;

		$this->columns = array(
			"desc"        	 => lang('app.description'),
			"type"           => lang("app.type"),
			"created"        => lang("app.start"),
			"expiry"         => lang("app.expiry"),
			"status"         => lang("app.status"),
			"action"         => lang('app.action'),
		);

	}

	public function index(){
		if (get_role('user')) {
			redirect(cn('statistics'));
		}

		$news = $this->model->get_news();
		$data = array(
			"module"     => "news",
			"news"       => $news,
			"columns"    => $this->columns
		);
		return view("Modules\News\Views\index", $data);
	}
	
	public function update($ids = ""){
		if (get_role('user') ) {
			redirect()->to(cn('statistics'));
		}

		$new = $this->model->get_news_by_ids($ids);
		$data = array(
			"module"   => "news",
			"new"      => $new,
		);
		return view("Modules\News\Views\update", $data);
		//$this->load->view('update', $data);
	}	

	public function ajax_notification($ids = ""){
		set_cookie("news_annoucement", "clicked", 21600);
		$news = $this->model->get_news_by_ajax();
		$data = array(
			"module"     => "news",
			"news"       => $news
		);
		//$this->load->view("ajax_news", $data);
		return view("Modules\News\Views\ajax_news", $data);
	}

	public function ajax_update($ids = ""){
		$type 	    	= post("type");
		$create 		= post("create");
		$expiry 		= post("expiry");
		$status 		= (int)post("status");
		$description    = $_POST["description"];
		if (!in_array($type, ['new_services','disabled_services','updated_services','announcement'])) {
			ms(array(
				"status"  => "error",
				"message" => lang("invalid_news_type")
			));
		}

		if($create == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("start_field_is_required")
			));
		}

		if($expiry == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("expiry_field_is_required")
			));
		}

		if($description == ""){
			ms(array(
				"status"  => "error",
				"message" => lang("Description_field_is_required")
			));
		}

		$create = str_replace('/', '-', $create);
		$expiry = str_replace('/', '-', $expiry);

		$data = array(
			"uid"             => session('uid'),
			"expiry"          => date("Y-m-d H:i:s", strtotime($expiry)),
			"created"         => date("Y-m-d H:i:s", strtotime($create)),
			"type"            => $type,
			"status"          => $status,
			"description"     => htmlspecialchars(@$description, ENT_QUOTES),
			"changed"		  => NOW,
		);

		$check_item = $this->model->get("ids", $this->tb_news, "ids = '{$ids}'");
		
		if(empty($check_item)){
			$data["ids"]     = ids();
			$this->model->common_insert($this->tb_news, $data);
		}else{
			$data["changed"] = NOW;
			$this->model->common_update($this->tb_news, $data, array("ids" => $check_item->ids));
		}
		
		if ($this->model->db->affectedRows() > 0) {
			ms(array(
				"status"  => "success",
				"message" => lang("Update_successfully")
			));
		}else{
			ms(array(
				"status"  => "error",
				"message" => lang("There_was_an_error_processing_your_request_Please_try_again_later")
			));
		}
		
	}

	public function ajax_delete_item($ids = ""){
		$this->model->delete($this->tb_news, $ids, false);
	}
}

