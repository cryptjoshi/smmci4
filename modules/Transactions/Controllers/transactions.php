<?php namespace Modules\Transactions\Controllers;
use App\Controllers\BaseController;

use Modules\Transactions\Models\TransactionsModel;
 
class Transactions extends BaseController {
    public $tb_users;
	public $tb_tickets;
	public $tb_ticket_messages;
	public $tb_categories;
	public $tb_services;
	public $tb_orders;
    public $model;
	

	public function __construct(){
		//parent::__construct();
		//$this->load->model(get_class($this).'_model', 'model');
        helper('common');
		$this->model =  new TransactionsModel();
		
     //   $this->users_model= new UsersModel();
	 $this->tb_users              = USERS;
	 $this->tb_categories         = CATEGORIES;
	 $this->tb_services           = SERVICES;
	 $this->tb_transaction_logs   = TRANSACTION_LOGS;
	 $this->columns = array(
		 "uid"              => lang('app.user'),
		 "transaction_id"   => lang('app.transaction_id'),
		 "type"             => lang('app.payment_method'),
		 "amount"           => lang('app.amount_includes_fee'),
		 "txn_fee"          => 'Transaction fee',
		 "note"             => 'Note',
		 "created"          => lang('app.created'),
		 "status"           => lang('app.status'),
	 );

	 if (!get_role("admin")) {
		 $this->columns = array(
			 "type"             => lang('app.payment_method'),
			 "amount"           => lang('app.amount_includes_fee'),
			 "txn_fee"          => 'Transaction fee',
			 "created"          => lang('app.created'),
			 "status"           => lang('app.status'),
		 );
	 }
 }

  
 public function index(){
	 // Delete all Unpaid Payment over 2 day
	 $this->cachePage(30);
	 $this->model->delete_unpaid_payment(2);
	 $page = (int)$this->request->getVar("page");
	 //$page        = (int)get("p");
	 
	 $page        = ($page > 0) ? ($page - 1) : 0;
	 $limit_per_page = get_option("default_limit_per_page", 10);
	 $query = array();
	 $query_string = "";
	 if(!empty($query)){
		 $query_string = "?".http_build_query($query);
	 }
	 $config = array(
		 'base_url'           => cn(get_class($this).$query_string),
		 'total_rows'         => $this->model->get_transaction_list(true),
		 'per_page'           => $limit_per_page,
		 'use_page_numbers'   => true,
		 'prev_link'          => '<i class="fe fe-chevron-left"></i>',
		 'first_link'         => '<i class="fe fe-chevrons-left"></i>',
		 'next_link'          => '<i class="fe fe-chevron-right"></i>',
		 'last_link'          => '<i class="fe fe-chevrons-right"></i>',
	 );
	 //echo $config['per_page'];
	//  $this->pagination->initialize($config);
	//  $links = $this->pagination->create_links();
	 $links = $this->model->pagination->makeLinks($page,$config['per_page'],$config['total_rows'],'default_full');
	 $transactions = $this->model->get_transaction_list(false, "all", $limit_per_page, $page * $limit_per_page);
	 $data = array(
		 "module"         => get_class($this),
		 "columns"        => $this->columns,
		 "transactions"   => $transactions,
		 "links"          => $links,
		 
	 );
	 
	//  $this->template->build('index', $data);
	return view('Modules\Transactions\Views\index',$data);
 }
 
 public function update($ids = ""){
	 if (!get_role('admin')) {
		 redirect(cn());
	 }
	 $transaction     = $this->model->get("*", $this->tb_transaction_logs, ['ids' => $ids]);
	 $data = array(
		 "module"   			=> get_class($this),
		 "transaction" 	    => $transaction,
	 );
	 $this->load->view('update', $data);
 }

 public function ajax_update($ids = ""){
	 if (!get_role('admin')) {
		 redirect(cn());
	 }
	 $uid 		        = (int)post("uid");
	 $ids	            = post("ids");
	 $note	            = post("note");
	 $transaction_id	    = post("transaction_id");
	 $payment_method	    = post("payment_method");
	 $status			    = (int)post("status");

	 if($uid == ""){
		 ms(array(
			 "status"  => "error",
			 "message" => 'User email is required'
		 ));
	 }
	 
	 if($transaction_id == ""){
		 ms(array(
			 "status"  => "error",
			 "message" => 'Transaction id is required'
		 ));
	 }	

	 if($payment_method == ""){
		 ms(array(
			 "status"  => "error",
			 "message" => 'Payment method is required'
		 ));
	 }

	 $check_item = $this->model->get("*", $this->tb_transaction_logs, ['uid' => $uid, 'transaction_id' => $transaction_id, 'type' => $payment_method]);
	 if(!empty($check_item)){

		 $data = array(
			 'note'   => $note,
			 'status' => $status
		 );
		 $this->db->update($this->tb_transaction_logs, $data, ['uid' => $check_item->uid, 'transaction_id' => $check_item->transaction_id, 'type' => $check_item->type]);
		 if ($status == 1 && $check_item->status == 0) {
			 $user_balance = $this->model->get("balance", $this->tb_users, ['id' => $check_item->uid])->balance;
			 $new_balance = $user_balance + ($check_item->amount - $check_item->txn_fee);
			 $this->db->update($this->tb_users, ["balance" => $new_balance], ["id" => $check_item->uid]);
		 }
		 if ($this->db->affected_rows() > 0) {
			 ms(array(
				 "status"  => "success",
				 "message" => lang("app.update_successfully")
			 ));
			 
		  
		 }
		 
		 $data = array(
			 "status"            => $status,
			 "uid"				=> $check_item->uid,
			 "transaction_id"    => $check_item->transaction_id,
			 "payment_type"		=> "offline",
			 "token"				=> $this->security->get_csrf_hash()
		 );
		  
		 push_message($data);
	 }else{
		 ms(array(
			 "status"  => "error",
			 "message" => 'Transaction does not exists'
		 ));
	 }
	 
 }
 
 public function ajax_delete_item($ids = ""){
	 $this->model->delete($this->tb_transaction_logs, $ids, false);
 }

 public function ajax_update_slip(){
	 $transaction_id = post('id');
	 $data = array(
		 "status"=>"1"
	 );
	 $check_item = $this->model->get("*", $this->tb_transaction_logs, ['transaction_id' => $transaction_id]);
	 $this->db->update($this->tb_transaction_logs, $data, ['uid' => $check_item->uid, 'transaction_id' => $check_item->transaction_id, 'type' => $check_item->type]);
	 if ($this->db->affected_rows() > 0) {
		 ms(array(
			 "status"  => "success",
			 "message" => lang("app.update_successfully")
		 ));
		 echo_json_string(array(
			 "status"=>"success"
		 ));
	 }else 
	 {
		 ms(array(
			 "status"  => "error",
			 "message" => lang("app.update_not_successfully")
		 ));
		 echo_json_string(array(
			 "status"=>"error"
		 ));
	 }
	  
	 }
	 

 public function slip(){

	 $transaction_id = post('id');
	 $uid = session('uid');
	  //$image = $this->db->get('slip',$this->tb_transaction_logs,['transaction_id'=>$transaction_id]);
	  $check_item = $this->model->get("*", $this->tb_transaction_logs, ['transaction_id' => $transaction_id]);
	 echo_json_string(array(
		 "slip" => $check_item->slip 
	 ));
 }

 //Search
 public function search(){
	 if (!get_role('admin')) {
		 redirect(cn($this->module));
	 }
	 $k           = get('query');
	 $k           = htmlspecialchars($k);
	 $search_type = (int)get('search_type');
	 $data_search = ['k' => $k, 'type' => $search_type];
	 $page        = (int)get("p");
	 $page        = ($page > 0) ? ($page - 1) : 0;
	 $limit_per_page = get_option("default_limit_per_page", 10);
	 $query = ['query' => $k, 'search_type' => $search_type];
	 $query_string = "";
	 if(!empty($query)){
		 $query_string = "?".http_build_query($query);
	 }
	 $config = array(
		 'base_url'           => cn(get_class($this)."/search".$query_string),
		 'total_rows'         => $this->model->get_count_items_by_search($data_search),
		 'per_page'           => $limit_per_page,
		 'use_page_numbers'   => true,
		 'prev_link'          => '<i class="fe fe-chevron-left"></i>',
		 'first_link'         => '<i class="fe fe-chevrons-left"></i>',
		 'next_link'          => '<i class="fe fe-chevron-right"></i>',
		 'last_link'          => '<i class="fe fe-chevrons-right"></i>',
	 );
	 $this->pagination->initialize($config);
	 $links = $this->pagination->create_links();
	 $transactions = $this->model->search_items_by_get_method($data_search, $limit_per_page, $page * $limit_per_page);
	 $data = array(
		 "module"         => get_class($this),
		 "columns"        => $this->columns,
		 "transactions"   => $transactions,
		 "links"          => $links,
	 );

	 $this->template->build('index', $data);
 }
}