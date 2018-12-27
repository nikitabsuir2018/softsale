var ssApp = angular.module('ssApp', ["ngRoute"]).config(function($routeProvider){
	// Маршруты
	$routeProvider.when('/catalog', {
		templateUrl: 'views/catalog.html',
		controller: 'CatalogController'
	});
	$routeProvider.when('/login', {
		templateUrl: 'views/login.html',
		controller: 'LoginController'
	});
	$routeProvider.when('/cart', {
		templateUrl: 'views/cart.html',
		controller: 'CartController'
	});
	$routeProvider.when('/categories', {
		templateUrl: 'views/categories.html',
		controller: 'CategoriesController'
	});
	$routeProvider.when('/developers', {
		templateUrl: 'views/developers.html',
		controller: 'DevelopersController'
	});
	$routeProvider.when('/clients', {
		templateUrl: 'views/clients.html',
		controller: 'ClientsController'
	});
	$routeProvider.when('/users', {
		templateUrl: 'views/users.html',
		controller: 'UsersController'
	});
	$routeProvider.when('/seller', {
		templateUrl: 'views/seller.html',
		controller: 'SellerController'
	});
	$routeProvider.when('/purchaser', {
		templateUrl: 'views/purchaser.html',
		controller: 'PurchaserController'
	});
	$routeProvider.when('/register', {
		templateUrl: 'views/register.html',
		controller: 'RegisterController'
	});
	// Маршрут по-умолчанию
	$routeProvider.otherwise({redirectTo: '/catalog'})
}).run(function($rootScope, $templateCache, $http) {
	$rootScope.$on('$routeChangeStart', function(event, next, current) {
		if (typeof(current) !== 'undefined') {
			$templateCache.remove(current.templateUrl);
		}
	});
	// Авторизация
	$rootScope.currentUser = { 
		login : "noname",
		token : "1",
		role_id : 0,
		client_id : 0,
		is_seller : 0,
		is_purchaser : 0
	};
	var currentUserToken = localStorage.currentUserToken;
	if (currentUserToken !== undefined) {
		var param = { token : currentUserToken };
		$http.post('/auth/token', param).then(
			function success(response) {
				if (response.data.is_logged) {
					$rootScope.CurrentUserSet(response.data.current_user);
				}
			}
		);
	}
	$rootScope.CurrentUserSet = function(current_user) {
		$rootScope.currentUser = current_user;
		localStorage.currentUserToken = current_user.token;
	};
	$rootScope.LogOut = function() {
		$rootScope.currentUser = { 
			login : "noname",
			token : "1",
			role_id : 0,
			client_id : 0,
			is_seller : 0,
			is_purchaser : 0
		};
		localStorage.currentUserToken = "1";
		$rootScope.currentCart = [];
		localStorage.currentCart = JSON.stringify($rootScope.currentCart);
	};
	// Корзина
	try {
		$rootScope.currentCart = JSON.parse(localStorage.currentCart);
	} catch (e) {
		$rootScope.currentCart = [];
	}
	$rootScope.AddToCart = function(item) {
		var flag = true;
		for (var i = 0; i < $rootScope.currentCart.length; i++) {
			if ($rootScope.currentCart[i].id == item.id) {
				flag = false;
				$rootScope.currentCart[i].quantity++;
			}
		}
		if (flag) {
			item.quantity = 1;
			$rootScope.currentCart.push(item);
		}
		localStorage.currentCart = JSON.stringify($rootScope.currentCart);
	};
	$rootScope.RemoveFromCart = function(id) {
		var number = -1;
		for (var i = 0; i < $rootScope.currentCart.length; i++) {
			if ($rootScope.currentCart[i].id == id) {
				number = i;
			}
		}
		if (number > -1) {
			$rootScope.currentCart.splice(number, 1);
			localStorage.currentCart = JSON.stringify($rootScope.currentCart);
		}
	};
	$rootScope.ClearCart = function() {
		$rootScope.currentCart = [];
		localStorage.currentCart = JSON.stringify($rootScope.currentCart);
	};
	
});