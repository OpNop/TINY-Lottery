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
    .filter('htmlToPlaintext', function() {
        return function(text) {
            return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
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
    .directive('fallbackSrc', () => {
        var fallbackSrc = {
            link: function postLink(scope, iElement, iAttrs) {
                iElement.bind('error', function() {
                    angular.element(this).attr("src", iAttrs.fallbackSrc);
                });
            }
        }
        return fallbackSrc;
    })
    .controller('lotteryCtrl', ($scope, $resource, $location) => {
        //should this be const? Also, can we convert it to query string (/api/listEntries?account), just for RESTfulness?
        let getEntries = $resource('api/listEntries/:accountName');
        let getprizeList = $resource('api/getPrizes/:bankTab');
        //Fake init method
        $scope.onLoad = () => {
            //Check for account in query string
            $scope.AccountName = $location.search().account;
            if (typeof $scope.AccountName != 'undefined') {
                $scope.searchEntries();
            }
            //load prizes, this should get moved to later bet meh for now
            getprizeList.query({ bankTab: 1 }, (result) => {
                $scope.prizeList = result;
            });
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