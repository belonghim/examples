var guestbookApp = angular.module('guestbook', ['ui.bootstrap']);

/**
 * Constructor
 */
function guestbookController() {}

guestbookController.prototype.onguestbook = function() {
	if (this.scope_.msg != "" && this.scope_.msg != null) {
		this.http_.get("guestbook.php?cmd=set&value=" + this.scope_.msg)
				.success(angular.bind(this, function(data) {
					this.scope_.guestbookResponse = "Updated.";
				}));
	}
	window.location.href = '/';
};

guestbookApp.controller('guestbookCtrl', function ($scope, $http, $location) {
        $scope.controller = new guestbookController();
        $scope.controller.scope_ = $scope;
        $scope.controller.location_ = $location;
        $scope.controller.http_ = $http;	

        $scope.controller.http_.get("guestbook.php?cmd=get")
            .success(function(data) {
                console.log(data);
                if (data.hasOwnProperty('error')) {
                    $scope.messages = [data.error];
                } else if( data.data != null ) {
                    $scope.messages = data.data.toString().split(",");
                } else {
					$scope.messages = "";
				}
            });
});