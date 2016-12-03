
$(window).on('load', function () {
    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");
});

(function ($) {

    var cnfg = RentoApp.Config;
    var cnstnt = RentoApp.Constants;
    var utilityGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                  cnstnt.SERVICES + cnstnt.FWD_SLASH + cnstnt.UTILITY + cnstnt.FWD_SLASH;
    var serviceGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                 cnstnt.SERVICES + cnstnt.FWD_SLASH;


    //prevent form submit.
    $('#login-form,#register-form,#regver-form,#modal-fwgpwd-form').on('submit', function (event) {
        event.preventDefault();
    });

    //clear modal fields on close.
    $('.rento-auth-modal').on('hidden.bs.modal', function () {
        var $modalId = $(this).attr('id');
        if ($modalId == 'modal-login') {
            var $elementfgPwd = $(this).find("#modal-fwgpwd-form");
            $elementfgPwd.hasClass('rento-hidden') ? 0 : $elementfgPwd.addClass('rento-hidden');
            var $elementLgn = $(this).find("#modal-login-form");
            $elementLgn.hasClass('rento-hidden') ? $elementLgn.removeClass('rento-hidden'): 0;
        } else if ($modalId == 'modal-register') {
            var $reg = $(this).find("#modal-body-register");
            var $regver = $(this).find("#modal-body-regverify");
            $regver.hasClass('rento-hidden') ? 0 : $regver.addClass('rento-hidden');
            $reg.hasClass('rento-hidden') ? $reg.removeClass('rento-hidden') : 0;
        }
        $(this).find("input:not(:submit):not(:button),textarea,select").val('').end();
    });


    //Get the current location of user.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }

    //show current location api.
    function showPosition(position) {
        lat = position.coords.latitude;
        long = position.coords.longitude;
        var city, state, currentLocation;
        var url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + long + "&sensor=true";
        var clbck = {
            scs: function (data) {
                var address = JSON.parse(data);
                var formattedAddress = address.results[0].formatted_address.split(",");
                city = formattedAddress[formattedAddress.length - 3].trim();
                state = formattedAddress[formattedAddress.length - 2].trim();
                state = state.split(" ")[0];
                currentLocation = city + ", " + state.toUpperCase();
                $('#rento-search-parallax #rento-search-city').val(currentLocation);
            }
        };

        RentoApp.RentoAjax.AjaxHttp(url,"GET",null,clbck);
    }


    //check the uid session.
    sessionMonitor("uid");

    //session check for userid.
    function sessionMonitor(user) {
        if (Cookies.get(user) || !!Cookies.get(user)) {
            //remove useless links.
            $('#userMenu .rento-hidden').remove();
            createUserMenu(user);
        } else {
            if (window.location.href.substr(window.location.href.lastIndexOf('/') + 1) != 'index.html') {
                //window.location.href = 'index.html';
                window.location.replace("index.html");
            }
        }
    }

    //create loggdin user menu
    function createUserMenu(user) {
        var innerli = '<li><a href="account-home.html"><i class="fa fa-user"></i> My Profile</a></li>'+
                                       '<li><a href="#"><i class="fa fa fa-cog"></i> My Bag</a></li>'+
                                        '<li><a href="account-post-product.html"><i class="fa fa-map-marker"></i> Post a Product</a></li>' +
                                        '<li><a href="#"><i class="fa fa-calendar"></i> Refer a Friend</a></li>'+
                                        '<li><a href="#"><i class="fa fa-calendar"></i> Contact Support</a></li>' +
                                        '<li class="divider"></li>'+
                                        '<li><a href="#" id="btnLogoutUser"><i class="fa fa-sign-out"></i>Log Out</a></li>';
        var li = $(document.createElement('li')).attr({
            'class': 'dropdown hasUserMenu'
        }).appendTo('#userMenu');
        var a = $(document.createElement('a')).attr({
            'href': '#',
            'class': 'dropdown-toggle',
            'data-toggle':'dropdown',
            'aria-expanded':'false'
        }).text(Cookies.get(user)).appendTo(li);
        $(document.createElement('i')).attr({
            'class': 'glyphicon glyphicon-log-in hide visible-xs'
        }).appendTo(a);
        $(document.createElement('b')).attr({
            'class': 'caret'
        }).appendTo(a);
        var ul = $(document.createElement('ul')).attr({
            'class': 'dropdown-menu'
        }).appendTo(li);
        $('.dropdown-menu').append(innerli);

        $("#btnLogoutUser").on('click', function () {
            ajaxLogout(user);
        });
    }


    //logout for user.
    function ajaxLogout(user) {
        window.location.reload();
        Cookies.remove(user);
    }


    //create navbar - get data.
    getCtgrs();

    //autocomplete search parallax.
    searchParallaxAutoComplete();

    //get categories and subcategories.
    function getCtgrs() {
        var lclUrl = serviceGblURL + "category/subcategory";
        var callback = {
            scs: function (data) {
                navStatic(data);
            },
            flr: function (data) {
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, callback);
    }

    //create navbar static.
    function navStatic(data) {
        var ctgy = JSON.parse(data);
        var ctgycnt = data.length;
        var rentonav = "#rento-navbar-nav";
        for (var i = 0; i < ctgycnt; i++) {
            var sbctgycnt = ctgy[i].subcategory.length;
            var sbctgy = ctgy[i].subcategory;
            //create categories
            var ctgyli = $(document.createElement('li')).attr({
                'class': 'dropdown mega-dropdown',
            }).appendTo(rentonav);

            var ctgya = $(document.createElement('a')).attr({
                'id': 'rentoctgy_' + ctgy[i].id,
                'class': 'dropdown-toggle',
                'href': ctgy[i].link,
                'data-toggle': 'dropdown'
            })
          .text(ctgy[i].name + " ")
          .appendTo(ctgyli);

            var ctgyacaret = $(document.createElement('span')).attr({
                'class': 'caret'
            }).appendTo(ctgya);

            var drpdwnmenu = $(document.createElement('div')).attr({
                'class': 'dropdown-menu mega-dropdown-menu'
            }).appendTo(ctgyli);

            var cntnrfld = $(document.createElement('div')).attr({
                'class': 'container-fluid'
            }).appendTo(drpdwnmenu);

            var tbcnt = $(document.createElement('div')).attr({
                'class': 'tab-content'
            }).appendTo(cntnrfld);

            var tbpne = $(document.createElement('div')).attr({
                'class': 'tab-pane active'
            }).appendTo(tbcnt);

            var ulnvlst = $(document.createElement('ul')).attr({
                'class': 'nav-list list-inline'
            }).appendTo(tbpne);

            for (var j = 0; j < sbctgycnt; j++) {
                var sbctgyli = $(document.createElement('li')).appendTo(ulnvlst);

                var lia = $(document.createElement('a')).attr({
                    'href': sbctgy[j].link,
                    'id': 'rentosbctgy_' + sbctgy[j].id
                }).appendTo(sbctgyli);

                var img = $(document.createElement('img')).attr({
                    'class': 'rento-navbar-img',
                    'src': 'content/images/rento/navbar/' + sbctgy[j].name + '.png'
                }).appendTo(lia);

                var spn = $(document.createElement('span')).attr({
                    'class': 'nav-list-label'
                }).text(sbctgy[j].name).appendTo(lia);
            }
        }
    }

    //autocomplete for search parallax
    function searchParallaxAutoComplete() {
        var config = RentoApp.Config;
        var constants = RentoApp.Constants;
        var attr, qryprm;
        var lclUrl1 = serviceGblURL + "address/cityname";

        var lclUrl2 = serviceGblURL + "category/subcategoryname";

        var rentoSearch = RentoApp.RentoSearch.SearchParallax;
        rentoSearch.Autocomplete("autocomplete-city", "rento-search-city", "city", lclUrl1);
        rentoSearch.Autocomplete("autocomplete-multiparam", "rento-search-category", "category", lclUrl2);
    }

    //forgot passord modal manipulation
    $("#modal-login #btnForgotPassword").on("click", function () {
        var $field = $("#login-user");
        $field.parsley().validate();
        if ($field.parsley().isValid()) {
            animateLRModalLoader('login', 'start');
            var $username = $("#login-user").val();
            var fgpwd = new Object();
            var fgpwdDetails = new Object();
            fgpwdDetails.username = $username;
            fgpwd.forgotPassword = fgpwdDetails;
            var dt = JSON.stringify(fgpwd);
            forgotPassword(dt);
        }
    });


    function forgotPassword(data) {
        var dtParsed = JSON.parse(data);
        var lclUrl = serviceGblURL + "user/forgotpassword";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.status == '1') {
                    rentoModalAlert(response.msg.trim());
                    $("#dup-login-user").val(dtParsed.forgotPassword.username);
                    animateLRModalLoader('login', 'stop');
                    $("#modal-fwgpwd-form").removeClass('rento-hidden');
                    $("#set-new-password,#btnFgPwdSubmit").attr("disabled", "disabled");
                    $("#modal-login-form").addClass('rento-hidden');
                    $("#uid-token").attr("value", response.userId);
                } else {
                    rentoModalAlert(response.msg.trim());
                    animateLRModalLoader('login', 'stop');
                }
            },
            flr: function (rsp) {
                rentoModalAlert(rsp.msg);
                animateLRModalLoader('login', 'stop');
            }
        };

        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }


    //verifyOTP
    $("#fgpwdotp").on("keyup", function () {
        if ($(this).val().length === 6) {
            var $value = $(this).val();
            var $userId = $("#uid-token").val();
            var verifyOtp = new Object();
            var verifyOtpDetails = new Object();
            verifyOtpDetails.userId = $userId;
            verifyOtpDetails.otp = $value;
            verifyOtp.verifyOtp = verifyOtpDetails;
            var dt = JSON.stringify(verifyOtp);
            verifyForgotPasswordOTP(dt);
        }
    });


    //varify OTP function.
    function verifyForgotPasswordOTP(data) {
        var lclUrl = serviceGblURL + "user/verifyotp";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.status == '1') {
                    rentoModalAlert(response.msg.trim());
                    $("#set-new-password,#btnFgPwdSubmit").removeAttr('disabled');
                } else {
                    rentoModalAlert(response.msg.trim());
                }
            },
            flr: function (rsp) {
            }
        };

        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }

    //set new password.
    $("#btnFgPwdSubmit").on("click", function () {
        if ($('#dup-login-form').parsley().isValid()) {
            animateLRModalLoader('login', 'start');
            var setPwd = new Object();
            var setPwdDetails = new Object();
            var $userId = $("#uid-token").val();
            var $pwd = $('#set-new-password').val();
            setPwdDetails.userId = $userId;
            setPwdDetails.password = $pwd;
            setPwd.setPassword = setPwdDetails;
            var dt = JSON.stringify(setPwd);
            setPassword(dt);
        }
    });

    //set new password function.
    function setPassword(data){
        var lclUrl = serviceGblURL + "user/setpassword";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.status == '1') {
                    rentoModalAlert(response.msg.trim()+"\nPlease Login Again!");
                    $("#modal-fwgpwd-form").addClass('rento-hidden');
                    $("#modal-login-form").removeClass('rento-hidden');
                    animateLRModalLoader('login', 'stop');
                } else {
                    rentoModalAlert(response.msg.trim());
                    animateLRModalLoader('login', 'stop');
                }
            },
            flr: function (rsp) {
                rentoModalAlert(rsp);
                animateLRModalLoader('login', 'stop');
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }

    //ajax login.
    $('#modal-login #btnModalLogin').on('click', function () {
        if ($('#login-form').parsley().isValid()) {
            animateLRModalLoader('login', 'start');
            var login = new Object();
            var loginDetails = new Object();
            var uname = $('#login-user').val();
            var pwd = $('#login-password').val();
            loginDetails.username = uname;
            loginDetails.password = pwd;
            login.login = loginDetails;
            var dt = JSON.stringify(login);
            ajaxLoginController(dt);
        }
    });

    //Login with modal
    function ajaxLoginController(data) {
        var lclUrl = serviceGblURL + "login";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.msg == 'Success') {
                    window.location.reload();
                    Cookies.set('uid', response.userId, { secure: false });
                    $("#uid-token").attr("value", response.userId);
                    animateLRModalLoader('login', 'stop');
                } else {
                    rentoModalAlert(response.msg.trim());
                    animateLRModalLoader('login', 'stop');
                }
            },
            flr: function (rsp) {
                rentoModalAlert(rsp);
                animateLRModalLoader('login', 'stop');
            }
        };

        RentoApp.RentoAjax.AjaxHttp(lclUrl,"POST",data,clbck);
    }

    //ajax register.
    $('#modal-register #btnModalRegister').on('click', function () {
        //window.Parsley.addAsyncValidator('mycustom', function (xhr) {
        //    if (xhr.status == '200') {
        //        return 200
        //    }
        //}, utilityGblURL + 'getEmail.php');

        if ($('#register-form').parsley().isValid()) {
            var register = new Object();
            var registerDetails = new Object();
            animateLRModalLoader('register', 'start');
            registerDetails.name = $('#register-name').val();
            registerDetails.email = $('#register-email').val();
            registerDetails.contact = $('#register-mobile').val();
            registerDetails.password = $('#register-password').val();
            register.register = registerDetails;
            var dt = JSON.stringify(register);
            ajaxRegisterController(dt);
        } 
    });

    //Login with modal
    function ajaxRegisterController(data) {
        var lclUrl = serviceGblURL + "register";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.status == "1") {
                    rentoModalAlert(response.msg.trim());
                    //$("#rento-u").val(response.userId);
                    $("#uid-token").attr("value", response.userId);
                    $("#modal-body-regverify").removeClass("rento-hidden");
                    $("#modal-body-register").addClass("rento-hidden");
                    animateLRModalLoader('register', 'stop');
                } else {
                    rentoModalAlert(response.msg.trim());
                    animateLRModalLoader('register', 'stop');
                }
            },
            flr: function (rsp) {
                rentoModalAlert(rsp);
                animateLRModalLoader('register', 'stop');
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }


    //verify user(otp).
    $('#modal-body-regverify #regverotp').on('keyup', function () {
        if ($(this).val().length === 6) {
            if ($('#regver-form').parsley().isValid()) {
                animateLRModalLoader('register', 'start');
                var verify = new Object();
                var verifyDetails = new Object();
                verifyDetails.userId = $("#uid-token").val();
                verifyDetails.otp = $("#regverotp").val();
                verify.verifyOtp = verifyDetails;
                var dt = JSON.stringify(verify);
                regVerfiyUser(dt);
            }
        }
    });

    //verify user(otp).
    function regVerfiyUser(data) {
        var lclUrl = serviceGblURL + "user/verifyotp";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                if (response.status == "1") {
                    rentoModalAlert(response.msg.trim());
                    Cookies.set('uid', response.userId, { secure: false });
                    window.location.reload();
                    animateLRModalLoader('register', 'stop');
                } else {
                    rentoModalAlert(response.msg.trim());
                    animateLRModalLoader('register', 'stop');
                }
            },
            flr: function (rsp) {
                rentoModalAlert(rsp);
                animateLRModalLoader('register', 'stop');
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }

    function animateLRModalLoader(windowName,step) {
        if (step == 'start') {
            $('.se-pre-con-' + windowName).fadeIn('fast');
            $('#modal-body-' + windowName).css({
                'opacity': '0.2'
            });

        } else if(step == 'stop'){
            $('.se-pre-con-' + windowName).fadeOut('fast');
            $('#modal-body-' + windowName).css({
                'opacity': '1'
            });
        }
    }


    function rentoModalAlert(msg) {

        $.notify({
            message: msg
        },
                    {
                        z_index: 10031,
                        delay: 2000,
                        offset: {
                            x: 0,
                            y: 100
                        },
                        type: "info",
                        animate: {
                            enter: 'animated bounceInUp',
                            exit: 'animated bounceOutDown'
                        },
                        placement: {
                            from: "bottom",
                            align: "center"
                        }
                    });
    }

})(jQuery);

