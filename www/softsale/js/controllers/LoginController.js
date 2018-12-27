ssApp.controller('LoginController', function LoginController($scope, $rootScope, $http, $location) {
	$scope.LogIn = function(current, form_login) {
		if (form_login.$valid) {
			// AJAX-запрос
			$http.post('/auth/login', current).then(
				function success(response) {
					$scope.message = response.data.message;
					if (response.data.is_logged) {
						$rootScope.CurrentUserSet(response.data.current_user);
						$location.path("catalog");
					}
				},
				function error(response) {
					$scope.message = "Не удалось подключиться";
				}
			);
	}};	
});