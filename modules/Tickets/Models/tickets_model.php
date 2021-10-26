<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
namespace Modules\Tickets\Models;
use App\Models\ExtendModel;

class tickets_model extends ExtendModel {
	public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
	public $tb_tickets;
	public $tb_ticket_message;
	public $pagination;

	public function __construct(){
		parent::__construct();
		//Config Module
		$this->tb_users      = USERS;
		$this->tb_categories = CATEGORIES;
		$this->tb_services   = SERVICES;
		$this->tb_orders     = ORDER;
		$this->tb_tickets    = TICKETS;
		$this->tb_ticket_message    = TICKET_MESSAGES;
		$this->pagination = \Config\Services::pager();

	}

	function get_tickets($total_rows = false, $status = "", $limit = "", $start = ""){
		$is_admin = 0;
		if (get_role("admin")) {
			$is_admin = 1;
		}

		$builder = $this->db->table($this->tb_tickets." tk");
		if (!$is_admin) {
			$builder->where("tk.uid", session("uid"));
		}
		if($status != "all" && !empty($status)){
			$builder->where("tk.status", $status);
		}
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}

		$builder->select('tk.*, u.email as user_email, u.last_name, u.first_name');
		
		$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');

		if($is_admin){
			$builder->orderBy('tk.admin_read', 'DESC');
			$builder->orderBy("FIELD ( tk.status, 'pending', 'answered', 'closed')");
		}
		
		if(!$is_admin){
			$builder->orderBy("FIELD ( tk.status, 'answered', 'pending', 'closed')");
		}
		$builder->orderBy('tk.changed', 'DESC');
		$query = $builder->get();
		if ($total_rows) {
			$result = $builder->countAllResults();
			return $result;
		}else{
			$result = $query->getResult();
			return $result;
		}
		return false;
	}

	function get_ticket_detail($id){
		$builder=$this->db->table($this->tb_tickets." tk");
		if (get_role("user")) {
			$builder->where("tk.uid", session("uid"));
		}
		$builder->select('tk.*, u.email as user_email, u.first_name, u.last_name,u.role');
		
		$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');
		$builder->where("tk.id", $id);
		$builder->orderBy('tk.changed', 'DESC');
		$query = $builder->get();
		if($query->getRow()){
			return $data = $query->getRow();
		}else{
			return false;
		}
	}

	function get_ticket_content($id){
		// if (!get_role("admin")) {
		// 	$this->db->where("tk_m.uid", session("uid"));
		// }
		$builder=$this->db->table($this->tb_ticket_message." tk_m");
		$builder->select('tk_m.*, u.email as user_email, u.first_name, u.last_name,u.role');
		
		$builder->join($this->tb_users." u", "u.id = tk_m.uid", 'left');
		$builder->where("tk_m.ticket_id", $id);
		$builder->orderBy('tk_m.created', 'ASC');
		$query = $builder->get();
		if($query->getResult()){
			return $data = $query->getResult();
		}else{
			return false;
		}
	}

	function get_search_tickets($k){
		$k = trim(htmlspecialchars($k));
		$builder=$this->db->table($this->tb_tickets." tk");
		if (get_role("user")) {
			$builder->select('tk.*, u.email as user_email, u.first_name, u.last_name');
			//$this->db->from($this->tb_tickets." tk");
			$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`tk`.`id` LIKE '%".$k."%' ESCAPE '!' OR `tk`.`subject` LIKE '%".$k."%' ESCAPE '!' OR  `tk`.`status` LIKE '%".$k."%' ESCAPE '!')");
			}	

			$builder->where("tk.uid", session("uid"));
			$builder->orderBy("FIELD ( tk.status, 'answered', 'pending', 'closed')");
			$builder->orderBy('tk.changed', 'DESC');
			$query = $builder->get();

		}else{
			$builder->select('tk.*, u.email as user_email, u.first_name, u.last_name');
			//$this->db->from($this->tb_tickets." tk");
			$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`tk`.`id` LIKE '%".$k."%' ESCAPE '!' OR `tk`.`subject` LIKE '%".$k."%' ESCAPE '!' OR  `tk`.`status` LIKE '%".$k."%' ESCAPE '!' OR  `u`.`email` LIKE '%".$k."%' ESCAPE '!' OR  `u`.`last_name` LIKE '%".$k."%' ESCAPE '!' OR  `u`.`first_name` LIKE '%".$k."%' ESCAPE '!')");
			}	
			$builder->orderBy("FIELD ( tk.status, 'new', 'pending', 'closed')");
			$builder->orderBy('tk.changed', 'DESC');
			$query = $builder->get();
		}
		if($query->getResult()){
			return $data = $query->getResult();
		}else{
			return false;
		}
	}

	// Get Count of orders by Search query
	public function get_count_tickets_by_search($search = []){
		$k = trim($search['k']);
		$builder=$this->db->table($this->tb_tickets." tk");
		$where_like = "";
		if (get_role("user")) {
			$builder->where("tk.uid", session("uid"));
			$where_like = "(`tk`.`id` LIKE '%".$k."%' ESCAPE '!' OR `tk`.`subject` LIKE '%".$k."%' ESCAPE '!')";
		}else{
			switch ($search['type']) {
				case 1:
					#Ticket ID
					$where_like = "`tk`.`id` LIKE '%".$k."%' ESCAPE '!'";
					break;
				case 2:
					# User Email
					$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 3:
					# Subjects
					$where_like = "`tk`.`subject` LIKE '%".$k."%' ESCAPE '!'";
					break;
			}
		}
		$builder->select('tk.id');
		//$this->db->from($this->tb_tickets." tk");
		$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');
		if ($where_like) $builder->where($where_like);
		$query = $builder->get();
		$number_row = $builder->countAllResults();
		return $number_row;
	}

	// Search Logs by keywork and search type
	public function search_logs_by_get_method($search, $limit = "", $start = ""){
		$k = trim($search['k']);
		$where_like = "";
		$builder=$this->db->table($this->tb_tickets." tk");
		if (get_role("user")) {
			$builder->select('tk.*, u.email as user_email, u.first_name, u.last_name');
			//$this->db->from($this->tb_tickets." tk");
			$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');

			$builder->where("(`tk`.`id` LIKE '%".$k."%' ESCAPE '!' OR `tk`.`subject` LIKE '%".$k."%' ESCAPE '!')");

			$builder->where("tk.uid", session("uid"));
			$builder->orderBy("FIELD ( tk.status, 'answered', 'pending', 'closed')");
			$builder->orderBy('tk.changed', 'DESC');
			$builder->limit($limit, $start);
			$query = $builder->get();
			$result = $query->getResult();
		}else{
			switch ($search['type']) {
				case 1:
					#Ticket ID
					$where_like = "`tk`.`id` LIKE '%".$k."%' ESCAPE '!'";
					break;
				case 2:
					# User Email
					$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
					break;

				case 3:
					# Subjects
					$where_like = "`tk`.`subject` LIKE '%".$k."%' ESCAPE '!'";
					break;
			}

			$builder->select('tk.*, u.email as user_email, u.first_name, u.last_name');
		//	$this->db->from($this->tb_tickets." tk");
			$builder->join($this->tb_users." u", "u.id = tk.uid", 'left');

			if ($where_like) $builder->where($where_like);

			$builder->orderBy('tk.admin_read', 'DESC');
			$builder->orderBy("FIELD ( tk.status, 'pending', 'answered', 'closed')");
			$builder->orderBy('tk.changed', 'DESC');
			$builder->limit($limit, $start);
			$query = $builder->get();
			$result = $query->getResult();
		}
		return $result;
	}
}
