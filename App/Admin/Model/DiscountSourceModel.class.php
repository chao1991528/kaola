<?php
namespace Admin\Model;
use Think\Model;

class DiscountSourceModel extends Model{
    
	
	public function getMapping($where = "is_delete = 0 and is_valid = 1"){
		$mapping = array();
		$result = $this->where($where)->field(array('id, source_name'))->select();
		foreach ($result as $item){
			$mapping[$item['id']] = $item['source_name'];
		}
		return $mapping;
	}
	
}

?>