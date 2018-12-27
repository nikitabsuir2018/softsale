ssApp.controller('UsersController', function UsersController($scope, $rootScope, $http) {
	// Получение списка элементов
	$scope.LoadItems = function() {
		var param = { token : $rootScope.currentUser.token };
		$http.post('/users/items_list', param).then(
			function success(response) {
				$scope.data = response.data;
				$scope.sectionView = 'lists';
			}
		);
	};
	// Вывод элемента
	$scope.ShowItem = function(item) {
		$scope.current = item;
		$scope.current.change_password = false;
		$scope.sectionView = 'form';
	};
	// Новый элемент
	$scope.NewItem = function() {
		$scope.current = {
			id : 0,
			login : '',
			password : '',
			change_password : true,
			client_id : '1',
			role_id : '2'
		};
		$scope.sectionView = 'form';
	};
	// Сохранение элемента
	$scope.SaveItem = function(item, form_edit) {
		if (form_edit.$valid) {
			var param = item;
			param.token = $rootScope.currentUser.token;
			$http.post('/users/item_save', param).then(
				function success(response) {
					alert(response.data.message);
					$scope.LoadItems();
				}
			);
		}		
	};
	// Удаление элемента
	$scope.DeleteItem = function(item) {
		var param = item;
		param.token = $rootScope.currentUser.token;
		$http.post('/users/item_delete', param).then(
			function success(response) {
				alert(response.data.message);
				$scope.LoadItems();
			}
		);
	};
	$scope.LoadItems();
});