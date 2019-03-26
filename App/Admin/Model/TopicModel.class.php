<?php
namespace Admin\Model;
use Think\Model;

class TopicModel extends Model{
    
	
	public function getMapping($where = "is_delete = 0 and is_valid = 1"){
		$mapping = array();
		$result = $this->where($where)->field(array('id, name'))->order('id desc')->select();
		foreach ($result as $item){
			$mapping[$item['id']] = $item['name'];
		}
		return $mapping;
	}
	
}

?>