<?php
// Каталог
class catalog extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mUser');
		$this->load->model('mCatalog');
	}
	private function array_convert_to_list($arr)
	{
		$out = array();
		$arr = array_unique($arr);
		foreach ($arr as $key => $value):
			$out[] = array(
				'id' => $key,
				'name' => $value
			);
		endforeach;
		return $out;
	}
	// Список ПО
	function items_list()
	{
		$out = array(
			'software' => array(),
			'categories' => array(),
			'developers' => array(),
			'sellers' => array()
		);
		$categories = array();
		$developers = array();
		$sellers = array();
		$json = json_decode(file_get_contents('php://input'), true);
		$category_id = (int)$json['category_id'];
		$developer_id = (int)$json['developer_id'];
		$seller_id = (int)$json['seller_id'];
		$res = $this->mCatalog->all();
		if (!empty($res)):
			foreach ($res as $row):
				$flag = true;
				$flag = ($category_id > 0 && $row->category_id != $category_id) ? false : $flag;
				$flag = ($developer_id > 0 && $row->developer_id != $developer_id) ? false : $flag;
				$flag = ($seller_id > 0 && $row->seller_id != $seller_id) ? false : $flag;
				if ($flag):
					$row->price = (float) $row->price;
					$out['software'][] = (array) $row;
				endif;
				$categories[$row->category_id] = $row->category_name;
				$developers[$row->developer_id] = $row->developer_name;
				$sellers[$row->seller_id] = $row->seller_name;
			endforeach;
			$out['categories'] = $this->array_convert_to_list($categories);
			$out['developers'] = $this->array_convert_to_list($developers);
			$out['sellers'] = $this->array_convert_to_list($sellers);
			$this->load->model('mReference');
			$out['categories_list'] = $this->mReference->all('categories');
			$out['developers_list'] = $this->mReference->all('developers');
		endif;
		echo json_encode($out);
	}
	// Обработка сохранения данных
	function item_save()
	{
		$out = array(
			'message' => 'Авторизация неудачна!',
			'is_logged' => false
		);
		$json = json_decode(file_get_contents('php://input'), true);
		$token = trim(htmlspecialchars(strip_tags($json['token'])));
		$res = $this->mUser->token($token);
		if(empty($res)):
		elseif($res->is_seller == 1):
			$out['is_logged'] = true;
			$id = (int)$json['id'];
			$arr = array(
				'name' => trim(htmlspecialchars(strip_tags($json['name']))),
				'description' => trim(htmlspecialchars(strip_tags($json['description']))),
				'category_id' => (int)$json['category_id'],
				'developer_id' => (int)$json['developer_id'],
				'seller_id' => $res->client_id,
				'price' => (float)$json['price']
			);
			$res = $this->mCatalog->get($id);
			if (empty($res)):
				$res = $this->mCatalog->insert($arr);
			else:
				$res = $this->mCatalog->update($arr, array('id' => $id));
			endif;
			if ($res > 0):
				$out['message'] = 'Данные успешно сохранены!';
			else:
				$out['message'] = 'Не удалось сохранить данные!';
			endif;
		endif;
		echo json_encode($out);
	}
	// Обработка удаления данных
	function item_delete()
	{
		$out = array(
			'message' => 'Авторизация неудачна!',
			'is_logged' => false
		);
		$json = json_decode(file_get_contents('php://input'), true);
		$token = trim(htmlspecialchars(strip_tags($json['token'])));
		$res = $this->mUser->token($token);
		if(empty($res)):
		elseif($res->is_seller == 1):
			$out['is_logged'] = true;
			$id = (int)$json['id'];
			$cnt = $this->mCatalog->cnt_where('orders', array('software_id' => $id));
			if ($cnt > 0):
				$out['message'] = 'Нельзя удалить элемент, на который имеются ссылки!';
			else:
				$res = $this->mCatalog->delete(array('id' => $id));
				if ($res > 0):
					$out['message'] = 'Данные успешно удалены!';
				else:
					$out['message'] = 'Не удалось удалить данные!';
				endif;
			endif;
		endif;
		echo json_encode($out);
	}
}
