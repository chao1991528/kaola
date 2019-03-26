<?php
namespace Admin\Model;
use Think\Model;

class OpenScreenSizeModel extends Model{
    
	
	public function getMapping($where = "is_delete = 0 and is_valid = 1"){
		$mapping = array();
		$result = $this->where($where)->field(array('id, size_name'))->select();
		foreach ($result as $item){
			$mapping[$item['id']] = $item['size_name'];
		}
		return $mapping;
	}
	
}

?>