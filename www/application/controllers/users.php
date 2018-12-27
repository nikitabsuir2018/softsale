<?php
// Пользователи
class users extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mUser');
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
			$res = $this->mUser->all();
			if(!empty($res)):
				foreach($res as $row):
					$out['lists'][] = array(
						'id' => $row->id,
						'login' => $row->login,
						'client_id' => $row->client_id,
						'client_name' => $row->client_name,
						'role_id' => $row->role_id,
						'role_name' => $row->role_name
					);
				endforeach;
			endif;
		endif;
		$out['roles_list'] = $this->mUser->ref_list('roles');
		$out['clients_list'] = $this->mUser->ref_list('clients');
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
			$change_password = (bool)$json['change_password'];
			$password = $json['password'];
			$arr = array(
				'login' => trim(htmlspecialchars(strip_tags($json['login']))),
				'role_id' => (int)$json['role_id'],
				'client_id' => (int)$json['role_id']
			);
			if ($id == 1):
				$arr['role_id'] = 1;
			endif;
			if ($change_password):
				$arr['password'] = md5($password);
			endif;
			$res = $this->mUser->get($id);
			if (empty($res)):
				$arr['token'] = '1';
				$res = $this->mUser->insert($arr);
			else:
				$res = $this->mUser->update($arr, array('id' => $id));
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
			$cnt = $this->mUser->cnt_where('orders', array('purchaser_id' => $id));
			if ($cnt > 0):
				$out['message'] = 'Нельзя удалить элемент, на который имеются ссылки!';
			elseif ($id == 1):
				$out['message'] = 'Нельзя удалить запись суперадминистратора!';
			else:
				$res = $this->mUser->delete(array('id' => $id));
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
