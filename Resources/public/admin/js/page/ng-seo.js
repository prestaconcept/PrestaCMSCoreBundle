/**
 * Page Edition : SEO Panel
 * Based on AngularJS
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */

var PrestaCMSCore = {};

PrestaCMSCore.PageModule = angular.module('PrestaCMS.Page', [])
    .controller('SeoController', function($scope) {
        $scope.urlRelative   = 'url.html';
        $scope.pathComplete  = 'www.mysote.com/path/';
        $scope.urlComplete   = 'www.mysote.com/path/' + $scope.urlRelative;
        $scope.urlCompleMode = true;
    })
    .filter('cleanUrl', function () {
        return function (input) {
            return input.replace(/\s+/g, '-')
                .replace(/[^a-z0-9-]/ig, '');
        };
    })
;
