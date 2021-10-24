<?php namespace Modules\About\Models;
use CodeIgniter\Model;
class AboutModel extends Model {
   protected $DBGroup = 'default';
   protected $table = 'orders';
   protected $primaryKey = 'id';
   protected $returnType = 'array';
   protected $useTimestamps = true;
   protected $allowedFields = ['id','ids','type'];
//    protected $createdField = 'created';
//    protected $updatedField = 'changed';
   
    public function __construct(){
        parent::__construct();
        //$this->tb_order = ORDER;
       
    }

    public function getData(){
        
         //var_dump($this->db);
        //$check_item_refill = $db->query(`SELECT name FROM {$this->tb_order} LIMIT 1`);
        return array(
            'title' => 'Home Page', 
            'view' => 'land/home'
        );
    }
}