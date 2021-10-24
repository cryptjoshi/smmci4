<?php
//
namespace Themes\Pergo\Controllers;
use App\Controllers\BaseController;
use Themes\Pergo\Models\PergoModel;

class pergo extends BaseController {
	protected $pergo;
	public function __construct(){
	//	parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
		$this->pergo = new PergoModel();
		//$this->template->set_layout('blank_page');
	}

	public function index(){
		$request = \Config\Services::request();
		if($request->getVar('referral')){
		//if($_GET["referral"]){
			if(get_option("enable_affiliate") == "1"){
				set_session("referral", $_GET["referral"]); 
			}
		}
		
		if (get_option("enable_disable_homepage", 0)) {
			redirect(cn("auth/login"));
		}
		$data = array("title"=>"pergo theme");
		 
		return view('Themes\Pergo\Views\index', $data);
	}

	public function header($display_html = true){
		$data = array(
			'display_html' => $display_html,
		);

		return view('Themes\Pergo\Views\blocks\header', $data);
	}

	public function footer($display_html = true){
		$data = array(
			'display_html' => $display_html,
			'lang_current' => "",//get_lang_code_defaut(),
			'languages'    => $this->model->fetch("*", LANGUAGE_LIST, "status = 1")
		);
		return view('Themes\Pergo\Views\blocks\footer', $data);
	}

}