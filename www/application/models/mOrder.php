<?php
class mOrder extends CI_Model {
	
	function get_where($where)
	{
		$this->db->order_by('dates', 'DESC');
		$arr = $this->db->get_where('order_view', $where)->result();
		if(count($arr) > 0):
			return $arr;
		else:
			return array();
		endif;		
	}

	function get($id)
	{
        $arr = $this->db->get_where('order_view', array('id'=>$id))->result();
		if(count($arr) > 0):
			return $arr[0];
		else:
			return false;
		endif;
	}

	function get_software($id)
	{
        $arr = $this->db->get_where('software', array('id'=>$id))->result();
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
	
	function insert_batch($arr)
	{
		return $this->db->insert_batch('orders', $arr);
	}
	
	function update($arr, $where)
    {
        return $this->db->update('orders', $arr, $where);
    }

}
