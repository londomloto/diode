
angular.module('diode', [
	'ui.router',
	'oc.lazyLoad'
])
.config(config)

/** @ngInject */
function config($stateProvider, $locationProvider) {
	
	// $locationProvider.html5Mode(true);

	$stateProvider
		.state('user', {
			url: '/user',
			views: {
				'@': {
					templateUrl: 'app/user/user.view.html',
					controller: 'UserController as vm'
				}
			},
			resolve: {
				/** @ngInject */
				controller: function($ocLazyLoad){
					return $ocLazyLoad.load('app/user/user.controller.js');
				}
			}
		})
		.state('finance', {

		});
}

function api(path) {

}