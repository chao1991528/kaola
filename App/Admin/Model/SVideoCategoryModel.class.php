<?php
namespace Admin\Model;
use Think\Model;

class SVideoCategoryModel extends Model{
    
	
	public function getMapping($where = "is_delete = 0 and is_valid = 1"){
		$mapping = array();
		$result = $this->where($where)->field(array('id, category_name'))->order('sort')->select();
		foreach ($result as $item){
			$mapping[$item['id']] = $item['category_name'];
		}
		return $mapping;
	}
	
}

?>