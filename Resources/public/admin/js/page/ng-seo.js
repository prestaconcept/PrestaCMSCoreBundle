/**
 * Page Edition : SEO Panel
 * Based on AngularJS
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */

var PrestaCMSCore = {};

PrestaCMSCore.PageModule = angular.module('PrestaCMS.Page', [])
    .controller('SeoController', function($scope) {
        $scope.init = function(urlRelative, pathComplete, urlComplete, isUrlCompleteMode) {
            $scope.urlRelative       = urlRelative;
            $scope.pathComplete      = pathComplete;
            $scope.urlComplete       = urlComplete;
            $scope.isUrlCompleteMode = isUrlCompleteMode;
        };
    })
    .filter('cleanUrl', function () {
        return function (input) {
            return input.replace(/\s+/g, '-')
                .replace(/[^a-z0-9-]/ig, '');
        };
    })
;
