ssApp.controller('RegisterController', function RegisterController($scope, $rootScope, $http, $location) {
	$scope.NewUser = function(current, form_register) {
		if (form_register.$valid) {
			// AJAX-запрос
			$http.post('/auth/new_user', current).then(
				function success(response) {
					$scope.message = response.data.message;
					if (response.data.is_logged) {
						alert(response.data.message);
						$location.path("login");
					} else {
						$scope.message = response.data.message;
					}
				},
				function error(response) {
					$scope.message = "Не удалось подключиться";
				}
			);
	}};	
});