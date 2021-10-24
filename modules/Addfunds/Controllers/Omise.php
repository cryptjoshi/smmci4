<?php
namespace Modules\Addfunds\Controllers;
use App\Controllers\BaseController;
use Modules\AddFunds\Models\omise_model;
//  ini_set('display_errors', '1');
//  ini_set('display_startup_errors', '1');
 //error_reporting(E_ALL);
//require_once(APPPATH.'modules/add_funds/libraries/omise_php/lib/Omise.php');

class Omise extends BaseController {
	 
    public $tab_creditcards;
	public $tb_transaction_logs;
	public $stripeapi;
	public $payment_type;
	public $currency_code;
    public $selected_card;
    public $omise_key;
    public $omise_secret;
    public $currency_rate;
    public $amount;
    public $amount_group;
    public $user_info;
    public $tb_payments;
    public $payment_id;
    public $mode;
    public $payment_fee;


   


	public function __construct(){
	    
		//parent::__construct();
        //$this->load->model(get_class($this).'_model', 'model');
        $this->model = new omise_model();
        helper('url');
        helper('common');
        helper('file_manager');
		$this->tb_users            = $this->model->tb_users;
        $this->tb_creditcards      = CREDITCARDS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
        $this->tb_payments       = PAYMENTS_METHOD;
		$this->payment_type		   = "omise";
		$this->currency_code       = get_option("currency_code");
        $this->currency_rate       = get_option("omise_currency_rate_to_usd");
      
        $this->user_info = $this->model->get_users_info();
       // $this->load->helper("paytm");
    //    if (!$payment) {
	 	$payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
             
	// 	}
        
	 	$this->payment_id 	= $payment->id;
	 	$params  			= $payment->params;
	 	$option             = get_value($params, 'option');
        
        
        define('OMISE_PUBLIC_KEY', get_value($option,"public_key"));
        define('OMISE_SECRET_KEY', get_value($option,"secret_key"));
	 	$this->mode         = get_value($option, 'environment');
	 	$this->payment_fee  = get_value($option, 'tnx_fee');
	
	

	}

	public function index(){

       
		// $path_file = APPPATH."./modules/setting/views/integrations/omise.php";
        // if (!file_exists($path_file)) {
        // 	redirect(cn('add_funds'));
        // }
        $creditcards = $this->model->get_card_lists(true);
        if($this->currency_code!="THB")
        $this->currency_rate=$this->currency_rate; 
        else $this->currency_rate=1;
        $this->amount =  session('amount')*$this->currency_rate;
   
  
       
		$data = array(
			"module"        => 'add_funds',
			"amount"        =>  number_format((float)$this->amount, 2, '.', ''),
            "creditcards"    => $creditcards,
            "selected_card"  => $this->selected_card,
            "omise_key"      => OMISE_PUBLIC_KEY,//get_option("omise_merchant_key"),
            "omise_secret"   => OMISE_SECRET_KEY,//get_option("omise_merchant_secret"),
            "currency"       => $this->currency_code,
            "currency_rate"  => $this->currency_rate,
            "omisetoken"     => session("omisetoken"),
            //"qr"             => $source['scannable_code']['image']['download_uri'],
            "amount_group"   => $this->amount_group
            
		);
		return view('Modules\Addfunds\Views\omise/omise_payform', $data);
        
	}

    public function process(){
		
		//$amount = post("amount");
		//$agree  = post("agree");
        $charge = NULL;
        $source = NULL;
        $user_id =session('uid');
        $transaction_id = "";
        $token_id ="";
        
        //$qr="";
        $total = session('amount');
        //$TXN_AMOUNT = $_POST["TXN_AMOUNT"];
		$payment_method = post('payment_method');
        $user_name = get_field(USERS, ["id" => session('uid')], 'first_name');
        $user_email = get_field(USERS, ["id" => session('uid')], 'email');
      
        $omise_customer_id=get_field(USERS, ["id" => session('uid')], 'omise_customer_id');
      
        $creditcards = NULL;
        $card_token=NULL;
        if($payment_method == "qrcode")
        {
            $charge = \OmiseCharge::create(array(
                             'amount' => $total*100,
                             'currency' => 'thb',
                             'source[type]' => 'promptpay',
                             //'' => session("omisetoken")
                    ));//,get_option("omise_merchant_key"),get_option("omise_merchant_secret"));
                   // if(get_option("is_active_".$this->payment_type)){
            $source =$charge['source'];
            $data = array(
                "ids" 				=> ids(),
                "uid" 				=> session('uid'),
                "type" 				=> $this->payment_type,
                "transaction_id" 	=> $source['id'],
                "amount" 	        => $total,
                "txn_fee"           => $total-session('real_amount'),
                "status"            => 0,
                "created" 			=> NOW,
            );

            $this->model->common_insert($this->tb_transaction_logs, $data);
            $transaction_id = $this->model->insertID();

        } else if($payment_method == "creditcard"){  
            
            if(count($this->model->get_card_lists(true))>0){
                $creditcards=$this->model->get_card_lists(true);
            
               
                $token_id = $creditcards[0]->omise_token_id;
                //$card_token['id'];
                 } if(empty($token_id)) {
                

                        $card_token = $this->model->card_token([
                            'omise_card_name' => post('cardholder'),
                            'omise_card_number' => preg_replace("/\s+/", "", post('cardnumber')),
                            'omise_card_month' => explode("/",post('cardexpire'))[0],
                            'omise_card_year' => explode("/",post('cardexpire'))[1],
                            'omise_card_cvv' => preg_replace("/\s+/", "", post('cardccv')),
                        ]);
                      
                         if($card_token){
                        $data = array(
                            "ids"             => session("ids"),
                            "uid"             => session("uid"),
                            "omise_token_id"   => $card_token['card']['id'],
                                "name"            => post('cardholder'),
                                "creditcardno"    => preg_replace("/\s+/", "", post('cardnumber')),
                                "year"            => explode("/",post('cardexpire'))[1],
                                "month"           => explode("/",post('cardexpire'))[0],
                                "status"          => 1
                            );
                        $this->model->update_creditcard($data);
                        $token_id = $card_token['id'];
                         }
                     }

                 

                    
                    try {
                    if(empty($omise_customer_id)){
                        $customer = \OmiseCustomer::create([
                            'email' => $user_email,
                            'description' => $user_name,
                            'card' => $token_id
                        ]);
                        $this->model->update_omiseid($customer['id']);
                        $omise_customer_id = $customer['id'];
                    }else {
                        $omise_customer = \OmiseCustomer::retrieve($omise_customer_id);
                        $cards = $omise_customer['cards'];
                        if (empty($cards['data'])) {
                            $omise_customer->update([
                            'card' => $token_id,
                            ]);
                        }
                    }
                    $charge = \OmiseCharge::create([
                        'amount' => $total*100,
                        'currency' => 'thb',
                        'customer' => $omise_customer_id,
                        'return_uri'=> cn('add_funds')."/complete"
                    ]);
                    if($charge['failure_code'] == null){
                        $payment_id = $charge['id'];
                        $transaction_id=$charge['id'];
                        /*----------  Insert to Transaction table  ----------*/
                            $data = array(
                                "ids" 				=> ids(),
                                "uid" 				=> session('uid'),
                                "type" 				=> $this->payment_type,
                                "transaction_id" 	=> $transaction_id,
                                "txn_fee"           => $total-session('real_amount'),
                                "amount" 	        => $total,
                                "created" 			=> NOW,
                            );
                            
            
                            $this->model->common_insert($this->tb_transaction_logs, $data);
                            $transaction_id = $this->model->insertID();
                            
                            /*----------  Add funds to user balance  ----------*/
                            $user_balance = get_field($this->tb_users, ["id" => $user_id], "balance");
            
                        
                            $user_balance += session("real_amount");
            
                            $this->model->common_update($this->tb_users, ["balance" => $user_balance], ["id" => $user_id]);
                        }else {
                            ms(array(
                                "status"  => "error",
                                "message" => $charge['failure_code']
                            ));
                        }
                    }
                    catch(\Exception $e){
                        ms(array(
                            "status"  => "error",
                            "message" => $e->getMessage()
                            
                        ));
                    }
                  
        } else if($payment_method == "offline") {
            if(isset($_FILES['imagefile'])){
            $this->offline();
            }
        } else if($payment_method == 'truewallet'){
 
            $data=array(
                "payment_method" => $payment_method
            );
 
        }

        
         
        $data = array(
			"module"        => 'add_funds',
			"amount"        =>  number_format((float)$total, 2, '.', ''),
            "creditcards"    => $creditcards,
            "selected_card"  => $this->selected_card,
            "omise_key"      => OMISE_PUBLIC_KEY,//get_option("omise_merchant_key"),
            "omise_secret"   => OMISE_SECRET_KEY,//get_option("omise_merchant_secret"),
            // "omise_key"      => get_option("omise_merchant_key"),
            // "omise_secret"   => get_option("omise_merchant_secret"),
            "currency"       => $this->currency_code,
            "currency_rate"  => $this->currency_rate,
            "omisetoken"     => session("omisetoken"),
            "qr"             => $source!=NULL?$source['scannable_code']['image']['download_uri']:"",
            "amount_group"   => $this->amount_group,
            "payment_method" => $payment_method,
            "image_file"     => "",
            "user_info"      => json_decode($this->user_info[0]->more_information)
            
		);
        
        
        if($payment_method=='creditcard' && $charge['failure_code']==null){
                    set_session("uid", $user_id);
    				set_session("transaction_id", $transaction_id);
                    return view('Modules\Addfunds\Views\omise/successful', $data);
        }else if($payment_method!='creditcard'){
        return view('Modules\Addfunds\Views\omise/'.$payment_method, $data);
        }
	}



    public function otp(){
        $phone_number = post('omise_phonenumber');
        $total = session('amount');
        $payment_method = post('payment_method');
        $return_uri = cn('add_funds')."/omise/checkotp";
        $authorize_uri="";
        $ids = ids();
        try {
            $email = get_field(USERS, ['id' => session('uid')], "email");
 
      $charge=  \OmiseCharge::create(array(
                'amount'      => $total*100,
                'currency'    => 'THB',
                'email'       => $email,
                'source'      => array( 'type' => 'truemoney', 'phone_number' => $phone_number ),
                //'source'      => $source['id'],
                'return_uri'  => $return_uri."?ids={$ids}"
            ),OMISE_PUBLIC_KEY,OMISE_SECRET_KEY);

            //print_r($charge);
       $transaction_id = $charge['source']['id'];
       $data = array(
                "ids" 				=> $ids,
                "uid" 				=> session('uid'),
                "type" 				=> $charge['source']['type'],
                "transaction_id" 	=> $transaction_id,
                "amount" 	        => $charge['source']['amount']/100,
                "status"            => 0,
                "txn_fee"           => $total-session('real_amount'),
                "created" 			=> $charge['source']['created_at']
             );
        $this->model->common_insert($this->tb_transaction_logs, $data);
        $transaction_id = $this->model->db->insertID();
        $authorize_uri = $charge['authorize_uri'];
         $data = array(
             "module"        => 'add_funds',
             "amount"        =>  number_format((float)$this->amount, 2, '.', ''),
             "omise_key"      => OMISE_PUBLIC_KEY,//get_option("omise_merchant_key"),
             "omise_secret"   => OMISE_SECRET_KEY,//get_option("omise_merchant_secret"),
            //  "omise_key"      => get_option("omise_merchant_key"),
            //  "omise_secret"   => get_option("omise_merchant_secret"),
             "currency"       => $this->currency_code,
             "payment_method" => $payment_method,
             "currency_rate"  => $this->currency_rate,
             "omisetoken"     => session("omisetoken"),
             'authorize_uri'  => $authorize_uri,
             "omise_phonenumber"=>$phone_number,
     
            
         );
         return view("Modules\Addfunds\Views\omise/redirect", $data);
       
        }
        catch( \Exception $ex)
        {
            
            $error_message = $ex->getMessage();
             ms(array(
			 	"status"  => "error",
			 	"message" => $error_message
			 ));
        }
    }

    public function checkotp(){
        $ids= get('ids');
        
		$result = $this->model->get("*", $this->tb_transaction_logs, "ids = '{$ids}' AND uid ='".session('uid')."'");
        
        $data = array(
             "module"        => get_class($this),
             "transaction"   => $result,
         );
        // if($result->status==1){
        return view('Modules\Addfunds\Views\omise\payment_successfully', $data);
        // }else {
        //     return view('Modules\Addfunds\Views\omise\payment_unsuccessfully', $data);
        // }
    }

  

    public function offline(){
      //  if(isset($_FILES['imagefile'])){
        //echo $_FILES['imagefile']['name'];
        
        $total = session('amount');
        //echo $image_new_name;
        $account_number=post("account_number");
        $user_id = post("user_id");
        $upload_folder="payments/offline/".$user_id."/".$account_number."/";
        if(check_image($_FILES['imagefile']['name'])){
        $image_new_name=$_FILES['imagefile']['name'];
        $image_file_extension = explode('.', $_FILES['imagefile']['name']);
        $image_file_extension = mb_strtolower(end($image_file_extension));
        $image_file_temp = $_FILES['imagefile']['tmp_name'];
        $image_new_name = md5(time() . rand()) . '.' . $image_file_extension;
        $suffix="-offline-".date("Y-m-d-His");
        $image_new_name = uploadimage($image_file_temp,$upload_folder,$image_new_name,$suffix);
       
        if($image_new_name['status']){
            $ids = ids();
             $transaction_id = "off_".session('uid')."_".$ids;
             $imgfile = $image_new_name["image_name"];//"off_".$user_id."_".generateNonce();
             $data = array(
                "status"=>"success",
                "ids" 				=> $ids,
                "uid" 				=> session('uid'),
                "type" 				=> "offline",
                "transaction_id" 	=> $transaction_id,
                "slip" 	            => $imgfile,
                "amount" 	        => $total,
                "txn_fee"           => $total-session('real_amount'),
                "status"            => 0,
                "created" 			=> NOW,
             );

             $this->model->common_insert($this->tb_transaction_logs, $data);
             $transaction_id = $this->model->insertID();
            // echo ($this->db->last_query());

         
            set_session("transaction_id", $transaction_id);
           // $result = $this->model->get("*", $this->tb_transaction_logs, "id = '{$transaction_id}'");
            //$data["transaction"]= $result;
           // echo cn("add_funds/omise/success");
            //redirect()->to(cn("add_funds/omise/success"));

           // if($result->status==1){
            //print_r($data);
           //return view('Modules\Addfunds\Views\payment_successfully', $data);        
           ms(array(
            "status"  => "succuss",
            "message" => "Offline payment success!",
            
        ));
        } else {
            ms(array(
				"status"  => "error",
				"message" => "Upload image file only!",
			));
        }
    
        }
        else {
        
            ms(array(
				"status"  => "error",
				"message" => "Upload image file only!",
			));
          
        
          }
 
        
    }

    public function truewallet(){

        $creditcards = NULL;
        $payment_method = post('payment_method');
        $data = array(
			"module"        => 'add_funds',
			"amount"        =>  number_format((float)$this->amount, 2, '.', ''),
            "creditcards"    => $creditcards,
            "selected_card"  => $this->selected_card,
            "omise_key"      => OMISE_PUBLIC_KEY,//get_option("omise_merchant_key"),
            "omise_secret"   => OMISE_SECRET_KEY,//get_option("omise_merchant_secret"),
            // "omise_key"      => get_option("omise_merchant_key"),
            // "omise_secret"   => get_option("omise_merchant_secret"),
            "currency"       => $this->currency_code,
            "payment_method" => $payment_method,
            "currency_rate"  => $this->currency_rate,
            "omisetoken"     => session("omisetoken"),
            
            //"qr"             => $source['scannable_code']['image']['download_uri'],
            "amount_group"   => $this->amount_group
            
		);
        return view('Modules\Addfunds\Views\omise/truewallet', $data);
    }
    
    public function creditcard(){
        $data= NULL;
        $charge = \OmiseCharge::create(array(
            'amount' => $this->amount*100,
            'currency' => 'thb',
            'source[type]' => 'card',
            
        )); 
        $source =$charge['source'];
        //print_r($source);
        view('Modules\Addfunds\Views\omise/redirect', $source);
    }

    public function change_card(){
        $uid = post('uid');
        
        $creditcards=$this->model->get_card_lists(true);
        $omise_customer_id=get_field(USERS, ["id" => session('uid')], 'omise_customer_id');
        $customer = \OmiseCustomer::retrieve($omise_customer_id);
        $card = $customer->getCards()->retrieve($creditcards[0]->omise_token_id);
        $card->destroy();

         $this->db->update("creditcards",array(
            "status"          => 0
            ),array(
            "id"             => $creditcards[0]->id
         ));
        

            // $card->isDestroyed();
            echo_json_string(array(
                "status"   => "success",
                "uid" => $uid,
                "creditcards"=>$creditcards,
               
            ));
        ms(array(
            "status"  => "succuss",
            "message" => "Card changed!",
            
        ));
      
            
    }


    public function qrcode(){
        $data = array();
        $charge = \OmiseCharge::create(array(
            'amount' => $this->amount*100,
            'currency' => 'thb',
            'source[type]' => 'promptpay',
            
        )); 
        $source =$charge['source'];
        return view('Modules\Addfunds\Views\omise\qrcode', $source);
    }

    public function update(){

        $id = post('id');
        $transaction_id = post('data[transaction_id]');
        $status = post('data[status]');
        $payment_type = post('data[payment_type]');
        $data = array(
			'status' => $status,
		);
         
        
        $check_item = $this->model->get("*", $this->tb_transaction_logs, ['transaction_id' => $transaction_id]);
        
			if (!empty($check_item)) {
				$this->db->update($this->tb_transaction_logs, $data, ['uid' => $check_item->uid, 'transaction_id' => $check_item->transaction_id, 'type' => $check_item->type]);
				if ($data['status'] == 1 && $check_item->status == 0) {
					$user_balance = $this->model->get("balance", $this->tb_users, ['id' => $check_item->uid])->balance;
					$new_balance = $user_balance + ($check_item->amount - $check_item->txn_fee);
					$this->db->update($this->tb_users, ["balance" => $new_balance], ["id" => $check_item->uid]);
				}
				if ($data['status'] == 1) {
					set_session('transaction_id', $check_item->transaction_id);
					$message = array(
						"status"  => $data['status'],
						"transaction_id" => $check_item->transaction_id,
                        "token"=>  $this->security->get_csrf_hash(),
                        "redirect" => cn('add_funds/omise/successful'),
						"message" => lang("{$check_item->transaction_id} Success")
					);
					//push_message($message);
					
				} else {
                    $message = array(
						"status"  => $data['status'],
						"transaction_id" => $check_item->transaction_id,
                        "token"=>  $this->security->get_csrf_hash(),
                        "redirect" => cn('add_funds/omise/unsuccessful'),
						"message" => lang("{$check_item->transaction_id} Not Success")
					);
                }
                // if($payment_type=="promptpay" && $payment_type == "offline"){
                //     redirect($message['redirect']);
                // }else {
                    echo_json_string($message);
                //}
				
			}
    }

    public function cron(){
       
        $id = post('id');
        
        $data = post('data');
        $data = array(
            'id' => $id,
			'eventdata' => $data,
		);
        //redirect(cn("add_funds/success"));
		//$this->load->view("omise/webhook", $data);
    }

   
   
	public function complete(){

		$ref_id = $this->input->get("ref_id"); // ใช้ ref_id คิวรี่หา charge_id แล้วใช้หาค่า status ว่า transaction นี้สำเร็จหรือไม่สำเร็จ
		$charge_id = NULL;
		$charge = \OmiseCharge::retrieve($charge_id);
        if($charge['status'] === 'successful') {
           // เงินเข้าบัญชีเรียบร้อยแล้ว
        }else{
           // อาจจะ failed หรือ pending อยู่
        }
	}
    public function unsuccessful(){
        $id = session("transaction_id");
		$transaction = $this->model->get("*", $this->tb_transaction_logs, "transaction_id = '{$id}' AND uid ='".session('uid')."'");
        $data = array(
            "module"        => get_class($this),
            "transaction"   => $transaction,
        );
        return view('Modules\Addfunds\Views\payment_unsuccessfully', $data);
    }
    public function successful(){
        $id = session("transaction_id");
		$transaction = $this->model->get("*", $this->tb_transaction_logs, "transaction_id = '{$id}' AND uid ='".session('uid')."'");
        $data = array(
            "module"        => get_class($this),
            "transaction"   => $transaction,
        );
        return view('Modules\Addfunds\Views\payment_successfully', $data);
    }
    public function success(){
		$id = session("transaction_id");
		$transaction = $this->model->get("*", $this->tb_transaction_logs, "id = '{$id}' AND uid ='".session('uid')."'");
		if (!empty($transaction)) {
			$data = array(
				"module"        => get_class($this),
				"transaction"   => $transaction,
			);
			unset_session("transaction_id");
           
            //redirect(cn("add_funds/success"));
			//$this->load->view('omise/successful', $data);
            if($transaction->status==1){
            return view('Modules\Addfunds\Views\payment_successfully', $data);
            }else if($transaction->status==0){
                return view('Modules\Addfunds\Views\payment_waitproof', $data);
            } else if($transaction->status==2){
                return view('Modules\Addfunds\Views\payment_unsuccessfully', $data);
            }
		}else{
            
			redirect()->to(cn("add_funds"));
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