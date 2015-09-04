'use strict';

angular.module('starter')
    .controller('ContestantCtrl', function($scope, $ionicModal, $stateParams, Activity) {

        function init() {
            $scope.eventId = $stateParams.eventId;
            Activity.getContestants($stateParams.eventId).then(function(data) {
                $scope.contestants = data.data;
                console.log($scope.contestants);
            });
        }

        init();
    })
    .controller('DetailCtrl', function($scope, $ionicModal, $ionicPopup, $stateParams, Activity) {

        function init() {
            console.log('$stateParams id: ', $stateParams.id);
            console.log('$stateParams eventId: ', $stateParams.eventId);

            Activity.getContestants($stateParams.eventId).then(function(data) {
                var result = _.find(data.data.childs, {
                    'contestantid': $stateParams.id
                })
                console.log('result: ', result);
                $scope.detail = result;
            });

            Activity.getCriteria($stateParams.id).then(function(data) {

                $scope.criterias = data.data;
                console.log('citeria', $scope.criterias);
            });

        }

        $scope.showPopup = function(id, score) {
            $scope.data = {};
            console.log('score: ', score);

            var judge = JSON.parse(localStorage.getItem('users'));

            $scope.data.criteriaid = id;
            $scope.data.contestantid = $stateParams.id;
            $scope.data.judgeid = judge.judgeid;
            $scope.data.eventId = $stateParams.eventId;

            var myPopup = $ionicPopup.show({
                template: '<input type="number" ng-model="data.scoring">',
                title: 'Enter Score / Rating',
                scope: $scope,
                buttons: [{
                    text: 'Cancel'
                }, {
                    text: '<b>Save</b>',
                    type: 'button-positive',
                    onTap: function(e) {
                        if (!$scope.data.scoring) {
                            e.preventDefault();
                        } else {
                            if (score === undefined || score === null ) {
                                Activity.saveScore($scope.data)
                                    .success(function(data) {
                                        setTimeout(function() {
                                            var alertPopup = $ionicPopup.alert({
                                                title: 'Notification',
                                                template: data.msg
                                            });
                                            alertPopup.then(function(res) {
                                                init();
                                            });
                                        }, 50);
                                    });
                            } else {
                                Activity.updateScore($scope.data)
                                    .success(function(data) {
                                        setTimeout(function() {
                                            var alertPopup = $ionicPopup.alert({
                                                title: 'Notification',
                                                template: data.msg
                                            });
                                            alertPopup.then(function(res) {
                                                init();
                                            });
                                        }, 50);
                                    });
                            }
                        }
                    }
                }]
            });
        }

        init();
    });
