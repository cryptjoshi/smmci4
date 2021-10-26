<?php 

namespace Modules\Services\Controllers;
 
use App\Controllers\BaseController;
use Predis\Configuration\Options;

class Services extends BaseController {

   protected $model;
   public $columns;
public function __construct()
{
   $this->model =  new \Modules\Services\Models\ServiceModel();
   helper('common');
   $this->columns = array(
    "price"            => lang("app.rate_per_1000")."(".get_option("currency_symbol","").")",
    "min_max"          => lang("app.min__max_order"),
    "desc"             => lang("app.description"),
);

if (get_role("admin") || get_role("supporter")) {
    $this->columns = array(
        "provider"         => 'Provider',
        "price"            => lang("app.rate_per_1000")."(".get_option("currency_symbol","").")",
        "min_max"          => lang("app.min__max_order"),
        "desc"             => lang("app.description"),
        "dripfeed"         => lang("app.dripfeed"),
        "refill"		   => lang("app.refill"),
        "status"           => lang("app.status"),
    );
}		
}

    public function index(){
        
        //$this->cachePage(30);
        
        
        if (!session('uid') && get_option("enable_service_list_no_login") != 1) {
			redirect(cn());
		}

		$data = array(
			"module"       => get_class($this),
			"columns"      => $this->columns,
            "hasData"      => False
		);
		$options = [
            "cache"=>60
        ];
		switch (session('uid')) {
			case TRUE:
				if (get_role('admin')) {
					$data['all_services'] = $data['categories'] = $this->model->get_services_list(1);
					//$this->template->build("ad_index", $data);
                    return view('\Modules\Services\Views\ad_index',$data,$options);
				}else{
					$data['all_services'] = $data['categories'] = $this->model->get_services_list();
					$data['custom_rates'] = $this->model->get_custom_rates();
					//$this->template->build("client/index", $data);
                    //echo view("layouts\general_page");
                    return view('\Modules\Services\Views\client\index',$data,$options);
                    //return view("App\Views\layouts\general_page",array("data"=>$data,"view"=>'\Modules\Services\Views/client/index'));
				}
				break;
			
			default:
				$data['all_services'] = $data['categories'] = $this->model->get_services_list();
				$data['custom_rates'] = [];
				//$this->template->set_layout('general_page');
				//$this->template->build("client/index", $data);
                //return view("App\Views\layouts\general_page",array("data"=>$data,"view"=>'\Modules\Services\Views/client/index'));
                return view('\Modules\Services\Views\client\index',$data,$options);
				break;
		}
        
    //     $data = array(
	// 		"module"       => get_class($this),
	// 		"columns"      => $this->columns,
	// 	);
    //     $data['all_services'] = $data['categories'] = $this->serviceModel->getData();//$this->model->get_services_list();
    //     $data['custom_rates'] = "1";
 
       
    //    return view('\Modules\Services\Views/client/index',$data);
    
    }
}
