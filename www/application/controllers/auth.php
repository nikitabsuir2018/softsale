<?php
// Пользователи
class auth extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('mUser');
	}
	
	// Редирект на клиентское веб-приложение
	function index()
	{
		redirect('/softsale/index.html', 'refresh');
	}
	
	// Обработка авторизации по логину и паролю
	function login()
	{
		// Получаем входные данные из массива post через file_get_contents (из-за особенностей angularjs)
		$json = json_decode(file_get_contents('php://input'), true);
		$login = $json['login'];
		$password = $json['password'];
		$res = $this->mUser->login($login, $password);
		if (empty($res)):
			$out = array(
				'message' => 'Авторизация неудачна!',
				'is_logged' => false
			);
		else:
			$out = array(
				'message' => 'Авторизация успешна!',
				'is_logged' => true,
				'current_user' => array(
					'login' => $res->login,
					'token' => $res->token,
					'client_id' => $res->client_id,
					'role_id' => $res->role_id,
					'is_seller' => $res->is_seller,
					'is_purchaser' => $res->is_purchaser
				)
			);
		endif;
		echo json_encode($out);
	}
	// Обработка авторизации по токену
	function token()
	{
		// Получаем входные данные из массива post через file_get_contents (из-за особенностей angularjs)
		$json = json_decode(file_get_contents('php://input'), true);
		$token = $json['token'];
		$res = $this->mUser->token($token);
		if (empty($res)):
			$out = array(
				'message' => 'Авторизация неудачна!',
				'is_logged' => false
			);
		else:
			$out = array(
				'message' => 'Авторизация успешна!',
				'is_logged' => true,
				'current_user' => array(
					'login' => $res->login,
					'token' => $res->token,
					'client_id' => $res->client_id,
					'role_id' => $res->role_id,
					'is_seller' => $res->is_seller,
					'is_purchaser' => $res->is_purchaser
				)
			);
		endif;
		echo json_encode($out);
	}
	// Регистрация пользователя
	function new_user()
	{
		$json = json_decode(file_get_contents('php://input'), true);
		$login = $json['login'];
        $arr = $this->db->get_where('users', array('login'=>$login))->result();
		if(count($arr) > 0):
			$out = array(
				'message' => 'Пользователь с таким логином ужен существует!',
				'is_logged' => false
			);
		else:
			$arr = array(
				'name' => trim(htmlspecialchars(strip_tags($json['name']))),
				'address' => trim(htmlspecialchars(strip_tags($json['address']))),
				'phone' => trim(htmlspecialchars(strip_tags($json['phone']))),
				'email' => trim(htmlspecialchars(strip_tags($json['email']))),
				'is_seller' => false,
				'is_purchaser' => true
			);
			if($this->db->insert('clients', $arr)):
				$client_id = $this->db->insert_id();
				$res = $this->db->insert('users', array(
					'login' => $login,
					'password' => md5($json['password']),
					'role_id' => 2,
					'client_id' => $client_id,
					'token' => '232'
				));
				if (empty($res)):
					$out = array(
						'message' => 'Не удалось зарегистрироваться!',
						'is_logged' => false
					);
				else:
					$out = array(
						'message' => 'Регистрация завершена!',
						'is_logged' => true
					);
				endif;
				
			else:
				$out = array(
					'message' => 'Не удалось зарегистрироваться!',
					'is_logged' => false
				);
			endif;

		endif;
		echo json_encode($out);
	}
}
