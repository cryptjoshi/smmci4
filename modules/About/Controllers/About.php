<?php
namespace Modules\About\Controllers;
use Modules\About\Models\AboutModel;
use App\Controllers\BaseController;
 

class About extends BaseController {

    private $logModel;
    public $tb_orders;
    public function __construct(){
		 
        //$this->logModel = new AboutModel();
       
		// $this->tb_users 		    = USERS;
		// $this->tb_categories 		= CATEGORIES;
		// $this->tb_services   		= SERVICES;
		// $this->tb_orders     		= ORDER;
		// $this->tb_tickets     		= TICKETS;
		// $this->tb_ticket_messages   = TICKET_MESSAGES;
		
	}

    public function index() {
        // error_reporting(E_ALL);
        // //echo 'Hello - I am the <strong>'. __CLASS__ . '</strong> Class';
        // // $db = \Config\Database::connect('default');
        // // $result = $db->query("SELECT name FROM ".$this->tb_order." LIMIT 1");
         $std = new AboutModel();
        // $content = $std->getData();
        $content=array();
        try {
            $content['data'] = $std->findAll();
      
        //$content['data'] = $std->findAll();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        return view('Modules\About\Views\index', $content);
         
    }

    public function me(){
        return array("me"=>"i am");
    }
}