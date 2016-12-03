﻿(function () {

    var cnfg = RentoApp.Config;
    var cnstnt = RentoApp.Constants;
    var utilityGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                  cnstnt.SERVICES + cnstnt.FWD_SLASH + cnstnt.UTILITY + cnstnt.FWD_SLASH;
    var serviceGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                 cnstnt.SERVICES + cnstnt.FWD_SLASH;

    if ($('#postProductContainer').length) {
            //animate loader.
            animateProductFormTabsLoader('start', 'post');
            //call product manipulation.
            initProductFormTabs();
            getProductCategories('');
            initProductImageInput();
    }

    //prevent forms from submitting.
    $('#postProductForm').on('submit', function (e) {
        e.preventDefault();
    });

    //post product form submit.
    $('#postProductForm #btnSubmitProductDetails').on('click', function () {
        if ($('#postProductForm').parsley().isValid()) {
            var imageValidated = validateProductImages();
            if (imageValidated == 'empty object') {
                rentoModalAlert('Please select atleast one image !');
            } else {
                //post a product
                //postUserProduct();
            }
        }
    });

    //validate product images.
    function validateProductImages() {
        var imageObj,
            validFields = $('.product_image').map(function () {
            if ($(this).val() != "")
                return $(this);
        }).get();

        if (validFields.length) {
            imageObj = getBase64ProductImages(validFields);
        }
        else {
            imageObj = 'empty object';
        }
        return imageObj;
    }

    //get images if validated.
    function getBase64ProductImages(validImage) {
        var imageObj = [];
        //set of 4 images tobe uploaded.
        for (var i = 0; i <= 3; i++) {
            if (validImage[i]) {
                var imageData = validImage[i].siblings().children().find('img').attr('src');
                   imageObj.push('{"image'+i+'":"'+imageData+'"}');
            } else {
                imageObj.push('{"image' + i + '":"empty"}');
            }
        }
        return imageObj;
    }


        //empty subcategories on category change.
        $('#category').on('change', function () {
            animateProductFormTabsLoader('start', 'post');
            var $cid = $(this).val();
            $('#sub-category')
                .empty()
                .append('<option selected="selected" value="0">Choose</option>');
                getProductSubCategoriesByCategoryId($cid,'');
        });

        //empty states,cities on country change.
        $('#product-add-country').on('change', function () {
            var $sid = $(this).val();
            $('.address-common-fields')
                .empty()
                .append('<option selected="selected" value="0">Choose</option>');
            animateProductFormTabsLoader('start', 'post');
            getStatesByCountryId($sid,'');
        });

        //change cities on state change.
        $('#product-add-state').on('change', function () {
            var $cid = $(this).val();
            $('#product-add-city')
                .empty()
                .append('<option selected="selected" value="0">Choose</option>');
            animateProductFormTabsLoader('start', 'post');
            getCitiesByStateId($cid,'');
        });

        //get product categories.
        function getProductCategories(selectedValue) {
            var lclUrl = serviceGblURL + "category/list";
            var clbck = {
                scs: function (rsp) {
                    var response = JSON.parse(rsp);
                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            $("#category").append($("<option></option>").val(response[i].id).html(response[i].name));
                        }
                    }
                    animateProductFormTabsLoader('stop', 'post');
                },
                flr: function (rsp) {
                    animateProductFormTabsLoader('stop', 'post');
                }
            };
            RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
        }

        //get product categories.
        function getProductSubCategoriesByCategoryId(id,selectedValue) {
            var lclUrl = serviceGblURL + "category/subcategory/" + id;
            var clbck = {
                scs: function (rsp) {
                    var response = JSON.parse(rsp);
                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            $("#sub-category").append($("<option></option>").val(response[i].id).html(response[i].name));
                        }
                    }
                    animateProductFormTabsLoader('stop', 'post');
                },
                flr: function (rsp) {
                    animateProductFormTabsLoader('stop', 'post');
                }
            };
            RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
        }

        //get countries.
        function getCountries(selectedValue) {
            var lclUrl = serviceGblURL + "address/country";
            var clbck = {
                scs: function (rsp) {
                    var response = JSON.parse(rsp);
                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            $("#product-add-country").append($("<option></option>").val(response[i].id).html(response[i].countryname));
                        }
                    }
                    animateProductFormTabsLoader('stop', 'post');
                },
                flr: function (rsp) {
                    animateProductFormTabsLoader('stop', 'post');
                }
            };

            RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
        }

        //get states by country id.
        function getStatesByCountryId(id,selectedValue) {
            var lclUrl = serviceGblURL + "address/state/" + id;
            var clbck = {
                scs: function (rsp) {
                    var response = JSON.parse(rsp);
                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            $("#product-add-state").append($("<option></option>").val(response[i].id).html(response[i].statename));
                        }
                    }
                    animateProductFormTabsLoader('stop', 'post');
                },
                flr: function (rsp) {
                    animateProductFormTabsLoader('stop', 'post');
                }
            };

            RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
        }

        //get countries.
        function getCitiesByStateId(id,selectedValue) {
            var lclUrl = serviceGblURL + "address/city/" + id;
            var clbck = {
                scs: function (rsp) {
                    var response = JSON.parse(rsp);
                    if (response.length > 0) {
                        for (var i = 0; i < response.length; i++) {
                            $("#product-add-city").append($("<option></option>").val(response[i].id).html(response[i].cityname));
                        }
                    }
                    animateProductFormTabsLoader('stop', 'post');
                },
                flr: function (rsp) {
                    animateProductFormTabsLoader('stop', 'post');
                }
            };

            RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
        }


        //manipulating the product form tabs.
        function initProductFormTabs() {
            var navListItems = $('ul.setup-panel li a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

            allWells.hide();
            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                        $item = $(this).closest('li');

                if (!$item.hasClass('disabled')) {
                    navListItems.closest('li').removeClass('active');
                    $item.addClass('active');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('ul.setup-panel li a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    nextStepWizardLi = $('ul.setup-panel li a[href="#' + curStepBtn + '"]').parent().next("li"),
                    formElements = "input[type='text'],input[type='email'],input[type='number'],input"+                                                                           "[type='date'],input[type='radio'],select,textarea",
                    curInputs = curStep.find(formElements),
                    isValid = 0;
                //for (var i = 0; i < curInputs.length; i++) {
                //    var elem = curInputs[i],
                //        elemId = $(elem).attr("id");
                //    if ($("#" + elemId).parsley().isValid()) {
                //        isValid++;
                //    } else {
                //        $("#" + elemId).parsley().validate();
                //    }
                //}

                //if (isValid == curInputs.length) {
                    nextStepWizardLi.removeClass('disabled').addClass('active');
                    nextStepWizard.trigger('click');
                    if (curStepBtn == 'step-2') {
                        //get countries in dropdown.
                        if ($('#product-add-country').has('option').length == 1){
                            animateProductFormTabsLoader('start', 'post');
                            getCountries('');
                        }
                    }
                //}
            });

            $('ul.setup-panel li.active a').trigger('click');
        }

        function initProductImageInput() {
            $(".product_image").fileinput({
                overwriteInitial: true,
                maxFileSize: 5000,
                showClose: false,
                showCaption: false,
                showBrowse: false,
                browseOnZoneClick: true,
                removeLabel: '',
                removeIcon: '',
                removeTitle: 'Cancel or reset changes',
                elErrorContainer: '#kv-avatar-errors-2',
                msgErrorClass: 'alert alert-block alert-danger',
                defaultPreviewContent: '<img src="/content/images/rento/navbar/mobile.png"'+
                                        'alt="Your Image" style="width:160px;position:relative;margin:5px 0px 5px 40px">' +
                                        '<h6 class="text muted" style="position:relative;margin-left:35%">Click to select</h6>',
                layoutTemplates: { main2: '{preview} {remove} {browse}' },
                allowedFileExtensions: ["jpg", "png", "gif"]
            });
        }

        //animate loader.
        function animateProductFormTabsLoader(step, windowName) {
            if (step == 'start') {
                $('.se-pre-con-tabs').fadeIn('fast');
                $('#' + windowName +'ProductContainer').css({
                    'opacity': '0.2'
                });

            } else if (step == 'stop') {
                $('.se-pre-con-tabs').fadeOut('fast');
                $('#' + windowName + 'ProductContainer').css({
                    'opacity': '1'
                });
            }
        }



    //Notify Rento.
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