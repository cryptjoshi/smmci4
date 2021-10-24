<?php 
namespace Modules\Home\Controllers;
use App\Controllers\BaseController;
class Home extends BaseController {

    public function __construct()
    {
        helper('common');
        
        //if(session("uid") && segment(2) != 'logout'){
        //    redirect()->to("statistics");
        //}
    }
    public function index () {
        $data = array();
        //return view('App\Views\template\general_page',array("view"=>'Themes\\'.get_theme().'\Views\index',"data"=>$data));
        return view('Themes\\'.get_theme().'\Views\index',array("data"=>"Home"));
    }
}