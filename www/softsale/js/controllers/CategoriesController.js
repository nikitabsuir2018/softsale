ssApp.controller('CategoriesController', function CategoriesController($scope, $rootScope, $http) {
	// Получение списка элементов
	$scope.LoadItems = function() {
		var param = { token : $rootScope.currentUser.token };
		$http.post('/categories/items_list', param).then(
			function success(response) {
				$scope.data = response.data;
				$scope.sectionView = 'lists';
			}
		);
	};
	// Новый элемент
	$scope.NewItem = function() {
		$scope.current = {
			id : 0,
			name : ''
		};
		$scope.sectionView = 'form';
	};
	// Вывод элемента
	$scope.ShowItem = function(item) {
		$scope.current = item;
		$scope.sectionView = 'form';
	};
	// Сохранение элемента
	$scope.SaveItem = function(item, form_edit) {
		if (form_edit.$valid) {
			var param = item;
			param.token = $rootScope.currentUser.token;
			$http.post('/categories/item_save', param).then(
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
		$http.post('/categories/item_delete', param).then(
			function success(response) {
				alert(response.data.message);
				$scope.LoadItems();
			}
		);
	};
	$scope.LoadItems();
});