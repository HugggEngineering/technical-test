<?php
namespace App\Repositories;
 
use App\Hug;
use App\Message;
 
class HugggRepository extends Repository {

	
    function model() {
        return 'App\Hug';
    }
    

	public function all( $where = [], $sort = null, $limit = 20 ) {
        return $transactions;
		
	}

    public function find($id, $columns = array('*')){

    }
 
    public function findBy($field, $value, $columns = array('*')){

    }
}
