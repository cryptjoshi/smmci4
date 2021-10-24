<?php

 
class cashmaal extends MX_Controller {
	public $tb_users;
	public $tb_transaction_logs;
	public $stripeapi;
	public $payment_type;
	public $currency_code;

	public function __construct(){
	    
		parent::__construct();
		$this->tb_users            = USERS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
		$this->payment_type		   = "cashmaal";
		$this->currency_code       = "USD";
	}

	public function index(){
	  //  echo '<pre>';print_r($_SESSION); die;
	     
		$path_file = APPPATH."./modules/setting/views/integrations/cashmaal.php";
        if (!file_exists($path_file)) {
        	redirect(cn('add_funds'));
        }
		$data = array(
			"module"        => 'add_funds',
			"amount"        => number_format((float)session('amount'), 2, '.', ''),
		);
		$this->template->build('cashmaal/cashmaal_form', $data);
	}

	/**
	 *
	 * Create payment
	 *
	 */
	public function create_payment(){

        $ORDER_ID = $_POST["ORDER_ID"];
		$TXN_AMOUNT = $_POST["TXN_AMOUNT"];
        
		$paramList = array();

		$paramList["pay_method"] = "";
		$paramList["amount"] = $TXN_AMOUNT;
		$paramList["currency"] = "USD";
		$paramList["succes_url"] = cn("add_funds/cashmaal/complete");
		$paramList["cancel_url"] = cn("add_funds/cashmaal/complete");
		$paramList["client_email"] = get_field(USERS, ["id" => session('uid')], 'email');
		$paramList["web_id"] = get_option('cashmaal_web_id','4258');
		$paramList["order_id"] = $ORDER_ID;
		$paramList["addi_info"] = session("uid")."_".session("real_amount");

	
		$data = array(
			'paramList' => $paramList,
		);

		$this->load->view("cashmaal/redirect", $data);
		
	}

	public function complete(){
	
    	$web_id = get_option('cashmaal_web_id','4258');
    
    	  
        if(isset($_POST['CM_TID'])){
    
    
            $CM_TID=$_POST['CM_TID'];   // getting TID with user redirection   
            
            $url="https://www.cashmaal.com/Pay/verify_v2.php?CM_TID=".urlencode($CM_TID)."&web_id=".urlencode($web_id);
            
            $result = $this->connect_api($url);
            
            $obj = json_decode($result);
            
            if($obj['status'] == 1){ // its mean payment received....
            
                    $getSeperate = explode('_',$obj['addi_info']);
    	     
                	$user_id = $getSeperate[0];
                	$real_amount =$getSeperate[1] ;
    			
    				/*----------  Insert to Transaction table  ----------*/
    				$data = array(
    					"ids" 				=> ids(),
    					"uid" 				=> $user_id,
    					"type" 				=> $this->payment_type,
    					"transaction_id" 	=> $obj["transaction_id"],
    					"amount" 	        => $obj['USD_amount']*get_option('cashmaal_currency_rate_to_usd', 76),
    					"created" 			=> NOW,
    				);
    
    				$this->db->insert($this->tb_transaction_logs, $data);
    				$transaction_id = $this->db->insert_id();
    				
    				/*----------  Add funds to user balance  ----------*/
    				$user_balance = get_field($this->tb_users, ["id" => $user_id], "balance");
    
    			
    				$user_balance += $real_amount*get_option('cashmaal_currency_rate_to_usd', 76);
     
    				$this->db->update($this->tb_users, ["balance" => $user_balance], ["id" => $user_id]);
    
    				/*----------  Send payment notification email  ----------*/
    				if (get_option("is_payment_notice_email", '')) {
    					$CI = &get_instance();
    					if(empty($CI->payment_model)){
    						$CI->load->model('model', 'payment_model');
    					}
    					$check_send_email_issue = $CI->payment_model->send_email(get_option('email_payment_notice_subject', ''), get_option('email_payment_notice_content', ''), $user_id);
    					if($check_send_email_issue){
    						ms(array(
    							"status" => "error",
    							"message" => $check_send_email_issue,
    						));
    					}
    				}
    				set_session("uid", $user_id);
    				set_session("transaction_id", $transaction_id);
    				 
    				redirect(cn("add_funds/success"));
                
            }else{
            
                redirect(cn("add_funds/unsuccess"));
            
            }
            
        }else{
            redirect(cn("add_funds/unsuccess"));
        }    
	    
	}
	
	private function connect_api($url) {
	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        
		return $result;
  }

}