

(function(){

	angular
		.module('diode')
		.controller('UserController', UserController);

	/** @ngInject */
	function UserController($scope) {
		$scope.email = 'roso@kct.co.id';
		
		$scope.users = [
			{name: 'Agus'},
			{name: 'Budi'}
		];
	}

}());
