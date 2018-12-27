ssApp.controller('CatalogController', function CatalogController($scope, $rootScope, $http) {
	$scope.sectionView = 'lists';
	$scope.mFilter = {
		category_id : '0',
		developer_id : '0',
		seller_id : '0'
	};
	// Получение списка элементов
	$scope.LoadItems = function(mFilter) {
		// AJAX-запрос
		$http.post('/catalog/items_list', mFilter).then(
			function success(response) {
				$scope.data = response.data;
				$scope.sectionView = 'lists';
			}
		);
	};
	// Вывод элемента
	$scope.ShowItem = function(item) {
		$scope.current = item;
		if(item.seller_id == $rootScope.currentUser.client_id) {
			$scope.sectionView = 'form';
		} else {
			$scope.sectionView = 'info';
		}
	};
	// Новый элемент
	$scope.NewItem = function() {
		$scope.current = {
			id : 0,
			name : '',
			description : '',
			category_id : null,
			developer_id : null,
			seller_id : $rootScope.currentUser.client_id,
			price : 1
		};
		$scope.sectionView = 'form';
	};
	// Сохранение элемента
	$scope.SaveItem = function(item, form_edit) {
		if (form_edit.$valid) {
			var param = item;
			param.token = $rootScope.currentUser.token;
			$http.post('/catalog/item_save', param).then(
				function success(response) {
					alert(response.data.message);
					$scope.LoadItems($scope.mFilter);
				}
			);
		}		
	};
	// Удаление элемента
	$scope.DeleteItem = function(item) {
		var param = item;
		param.token = $rootScope.currentUser.token;
		$http.post('/catalog/item_delete', param).then(
			function success(response) {
				alert(response.data.message);
				$scope.LoadItems($scope.mFilter);
			}
		);
	};
	$scope.LoadItems($scope.mFilter);
});