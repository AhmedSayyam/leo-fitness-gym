var app = angular.module("myApp", ['ngRoute', 'datatables', 'pascalprecht.translate']);
// var app = angular.module("myApp", ['ngRoute']);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl: "public/views/admin/dashboard.html",
            controller: "dashboard_ctrl"
        })
        .when("/dashboard", {
            templateUrl: "public/views/admin/dashboard.html",
            controller: "dashboard_ctrl"
        })
        .when("/staff", {
            templateUrl: "public/views/admin/staff.html",
            controller: "staff_ctrl"  
        })
        .when("/package", {
            templateUrl: "public/views/admin/package.html",
            controller: "package_ctrl"
        })
        .when("/fee", {
            templateUrl: "public/views/admin/fee.html",
            controller: "fee_ctrl"
        })
        .when("/members", {
            templateUrl: "public/views/admin/members.html",
            controller: "members_ctrl"
        }).
        otherwise({
            templateUrl: "public/views/admin/404.html"
        });;


});

app.run(['$rootScope', function($rootScope) {
    $rootScope.lang = 'en';
}])


app.config(["$translateProvider", function($translateProvider){

    $translateProvider.useStaticFilesLoader({
        prefix: 'public/locales/locale-',
        suffix: '.json'
    })
    .useSanitizeValueStrategy('sanitizeParameters')    
    .preferredLanguage('en');
}]);