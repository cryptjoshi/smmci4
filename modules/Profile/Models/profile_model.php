<?php
namespace Modules\Profile\Models;
use App\Models\ExtendModel;
class profile_model extends ExtendModel {
	public $tb_users;

	public function __construct(){
		parent::__construct();
		$this->tb_users = USERS;
	}

}
