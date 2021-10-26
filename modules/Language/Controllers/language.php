<?php
namespace Modules\Language\Controllers;
use App\Controllers\BaseController;
use Modules\Language\Models\language_model;

class Language extends BaseController {
	public $table;
	public $columns;
	public $module_name;
	public $module_icon;

	public function __construct(){
		//parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
		helper('language');
		$this->model = new language_model();
		//Config Module
		$this->tb_language_list       = LANGUAGE_LIST;
		$this->tb_language           = LANGUAGE;
		$this->module_icon = "fa fa-language";
		
		$this->columns = array(
			"name"      => lang("app.name"),
			"code"      => lang("app.code"),
			"icon"      => lang("app.icon"),
			"default"   => lang("app.default"),
			"created"   => lang("app.created"),
			"status"    => lang("app.status"),
		);
	}

	public function index(){
		if (get_role('user') || get_role('supporter')) {
			redirect()->to(cn('statistics'));
		}

		$data = array(
			"columns" 	=> $this->columns,
			"module"  	=> "language",
			"languages" => $this->model->fetch("*", $this->tb_language_list),
		);
		return view('Modules\Language\Views\index', $data);
	}

	public function update($ids = ''){
		/*----------  import all lang line to default lang  ----------*/
		// import_lang_line(APPPATH.'../');
		// create_language();

		$data = array(
			"module"  	=> "language",
			"module_icon"    => $this->module_icon,
			"default_lang"   => create_default_lang(),
		);

		if(!empty($ids)){
			$language = $this->model->get('*', $this->tb_language_list, "ids = '{$ids}'");
			if(!empty($language)){
				$data['lang']      = $language;
				$data['lang_db']   = [];
				//get data old version
				$old_path = FCPATH."app/language/tmp/lang_".$language->code.".txt";
				if (file_exists($old_path)) {
					$data['lang_db']   = get_json_content( $old_path );
				}
				// New version 
				$new_path = FCPATH."app/language/data/". $language->code ."_lang.php";
				if (!$data['lang_db'] && file_exists($new_path)) {
					include($new_path);
					$data['lang_db'] = (object)$lang;
				}
			}else{
				load_404();
			}
		}
		return view('Modules\Language\Views\update', $data);
	}

	public function ajax_update($ids = ""){
		if (get_role('user')) {
			redirect(cn('statistics'));
		}
		$language_code       = post('language_code');
		$country_code        = post('country_code');
		$status    		     = (int)post('status');
		$default    		 = (int)post('default');
		$langs               = $_POST['lang'];
		$data = array(
			"code"               => $language_code,
			"country_code"       => $country_code,
			"status"             => $status,
			"is_default"         => $default,
		);
		// check exists language code
		if(!language_codes($language_code)){
			ms(array(
				"status"  => "error",
				"message" => lang("app.language_code_does_not_exists")
			));
		}

		// Check lang defaut
		if($default == 1){
			$checkLangDefault = $this->model->fetch('*',$this->tb_language_list, "is_default = 1");
			if(!empty($checkLangDefault)){
				$this->model->common_update($this->tb_language_list, array('is_default' => 0));
			}
		}
		
		if ($ids != '') {
			// check lang exists
			$checkLangList = $this->model->get('code, ids', $this->tb_language_list, "ids = '{$ids}'");
			if(!empty($checkLangList)){
				$this->model->common_update($this->tb_language_list, $data, ['ids' => $ids]);
				//creating array of language file
	            $lang_path = FCPATH."app/language/data/".$language_code ."_lang.php";
	            create_lang_file($lang_path, $langs);

				if (file_exists($lang_path)) {
					$this->db->delete($this->tb_language, ["lang_code" => $language_code]);
				}
				
				// Delete old lang file
				$old_path = FCPATH."app/language/tmp/lang_".$language_code.".txt";
				if (file_exists($old_path)) {
					@unlink($old_path);
				}
				
				ms(array(
					'status'  => 'success',
					'message' => lang("app.update_successfully"),
				));
			}

		} else {
			$checklang = $this->model->get('*', $this->tb_language_list, "code = '{$language_code}'");
			if(!empty($checklang)){
				ms(array(
					'status'  => 'error',
					'message' => lang("app.language_code_already_exists"),
				));
			}
			$data['ids']     = ids();
			$data['created'] = NOW;
			$this->model->common_insert($this->tb_language_list, $data);

			//create language file
			if(is_array($langs) && !empty($langs)){
				//creating array of language file
	            $lang_path = FCPATH."app/language/data/".$language_code ."_lang.php";
	            create_lang_file($lang_path, $langs);
				ms(array(
					'status'  => 'success',
					'message' => lang("app.update_successfully"),
				));
			}
		}
	}

	public function export(){
		export_csv($this->table,"export");
	}

	public function ajax_delete_item($ids = ""){
		$this->model->delete($this->tb_language_list, $ids, false);
	}

	public function set_language($ids = ""){
		$ids=segment(3);
		$checkLang = $this->model->get('*', $this->tb_language_list, "ids = '{$ids}'");
		
		if(!empty($checkLang)){
			unset_session('langCurrent');
			unset_session('locale');
			set_session('langCurrent',$checkLang);
			set_session('locale',$checkLang->code);
			$this->request->setLocale($checkLang->code);
			ms(array(
				'status'  => 'success',
				'message' => lang("update_successfully"),
			));
		}
	}
}