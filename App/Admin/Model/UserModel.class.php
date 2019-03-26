<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model{
    const USER_SESSION = "user";
    /**
     * 检测登录用户名和密码
     * @param unknown $username
     * @param unknown $password
     * @return \Think\mixed|boolean
     */
    public function checkLogin($username, $password)
    {
        $where = $result = array();
        $where['user_number'] = $username;
        $where['user_password'] = md5($password);
        $where['is_valid'] = 1;
        $result = $this->where($where)->find();
        if (!empty($result) && $result['user_password'] == $where['user_password']) {
            $role_list = C('ROLE_LIST');
            $result['rolename'] = $role_list[$result['role_id']];
            session(self::USER_SESSION, $result);
            return true;
        }
        return false;
    }

    private function _setLoginLog($userId){
        $data = array();
        $data['login_uid'] = $userId;
        $data['login_time'] = date('Y-m-d H:i:s');
        $data['login_ip'] = $_SERVER['REMOTE_ADDR'];
        M('UserLoginLog')->add($data);
    }

    public function isLogin(){
	    $userSession =  session(self::USER_SESSION);
	    return empty($userSession) ? false : $userSession;
    }
	
	public function getUserMapping(){
		$mapping = array();
		$result = $this->field(array('id, user_number'))->select();
		foreach ($result as $item){
			$mapping[$item['id']] = $item['user_number'];
		}
		return $mapping;
	}
	
}

?>