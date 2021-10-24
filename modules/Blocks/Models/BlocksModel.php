<?php
//
namespace Modules\blocks\models;

use App\Models\ExtendModel;
class BlocksModel extends ExtendModel {

	public $tb_tickets;
	public $tb_ticket_message;
    
	public function __construct(){
		parent::__construct();
		$this->tb_tickets    = TICKETS;
		$this->tb_ticket_message    = TICKET_MESSAGES;
	}

	function getList($limit=-1, $page=-1){
		// $db = db_connect();
		// $sql = "";
		// if($limit == -1){
		// 	$sql = 'count(*) as sum from '.INSTAGRAM_ACCOUNT_TB;
		// }else{
		// 	$sql = '* from '.INSTAGRAM_ACCOUNT_TB;
		// }
		
		// //$this->db->from(INSTAGRAM_ACCOUNT_TB);

		// if($limit != -1) {
		// 	$this->db->limit($limit,$page);
		// }

		// $this->db->where("uid = '".session("uid")."'");

		// $this->db->order_by('created','desc');
		// $query = $this->db->get();

		// if($query->result()){
		// 	if($limit == -1){
		// 		return $query->row()->sum;
		// 	}else{
		// 		$result =  $query->result();
		// 		return $result;
		// 	}
		// }else{
		// 	return false;
		// }
		return false;
	}

	

}
