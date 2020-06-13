var app = angular.module('lookUpApp', []),
        product_id = GetQueryStringParams('p');
        

    app.controller('lookUpCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
        var currentDate = new Date(),
            categories_url = '../model/categories.json';
        
            $scope.currentYear = currentDate.getFullYear();
        
        angular.element(document).ready(function () {            
            $scope.getCategories();
        });

        $scope.getCategories = function(){
            $http.get(categories_url).success(function (response) {
                console.log(response);
                $scope.category = response.data;
            });
        }

        $scope.searchByCategory = function() {
            var selectedCategory = angular.element("#searchCategories option[value='" + $scope.navSearchCategory + "']").attr('id');
            if(selectedCategory != undefined){
                var category_id = selectedCategory.replace('nav_category_', ''); 
                window.location = "../?c=" + btoa(category_id);
            }
        }

        $scope.searchByCategory = function() {
            var selectedClassification_id = angular.element("#searchCategories option[value='" + $scope.navSearchCategory + "']").attr('id'),
                selectedClassification = $scope.navSearchCategory;
            if(selectedClassification_id != undefined && selectedClassification != undefined){
                var selectedClassification_id = selectedClassification_id.replace('nav_category_', '');
                window.location = "../?ci=" + btoa(selectedClassification_id) + "&cn=" + btoa(selectedClassification);
            } else {
                $scope.clearf('category');
            }
        }



    }]);
    
    $("#firstname").blur(function(){
        if($("#firstname").val() != ""){
            $("#firstname").removeClass("alert-danger");
        } else {
            $("#firstname").addClass("alert-danger");
        }
    });

    $("#lastname").blur(function(){
        if($("#lastname").val() != ""){
            $("#lastname").removeClass("alert-danger");
        } else {
            $("#lastname").addClass("alert-danger");
        }
    });

    $("#email").blur(function(){
        if($("#email").val() != ""){
            $("#email").removeClass("alert-danger");
        } else {
            $("#email").addClass("alert-danger");
        }
    });
    
    $("#contactno").blur(function(){
        if($("#contactno").val() != ""){
            $("#contactno").removeClass("alert-danger");
        } else {
            $("#contactno").addClass("alert-danger");
        }
    });

    function request_quote(){
        var firstname = $("#firstname"),
            lastname = $("#lastname"),
            email = $("#email"),
            contactno = $("#contactno"),
            message = $("#message"),
            product_name = $("#product_name"),
            seller = $('.seller-name');

        if(firstname.val() === "" || firstname.val() === undefined){
            alert("First Name is required");
            firstname.focus();
            firstname.addClass("alert-danger");
            return false;
        } else if(lastname.val() === "" || lastname.val() === undefined){
            alert("Last Name is required");
            lastname.focus();
            lastname.addClass("alert-danger");
            return false;
        } else if(email.val() === "" || email.val() === undefined){
            alert("Email Address is required");
            email.focus();
            email.addClass("alert-danger");
            return false;
        } else if(contactno.val() === "" || contactno.val() === undefined){
            alert("Contact Number is required");
            contactno.focus();
            contactno.addClass("alert-danger");
            return false;
        } else {
            //return true;

            var url = "../mail/send-quote.php";
                url += "?key=c0061f705a33fb7cd3a83cda3f2a7a7c";

            var params = {
                firstname: firstname.val(),
                lastname: lastname.val(),
                email: email.val(),
                contactno: contactno.val(),
                message: message.val(),
                seller: seller.text(),
                product_name: product_name.text()
            };

            console.log(params);
            
            $.post(url, params).success(function(response) {
                var response = JSON.parse(response)[0];
                if(response.result){
                    alert(response.message);
                    
                    setTimeout(function(){
                        window.location = '../';
                    }, 1000);
                } else {
                    alert('Unexpected error encountered.');
                }
            });
        }
    }

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