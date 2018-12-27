<?php
class mUser extends CI_Model {
	
	function login($login, $password)
	{
        $arr = $this->db->get_where('user_view', array('login'=>$login, 'password'=>md5($password)))->result();
		if(count($arr) > 0):
			$user = $arr[0];
			$uid = uniqid(md5($user->login));
			if($this->db->update('users', array('token' => $uid), array('id' => $user->id))):
				$user->token = $uid;
				return $user;
			else:
				return false;
			endif;
		else:
			return false;
		endif;
	}

	function token($token)
	{
        $arr = $this->db->get_where('user_view', array('token'=>$token))->result();
		if(count($arr) > 0):
			return $arr[0];
		else:
			return false;
		endif;
	}

	function all()
	{
		$arr = $this->db->get('user_view')->result();
		if(count($arr) > 0):
			return $arr;
		else:
			return false;
		endif;		
	}

	function get($id)
	{
        $arr = $this->db->get_where('users', array('id'=>$id))->result();
		if(count($arr) > 0):
			return $arr[0];
		else:
			return false;
		endif;
	}
    
    function ref_list($table)
    {
		$query = $this->db->get($table);
		$arr = array();
		foreach($query->result() as $row):
			$arr[] = array(
				'id' => $row->id,
				'name' => $row->name
			);
		endforeach;
		return $arr;    
    }

    function cnt_where($table, $where)
    {
        return count($this->db->get_where($table, $where)->result());
    }
	
	function insert($arr)
	{
        if($this->db->insert('users', $arr)):
			return $this->db->insert_id();
		else:
			return 0;
		endif;
	}
	
	function update($arr, $where)
    {
        return $this->db->update('users', $arr, $where);
    }

	function delete($where)
	{
        return $this->db->delete('users', $where);
	}

}
