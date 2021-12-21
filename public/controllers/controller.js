const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});
function notify(msg, sign){
    Toast.fire({
        icon: sign,
        title: msg
    });
}


app.controller('main_cont', function($scope){
    console.log("Main Controller");
});


app.controller('dashboard_ctrl', function($scope, $translate, $rootScope){
    // console.log("Dashboard Controller");
    $scope.changeLanguage = function (key) {
        console.log("KEY: ",key);
        $rootScope.lang = key;
        $translate.use(key);
    };

    $("#pimage").change(function(event){
        console.log(event.files[0]);
    });
});


app.controller('staff_ctrl', function($scope, $http, $route){
    // console.log("Staff Controller");
    $("#createProductSection").hide();
    
    $scope.getStaff = function(){
        $http.get('http://localhost/gym/Admin/getStaff', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.staff_data = response.data.data;
            }
        });
    }
    $scope.getStaff();

    $scope.showCreateProductSection = function(){
        $("#createProductSection").slideDown();
    }

    // Dropzone of add staff code starts 
    $scope.$on("$viewContentLoaded", function () {
        $("#staff_dropzone").dropzone({
            url: 'http://localhost/gym/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.staff_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#staff_dropzone");
        };
        // Dropzone of add product page code ends 
    });

    $scope.createStaff = function(){
        var postData = $.param({
            fullname: $scope.ng_sname,
            phone: $scope.ng_sphone,
            address: $scope.ng_saddress,
            blood_group: $scope.ng_sbgroup,
            age: parseFloat($scope.ng_sage, 10),
            gender: $scope.ng_gender,
            cnic: $scope.ng_scnic,
            image: $scope.staff_image
        });

        $http.post('http://localhost/gym/Admin/createStaff', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createProductSection").slideUp();
                        $scope.getStaff();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.delStaff = function(id){
        $http.delete('http://localhost/gym/Admin/delStaff/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddProduct = function(){
        $("#createProductSection").slideUp();
        
        // notify('Poor Connection!', 'warning');
        // notify('Poor Connection!', 'error');
        // notify('Poor Connection!', 'info');
    }

    // Dropzone of update staff code starts
    $scope.edit_staff_image = ''; 
    $scope.$on("$viewContentLoaded", function () {
        $("#editstaff_dropzone").dropzone({
            url: 'http://localhost/gym/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.edit_staff_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#staff_dropzone");
        };
        // Dropzone of add product page code ends 
    });


    $scope.updateStaffModal = function(staff){
        $scope.ng_editsid = staff.instructor_id;
        $scope.ng_editsname = staff.instructor_name;
        $scope.ng_editgender = staff.gender;
        $scope.ng_editsphone = staff.phone;
        $scope.ng_editsbgroup = staff.blood_group;
        $scope.ng_editsage = parseFloat(staff.age, 10);
        $scope.ng_editscnic = staff.cnic;
        $scope.ng_editsaddress = staff.address;
        $('#modalStaffEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalStaffEdit').modal('show');
    }

    $scope.updateStaff = function(){
        var postData = $.param({
            id: $scope.ng_editsid,
            fullname: $scope.ng_editsname,
            phone: $scope.ng_editsphone,
            address: $scope.ng_editsaddress,
            blood_group: $scope.ng_editsbgroup,
            age: parseFloat($scope.ng_editsage, 10),
            gender: $scope.ng_editgender,
            cnic: $scope.ng_editscnic,
            image: $scope.edit_staff_image
        });

        $http.post('http://localhost/gym/Admin/updateStaff', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalStaffEdit').modal('hide');
                        $scope.getStaff();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });

    }

    $scope.viewStaffModal = function(staff){
        $scope.view_sname = staff.instructor_name;
        $scope.view_gender = staff.gender;
        $scope.view_sphone = staff.phone;
        $scope.view_sbgroup = staff.blood_group;
        $scope.view_sage = parseFloat(staff.age, 10);
        $scope.view_scnic = staff.cnic;
        $scope.view_saddress = staff.address;
        $scope.view_simage = staff.image;
        $('#modalStaffView').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalStaffView').modal('show');
    }

});


app.controller('package_ctrl', function($scope, $http, $route){
    // console.log("package Controller");
    $("#createCategorySection").hide();
    
    $scope.getPackage = function(){
        $http.get('http://localhost/gym/Admin/getPackage', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.packages_data = response.data.data;
                // console.log($scope.members_data);
            }
        });
    }
    $scope.getPackage();

    $scope.showCreateCategorySection = function(){
        $("#createCategorySection").slideDown();
    }

    $scope.createPackage = function(){
        var postData = $.param({
            label: $scope.ng_packname,
            amount: $scope.ng_packamount,
            period: $scope.ng_packperiod
        });

        $http.post('http://localhost/gym/Admin/createPackage', postData, {
            headers:{ 
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createCategorySection").slideUp();
                        $scope.getPackage();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.delPackage = function(id){
        $http.delete('http://localhost/gym/Admin/delPackage/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddCategory = function(){
        $("#createCategorySection").slideUp();
    }

    

    $scope.updatePackageModal = function(pack){
        $scope.ng_editpackid = pack.package_id;
        $scope.ng_editpackname = pack.package_name;
        $scope.ng_editpackamount = pack.package_amount;
        $scope.ng_editpackperiod = pack.package_period;
        $('#modalPackageEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalPackageEdit').modal('show');
    }

    $scope.updatePackage = function(){
        var postData = $.param({
            id: $scope.ng_editpackid,
            label: $scope.ng_editpackname,
            amount: $scope.ng_editpackamount,
            period: $scope.ng_editpackperiod
        });

        $http.post('http://localhost/gym/Admin/updatePackage', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalPackageEdit').modal('hide');
                        $scope.getPackage();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

});


app.controller('members_ctrl', function($scope, $http, $route){
    // console.log("Members Controller");
    $("#createMemberSection").hide();

    $scope.getMembers = function(){
        $http.get('http://localhost/gym/Admin/getMembers', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.members_data = response.data.data;
                // console.log($scope.members_data);
            }
        });
    }
    $scope.getMembers();

    $scope.getStaff = function(){
        $http.get('http://localhost/gym/Admin/getStaff', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.staff_data = response.data.data;
            }
        });
    }
    $scope.getStaff();

    $scope.getPackage = function(){
        $http.get('http://localhost/gym/Admin/getPackage', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.packages_data = response.data.data;
            }
        });
    }
    $scope.getPackage();


    $scope.showCreateMemberSection = function(){
        $("#createMemberSection").slideDown();
    }


    $scope.createMember = function(){
        var postData = $.param({
            fullname: $scope.ng_mname,
            phone: $scope.ng_mphone,
            address: $scope.ng_maddress,
            blood_group: $scope.ng_mbgroup,
            age: parseFloat($scope.ng_mage, 10),
            gender: $scope.ng_gender,
            cnic: $scope.ng_mcnic,
            pack_id: $scope.ng_mpack,
            ref_id: $scope.ng_mref,
            image: $scope.member_image
        });

        $http.post('http://localhost/gym/Admin/createMember', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $("#createMemberSection").slideUp();
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.delMember = function(id){
        $http.delete('http://localhost/gym/Admin/delMember/' + id, $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                notify(response.data.error, "success");
                setTimeout(function(){
                    $route.reload();
                }, 1000)
            }
            else{
                notify(response.data.error, "error");
            }
        });
    }

    $scope.hideaddMember = function(){
        $("#createMemberSection").slideUp();
    }

    // Dropzone of add member page code starts 
    $scope.$on("$viewContentLoaded", function () {
         $("#member_dropzone").dropzone({
             url: 'http://localhost/gym/Admin/image_upload',
             addRemoveLinks: true,
             maxFiles: 1,
             maxFilesize: 2, // MB
             paramName: "image",
             dictDefaultMessage: "Upload Image",
             acceptedFiles: "image/*",
             success: function (file, response) { 
                 $scope.member_image = response.data;
             }
         });
         $scope.clear = () => {
             var myDropzone = Dropzone.forElement("#member_dropzone");
         };
         // Dropzone of add product page code ends 
    });

    $scope.viewMemberModal = function(member){
        $scope.view_mname = member.fullname;
        $scope.view_gender = member.gender;
        $scope.view_mphone = member.phone;
        $scope.view_mbgroup = member.blood_group;
        $scope.view_mage = parseFloat(member.age, 10);
        $scope.view_mcnic = member.cnic;
        $scope.view_maddress = member.address;
        $scope.view_mimage = member.image;
        $scope.view_mpack = member.package_name;
        $scope.view_mref = member.instructor_name;
        $('#modalMemberView').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalMemberView').modal('show');
    }

    $scope.updateMemberModal = function(member){
        $scope.ng_editmid = member.member_id;
        $scope.ng_editmname = member.fullname;
        $scope.ng_editmgender = member.gender;
        $scope.ng_editmphone = member.phone;
        $scope.ng_editmbgroup = member.blood_group;
        $scope.ng_editmage = parseFloat(member.age, 10);
        $scope.ng_editmcnic = member.cnic;
        $scope.ng_editmpack = member.pack_id;
        $scope.ng_editmref = member.ref_id;
        $scope.ng_editmaddress = member.address;
        $('#modalMemberEdit').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalMemberEdit').modal('show');
    }

    // Dropzone of update member page code starts 
    $scope.edit_member_image = '';
    $scope.$on("$viewContentLoaded", function () {
        $("#editmember_dropzone").dropzone({
            url: 'http://localhost/gym/Admin/image_upload',
            addRemoveLinks: true,
            maxFiles: 1,
            maxFilesize: 2, // MB
            paramName: "image",
            dictDefaultMessage: "Upload Image",
            acceptedFiles: "image/*",
            success: function (file, response) { 
                $scope.edit_member_image = response.data;
            }
        });
        $scope.clear = () => {
            var myDropzone = Dropzone.forElement("#member_dropzone");
        };
   });

    $scope.updateMember = function(){
        var postData = $.param({
            id: $scope.ng_editmid,
            fullname: $scope.ng_editmname,
            phone: $scope.ng_editmphone,
            address: $scope.ng_editmaddress,
            blood_group: $scope.ng_editmbgroup,
            age: parseFloat($scope.ng_editmage, 10),
            gender: $scope.ng_editmgender,
            cnic: $scope.ng_editmcnic,
            pack_id: $scope.ng_editmpack,
            ref_id: $scope.ng_editmref,
            image: $scope.edit_member_image
        });

        $http.post('http://localhost/gym/Admin/updateMember', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalMemberEdit').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });

    }
});


app.controller('fee_ctrl', function($scope, $http, $route){
    console.log("Fee Collection Controller");

    $scope.getMembers = function(){
        $http.get('http://localhost/gym/Admin/getMembers', $.param({}), {
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function (response) {
            if (response.data.status) {
                $scope.members_data = response.data.data;
                console.log($scope.members_data);
            }
        });
    }
    $scope.getMembers();

    $scope.collectFeeModal = function(member){
        $('#modalCollectFee').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#modalCollectFee').modal('show');
        $scope.ng_memid = member.member_id;
        $scope.ng_memname = member.fullname;
        $scope.ng_mempack = member.package_name;
        $scope.ng_memfee = member.package_amount;
        $scope.ng_feedate = new Date();
    }

    $scope.collectFee = function(){
        var postData = $.param({
            mem_id: $scope.ng_memid,
            pack_name: $scope.ng_mempack,
            fee: $scope.ng_memfee,
            deposit_date: moment($scope.ng_feedate).format("YYYY-MM-DD"),
            status: 1
        });

        $http.post('http://localhost/gym/Admin/collectFee', postData, {
            headers:{
                "Content-Type": "application/x-www-form-urlencoded",
            },
        }).then(function(response){
            if(response.data.status == true){
                if(response.data.data !== null){
                    notify(response.data.error, "success");
                    setTimeout(function(){
                        $('#modalCollectFee').modal('hide');
                        $scope.getMembers();
                    }, 1000);
                }
                else{
                    notify(response.data.error, "warning");
                }
            }
            else{
                notify(response.data.error, "error");
            }
        });


        // notify('Fee Collected Successfully', 'success');
        // $('#modalCollectFee').modal('hide');
    }
});