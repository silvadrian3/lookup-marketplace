var app = angular.module('lookUpApp', []);

let home_products_url = "model/products.json",
    categories_url = "model/categories.json";

app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start;
            return input.slice(start);
        }
        return [];
    };
});

app.controller('lookUpCtrl', ['$scope', '$http', 'filterFilter', function ($scope, $http, filterFilter) {

    var currentDate = new Date();
    $scope.currentYear = currentDate.getFullYear();
    $scope.home_products = [];
    $scope.home_services = [];
    $scope.his_statements_products = [];

    angular.element(document).ready(function () {
        $("#page-load").css("display", "block");
        $scope.getHomeProducts();
        $scope.getProductsList();
        $scope.getCategories();
        
    });

    $scope.getCategories = function(){
        $http.get(categories_url).success(function (response) {
            console.log(response);
            $scope.category = response.data;
        });
    }

    $scope.getHomeProducts = function(){

        $http.get(home_products_url).success(function (response) {
            if(response.result){
                $scope.home_products = response.data; 
                
                setTimeout(function(){
                    $('.home-products-slide').owlCarousel({
                        autoplay: true,
                        autoplayTimeout: 4000,
                        navigation: false,
                        margin: 10,
                        loop:true,
                        smartSpeed:2000,
                        responsiveClass: true,
                        //autoplayHoverPause: true, // Stops autoplay
                        responsiveRefreshRate : 10,
                        items: 4,
                        dots: false
                    });
                }, 0);
                
            }
        });
    }
    
    $scope.redirect_product = function(id){
        window.location = "view-product.php?p=" + btoa(id);
    }
    
    $scope.redirect_service = function(id){
        window.location = "request-quotation.php?s=" + btoa(id);
    }    
    
    
    $scope.getProductsList = function(){
        $("#page-load").css("display", "block");
        
        //var categories_url = 'api/v1/categories.php?k=5fea10f9b07309ead88909855cfff695&client_id=' + btoa(3);
        var categories_url = 'model/categories.json';
        
        $http.get(categories_url).success(function (response) {
            
            $scope.category = response.data;
        });
        
        //var products_url = 'api/v1/products.php?k=5fea10f9b07309ead88909855cfff695&client_id=' + btoa(3);
        var products_url = 'model/products.json';
        
        $http.get(products_url).success(function (response) {
            console.log(response);
            if(response.result){
                $scope.items = response.data;
                $scope.totalresult = response.data.length;

                // create empty search model (object) to trigger $watch on update
                $scope.search = {};

                var ciPattern = /[?&]ci=/,
                    cnPattern = /[?&]cn=/,
                    URL = location.search;

                if(ciPattern.test(URL) && cnPattern.test(URL)){
                    if(GetQueryStringParams("ci")!="" && GetQueryStringParams("cn")!=""){
                        var classification_id = GetQueryStringParams("ci"),
                            classification = GetQueryStringParams("cn"),
                            selectedClassification_id = atob(classification_id),
                            selectedClassification = atob(classification);
                            $scope.search = {classification_id: selectedClassification_id};
                            $scope.search = {classification: selectedClassification};
                            $scope.filter_category(selectedClassification_id, selectedClassification);
                    }
                }
                
                // pagination controls
                /**
                $scope.currentPage = 1;
                $scope.totalItems = $scope.items.length;
                $scope.entryLimit = 12; // items per page
                $scope.noOfPages = Math.ceil($scope.totalItems / $scope.entryLimit);
                */

                // $watch search to update pagination
                $scope.$watch('search', function (newVal, oldVal) {
                document.getElementById('page-load').style.display = 'block';
                document.getElementById('divShowProducts').style.display = 'none';
                    
                $scope.filtered = filterFilter($scope.items, newVal);
                $scope.totalItems = $scope.filtered.length;
                $scope.noOfPages = Math.ceil($scope.totalItems / $scope.entryLimit);
                $scope.currentPage = 1;

                if($scope.totalItems == $scope.totalresult){
                    $scope.show_all = false;
                } else {
                    $scope.show_all = true;
                }

                
                if($scope.totalItems > 0){
                    $scope.itemResult = true;
                    /**
                    if($scope.totalItems > 12){
                        document.getElementById('paganation').style.display = 'block';    
                    } else {
                        document.getElementById('paganation').style.display = 'none';
                    }
                    */
                } else {
                    $scope.itemResult = false;
                    //document.getElementById('paganation').style.display = 'none';
                }
                

                document.getElementById('page-load').style.display = 'none';
                $("#divShowProducts").css("display", "block");
                document.getElementById('divResult').style.display = 'block';

            }, true);

            }
        
            $("#divproducts").css("display", "block");
            $("#divShowProducts").css("display", "block");    
            $("#page-load").css("display", "none");
        
        });
    };


    $scope.resetFilters = function () {
            // needs to be a function or it won't trigger a $watch
            $scope.search = {};
            $scope.clearf('all');
        };

    $scope.filter_category = function(id, classification){

        console.log(id, classification);

        $scope.selectCategory = true;
        $scope.selected_Category = classification;
        $scope.navSearchCategory = classification;
        $scope.selected_CategoryID = id;
        //$scope.selected_Category = $("#category_" + id).text();

        $(".categories").css("background-color", "");
        $(".categories").removeClass("active_category");
        
        if(!$("#category_" + id).hasClass("active_category")) {
            $("#category_" + id).addClass("active_category");
        } else {
            $("#category_" + id).removeClass("active_category");
            $("#category_" + id).css("background-color", "#ffffff");
        }
        
    }
    
    $scope.filter_range = function(id){
        $scope.selectRange = true;
        $scope.selected_Range = $("#range_" + id).text();
        
        $(".pricerange").css("background-color", "");
        $(".pricerange").removeClass("active_range");
        
        if(!$("#range_" + id).hasClass("active_range")) {
            $("#range_" + id).addClass("active_range");
        } else {
            $("#range_" + id).removeClass("active_range");
            $("#range_" + id).css("background-color", "#ffffff");
        }
    }

    $scope.getProductRate = function (t, x){
        
        let range = [];

        if(t == 1){
            for (y = 1; y <= x; y++) {
                range.push(y);
            }
        } else {
            for (y = 5; y > x; y--) {
                range.push(y);
            }
        }
        return range;
    }
    
    $scope.clearf = function(type){
        
        if(type == 'category'){
            $(".categories").css("background-color", "#ffffff");
            $(".categories").removeClass("active_range");
            
            $scope.selectCategory = false;
            $scope.selected_Category = "";
            //delete $scope.search.category_id;
            delete $scope.search.classification_id;
            delete $scope.search.classification;
            
        } else if(type == 'range'){
            /**
            $(".pricerange").css("background-color", "#ffffff");
            $(".pricerange").removeClass("active_range");
            $scope.selectRange = false;
            $scope.selected_Range = "";
            delete $scope.search.price_range_id;
            */
        } else {
            $(".categories").css("background-color", "#ffffff");
            $(".pricerange").css("background-color", "#ffffff");
            $(".categories").removeClass("active_range");
            $(".pricerange").removeClass("active_range");
            
            $scope.selectRange = false;
            $scope.selectCategory = false;
            $scope.selected_Range = "";
            $scope.selected_Category = "";
            delete $scope.search.price_range_id;
            //delete $scope.search.category_id;
            delete $scope.search.classification_id;
            delete $scope.search.classification;
        }
    }
    
    $scope.redirect = function(page){
        window.location = "products/" + page;
    }

    $scope.searchByCategory = function() {
        var selectedClassification_id = angular.element("#searchCategories option[value='" + $scope.navSearchCategory + "']").attr('id'),
            selectedClassification = $scope.navSearchCategory;
        if(selectedClassification_id != undefined && selectedClassification != undefined){
            var selectedClassification_id = selectedClassification_id.replace('nav_category_', '');
                 
            $scope.search = {classification_id: selectedClassification_id};
            $scope.search = {classification: selectedClassification};

            $scope.filter_category(selectedClassification_id, selectedClassification);
        } else {
            $scope.clearf('category');
        }
    }

}]);
    

$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top'
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});        

//get QueryString Parameters 
function GetQueryStringParams(e) {
    "use strict";
    var t = window.location.search.substring(1),
        n = t.split("&"),
        r,
        i;

    for (r = 0; r < n.length; r++) {
        i = n[r].split("=");
        if (i[0] === e) {
            return i[1];
        }
    }
}


