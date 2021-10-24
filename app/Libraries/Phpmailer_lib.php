<?php 

//

/**
 * Include PHPMailer
 */
class Phpmailer_lib {
	function __construct(){
		require_once 'PHPMailer/src/Exception.php';
		require_once 'PHPMailer/src/PHPMailer.php';
		require_once 'PHPMailer/src/SMTP.php';
	}
}
