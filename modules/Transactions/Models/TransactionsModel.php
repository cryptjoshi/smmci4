<?php
 namespace Modules\Transactions\Models;

 use App\Models\ExtendModel;
class TransactionsModel extends ExtendModel{
    public $tb_users;
	public $tb_categories;
	public $tb_services;
	public $tb_transaction_logs;
    public $pagination;
	protected $table = TRANSACTION_LOGS;

	public function __construct(){
		$this->tb_users 		     = USERS;
		$this->tb_categories 		 = CATEGORIES;
		$this->tb_services   		 = SERVICES;
		$this->tb_transaction_logs   = TRANSACTION_LOGS;
        $this->pagination = \Config\Services::pager();
		parent::__construct();
        //$this->model->db = new \Config\Database::connect('group_name');
	}

	function get_transaction_list($total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();
        $builder = $this->db->table($this->tb_transaction_logs." tl");
		if (get_role("user")) {
			$builder->where("tl.uid", session('uid'));
			//$this->db->where("tl.status", 1);
		}
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		$builder->select("tl.*, u.email");
		
		$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');
		$builder->orderBy("tl.id", 'DESC');
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
	function get_transaction_byid($id="",$total_rows = false, $status = "", $limit = "", $start = ""){
		$data  = array();
		$builder=$this->db->table("tl.*, u.email");
        //if (get_role("user")) {
			$builder->where("tl.uid", $id);
			//$this->db->where("tl.status", 1);
		//}
		if ($limit != "" && $start >= 0) {
			$builder->limit($limit, $start);
		}
		
		//$this->db->from($this->tb_transaction_logs." tl");
		$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');
		$builder->orderBy("tl.id", 'DESC');
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
	function get_transactions_by_search($k){
		$k = trim(htmlspecialchars($k));

        $builder = $this->db->table($this->tb_transaction_logs." tl");
		if (get_role("user")) {
			$builder->select("tl.*, u.email");
			
			$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`tl`.`transaction_id` LIKE '%".$k."%' ESCAPE '!' OR `tl`.`type` LIKE '%".$k."%' ESCAPE '!')");
			}
			$builder->where("u.id", session("uid"));
			$builder->where("tl.status", 1);
			$builder->orderBy("tl.id", 'DESC');
			$query = $builder->get();
			$result = $query->getResult();
		}else{
			//$this->db->from($this->tb_transaction_logs." tl");
            $builder->select("tl.*, u.email");
			$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');

			if ($k != "" && strlen($k) >= 2) {
				$builder->where("(`tl`.`transaction_id` LIKE '%".$k."%' ESCAPE '!' OR `tl`.`type` LIKE '%".$k."%' ESCAPE '!' OR `u`.`email` LIKE '%".$k."%' ESCAPE '!')");
			}
			$builder->orderBy("tl.id", 'DESC');
			$query = $builder->get();
			$result = $query->getResult();
		}

		return $result;
	}

	function delete_unpaid_payment($day = ""){
		if ($day == "") {
			$day = 7;
		}
		$SQL   = "DELETE FROM ".$this->tb_transaction_logs." WHERE `status` != 1 AND created < NOW() - INTERVAL ".$day." DAY";
		$query = $this->db->query($SQL);
		return $query;
	}

	// Get Count of orders by Search query
	public function get_count_items_by_search($search = []){
		$k = trim($search['k']);
		$where_like = "";
		switch ($search['type']) {
			case 1:
				#User Email
				$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
				break;
			case 2:
				# Transaction ID
				$where_like = "`tl`.`transaction_id` LIKE '%".$k."%' ESCAPE '!'";
				break;
		}
        $builder = $this->db->table($this->tb_transaction_logs." tl");
		$builder->select("tl.*, u.email");
		//$this->db->from($this->tb_transaction_logs." tl");
		$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');

		if ($where_like) $builder->where($where_like);
		$builder->orderBy("tl.id", 'DESC');
		$query = $builder->get();
		$number_row = $builder->countAllResults();
		return $number_row;
	}

	// Search Logs by keywork and search type
	public function search_items_by_get_method($search, $limit = "", $start = ""){
		$k = trim($search['k']);
		$where_like = "";
		switch ($search['type']) {
			case 1:
				#User Email
				$where_like = "`u`.`email` LIKE '%".$k."%' ESCAPE '!'";
				break;
			case 2:
				# Transaction ID
				$where_like = "`tl`.`transaction_id` LIKE '%".$k."%' ESCAPE '!'";
				break;
		}
        $builder = $this->db->table($this->tb_transaction_logs." tl");
		$builder->select("tl.*, u.email");
		//$this->db->from($this->tb_transaction_logs." tl");
		$builder->join($this->tb_users." u", "u.id = tl.uid", 'left');

		if ($where_like) $builder->where($where_like);
		
		$builder->orderBy("tl.id", 'DESC');
		$builder->limit($limit, $start);
		$query = $builder->get();
		$result = $query->getResult();
		return $result;
	}

}