ssApp.controller('SellerController', function SellerController($scope, $rootScope, $http) {
	// Получение списка элементов
	$scope.LoadItems = function() {
		var param = { token : $rootScope.currentUser.token };
		$http.post('/orders/seller_list', param).then(
			function success(response) {
				$scope.data = response.data;
				$scope.sectionView = 'lists';
			}
		);
	};
	//  Вывод элемента
	$scope.ShowItem = function(item) {
		$scope.current = item;
		$scope.sectionView = 'info';
	};
	// Изменение статуса
	$scope.ChangeStatus = function(id, status_id) {
		var param = {
			token : $rootScope.currentUser.token,
			id : id,
			status_id : status_id
		};
		$http.post('/orders/change_status', param).then(
			function success(response) {
				alert(response.data.message);
				$scope.LoadItems();
			}
		);
		$scope.sectionView = 'lists';
	};
	$scope.LoadItems();
});