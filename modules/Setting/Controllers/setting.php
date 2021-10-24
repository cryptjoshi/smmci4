<?php
namespace Modules\Setting\Controllers;
use App\Controllers\BaseController;
 
class Setting extends BaseController {
	private $model;
	private $sub_model;
	public function __construct(){

		//parent::__construct();
		helper('common');
		$this->model = new \Modules\Setting\Models\setting_model();
		$this->sub_model = new \Modules\Language\Models\language_model();
		//$this->load->model('language/language_model', 'sub_model');
	}

	public function index($tab = ""){
		$this->cachePage(60);
		$tab = segment(2);
		$path              = 'modules/Setting/Views/';
		$path_integrations = 'modules/Setting/Views/integrations/';
		$tabs = array_merge(get_name_of_files_in_dir($path, ['.php']), get_name_of_files_in_dir($path_integrations, ['.php']));
		unset($tabs[array_search('index', $tabs, true)]);
		//echo $tab;
		// print_r($tabs);
		if ($tab == "") {
			$tab = "website_setting";
		}
		if (!in_array($tab, $tabs)) {
			redirect()->to(cn('index'));
		}
		$data = array(
			"module"          => get_class($this),
			"tab"             => $tab,
		);
		return view('Modules\Setting\Views\index', $data);

	}

	public function get_content($tab = ""){
		$path              = '/modules/Setting/Views/';
		$path_integrations = '/modules/Setting/Views/integrations/';
		$tabs = array_merge(get_name_of_files_in_dir($path, ['.php']), get_name_of_files_in_dir($path_integrations, ['.php']));
		unset($tabs[array_search('index', $tabs, true)]);
		
		if ($tab == "") {
			$tab = "website_setting";
		}

		if (!in_array($tab, $tabs)) {
			redirect()->to(cn('setting'));
		}

		$data = array(
			"module"          => get_class($this),
			"tab"             => $tab,
		);
		return view('Modules\Setting\Views\index', $data);
	}

	public function ajax_general_settings(){
		$data = $this->request->getPost();
		
		$default_home_page = post("default_home_page");
		if(is_array($data)){
			foreach ($data as $key => $value) {

				if(in_array($key, ['embed_javascript', 'embed_head_javascript', 'manual_payment_content'])){
					$value = htmlspecialchars(@$_POST[$key], ENT_QUOTES);
				}	

				if (in_array($key, ['midtrans_payment_channels', 'coinpayments_acceptance', 'freekassa_acceptance' ])) {
					$value = json_encode($value);
				}
				
				if ($key == 'new_currecry_rate') {
					$value = (double)$value;
					if ($value <= 0 ) {
						$value = 1;
					}
				}
				update_option($key, $value);
			}
		}
		if($default_home_page != ""){
			$theme_file = fopen("themes/config.json", "w");
			$txt = '{ "theme" : "'.$default_home_page.'" }';
			fwrite($theme_file, $txt);
			fclose($theme_file);
		}

		ms(array(
        	"status"  => "success",
        	"message" => lang('Update_successfully')
        ));
	}
	
}