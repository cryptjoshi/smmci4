<?php
use CodeIgniter\HTTP\IncomingRequest;
require_once (APPPATH.'/libraries/unirest-php/src/Unirest.php');

helper("URL");

if(!function_exists('post_get')){

	function post_get($name = ""){
        $request = service('request');
		//$CI = get_instance();
		if($name != ""){
			return $request->getPostGet(trim($name));
		}else{
			return $request->getPostGet();
			
		}
	}
}

if(!function_exists('get')){
	function get($name = ""){
		$CI =  \Config\Services::request();

		$result = $CI->getVar(trim($name));
		
		$result = strip_tags($result);
		$result = html_entity_decode($result);
		$result = urldecode($result);
		$result = addslashes($result);
		return $result;
	}
}


if(!function_exists('post')){
	function post($name = ""){
		$CI =  service('request');
		if($name != ""){
			$post = $CI->getPost(trim($name));
			if(is_string($post)){
				$result =  addslashes($CI->getPost(trim($name)));
				$result =  strip_tags($result);
			}else{
				$result = $post;
			}
			return $result;
		}else{
			return $CI->getPost();
		}
	}
}

function xss_clean($data) {
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do
	{
	    // Remove really unwanted tags
	    $old_data = $data;
	    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	// we are done...
	return $data;
}

 
// if (!function_exists('get_configs')) {
//     function get_configs($item_name, $filename = "") {
//     	if ($filename == "") {
//     		$filename = 'config';
//     	}
//         $CI = &get_instance();
//         $CI->load->config($filename);
//         $item = $CI->config->item($item_name);
//         return $item;
//     }
// }

 
// if (!function_exists('update_configs')) {
//     function update_configs($item_name, $item_value, $filename = "") {
//     	if ($filename == "") {
//     		$filename = 'app_configs';
//     	}
//         $CI = &get_instance();
//         $CI->load->config($filename);
//         $item = $CI->config->set_item($item_name, $item_value);
//     }
// }


if ( ! function_exists('set_status_header'))
{
	/**
	 * Set HTTP Status Header
	 *
	 * @param	int	the status code
	 * @param	string
	 * @return	void
	 */
	function set_status_header($code = 200, $text = '')
	{
		if (is_cli())
		{
			return;
		}

		if (empty($code) OR ! is_numeric($code))
		{
			throw new \Exception('Status codes must be numeric',500);
			//show_error('Status codes must be numeric', 500);
		}

		if (empty($text))
		{
			is_int($code) OR $code = (int) $code;
			$stati = array(
				100	=> 'Continue',
				101	=> 'Switching Protocols',

				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				303	=> 'See Other',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				402	=> 'Payment Required',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',
				426	=> 'Upgrade Required',
				428	=> 'Precondition Required',
				429	=> 'Too Many Requests',
				431	=> 'Request Header Fields Too Large',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported',
				511	=> 'Network Authentication Required',
			);

			if (isset($stati[$code]))
			{
				$text = $stati[$code];
			}
			else
			{
				throw new \Exception('No status text available. Please check your status code number or supply your own message text.',500);
				//show_error('No status text available. Please check your status code number or supply your own message text.', 500);
			}
		}

		if (strpos(PHP_SAPI, 'cgi') === 0)
		{
			header('Status: '.$code.' '.$text, TRUE);
			return;
		}

		$server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
			? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header($server_protocol.' '.$code.' '.$text, TRUE, $code);
	}
}


if ( ! function_exists('get_config'))
{
	/**
	 * Loads the main config.php file
	 *
	 * This function lets us grab the config file even if the Config class
	 * hasn't been instantiated yet
	 *
	 * @param	array
	 * @return	array
	 */
	function get_config(Array $replace = array())
	{
		static $config;

		if (empty($config))
		{
			$file_path = APPPATH.'config/config.php';
			$found = FALSE;
			if (file_exists($file_path))
			{
				$found = TRUE;
				require($file_path);
			}

			// Is the config file in the environment folder?
			if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
			{
				require($file_path);
			}
			elseif ( ! $found)
			{
				set_status_header(503);
				echo 'The configuration file does not exist.';
				exit(3); // EXIT_CONFIG
			}

			// Does the $config array exist in the file?
			if ( ! isset($config) OR ! is_array($config))
			{
				set_status_header(503);
				echo 'Your config file does not appear to be formatted correctly.';
				exit(3); // EXIT_CONFIG
			}
		}

		// Are any values being dynamically added or replaced?
		foreach ($replace as $key => $val)
		{
			$config[$key] = $val;
		}

		return $config;
	}
}

if ( ! function_exists('config_item'))
{
	 
	function config_item($item)
	{
		static $_config;

		if (empty($_config))
		{
			// references cannot be directly assigned to static variables, so we use an array
			$_config[0] =& get_config();
		}

		return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
	}
}

if (!function_exists('get_option')) {
function get_option($key, $value = ""){
	 
		$db  = \Config\Database::connect('default');
		$query = $db->query("select value from ".OPTIONS." where name='{$key}'");
		$result =  $query->getRow();
	
		$option =  $result;
		
		 if(empty($option)){
		 	$db->table(OPTIONS)->insert(array("name" => $key, "value" => $value));
		 	return $value;
		 }else{
			return $option->value;
		} 
		$db->close();
		
	}
}

if (!function_exists('cn')) {
	function cn($module=""){
		return PATH."/".$module;
	};
}


if(!function_exists('get_theme')){
	function get_theme(){
		$theme_config = APPPATH."../themes/config.json";
		$theme = "basic";
		if(file_exists($theme_config)){	
			$config = file_get_contents($theme_config);
			$config = json_decode($config);
			if(is_object($config) && isset($config->theme)){
				$theme = $config->theme;
			}
		}
		return $theme;
	}
}
	if (!function_exists('getEmailTemplate')) {
		function getEmailTemplate($key = ""){
			$result = (object)array();
			$result->subject = '';
			$result->content = '';
			if(!empty($key)){
				switch ($key) {
	
					case 'payment':
						$result->subject = "{{website_name}} -  Thank You! Deposit Payment Received";
						$result->content = "<p>Hi<strong> {{user_firstname}}! </strong></p><p>We&#39;ve just received your final remittance and would like to thank you. We appreciate your diligence in adding funds to your balance in our service.</p><p>It has been a pleasure doing business with you. We wish you the best of luck.</p><p>Thanks and Best Regards!</p>";
						return $result;
						break;
	
					case 'verify':
						$result->subject = "{{website_name}} - Please validate your account";
						$result->content = "<p><strong>Welcome to {{website_name}}! </strong></p><p>Hello <strong>{{user_firstname}}</strong>!</p><p> Thank you for joining! We&#39;re glad to have you as community member, and we&#39;re stocked for you to start exploring our service.  If you don&#39;t verify your address, you won&#39;t be able to create a User Account.</p><p>  All you need to do is activate your account by click this link: <br>  {{activation_link}} </p><p>Thanks and Best Regards!</p>";
						return $result;
						break;
	
					case 'welcome':
						$result->subject = "{{website_name}} - Getting Started with Our Service!";
						$result->content = "<p><strong>Welcome to {{website_name}}! </strong></p><p>Hello <strong>{{user_firstname}}</strong>!</p><p>Congratulations! <br>You have successfully signed up for our service - {{website_name}} with follow data</p><ul><li>Firstname: {{user_firstname}}</li><li>Lastname: {{user_lastname}}</li><li>Email: {{user_email}}</li><li>Timezone: {{user_timezone}}</li></ul><p>We want to exceed your expectations, so please do not hesitate to reach out at any time if you have any questions or concerns. We look to working with you.</p><p>Best Regards,</p>";
						return $result;
						break;
	
					case 'forgot_password':
						$result->subject = "{{website_name}} - Password Recovery";
						$result->content = "<p>Hi<strong> {{user_firstname}}! </strong></p><p>Somebody (hopefully you) requested a new password for your account. </p><p>No changes have been made to your account yet. <br>You can reset your password by click this link: <br>{{recovery_password_link}}</p><p>If you did not request a password reset, no further action is required. </p><p>Thanks and Best Regards!</p>                ";
						return $result;
						break;
	
					case 'new_user':
						$result->subject = "{{website_name}} - New Registration";
						$result->content = "<p>Hi Admin!</p><p>Someone signed up in <strong>{{website_name}}</strong> with follow data</p><ul><li>Firstname {{user_firstname}}</li><li>Lastname: {{user_lastname}}</li><li>Email: {{user_email}}</li><li>Timezone: {{user_timezone}}</li></ul> ";
						return $result;
						break;
	
					case 'order_success':
						$result->subject = "{{website_name}} - New Order";
						$result->content = "<p><strong>Hi Admin!</strong></p><p>Someone have already placed order successfully  in <strong>{{website_name}}</strong> with follow data:</p><ul><li>Email: <strong>{{user_email}}</strong></li><li>OrderID:    <strong>{{order_id}}</strong>  </li><li>Total Charge:  <strong>{{currency_symbol}}{{total_charge}}</strong>    </li></ul>";
						return $result;
						break;
	
					case 'new_manual_order':
						$result->subject = "{{website_name}} - New Manual Order";
						$result->content = "<p><strong>Hi Admin!</strong></p><p>Someone have already placed order successfully in <strong>{{website_name}}</strong> with manual order:</p><ul><li>Email: <strong>{{user_email}}</strong></li><li>OrderID:    <strong>{{order_id}}</strong>  </li></ul>";
						return $result;
						break;
						
				}
			}
			return $result;
		}
	}
	/**
 * Get Value from JSone string
 * @param $dataJson, $key
 * @return index of key
 */
if(!function_exists('get_value')){
	function get_value($dataJson, $key, $parseArray = false, $return = false){
		if(is_string($dataJson)){
			$dataJson = json_decode($dataJson);
		}

		if(is_object($dataJson)){
			if(isset($dataJson->$key)){
				if($parseArray){
					return (array)$dataJson->$key;
				}else{
					return $dataJson->$key;
				}
			}
		}else if(is_array($dataJson)){
			if(isset($dataJson[$key])){
				return $dataJson[$key];
			}
		}else{
			return $dataJson;
		}
		
		return $return;
	}
}

if(!function_exists('get_secure')){
	function get_secure($name = ""){
		$CI =  service('request');//&get_instance();
		return filter_input_xss($CI->getGet(trim($name)));
	}
}

if(!function_exists('remove_empty_value')){
	function remove_empty_value($data){
		if(!empty($data)){
			return array_filter($data, function($value) {
			    return ($value !== null && $value !== false && $value !== ''); 
			});
		}else{
			return false;
		}
	}
}

if(!function_exists('get_random_value')){
	function get_random_value($data){
		if(is_array($data) && !empty($data)){
			$index = array_rand($data);
			return $data[$index];
		}else{
			return false;
		}
	}
}

if(!function_exists('get_random_values')){
	function get_random_values($data, $limit){
		if(is_array($data) && !empty($data)){
			shuffle($data);
			if(count($data) < $limit){
				$limit = count($data);
			}

			return array_slice($data, 0, $limit);
		}else{
			return false;
		}
	}
}

if(!function_exists('specialchar_decode')){
	function specialchar_decode($input){
		$input = str_replace("\\'", "'", $input);
		$input = str_replace('\"', '"', $input);
        $input = htmlspecialchars_decode($input, ENT_QUOTES);
		return $input;
	}
}

if(!function_exists('filter_input_xss')){
	function filter_input_xss($input){
        $input = htmlspecialchars($input, ENT_QUOTES);
		return $input;
	}
}

if(!function_exists('ms')){
	function ms($array){
		print_r(json_encode($array));
		exit(0);
	}
}

/**
 * @param string $status error/success
 * @param string $message
 * @return Print Message
 */
if(!function_exists('_validation')){
	function _validation($status, $ms){
		ms(['status' => $status, 'message' => $ms]);
	}
}



if(!function_exists("get_curl")){
	function get_curl($url){
		$user_agent='Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3';

		$headers = array
		(
		    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		    'Accept-Language: en-US,fr;q=0.8;q=0.6,en;q=0.4,ar;q=0.2',
		    'Accept-Encoding: gzip,deflate',
		    'Accept-Charset: utf-8;q=0.7,*;q=0.7',
		    'cookie:datr=; locale=en_US; sb=; pl=n; lu=gA; c_user=; xs=; act=; presence='
		); 

        $ch = curl_init( $url );

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_POST, false);     
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_REFERER, base_url());

        $result = curl_exec( $ch );
       
        curl_close( $ch );

        return $result;
	}
}


if (!function_exists('get_json_content')) {
    function get_json_content($path , $data = []) {
    	if ($data) {
    		$arrContextOptions = array(
				"ssl"=>array(
					"verify_peer" => false,
					"verify_peer_name" => false,
				),
			);  
			return json_decode(file_get_contents($path.'?'.http_build_query($data), false, stream_context_create($arrContextOptions) ));
    	}else{
	        if (file_exists($path)) {
				return json_decode(file_get_contents($path));
			}else{
				return false;
			}
    	}
    }
}

if (!function_exists('ids')) {
	function ids(){
        $encrypter = \Config\Services::encrypter();
		//$CI = &get_instance();
		return md5($encrypter->encrypt(time()));
	};
}

if (!function_exists('session')){
	function session($input){
		$CI =  \Config\Services::session();

		if ($input == 'uid' && session('uid_tmp')) {
			return session('uid_tmp');
		}
		return $CI->get($input);
	}
}

if (!function_exists('set_session')){
	function set_session($name,$input){
		$CI =  \Config\Services::session();
		return $CI->set($name,$input);
	}
}

if (!function_exists('unset_session')){
	function unset_session($name){
		$CI =  \Config\Services::session();
		return $CI->remove($name);
	}
}

if (!function_exists('encrypt_encode')) {
	function encrypt_encode($text){
	 
		return \Config\Services::encrypter()->encrypt($text);
	};
}

if (!function_exists('encrypt_decode')) {
	function encrypt_decode($key){
	 
		return \Config\Services::encrypter()->decrypt($key);
	};
}

if (!function_exists('segment')){
	function segment($index){ 
		$request = \Config\Services::request();

        if ($request->uri->getTotalSegments() >= $index && $request->uri->getSegment($index))
        {
            return $request->uri->getSegment($index);
        }
        else
        {
            return false;
        }
		//$CI = service('uri');
        //return $this->request->uri->getSegment(1);//$CI->getSegment($index);
	}
}

if (!function_exists('ini_params')) {
	function ini_params($type){
		switch ($type) {
			case '1':
				return ['type' => base64_decode('aW5zdGFsbA==') , 'main' => 1, base64_decode('ZG9tYWlu') => urlencode(base_url())];
				break;
			case '2':
				return ['type' => 'upgrade', base64_decode('ZG9tYWlu') => urlencode(base_url())];
				break;
			case '3':
				return ['type' => base64_decode('aW5zdGFsbA==') , 'main' => 0, base64_decode('ZG9tYWlu') => urlencode(base_url())];
				break;	
		}
	}
} 

function __curl($url, $zipPath = ""){
	$zipResource = fopen($zipPath, "w");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch, CURLOPT_FILE, $zipResource);
	$page = curl_exec($ch);
	if(!$page) {
		ms(array(
			"status" 	=> "error",
			"message"   => "Error :- ".curl_error($ch),
		));
	}
	curl_close($ch);
}

if(!function_exists("__inst")){
	function _inst($result){
		if (empty($result)) {
			ms(array(
				"status"  => "error", 
				"message" => 'There was an error processing your request. Please contact author to get a support'
			));
		}
		if ((isset($result->status) &&  $result->status == 'error')) {
			ms(array(
				"status"  => "error", 
				"message" => $result->message
			));
		}
		if (isset($result->status) &&  $result->status = 'success') {
			$result_object = explode("{|}", $result->response);
			$file_path = 'files.zip';
			__curl(base64_decode($result_object[2]), $file_path);
			if (filesize($file_path) <= 1) {
				ms(array(
					"status" 	=> "error",
					"message"   => "There was an error processing your request. Please contact author to get a support",
				));
			}
			$zip = new ZipArchive;
			if($zip->open($file_path) != TRUE){
				ms(array(
					"status" 	=> "error",
					"message"   => "Error :- Unable to open the Zip File",
				));
			} 
			$zip->extractTo("./");
			$zip->close();
			@unlink($file_path);
			return $result_object;
		}
	}
}

function extract_zip_file($output_filename){
	$zip = new ZipArchive;
	$extractPath = $output_filename;
	if($zip->open($zipFile) != "true"){
		ms(array(
			"status" 	=> "error",
			"message"   => "Error :- Unable to open the Zip File",
		));
	} 
	$zip->extractTo($extractPath);
	$zip->close();
}


if (!function_exists('cn')) {
	function cn($module=""){
		return PATH.$module;
	};
}

if (!function_exists('load_404')) {
	function load_404(){
		//$CI = &get_instance();
		return	view("errors/html/error_404.php");
	};
}

if (!function_exists('time_elapsed_string')) {
	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . lang($v . ($diff->$k > 1 ? 's' : ''));
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' '.lang('ago') : lang('just_now');
	}
}

if (!function_exists('ajax_page')) {
	function ajax_page(){
		//$CI = &get_instance();
		if(!post()){
			//$CI = &get_instance();
			view("errors/html/error_404.php");
			return false;
		}else{
			return true;
		}
	};
}

if (!function_exists('require_all')) {
	function require_all($dir = "", $depth=0) {
		if($dir == ""){
			$segment = segment(1);
			$dir = APPPATH."../public/".$segment."/config/constants/";
		}

	    // require all php files
	    $scan = glob("$dir/*");
	    foreach ($scan as $path) {
	        if (preg_match('/\.php$/', $path)) {
	            require_once $path;
	        }
	        elseif (is_dir($path)) {
	            require_all($path, $depth+1);
	        }
	    }
	}
}

if (!function_exists('get_name_folder_from_dir')) {
	function get_name_folder_from_dir($dir = "") {
		if($dir == ""){
			$dir = APPPATH."../themes";
		}
	    // require all php files
	    $dirs = glob($dir . '/*' , GLOB_ONLYDIR);

	    $folder_names = [];
	    foreach ($dirs as $folder_path) {
		    $folder_names[] = basename($folder_path ); 
		}

		if (!empty($folder_names)) {
			return $folder_names;
		}else{
			return [];
		}
	}
}

if (!function_exists('get_all_file_from_folder')) {
	function get_all_file_from_folder($dir = "") {
		$data = array();
		if($dir == ""){
			$segment = segment(1);
			$dir = APPPATH."../public/".$segment."/config/constants/";
		}

	    // require all php files
	    $scan = glob("$dir/*");
	    foreach ($scan as $path) {
	        if (preg_match('/\.php$/', $path)) {
	        	$data[] = $path;
	        }
	    }

	    return $data;
	}
}



// if (!function_exists('get_path_module')) {
// 	function get_path_module(){
// 		$CI = &get_instance();
// 		return APPPATH.'modules/'.$CI->router->fetch_module().'/';
// 	}
// }

if (!function_exists('folder_size')) {
	function folder_size($dir){
	    $size = 0;
	    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
	        $size += is_file($each) ? filesize($each) : folderSize($each);
	    }
	    return $size;
	}
}

if (!function_exists('pr')) {
    function pr($data, $type = 0) {
        print '<pre>';
        print_r($data);
        print '</pre>';
        if ($type != 0) {
            exit();
        }
    }
}

if(!function_exists('pr_sql')){
	function pr_sql($type=0){
		//$CI = &get_instance();
		$db = db_connect();
		$sql = $db->getLastQuery();
		pr($sql,$type);
	}
}

// escape output data before print
if(!function_exists('_echo')){
	function _echo($output, $is_image = TRUE){
		if ($is_image) {
			$output = htmlspecialchars($output);
		}
		echo $output;
	}
}

if(!function_exists("convert_datetime")){
	function convert_datetime($datetime){
		return date("h:iA M d, Y", strtotime($datetime));
	}
}

if(!function_exists("convert_date")){
	function convert_date($date){
		return date("M d, Y", strtotime($date));
	}
}

if(!function_exists("convert_datetime_sql")){
	function convert_datetime_sql($datetime){
		return date("Y-m-d H:i:s", get_to_time($datetime));
	}
}

if(!function_exists("convert_date_sql")){
	function convert_date_sql($date){
		return date("Y-m-d", get_to_time($date));
	}
}

if(!function_exists("validateDate")){
	function validateDate($date, $format = 'Y-m-d'){
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
}

if(!function_exists("get_to_time")){
	function get_to_time($date){
		if(is_numeric($date)){
			return $date;
		}else{
			return strtotime(str_replace('/', '-', $date));
		}
	}
}

if(!function_exists("get_to_day")){
	function get_to_day($date, $fulltime = true){
		$strtime = strtotime(str_replace('/', '-', $date));
		if($fulltime){
			return date("Y-m-d H:i:s", $strtime);
		}else{
			return date("Y-m-d", $strtime);
		}
	}
}

if(!function_exists("row")){
	function row($data, $field){
		if(is_object($data)){
			if(isset($data->$field)){
				return $data->$field;
			}else{
				return "";
			}
		}

		if(is_array($data)){
			if(isset($data[$field])){
				return $data[$field];
			}else{
				return "";
			}
		}
	}
}


if (!function_exists('tz_convert')){
	function tz_convert($timezone) {
		date_default_timezone_set($timezone);
	  	$zones_array = array();
	  	$timestamp = time();
	  	foreach(timezone_identifiers_list() as $key => $zone) {
	   		if($zone == $timezone){
	   			return date('P', $timestamp);
	   		}
	  	}
		
	  	return false;
	}
}

if (!function_exists('get_line_with_string')){
	function get_line_with_string($fileName, $str) {
		if(is_file($fileName)){
	    	$lines = file($fileName);
		    foreach ($lines as $lineNumber => $line) {
		        if (strpos($line, $str) !== false) {
		            return trim(str_replace("/*", "", str_replace("*/", "", $line)));
		        }
		    }
		}else{
			$lines = $fileName;
		}
		
	    return false;
	}
}
	

if (!function_exists("echo_json_string")) {
	function echo_json_string($array){
		echo json_encode($array, JSON_PRETTY_PRINT);
		exit(0);
	}
}

	/**
	 * Replace all merge fields and return the template for email
	 * 
	 *
	 */
	if (!function_exists('parse_merge_fields')) {
	
		function parse_merge_fields($content = '', $merge_fields = '', $replace_main_content = true){
			if ($replace_main_content) {
				$template = file_get_contents(APPPATH.'/libraries/PHPMailer/template.php');
			}else{
				$template = $content;
			}
	
			$search = array(
				"{{email_content}}" => $content,
				"{{website_logo}}"  => get_option('website_logo', BASE."assets/images/logo.png"),
				"{{website_link}}"  => PATH,
				"{{website_name}}"  => get_option("website_name", "Smartpanel PANEL"),
				"{{copyright}}"     => get_option('copy_right_content',"Copyright &copy; 2020 - SmartPanel"),
			);
	
			if (is_array($merge_fields)) {
				$search = array_merge($search, $merge_fields);
			}
	
			foreach ($search as $key => $val) {
				if (strrpos($template, $key) !== false) {
					$template = str_replace($key, $val, $template);
				}
			}
			return $template;
		}
	}


	if(!function_exists("get_field")){
		function get_field($table, $where = array(), $field=null){
		
			$db = db_connect();
			$builder = $db->table($table);
			$builder->select($field);
			$builder->where($where);
			$query = $builder->get();
		 	$result =  $query->getResultArray();
			//print_r($where);//$db->getLastQuery();
			$db->close();
		
			if(count($result)>0){
			$item =  $result[0];
			}else {
				$item = $result;
			}
		
		
			 
			if(!empty($item) && isset($item[$field])){
				return $item[$field];
			}else{
				return false;
			}
		}
	}

	if(!function_exists("get_current_user_data")){
		function get_current_user_data($id = ""){
			if ($id == "") {
				$id = session("uid");
			}
			//$CI = &get_instance();
			$db = db_connect();
			$builder = $db->table(USERS);
			$builder->select("*");
			$builder->where("id",$id);
			$query = $builder->get();

		 	$user =  count($query->getResultArray())>0?$query->getResultArray()[0]:$query->getResultArray();
		 
 
			if(!empty($user)){
				return $user;
			}else{
				return false;
			}
		}
	}
	if (!function_exists('allowed_search_bar')) {
		function allowed_search_bar($module = ""){
			if (get_role('user')) {
				$allowed_search = ['subscriptions', 'dripfeed', 'log', 'tickets', 'services'];
			}else{
				$allowed_search = ['user_block_ip', 'user_logs', 'user_mail_logs', 'services', 'subscriptions', 'dripfeed', 'users','tickets', 'faqs', 'log', 'search', 'transactions', 'subscribers'];
			}
			if (in_array($module, $allowed_search)) {
				return true;
			}
			return false;
		}
	}
	
	if(!function_exists('get_role')){
		function get_role($role_type = "", $id = ""){
		
			if (isset($_SESSION['user_current_info']['role']) && $_SESSION['user_current_info']['role'] != '') {
				$role = $_SESSION['user_current_info']['role'];
			}else{
			
				if(empty($id))
				$user = get_current_user_data( session('uid') );
				else
				$user =get_current_user_data($id);
				
			if (!empty($user)) {
				
				$data_session = array(
					'user'=>$user,
					 'role'       => $user['role'],
					 'email'      => $user['email'],
					 'first_name' => $user['first_name'],
					 'last_name'  => $user['last_name'],
					 'timezone'   => $user['timezone'],
				);
				set_session('user_current_info', $data_session);
				$role = $user['role'];
			}else{
				return false;
			}
		}
			if($role != '' && $role == $role_type){
				return true;
			}else{
				return false;
			}
		
	}
	}

	if (!function_exists('segment')){
		function segment($index){ 
			$CI = service('uri');
			return $CI->getSegment($index);
		}
	}

	
if(!function_exists("get_client_ip")){
	function get_client_ip() {
	    if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');

			if (strstr($ip, ',')) {
				$tmp = explode(',', $ip);
				$ip = trim($tmp[0]);
			}
		} else {
			$ip = getenv('REMOTE_ADDR');
		}

	    return $ip;
	}
}

if(!function_exists("info_client_ip")){
	function info_client_ip(){
		$result = get_curl("https://timezoneapi.io/api/ip");

		$result = json_decode($result);
		if(!empty($result)){
			return $result;
		}
		return false;
	}
}

function get_location_info_by_ip($ip_address){
	$result = (object)array();
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_address));    
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result->country     = $ip_data->geoplugin_countryName;
        $result->timezone    = $ip_data->geoplugin_timezone;
        $result->city        = $ip_data->geoplugin_city;
    }else{
    	$result->country     = 'Unknown';
        $result->timezone    = 'Unknown';
        $result->city        = 'Unknown';
    }
    return $result;
}

if(!function_exists('truncate_string')){
	function truncate_string($string = "", $max_length = 50, $ellipsis = "...", $trim = true) {
	    $max_length = (int)$max_length;
	    if ($max_length < 1) {
	        $max_length = 50;
	    }

	    if (!is_string($string)) {
	        $string = "";
	    }

	    if ($trim) {
	        $string = trim($string);
	    }

	    if (!is_string($ellipsis)) {
	        $ellipsis = "...";
	    }

	    $string_length = mb_strlen($string);
	    $ellipsis_length = mb_strlen($ellipsis);
	    if($string_length > $max_length){
	        if ($ellipsis_length >= $max_length) {
	            $string = mb_substr($ellipsis, 0, $max_length);
	        } else {
	            $string = mb_substr($string, 0, $max_length - $ellipsis_length)
	                    . $ellipsis;
	        }
	    }

	    return $string;
	}
}
if(!function_exists('convert_timezone')){
	function convert_timezone($datetime, $case, $uid = ''){
		$zonesystem  = date_default_timezone_get();

		if ($uid != '') {
			$zoneuser    = get_user_timezone($uid);
		}else{
			if (isset($_SESSION['user_current_info']['timezone']) && $_SESSION['user_current_info']['timezone'] != '') {
				$zoneuser = $_SESSION['user_current_info']['timezone'];
			}else{
				$zoneuser    = get_user_timezone(session('uid'));
			}
		}

		switch ($case) {
			case 'user':
				$currentTZ   = new DateTimeZone($zonesystem);
				$newTZ       = "";//new DateTimeZone($zoneuser);
				break;

			case 'system':
				$currentTZ   = new DateTimeZone($zoneuser);
				$newTZ       = "";//new DateTimeZone($zonesystem);
				break;
		}
		
		$date        = new DateTime( $datetime, $currentTZ );
		$date->setTimezone(  $currentTZ);//$newTZ );
		return $date->format('Y-m-d H:i:s');
	}
}
if(!function_exists("get_user_timezone")){
	function get_user_timezone($uid = null){
		if(!empty($uid)){
			$userZone = get_field(USERS, ['id' => $uid], 'timezone');
			if(!empty($userZone)){
				return $userZone;
			}
		}
		return false;
	}
}

if(!function_exists("order_status_title")){
	function order_status_title($key){
		switch ($key) {
			case 'completed':
				return lang("Completed");
				break;			
			case 'processing':
				return lang("Processing");
				break;			
			case 'inprogress':
				return lang("In_progress");
				break;			
			case 'pending':
				return lang('Pending');
				break;			
			case 'partial':
				return lang("Partial");
				break;			
			case 'canceled':
				return lang("Canceled");
				break;	

			case 'refunded':
				return lang("Refunded");
				break;	

			case 'active':
				return lang("Active");
				break;	

			case 'awaiting':
				return lang("Awaiting");
				break;	
			case 'rejected':
					return lang("Rejected");
					break;
	
			/*----------  subscriptions  ----------*/

			case 'Active':
				return lang("Active");
				break;

			case 'Completed':
				return lang("Completed");
				break;

			case 'Paused':
				return lang("Paused");
				break;

			case 'Expired':
				return lang("Expired");
				break;

			case 'Canceled':
				return lang("Canceled");
				break;

			case 'fail':
				return lang("Fail");
				break;	

			case 'error':
				return lang("Error");
				break;						
		}
	}
}
if (!function_exists("order_status_array")) {
	function order_status_array(){
		$data = array('pending','processing','inprogress','completed','partial','canceled','refunded', 'awaiting', 'error');
		return $data;
	}
}
if (!function_exists("currency_format")) {
	function currency_format($number, $number_decimal = "", $decimalpoint = "", $separator = ""){
		$decimal = 2;

		if ($number_decimal == "") {
			$decimal = get_option('currency_decimal', 2);
		}else{
			$decimal = $number_decimal;
		}
		
		switch ($decimalpoint) {
			case 'dot':
				$decimalpoint = '.';
				break;
			case 'comma':
				$decimalpoint = ',';
				break;
			default:
				$decimalpoint = ".";
				break;
		}

		switch ($separator) {
			case 'dot':
				$separator = '.';
				break;
			case 'comma':
				$separator = ',';
				break;
			default:
				$separator = ',';
				break;
		}
		
		$number = number_format($number, $decimal, $decimalpoint, $separator);
		return $number;
	}
}

/*----------  Show custom metion  ----------*/
if (!function_exists('get_list_custom_mention')) {
	function get_list_custom_mention($order){
		switch ($order->service_type) {
			case 'custom_comments':
				$result = (object)array(
					'exists_list'     => true,
					'title'		      => lang('comments'),
					'list'	          => json_decode($order->comments)
				);
				break;
			
			case 'comment_likes':
				$result = (object)array(
					'exists_list'     => true,
					'title'		      => lang('username'),
					'list'	          => $order->username
				);
				break;

			case 'mentions_hashtag':
				$result = (object)array(
					'exists_list'     => true,
					'title'		      => lang('hashtag'),
					'list'	          => $order->hashtag
				);
				break;	

			case 'mentions_user_followers':
				$result = (object)array(
					'exists_list'     => true,
					'title'		      => lang('username'),
					'list'	          => $order->hashtag
				);
				break;
			
			default:
				$result = (object)array(
					'exists_list' => false,
				);
				break;
		}
		return $result;


	}
}
if (!function_exists('get_name_of_files_in_dir')) {
	function get_name_of_files_in_dir($path, $file_types = array('')){
		if (empty($file_types)) {
			$file_types = ['.php'];
		}
		$name_of_files = [];
		if ($path != "" && is_dir($path)) {
			$dir = new DirectoryIterator($path);
		 	foreach ($dir as $fileinfo) {
			    if (!$fileinfo->isDot()) {
			    	foreach ($file_types as $key => $row) {
	        			if (strrpos($fileinfo->getFilename(), $row)) {
	        				$name_of_files[] = basename($fileinfo->getFilename(), $row);
	        			}
			    	}
			    }
			}
		}
		return $name_of_files;
	}
}

if (!function_exists('get_payments_method')) {
	function get_payments_method(){
		$path = "modules/Addfunds/Controllers/";
		$payment_methods = array();
		if ($path != "") {
			$dir = new DirectoryIterator($path);
		 	foreach ($dir as $fileinfo) {
			    if (!$fileinfo->isDot()) {
			        if ($fileinfo->getFilename() != 'add_funds.php') {
			        	if (!in_array(basename($fileinfo->getFilename(), ".php"), ['paypal', 'stripe', 'two_checkout'])) {
			        		$payment_methods[] = basename($fileinfo->getFilename(), ".php");
			        	}
			        }
			    }
			}
			return $payment_methods;
		}
	}
	
}

if(!function_exists('get_current_url')){
	function get_current_url(){
	    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    if ($url == "") {
	    	$url = segment(1);
	    }
	    return $url;
	}
}

if (!function_exists("currency_codes")) {
	function currency_codes(){
		$data = array(
			"AUD" => "Australian dollar",
			"BRL" => "Brazilian dollar",
			"CAD" => "Canadian dollar",
			"CZK" => "Czech koruna",
			"DKK" => "Danish krone",
			"EUR" => "Euro",
			"HKD" => "Hong Kong dollar",
			"HUF" => "Hungarian forint",
			"INR" => "Indian rupee",
			"ILS" => "Israeli",
			"JPY" => "Japanese yen",
			"MYR" => "Malaysian ringgit",
			"MXN" => "Mexican peso",
			"TWD" => "New Taiwan dollar",
			"NZD" => "New Zealand dollar",
			"NOK" => "Norwegian krone",
			"PHP" => "Philippine peso",
			"PLN" => "Polish złoty",
			"GBP" => "Pound sterling",
			"RUB" => "Russian ruble",
			"SGD" => "Singapore dollar",
			"SEK" => "Swedish krona",
			"CHF" => "Swiss franc",
			"THB" => "Thai baht",
			"USD" => "United States dollar",
		);

		return $data;
	}
}

if(!function_exists("update_option")){
	function update_option($key, $value){
		//$CI = &get_instance();
		// $db = db_connect();
		// $builder = $db->table(USERS);
		// $builder->select("*");
		// $builder->where("id",$id);
		// $query = $builder->get();
		// if(empty($CI->help_model)){
		// 	$CI->load->model('model', 'help_model');
		// }
		
		$CI = new \App\Models\ExtendModel();
		
		$option = $CI->get("value", OPTIONS, "name = '{$key}'");
		if(empty($option)){
			$CI->common_insert(OPTIONS, array("name" => $key, "value" => $value));
		}else{
			$CI->common_update(OPTIONS, array("value" => $value), array("name" => $key));
		}
	}
}

function tz_list()
{
    $timezoneIdentifiers = DateTimeZone::listIdentifiers();
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));
 
    $tempTimezones = array();
    foreach ($timezoneIdentifiers as $timezoneIdentifier) {
        $currentTimezone = new DateTimeZone($timezoneIdentifier);
 
        $tempTimezones[] = array(
            'offset' => (int)$currentTimezone->getOffset($utcTime),
            'identifier' => $timezoneIdentifier
        );
    }
 
    // Sort the array by offset, identifier ascending
    usort($tempTimezones, function($a, $b) {
		return ($a['offset'] == $b['offset'])
			? strcmp($a['identifier'], $b['identifier'])
			: $a['offset'] - $b['offset'];
    });
 
	$timezoneList = array();
    foreach ($tempTimezones as $key => $tz) {
		$sign                       = ($tz['offset'] > 0) ? '+' : '-';
		$offset                     = gmdate('H:i', abs($tz['offset']));
        $timezoneList[$key]['time'] = '(UTC ' . $sign . $offset . ') ' . $tz['identifier'];
		$timezoneList[$key]['zone'] = 	$tz['identifier'];
    }
    return $timezoneList;
}
if(!function_exists("create_random_api_key")){
	function create_random_string_key($length = "") {
		if ($length == "") {
			$length = 32;
		}
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
if (!function_exists('payment_method_exists')) {
	function payment_method_exists($payment_gateway){
		$path_file1 = APPPATH."modules/setting/views/integrations/".$payment_gateway.".php";
        $path_file2 = APPPATH."modules/Addfunds/controllers/".$payment_gateway.".php";
       // echo $path_file2;
		if (file_exists($path_file1) && file_exists($path_file2)) {
        	return true;
        }
        return false;
	}
}


if (!function_exists('uploadimage')){
	function uploadimage($image_file_temp,$upload_folder,$image_new_name,$suffix){

       
        $headers = array('Accept' => 'application/json');
        $body = array("file"=>\Unirest\Request\Body::file($image_file_temp,mime_content_type($image_file_temp), $image_new_name),
        "path"=>$upload_folder,
        'needSrcImg' => 'true',
        'PNGBGColor' => '255-0-0',
        "options"=>'{"dimensions":[{"key":"'.session("uid").'", "height":400, "suffix":"'.$suffix.'", "name": "'.session("uid").'","conditionRatio":{"vertical":[2560,2048],"square":[3000,2000],"horizontal":[1600,960]}}]}',
        'srcImgName' => '{"dimensions": [{ "name": "'.session("uid").'" , "suffix": "-original" }]}',
        'provider' => 'aws',
        'appId' => 'EAAcyxgV1VRrUw7gjT7He40G6LLNdH-redemgo-com',
        'hot' => 'true');

        

        $response = \Unirest\Request::post(URL_UPLOADS,$headers,$body);

        //print_r($response->body);
        if($response->code==200){
            return array(
				 "status"=>true,
				 "image_name"=>CDN_UPLOAD.$response->body->location[0]->relativePath
			 );

        }else {
			return array(
				"status"=>false,
				"image_name"=>""
			);
		}
		//return false;
        
    }
}
 
    
if (!function_exists('push_message')){
	function push_message($charge){
    $options = array(
            'cluster' => getenv('PUSHER_CLUSTER'),
            'useTLS' => true
        );
		
        //echo APPPATH .'vendor/autoload.php';
         $pusher = new Pusher\Pusher(
              getenv('PUSHER_KEY'),
              getenv('PUSHER_SECRET'),
              getenv('PUSHER_ID'),
              $options
         );

         
         $pusher->trigger('my-channel','my-event',$charge);
		}
	}
 
/********************************* */

	if(strpos(current_url(), "cron") === FALSE && get_option('enable_https', 0) == 1){
		if($_SERVER["HTTPS"] != "on"){
			define("PATH", base_url());
			if(strpos(base_url(), "https") === FALSE){
				redirect(str_replace("http://", "https://", base_url()));
			}
		}else{
			define("PATH", str_replace("http://", "https://", base_url()));
		}
	}else{
		define("PATH", base_url());
	}

	define('BASE',base_url()."/");
	 