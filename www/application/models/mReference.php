<?php
class mReference extends CI_Model {
	
	function all($table)
	{
		$arr = $this->db->get($table)->result();
		if(count($arr) > 0):
			return $arr;
		else:
			return array();
		endif;		
	}

	function get($table, $id)
	{
        $arr = $this->db->get_where($table, array('id'=>$id))->result();
		if(count($arr) > 0):
			return $arr[0];
		else:
			return false;
		endif;
	}
    
    function cnt_where($table, $where)
    {
        return count($this->db->get_where($table, $where)->result());
    }
	
	function insert($table, $arr)
	{
        if($this->db->insert($table, $arr)):
			return $this->db->insert_id();
		else:
			return 0;
		endif;
	}
	
	function update($table, $arr, $where)
    {
        return $this->db->update($table, $arr, $where);
    }

	function delete($table, $where)
	{
        return $this->db->delete($table, $where);
	}

}
