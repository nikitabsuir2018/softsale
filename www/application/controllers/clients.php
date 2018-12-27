<?php
// Клиенты
class clients extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mUser');
		$this->load->model('mReference');
	}

	// Вывод списка
	function items_list()
	{
		$out = array(
			'message' => 'Авторизация неудачна!',
			'is_logged' => false,
			'lists' => array()
		);
		$json = json_decode(file_get_contents('php://input'), true);
		$token = $json['token'];
		$res = $this->mUser->token($token);
		if(empty($res)):
		elseif($res->role_id == 1):
			$out['message'] = 'Авторизация успешна!';
			$out['is_logged'] = true;
			$res = $this->mReference->all('clients');
			if(!empty($res)):
				foreach($res as $row):
					$row->is_seller = $row->is_seller == 1;
					$row->is_purchaser = $row->is_purchaser == 1;
					$out['lists'][] = (array)$row;
				endforeach;
			endif;
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
		elseif($res->role_id == 1):
			$out['is_logged'] = true;
			$id = (int)$json['id'];
			$arr = array(
				'name' => trim(htmlspecialchars(strip_tags($json['name']))),
				'address' => trim(htmlspecialchars(strip_tags($json['address']))),
				'phone' => trim(htmlspecialchars(strip_tags($json['phone']))),
				'email' => trim(htmlspecialchars(strip_tags($json['email']))),
				'is_seller' => (bool)$json['is_seller'],
				'is_purchaser' => (bool)$json['is_purchaser']
			);
			$res = $this->mReference->get('clients', $id);
			if (empty($res)):
				$res = $this->mReference->insert('clients', $arr);
			else:
				$res = $this->mReference->update('clients', $arr, array('id' => $id));
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
		elseif($res->role_id == 1):
			$out['is_logged'] = true;
			$id = (int)$json['id'];
			$cnt = $this->mReference->cnt_where('software', array('seller_id' => $id)) + $this->mReference->cnt_where('users', array('client_id' => $id));
			if ($cnt > 0):
				$out['message'] = 'Нельзя удалить элемент, на который имеются ссылки!';
			elseif ($id == 1):
				$out['message'] = 'Нельзя удалить запись владельца сервиса!';
			else:
				$res = $this->mReference->delete('clients', array('id' => $id));
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
