<?php 
namespace App\Models;

use CodeIgniter\Model;

 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ExtendModel extends Model
{
    protected $DBGroup = 'default';
    //public $db;
	public $tb_users;
	public $tb_users_price;
	public $tb_categories;
	public $tb_services;
	public $tb_api_providers;
    public function __construct(){
		$this->tb_categories     = CATEGORIES;
		$this->tb_services       = SERVICES;
		$this->tb_api_providers  = API_PROVIDERS;
		$this->tb_users_price    = USERS_PRICE;
		
		$this->db = db_connect();
		//parent::__construct();
        
	}
	public function get_services_list($user_level = ""){
		switch ($user_level) {
			case '1':
                $builder = $this->db->table($this->tb_services." s");
                
				$builder->select('s.*, api.name as api_name, c.name as category_name, c.id as main_cate_id');
				$builder->join($this->tb_categories." c", "c.id = s.cate_id", 'left');
				$builder->join($this->tb_api_providers." api", "s.api_provider_id = api.id", 'left');
				$builder->orderBy("c.sort", 'ASC');
				$builder->orderBy("s.price", 'ASC');
				$builder->orderBy("s.name", 'ASC');
				break;
			
			default:
                $builder = $this->db->table($this->tb_services." s");
				$builder->select('s.id, s.desc, s.ids, s.name, s.min, s.max, s.price, c.name as category_name, c.id as main_cate_id');
				$builder->join($this->tb_categories." c", "c.id = s.cate_id", 'left');
				$builder->where("s.status", "1");
				$builder->orderBy("c.sort", 'ASC');
				$builder->orderBy("s.price", 'ASC');
				$builder->orderBy("s.name", 'ASC');
				break;
		}
		$query = $builder->get();
		$result = $query->getResult();
		$category = array();
		if ($result) {
			foreach ($query->getResultArray() as $row) {
               $category[$row['category_name']][] = (object)$row;
         	}
		}
		return $category;
	}
    function fetch($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $start = -1, $limit = 0, $return_array = false)
	{
		
        $builder = $this->db->table($table);
		
		if($where != "")
		{
			$builder->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$builder->orderBy('rand()');
			}else{
				$builder->orderBy($order, $by);
			}
		}
		
		if((int)$start >= 0 && (int)$limit > 0)
		{
			$builder->limit($limit, $start);
		}
		#Query
		$query = $builder->get();
		if($return_array){
			$result = $query->getResultArray();
		} else {
			$result = $query->getResult();
		}
		$query->freeResult();
		return $result;
	}	
	
	public function get($select = "*", $table = "", $where = "", $order = "", $by = "DESC", $return_array = false)
	{
		$builder = $this->db->table($table);
		if($where != "")
		{
			$builder->where($where);
		}
		if($order != "" && (strtolower($by) == "desc" || strtolower($by) == "asc"))
		{
			if($order == 'rand'){
				$builder->orderBy('rand()');
			}else{
				$builder->orderBy($order, $by);
			}
		}		
		#Query
		$query = $builder->get();
		if($return_array){
			$result = $query->getRowArray();
		} else {
			$result = $query->getRow();
		}
		$query->freeResult();

		return $result;
	}

	public function common_insert($table="",$data=array()){
		$builder = $this->db->table($table);
		return $builder->insert($data);

	}
 
	public function insertID(){

		return $this->db->insertID();
	}

	public function common_update($table="",$data=array(),$where=array()){
		
		$builder = $this->db->table($table);
		return $builder->update($data,$where);
		
		
	}
	/**
	 * @param $table
	 * @param $where
	 * @param $select_sum - a field want to sum
	 * @return int
	 */
	public function sum_results($select_sum, $table, $where = "")
	{
	 
        $builder = $this->db->table($table);
		if ($where != "") {
			$builder->where($where);
		}

		$builder->selectSum($select_sum);
		$query = $builder->get($table);
		$result = $query->getResult();

		if ($result[0]->$select_sum > 0) {
			return $result[0]->$select_sum;
		} else {
			return 0;
		}
	}
	
	function check_record($fields, $table, $id, $check_user, $get_data,$isarray=true){
		if (!$get_data) {
			if ($id == "") {
				return false;
			}
		}

		if($check_user){
			$where = array(
				"uid" => session("uid"),
				"id" => $id,
			);
		}else{
			$where = array(
				"id" => $id,
				"status" => 1
			);
		}
		$query = $this->db->table($table)->select($fields)->where($where)->get();
		//echo $this->db->getLastQuery();
		
		$item = $query->getResultArray();

		if ($get_data) {
			if(!$isarray)
			return (object)$item[0];
			else
			return $item;
		}

		if(!empty($item)){
			return true;
		}else{
			return false;
		}
	}

	function history_ip($userid){
        $builder = $this->db->table(USERS);
		$builder->where("id",$userid);
		$builder->update(array('history_ip' => get_client_ip()));
		//$user = $this->get("id, history_ip", USERS, "id = '{$userid}'");	
		//if(!empty($user)){
         //   $builder = $this->db->table(USERS);
		//	$builder->update(array('history_ip' => get_client_ip()), array("id" => $userid));
		//}	
	}

	function get_storage($check_type = "", $size = 0){
		$user = $this->get("*", USERS, "id = '".session("uid")."'");
		$data = array(
			"max_storage_size" => 100,
			"max_file_size" => 5,
			"total_storage_size" => 0
		);
        $builder = $this->db->table(FILE_MANAGER);
		$builder->select("uid, SUM(file_size) AS size");
		$builder->from(FILE_MANAGER);
		$builder->where("uid", session("uid"));
		$builder->groupBy("uid");
		$query = $builder->get();
		if($query->getRow()){
			$result = $query->getRow();
			if(!empty($result)){
				$total_size = (float)$result->size/1024;
				$data['total_storage_size'] = $total_size;
			}
		}else{
			
		}

		if(!empty($user)){
			$permission = (array)json_decode($user->permission);
			if(!empty($permission)){
				if(isset($permission['max_storage_size'])){
					$data['max_storage_size'] = $permission['max_storage_size'];
				}

				if(isset($permission['max_file_size'])){
					$data['max_file_size'] = $permission['max_file_size'];
				}
			}
		}

		$data = (object)$data;

		switch ($check_type) {
			case 'storage':
				$total_size = $data->total_storage_size + $size/1024;
				if($total_size > $data->max_storage_size){
					ms(array(
						"status" => "error",
						"message" => lang("app.you_have_exceeded_the_storage_limit")
					));
				}
				break;

			case 'file':
				$size = $size/1024;
				if($size > $data->max_file_size){
					ms(array(
						"status" => "error",
						"message" => lang("app.you_have_exceeded_the_file_limit")
					));
				}

				$total_size = $data->total_storage_size + $size;
				if($total_size > $data->max_storage_size){
					ms(array(
						"status" => "error",
						"message" => lang("app.you_have_exceeded_the_storage_limit")
					));
				}
				break;
			
			default:
				return $data;
				break;
		}
	}		

	function send_email($subject, $email_content, $user_id, $check_replace = true){
		$user_info = $this->get("first_name, last_name, email, timezone, reset_key, activation_key", USERS, "id = '{$user_id}'");

		if (empty($user_info)) {
			return "Account does not exists!";
		}
		/*----------  Get Mail Template  ----------*/
		$mail_template = file_get_contents(APPPATH.'/libraries/PHPMailer/template.php');

		/*----------  replace variable in email content, subject  ----------*/
		$email_from = get_option('email_from', '')?get_option('email_from', ''):"do-not-reply@smm.com";
		$email_name = get_option('email_name', '')?get_option('email_name', ''):get_option('website_title', '');
		$user_firstname = $user_info->first_name;
		$user_lastname  = $user_info->last_name;
		$user_timezone  = $user_info->timezone;
		$user_email     = $user_info->email;

		$website_link = PATH;
		$website_logo = get_option('website_logo', BASE."assets/images/logo.png");
		$website_name = get_option("website_name", "SMM PANEL");
		$copyright    = get_option('copy_right_content',"Copyright &copy; 2020 - SmartPanel");

		/*----------  Need to replace subject, content or Not  ----------*/
		if ($check_replace) {
			$subject = str_replace("{{user_firstname}}", $user_firstname, $subject);
			$subject = str_replace("{{user_lastname}}", $user_lastname, $subject);
			$subject = str_replace("{{user_timezone}}", $user_timezone, $subject);
			$subject = str_replace("{{user_email}}", $user_email, $subject);
			$subject = str_replace("{{activation_link}}", cn("auth/activation/".$user_info->activation_key), $subject);
			$subject = str_replace("{{website_name}}", $website_name, $subject);
			$subject = str_replace("{{recovery_password_link}}", cn("auth/reset_password/".$user_info->reset_key), $subject);

			$email_content = str_replace("{{user_firstname}}", $user_firstname, $email_content);
			$email_content = str_replace("{{user_lastname}}", $user_lastname, $email_content);
			$email_content = str_replace("{{user_timezone}}", $user_timezone, $email_content);
			$email_content = str_replace("{{activation_link}}", cn("auth/activation/".$user_info->activation_key), $email_content);
			$email_content = str_replace("{{user_email}}", $user_email, $email_content);
			$email_content = str_replace("{{website_name}}", $website_name, $email_content);
			$email_content = str_replace("{{recovery_password_link}}", cn("auth/reset_password/".$user_info->reset_key), $email_content);
		}

		$mail_template = str_replace("{{website_logo}}", $website_logo, $mail_template);
		$mail_template = str_replace("{{website_link}}", $website_link, $mail_template);
		$mail_template = str_replace("{{website_name}}", $website_name, $mail_template);
		$mail_template = str_replace("{{copyright}}", $copyright, $mail_template);
		$mail_template = str_replace("{{email_content}}", $email_content, $mail_template);

		/*----------  Call PHPMaler  ----------*/
		$this->load->library("Phpmailer_lib");
		$mail = new PHPMailer(true);
		$mail->CharSet = "utf-8";
		try {

			/*----------  Check send email through PHP mail or SMTP  ----------*/
			$email_protocol_type 	= get_option("email_protocol_type", "");
			$smtp_server 			= get_option("smtp_server", "");
			$smtp_port 				= get_option("smtp_port", "");
			$smtp_username 			= get_option("smtp_username", "");
			$smtp_password 			= get_option("smtp_password", "");
			$smtp_encryption 		= get_option("smtp_encryption", "");

			if($email_protocol_type == "smtp" && $smtp_server != "" && $smtp_port != "" && $smtp_username != "" && $smtp_password != ""){
			    $mail->isSMTP();
			    $mail->SMTPDebug = 0; 
			    //Enable SMTP debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages                               
			    $mail->Host = $smtp_server; 
			    $mail->SMTPAuth = false;                             
			    if ($smtp_username != "" && $smtp_username != "")  {
			    	$mail->SMTPAuth = true;                             
			    	$mail->Username = $smtp_username;                
			    	$mail->Password = $smtp_password;                         
			    }                         
			    $mail->SMTPSecure = $smtp_encryption;                         
			    $mail->Port = $smtp_port;
			    $mail->SMTPOptions = array(
			        'ssl' => array(
			            'verify_peer' => false,
			            'verify_peer_name' => false,
			            'allow_self_signed' => true
			        )
			    );                                    
			}else{
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
			}

		    //Recipients
		    $mail->setFrom($email_from, $email_name);
		    $mail->addAddress($user_email, $user_firstname);    
		    $mail->addReplyTo($email_from, $email_name);

		    //Content
		    $mail->isHTML(true); 
		    $mail->Subject = $subject;
		    $mail->MsgHTML($mail_template);

		    $mail->send();

		    return false;
		} catch (Exception $e) {
			$message = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
		    return $message;
		}
	}
 
	// function send_mail_template($template = [], $user_id_or_email, $from_email_data = []){

    //     // Get Receive email, name
    //     if (is_numeric($user_id_or_email)) {
    //         $user_info = $this->get("email, role, timezone", USERS, ['id' => $user_id_or_email]);
    //         if (empty($user_info)) {
    //             return "Failed to send email template! User Account does not exists!";
    //         }
    //         $recipient_email_address = $user_info->email;
    //         $recipient_name          = 'Admin';

    //     }else{
    //         $recipient_email_address = $user_id_or_email;
    //         $recipient_name          = 'Clients';
    //     }

	// 	// Set default from email header
	//     $default_from_email = get_option('email_from', '') ? get_option('email_from', '') : "do-not-reply@smm.com";
    //     // Get Send email, name
    //     if (isset($from_email_data['from_email']) && $from_email_data['from_email'] != "") {
    //         $from_email = $from_email_data['from_email'];
    //     }else{
    //         $from_email = $default_from_email;
    //     }

    //     if (isset($from_email_data['from_email_name']) && $from_email_data['from_email_name'] != "") {
    //         $from_email_name = $from_email_data['from_email_name'];
    //     }else{
    //         $from_email_name = get_option('email_name', '') ? get_option('email_name', '') : get_option('website_title', '');
    //     }

    //     if (isset($template['merge_fields']) && $template['merge_fields'] != '') {
    //     	$merge_fields = $template['merge_fields'];
    //     }else{
    //     	$merge_fields = array();
    //     }


    //     $subject       = parse_merge_fields($template['subject'], $merge_fields, false);
    //     $mail_template = parse_merge_fields($template['message'], $merge_fields, true);

    //     /*----------  Call PHPMaler  ----------*/
    //     $this->load->library("phpmailer_lib");
    //     $mail = new PHPMailer(true);
    //     $mail->CharSet = "utf-8";
    //     try {
    //         /*----------  Check send email through PHP mail or SMTP  ----------*/
    //         $email_protocol_type    = get_option("email_protocol_type", "");
    //         $smtp_server            = get_option("smtp_server", "");
    //         $smtp_port              = get_option("smtp_port", "");
    //         $smtp_username          = get_option("smtp_username", "");
    //         $smtp_password          = get_option("smtp_password", "");
    //         $smtp_encryption        = get_option("smtp_encryption", "");

    //         if($email_protocol_type == "smtp" && $smtp_server != "" && $smtp_port != "" && $smtp_username != "" && $smtp_password != ""){
    //             $mail->isSMTP();
    //             $mail->SMTPDebug = 0; 
    //             //Enable SMTP debugging
    //             // 0 = off (for production use)
    //             // 1 = client messages
    //             // 2 = client and server messages                               
    //             $mail->Host = $smtp_server; 
    //             $mail->SMTPAuth = false;                             
    //             if ($smtp_username != "" && $smtp_username != "")  {
    //                 $mail->SMTPAuth = true;                             
    //                 $mail->Username = $smtp_username;                
    //                 $mail->Password = $smtp_password;                         
    //             }                         
    //             $mail->SMTPSecure = $smtp_encryption;                         
    //             $mail->Port = $smtp_port;
    //             $mail->SMTPOptions = array(
    //                 'ssl' => array(
    //                     'verify_peer' => false,
    //                     'verify_peer_name' => false,
    //                     'allow_self_signed' => true
    //                 )
    //             );                                    
    //         }else{
    //             // Set PHPMailer to use the sendmail transport
    //             $mail->isSendmail();
    //         }
    //         /* Set the mail sender. */
    //         $mail->setFrom($default_from_email, $from_email_name);
    //         $mail->addReplyTo($from_email, $from_email_name);

    //         //Recipients
    //         $mail->addAddress($recipient_email_address, $recipient_name);    

    //         //Content
    //         $mail->isHTML(true); 
    //         $mail->Subject = $subject;
    //         $mail->MsgHTML($mail_template);

    //         $mail->send();

    //         return false;
    //     } catch (Exception $e) {
    //         $message = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
    //         return $message;
    //     }
    // }

    function get_class(){
		if (segment(2) == base64_decode('Y3Jvbg==')) {
			$item = $this->get(base64_decode('cHVyY2hhc2VfY29kZQ==')." as item", base64_decode('Z2VuZXJhbF9wdXJjaGFzZQ=='))->item;
			if (md5($item) != get_configs(base64_decode('ZW5jcnlwdGlvbl9rZXk=')) || empty($item)) {
			 	echo base64_decode('U3VjY2Vzc2Z1bGx5IE5H'); exit(0);
			}
		}
	}
	/**
	 *
	 * Call phpass class
	 *
	 */
	function app_hasher(){
		//require_once APPPATH."\Libraries\PasswordHash.php";
		$app_hasher = new \App\Libraries\PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
		return $app_hasher;
	}

	//hash password user
	function app_password_hash($input_password){
		return $this->app_hasher()->HashPassword($input_password);
	}

	// Password verify
	function app_password_verify($input_password, $hash_password){
		$result = $this->app_hasher()->CheckPassword($input_password, $hash_password);
		if ($result) {
			return true;
		}else{
			return false;
		}
	}

}