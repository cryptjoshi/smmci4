<?php
namespace Modules\Auth\Controllers;

use App\Controllers\BaseController;
use Modules\Auth\Models\AuthModel;

use function PHPUnit\Framework\isEmpty;

require APPPATH.'./libraries/google/autoload.php';

class Auth extends BaseController{

private $model;
public $tb_user_logs;
public $tb_user_block_ip;
public $tb_users;
private $auth;


public function __construct()
{
    helper('common');
    helper('cookie');
    
        $this->tb_users 			= USERS;
		$this->tb_user_logs   		= USER_LOGS;
		$this->tb_user_block_ip   	= USER_BLOCK_IP;

    if (get_option("enable_goolge_recapcha", '')  &&  get_option('google_capcha_site_key') != "" && get_option('google_capcha_secret_key') != "") {
        $this->recaptcha = new \ReCaptcha\ReCaptcha(get_option('google_capcha_secret_key'));
    }

    if(session("uid") && segment(2) != 'logout'){
        redirect()->to("statistics");
    }
    $this->model = new AuthModel();
}

public function index(){
    $data = array();
    return view('App\Views\template\blank_page',array("view"=>'Themes\\'.get_theme().'\Views\index',"data"=>$data));

}


public function ajax_sign_in(){
     $email = $this->request->getPostGet("email"); 
     
     $password = md5($this->request->getPostGet("password"));
     $remember = $this->request->getPostGet("remember");

     if($email == ""){
        ms(array(
            "status"  => "error",
            "message" => lang("app.email_is_required")
        ));
    }

    if($password == ""){
        ms(array(
            "status"  => "error",
            "message" => lang("app.password_is_required")
        ));
    }
    if (isset($_POST['g-recaptcha-response']) && get_option("enable_goolge_recapcha", '')  &&  get_option('google_capcha_site_key') != "" && get_option('google_capcha_secret_key') != "") {
        $resp = $this->recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
                  ->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) {
            ms(array(
                'status'  => 'error',
                'message' => lang("app.please_verify_recaptcha"),
            ));
        }
    }
    $this->auth = new \Modules\Auth\Models\AuthModel();
    $builder = $this->auth->db->table(USERS)->select("id, status, ids, email, password, role, first_name, last_name, timezone")->where("email",$email)->orderBy("id");
    $user = $builder->get()->getResult();
    //print_r(is_array($user));
    if(is_array($user)){
        $user = $user[0];
    if ($user->password == md5($this->request->getPostGet("password"))) {
        // update new password_hash
        $this->auth->db->update(USERS, ['password' => $this->model->app_password_hash(post("password"))] , ['id' => $user->id]);
        $error = false;
    }else{
        // check the last hash password
        if ($this->model->app_password_verify($this->request->getPostGet("password"), $user->password)) {
            $error = false;
        }else{
            $error = true;
        }
    }
	if($user->status != 1){
				ms(array(
					"status"  => "error",
					"message" => lang("app.your_account_has_not_been_activated")
				));
			}
			set_session("uid", $user->id);
            set_session("isLoggedIn",true);
			$data_session = array(
				'role'       => $user->role,
				'email'      => $user->email,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'timezone'   => $user->timezone,
			);
			set_session('user_current_info', $data_session);
            $this->model->history_ip($user->id);
			
			$this->insert_user_activity_logs();
			
			if($remember){
				set_cookie("cookie_email", encrypt_encode(post("email")), 1209600);
				set_cookie("cookie_pass", encrypt_encode(post("password")), 1209600);

				// delete_cookie("cookie_email");
				// delete_cookie("cookie_pass");
			}else{
				delete_cookie("cookie_email");
				delete_cookie("cookie_pass");
			}

			// Update new Reset key
			$this->auth->db->table(USERS)->update(['reset_key' => ids() ], ['id' => $user->id]);

			 ms(array(
			 	"status"  => "success",
			 	"message" => lang("app.login_successfully")
			 ));
	 

            }else {
                ms(array(
					"status"  => "error",
					"message" => lang("app.login_unsuccessfully")
				));
            }
}

 
private function insert_user_activity_logs($type = ''){
    if (!$this->model->db->tableExists($this->tb_user_logs)) {
        return false;
    }
    if (session('uid')) {
        $ip_address = get_client_ip();
        $data_user_logs = array(
            "ids"		=> ids(),
            "uid"		=> session('uid'),
            "ip"		=> $ip_address,
            "type"		=> ($type == 'logout') ? 0 : 1,
            "created"   => NOW,
        );
        $location = get_location_info_by_ip($ip_address);
        if ($location->country != 'Unknown' && $location->country != '') {
            $data_user_logs['country'] = $location->country;
        }else{
            $data_user_logs['country'] = 'Unknown';
        }
        $this->model->db->table($this->tb_user_logs)->insert($data_user_logs);
    }
}
public function login(){
    //		$this->lang->load('../../../../themes/'.get_theme().'/language/english/'.get_theme());
            $data = array();
            return view('App\Views\template\blank_page',array("view"=>'Themes\\'.get_theme().'\Views\sign_in',"data"=>$data));
            //$this->template->build('../../../themes/'.get_theme().'/views/sign_in', $data);
        }
    


public function logout(){
    /*----------  Insert User logs  ----------*/
    $this->insert_user_activity_logs('logout');
    unset_session("uid");
    unset_session("auto_confirm");
    unset_session("user_current_info");
    unset_session("isLoggedIn");
    session_destroy();
    if (get_option("is_maintenance_mode")) {
        delete_cookie("verify_maintenance_mode");
    }
    return redirect()->to(BASE);
}
}