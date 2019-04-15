const app = angular.module('tinyLottery', ['ngResource'])
    .config(($locationProvider) => {
        $locationProvider.html5Mode(true);
    })
    .filter('coins2gold', ($sce) => {
        return function (price) {
            //there MAY be a shorter way to do this. Not sure tho. -Healy
            const priceStr = price.toString();
            const priceArr = priceStr.split(''); //split price into array and add 'c' (copper) to end
            priceArr.push('<i class="sprite sprite-copper">c</i>');
            if (priceStr.length > 2) {
                //greater than 1 s
                priceArr.splice(-3, 0, '<i class="sprite sprite-silver">s</i>')
            }
            if (priceStr.length > 4) {
                //greater than 1 g
                priceArr.splice(-6, 0, '<i class="sprite sprite-gold">g</i>');
            }
            return $sce.trustAsHtml(priceArr.join(''));
        };
    })
    .directive('tooltip', () => {
        return {
            restrict: 'A',
            link: function (scope, element) {
                element.hover(function () {
                    // on mouseenter
                    element.tooltip('show');
                }, function () {
                    // on mouseleave
                    element.tooltip('hide');
                });
            }
        };
    })
    .controller('lotteryCtrl', ($scope, $resource, $location) => {
        //should this be const? Also, can we convert it to query string (/api/listEntries?account), just for RESTfulness?
        let getEntries = $resource('api/listEntries/:accountName');
        //Fake init method
        $scope.onLoad = () => {
            //Check for account in query string
            $scope.AccountName = $location.search().account;
            if (typeof $scope.AccountName != 'undefined') {
                $scope.searchEntries();
            }
        }

        $scope.searchEntries = () => {
            if (typeof $scope.AccountName != "undefined" && $scope.AccountName != "") {
                $location.search('account', $scope.AccountName);
                getEntries.query({ accountName: $scope.AccountName }, (result) => {
                    $scope.entryList = result;
                });
            } else {
                $scope.entryList = [];
            }
        }

        $scope.formatDate = date => new Date(date.split("-").join("/") + ' UTC');
    });