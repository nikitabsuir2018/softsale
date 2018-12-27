<?php
class mCatalog extends CI_Model {
	
	function all()
	{
		$arr = $this->db->get('catalog_view')->result();
		if(count($arr) > 0):
			return $arr;
		else:
			return false;
		endif;		
	}

	function get($id)
	{
        $arr = $this->db->get_where('software', array('id'=>$id))->result();
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
	
	function insert($arr)
	{
        if($this->db->insert('software', $arr)):
			return $this->db->insert_id();
		else:
			return 0;
		endif;
	}
	
	function update($arr, $where)
    {
        return $this->db->update('software', $arr, $where);
    }

	function delete($where)
	{
        return $this->db->delete('software', $where);
	}

}
