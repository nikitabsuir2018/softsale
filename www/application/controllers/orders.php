<?php
// Заказы
class orders extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mUser');
		$this->load->model('mOrder');
	}
	
	// Вывод списка покупателя
	function purchaser_list()
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
		elseif($res->is_purchaser == 1):
			$out['message'] = 'Авторизация успешна!';
			$out['is_logged'] = true;
			$res = $this->mOrder->get_where(array('purchaser_id' => $res->id));
			if(!empty($res)):
				foreach($res as $row):
					$out['lists'][] = (array)$row;
				endforeach;
			endif;
		endif;
		echo json_encode($out);
	}
	// Вывод списка продавца
	function seller_list()
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
		elseif($res->is_seller == 1):
			$out['message'] = 'Авторизация успешна!';
			$out['is_logged'] = true;
			$res = $this->mOrder->get_where(array('seller_id' => $res->client_id));
			if(!empty($res)):
				foreach($res as $row):
					$out['lists'][] = (array)$row;
				endforeach;
			endif;
		endif;
		echo json_encode($out);
	}
	// Обработка изменения статуса
	function change_status()
	{
		$out = array(
			'message' => 'Авторизация неудачна!',
			'is_logged' => false
		);
		$json = json_decode(file_get_contents('php://input'), true);
		$token = trim(htmlspecialchars(strip_tags($json['token'])));
		$user = $this->mUser->token($token);
		if(empty($user)):
		else:
			$out['is_logged'] = true;
			$id = (int)$json['id'];
			$status_id = (int)$json['status_id'];
			$item = $this->mOrder->get($id);
			if (empty($item)):
				$out['message'] = 'Заказ не найден!';
			else:
				$res = 0;
				if (($status_id == 2 && $item->status_id == 1 && $item->seller_id == $user->client_id) || ($status_id == 3 && $item->status_id == 1 && $item->seller_id == $user->client_id) || ($status_id == 4 && $item->status_id == 1 && $item->client_id == $user->client_id)):
					$res = $this->mOrder->update(array('status_id' => $status_id), array('id' => $id));
				endif;
				if ($res > 0):
					$out['message'] = 'Данные успешно сохранены!';
				else:
					$out['message'] = 'Не удалось сохранить данные!';
				endif;
			endif;
		endif;
		echo json_encode($out);
	}
	// Регистрация заказа
	function item_new()
	{
		$out = array(
			'message' => 'Авторизация неудачна!',
			'is_logged' => false
		);
		$json = json_decode(file_get_contents('php://input'), true);
		$token = trim(htmlspecialchars(strip_tags(@$json['token'])));
		$res = $this->mUser->token($token);
		if(empty($res)):
		elseif($res->is_purchaser == 1):
			$user_id = $res->id;
			$software = @$json['software'];
			if (is_array($software)):
				$arr = array();
				foreach($software as $cartitem):
					$id = @$cartitem['id'];
					$quantity = @$cartitem['quantity'];
					$res = $this->mOrder->get_software($id);
					if ($res && $quantity > 0):
						$arr[] = array(
							'dates' => date('Y-m-d', strtotime('now')),
							'purchaser_id' => $user_id,
							'status_id' => 1,
							'software_id' => $id,
							'quantity' => $quantity,
							'price' => $res->price,
							'summa' => $res->price * $quantity
						);
					endif;
				endforeach;
				if(count($arr) > 0):
					if ($this->mOrder->insert_batch($arr)):
						$out['message'] = 'Заказ успешно сохранен!';
					else:
						$out['message'] = 'Не удалось сохранить заказ!';
					endif;
				else:
					$out['message'] = 'Корзина пуста!';
				endif;
			else:
				$out['message'] = 'Данные корзины не найдены!';
			endif;
		endif;
		echo json_encode($out);
	}
	
	
}
