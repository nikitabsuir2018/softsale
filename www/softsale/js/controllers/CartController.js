ssApp.controller('CartController', function CartController($scope, $rootScope, $http, $location) {
	$scope.NewOrder = function() {
		var param = { software : $rootScope.currentCart };
		param.token = $rootScope.currentUser.token;
		$http.post('/orders/item_new', param).then(
			function success(response) {
				alert(response.data.message);
				$location.path("catalog");
			}
		);
	};
});