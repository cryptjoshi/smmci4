<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
if (defined('TIMEZONE')) {
    date_default_timezone_set(TIMEZONE);
}

/**
 *
 * Contant for PHPASS_HASH_STRENGTH
 *
 */
define("PHPASS_HASH_STRENGTH", 8);
define('PHPASS_HASH_PORTABLE', FALSE);

define("TEMP_PATH", APPPATH . "../assets/tmp");
define('DEMO_VERSION', false);
define('CHILD_PANEL', FALSE);
define("NOW", date("Y-m-d H:i:s"));


define("CATEGORIES", "categories");
define("API_PROVIDERS", "api_providers");
define("SERVICES", "services");
define("ORDER", "orders");
define("REFILLS", "refills");
define("CHILDPANEL", "childpanels");
define("SUBSCRIPTIONS", "subscriptions");
define("FAQS", "faqs");
define("TICKETS", "tickets");
define("TICKET_MESSAGES", "ticket_messages");

define("NEWS", "general_news");
define("COUPONS", "general_coupons");
define("OPTIONS", "general_options");
define("TRANSACTION_LOGS", "general_transaction_logs");
define("PURCHASE", "general_purchase");
define("LANGUAGE", "general_lang");
define("LANGUAGE_LIST", "general_lang_list");

define("USERS", "general_users");
define("CREDITCARDS","creditcards");
define("USERS_PRICE", "general_users_price");
define("USER_LOGS", "general_user_logs");
define("USER_BLOCK_IP", "general_user_block_ip");
define("USER_MAIL_LOGS", "general_user_mail_logs");
define("SUBSCRIBERS", "general_subscribers");

define("PAYMENTS_METHOD", "payments");
define("PAYMENTS_BONUSES", "payments_bonus");
define('CDN_UPLOAD',getenv('CDN_UPLOAD'));
define('URL_UPLOADS',getenv('UPLOAD_PATH'));
define('RAPID_ENDPOINT',getenv('RAPID_URL'));
define('SUPER_KEY',getenv('SUPER_KEY'));
