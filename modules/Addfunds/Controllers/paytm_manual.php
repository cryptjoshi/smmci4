<?php

 
class paytm_manual extends MX_Controller {
	public $tb_users;
	public $tb_transaction_logs;
	public $payment_type;
	public $currency_code;
	public $mode;

	public function __construct(){
		parent::__construct();
		$this->tb_users            = USERS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
		$this->payment_type		   = "paytm_manual";
		$this->currency_code       = "INR";
		$this->load->model('model', 'model');
	}

	public function index(){
		$path_file = APPPATH."./modules/setting/views/integrations/paytm_manual.php";
        if (!file_exists($path_file)) {
        	redirect(cn('add_funds'));
        }
		$data = array(
			"module"        => 'add_funds',
			"amount"        => number_format((float)session('amount'), 2, '.', ''),
		);
		$this->template->build('paytm/paytm_form', $data);
	}

	public function verify(){
		$amount = post("amount");
		$txnId = preg_replace("/[^a-zA-Z0-9]/", "", post("txn_id"));
		$agree  = post("agree");
		$payment_method = post('payment_method');
		if ($amount  == "" || $txnId == "") {
			ms(array(
				"status"  => "error",
				"message" => "Please fill all details.",
			));
		}

		if ($amount  < 0) {
			ms(array(
				"status"  => "error",
				"message" => lang("app.amount_must_be_greater_than_zero"),
			));
		}

		if (!$agree) {
			ms(array(
				"status"  => "error",
				"message" => lang("app.you_must_confirm_to_the_conditions_before_paying")
			));
		}
		
		$checkTxn = get_field($this->tb_transaction_logs, ["transaction_id" => $txnId], "id");
		// pr(get_option('paytm_email'));exit();

		if($checkTxn != ""){
			ms(array(
				"status" => "error",
				"message" => "Duplicate transaction ID.",
			));
			exit();
		}

		$connection = imap_open(trim("{imap.gmail.com:993/imap/ssl/novalidate-cert}"), trim(get_option('paytm_email')), trim(get_option('paytm_password'))) or die('Error : ' . imap_last_error());
		// var_dump($connection); exit();
		if($connection == false){
			ms(array(
				"status" => "error",
				"message" => "Please try again later or contact support.",
			));
		}

        //Connection established to the mail server, now search the transaction
        $matchTxn = imap_search($connection, 'TEXT "'.$txnId . '"',SE_FREE, "UTF-8");
		// var_dump($matchTxn); exit();

        if ($matchTxn !== false) {
            //get message id
            $data = var_export($matchTxn, true);
            $whatIWant = substr($data, strpos($data, ">") + 1);
            $to = ", )";
            $c = chop($whatIWant, $to);
            $d = str_replace(",", "", $c);
            $e = preg_replace('/\s+/', '', $d);

            //only if the certain amount was sent
            $headerInfo = imap_body($connection, $e);
			// pr($headerInfo);exit();
			
            $amount1 = ">".$amount."<";
            if (!strpos($headerInfo, $amount1)) {
                ms(array(
					"status" => "error",
					"message" => "Transaction failed. Make sure you enter same amount you paid.",
				));
			}
			$user_balance = $old_balance = get_field($this->tb_users, ["id" => session("uid")], "balance");
			$user_balance += $amount;
			
			$data_tnx_log = array(
				"ids" 				=> ids(),
				"uid" 				=> session("uid"),
				"type" 				=> $this->payment_type,
				"transaction_id" 	=> $txnId,
				"amount" 	        => number_format($amount, 2),
				"status" 	        => 1,
				"b_balance"	        => $old_balance,
				"a_balance"	        => $user_balance,
				"created" 			=> NOW,
			);

			$this->db->insert($this->tb_transaction_logs, $data_tnx_log);
			$transaction_id = $this->db->insert_id();

			// pr($amount);exit();
			$this->db->update($this->tb_users, ["balance" => $user_balance], ["id" => session("uid")]);
			set_session("transaction_id", $transaction_id);
			// redirect(cn("add_funds/success"));

			ms(array(
				"status" => "success",
				"message" => "Transaction successful, fund added to your account",
			));
		}else{
			ms(array(
				"status" => "error",
				"message" => "Transaction ID not found.",
			));
			// redirect(cn("add_funds"));
		}
	}

}