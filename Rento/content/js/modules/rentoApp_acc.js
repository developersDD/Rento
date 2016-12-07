﻿(function () {
    //update product - validate image remaining and then view product.
    var cnfg = RentoApp.Config;
    var cnstnt = RentoApp.Constants;
    var utilityGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                  cnstnt.SERVICES + cnstnt.FWD_SLASH + cnstnt.UTILITY + cnstnt.FWD_SLASH;
    var serviceGblURL = cnfg.localBaseUrl + cnstnt.FWD_SLASH + cnstnt.RENTOMOJO + cnstnt.FWD_SLASH +
                 cnstnt.SERVICES + cnstnt.FWD_SLASH;

    //init product image file input
    $(".product_image").fileinput();

    if ($('#postProductContainer').length) {
            //animate loader.
            animateProductFormTabsLoader('start', 'post');
            //call product manipulation.
            initProductFormTabs();
            getProductCategories('');
            initProductImageInput();
    } else if ($('#updateProductContainer').length) {
        //get user products.
        animateProductFormTabsLoader('start', 'bag');
        getUserProducts(Cookies.get('uid'));
        //call product manipulation.
        initProductFormTabs();
        getProductCategories('');
    }

    //get all the user products.
    function getUserProducts(id) {
        var lclUrl = serviceGblURL + "user/product/" + id;
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                animateProductFormTabsLoader('start', 'bag');
                userProductDisplay(response);
            },
            flr: function (rsp) {
                animateProductFormTabsLoader('stop', 'bag');
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "GET", null, clbck);
    }

    //display all user products.
    function userProductDisplay(data) {
        var imageUrl = cnfg.localBaseUrl + cnstnt.FWD_SLASH + "retomojo/images" + cnstnt.FWD_SLASH;
        var dt = $('#rento-products-bag').DataTable({
            aLengthMenu: [
                [5,10, 25, 50, 100, 200, -1],
                [5,10, 25, 50, 100, 200, "All"]
            ],
            destroy: true,
            iDisplayLength: 5,
            "columns": [
             { "data": "0" },
            { "data": "1" },
            { "data": "2" },
            { "data": "3" }],
            "order": [[1, 'asc']]
        });
        //Adding data to Datatable
        for (var i = 0; i < data.length; i++) {
            var image = 'content/images/rento/navbar/mobile.png',
                productType = 'Rented',
                productTypeElement = '<span class="label label-primary">'+productType+'</span>';
            if (data[i].image[0]) {
                image = imageUrl + data[i].image[0].image_name
            }
            if (data[i].product_type == '2') {
                productType = 'Hired';
            }
            var productBox1 = '<img src=" ' + image + '" style="height:130px;width:150px;"/>',
                productBox2 = '<div><br><h3><b>' + data[i].name + '</b></h3><p style="max-width:70%">' + data[i].description + ''+
                '</p><small>Posted on : ' + data[i].posted_date + '</small><br>' + productTypeElement+'</div>',
                productBox3 = '<div style="text-align:center"><br><h3><b>Rs. ' + data[i].rate_per_day + '</b><small> /per day</small>'+
                              '</h3>' +
                              '<a href="#" id="terms_condition_' + i + "_" + data[i].id + '"><label class="label label-success">' +
                              'Terms and Conditions</label></a></div>',

                productBox4 = '<div class="text-center center-block" style="width:250px">' +
                            '<div class="product-share clearfix"><div class="socialIcon">' +
                            '<a href="#" class="rento-product-actions" data-toggle="modal" data-target="#product-details-modal"' +
                            'data-backdrop="static" data-keyboard="false"' +
                            'id="viewProduct_' + i +"_"+ data[i].id + '"> <i class="fa fa-eye"></i></a>' +
                            '<a href="#" class="rento-product-actions" data-toggle="modal" data-target="#updateProductContainer"' +
                            'data-backdrop="static" data-keyboard="false"'+
                            'id="editProduct_' + i + "_" + data[i].id + '"> <i class="fa fa-edit"></i></a>' +
                            '<a href="#" class="rento-product-actions" id="deleteProduct_' + i + "_" + data[i].id + '">'+
                            '<i class="fa fa-trash"></i></a></div>' +
                            '<div class="clearfix"><br><p>SHARE </p>' +
                            '<a href="#"><i id="social-fb" class="fa fa-facebook-square fa-2x social"></i></a>' +
                             '<a href="#"><i id="social-tw" class="fa fa-twitter-square fa-2x social"></i></a>' +
                             '<a href="#"><i id="social-gp" class="fa fa-google-plus-square fa-2x social"></i></a>' +
                             '<a href="#"><i id="social-em" class="fa fa-envelope-square fa-2x social"></i></a>' +
                            '</div>' +
                            '</div>';
                             
            dt.row.add([productBox1, productBox2,productBox3,productBox4
            ]).draw(false);
        }
        animateProductFormTabsLoader('stop', 'bag');
        //action button click event.
        $('#rento-products-bag tbody').on('click', '.rento-product-actions', function () {
            var $id = $(this).attr('id'),
                $product = $id.split("_")[1];
            if ($id.split("_")[0] == 'editProduct') {
                getProductCategories(data[$product].category);
                getProductSubCategoriesByCategoryId(data[$product].category, data[$product].sub_category);
                $('#product-name').val(data[$product].name);
                $('#product-desc').val(data[$product].description);
                $('#terms-conditions').val(data[$product].terms_condition);
                $('#rate-per-day').val(data[$product].rate_per_day);
                $('#product-deposit').val(data[$product].deposit);
                $('#product-valid-from').val(data[$product].from_date);
                $('#product-valid-till').val(data[$product].to_date);
                data[$product].ad_type == 0 ? $('#ad-type-normal').iCheck('check') :
                                              $('#ad-type-premium').iCheck('check');
                getCountries(data[$product].address[0].country_id);
                getStatesByCountryId(data[$product].address[0].country_id, data[$product].address[0].state_id);
                getCitiesByStateId(data[$product].address[0].state_id, data[$product].address[0].city_id);
                $('#product-add-pin').val(data[$product].address[0].pincode);
                $('#product-add-area').val(data[$product].address[0].area);
                updateProductImageInput(data[$product].image);
            }
        });
    }

    //update the user product images.
    function updateProductImageInput(images) {
        if (images) {
            for(var i=0;i<=3;i++){
                var imagePath = 'content/images/rento/navbar/mobile.png',
                    imgId='rento_empty_product_image_'+(i+1);
                if(images[i]){
                    imagePath = cnfg.localBaseUrl + cnstnt.FWD_SLASH + "/retomojo/images/" + images[i].image_name,
                    imgId = 'rento_product_image_'+images[i].id;
                }
                $("#product_image_" + (i + 1)).fileinput('refresh', {
                    overwriteInitial: true,
                    maxFileSize: 5000,
                    showClose: true,
                    showCaption: false,
                    showBrowse: false,
                    browseOnZoneClick: true,
                    removeLabel: '',
                    removeIcon: '',
                    removeTitle: 'Cancel or reset changes',
                    elErrorContainer: '#kv-avatar-errors-2',
                    msgErrorClass: 'alert alert-block alert-danger',
                    defaultPreviewContent: '<img src="' + imagePath + '"' +'alt="Your Image" '+
                        'id="'+imgId+'" style="width:160px;position:relative;margin:5px 0px 5px 40px">' +
                                            '<h6 class="text muted" style="position:relative;margin-left:35%">Click to select</h6>',
                    layoutTemplates: { main2: '{preview} {remove} {browse}' },
                    allowedFileExtensions: ["jpg", "png", "gif"]
                });
            }
           
        }
    }

    //updateProductContainer mannipulation.
    $('#updateProductContainer').on('shown.bs.modal', function () {
    }).on('hidden.bs.modal', function () {
        //do something.
    });

    //prevent forms from submitting.
    $('#postProductForm,#updateProductForm').on('submit', function (e) {
        e.preventDefault();
    });

    //post product form submit.
    $('#postProductForm #btnSubmitProductDetails').on('click', function () {
        var validProduct = validateProduct('post');
        if (validProduct) {
            //post user product.
            animateProductFormTabsLoader('start', 'post');
            postUserProduct(validProduct);
        }
    });

    //update product form submit.
    $('#updateProductForm #btnUpdateProductDetails').on('click', function () {
        var validProduct = validateProduct('update');
        if (validProduct) {
            //post user product.
            console.log("-->"+validProduct);
        }
    });

    //validate product details.
    function validateProduct(validateType) {
        if ($('#' + validateType + 'ProductForm').parsley().isValid()) {
            var productData,
            imageValidated = validateProductImages(validateType);
            if (imageValidated == 'empty object') {
                rentoModalAlert('Please select atleast one image !');
            } else {
               productData = createUserProductRequestData(imageValidated);
            }
        } else {
            rentoModalAlert('Please fill in all the required fields !');
        }
        return productData;
    }

    //create user product data
    function createUserProductRequestData(validImages) {
        var productDetails = new Object();
        var product = new Object();
        var address = new Object();
        var image = new Object();
        productDetails.name = $('#product-name').val();
        productDetails.description = $('#product-desc').val();
        productDetails.category = $('#category').val();
        productDetails.sub_category = $('#sub-category').val();
        productDetails.rate_per_day = $('#rate-per-day').val();
        productDetails.owner_id = Cookies.get('uid');
        productDetails.from_date = $('#product-valid-from').val();
        productDetails.to_date = $('#product-valid-till').val();
        productDetails.ad_type = $('input[name="ad-type"]').val();
        productDetails.terms_condition = $('#terms-conditions').val();
        productDetails.product_type = 1;
        productDetails.deposit = $('#product-deposit').val();
        address.area = $('#product-add-area').val();
        address.city_id = $('#product-add-city').val();
        address.state_id = $('#product-add-state').val();
        address.country_id = $('#product-add-country').val();
        address.pincode = $('#product-add-pin').val();

        productDetails.address = address;
        productDetails.product_images = validImages;
        product.productDetails = productDetails;
        var productData = JSON.stringify(product);
        return productData;
    }

    //post user product
    function postUserProduct(data) {
        var lclUrl = serviceGblURL + "product/addproduct";
        var clbck = {
            scs: function (rsp) {
                var response = JSON.parse(rsp);
                rentoModalAlert(response.msg);
                animateProductFormTabsLoader('stop', 'post');
            },
            flr: function (rsp) {
                animateProductFormTabsLoader('stop', 'post');
            }
        };
        RentoApp.RentoAjax.AjaxHttp(lclUrl, "POST", data, clbck);
    }
    
    //validate product images.
    function validateProductImages(validateType) {
        var imageObj,imagePath="content/images/rento/navbar/mobile.png",
            validFields = $('.product_image').map(function () {
                if ($(this).val() != "" || $(this).siblings().children().find('img').attr('src') != imagePath)
                return $(this);
        }).get();

        if (validFields.length) {
            imageObj = getBase64ProductImages(validFields, validateType);
        }
        else {
            imageObj = 'empty object';
        }
        return imageObj;
    }

    //get images if validated.
    function getBase64ProductImages(validImage,validateType) {
        var imageObj = {};
        //set of 4 images tobe uploaded.
        for (var i = 0; i <= 3; i++) {
            var item = {};
            if (validImage[i]) {
                var imageData = validImage[i].siblings().children().find('img').attr('src'),
                    imageId = validImage[i].siblings().children().find('img').attr('id');
                if (imageData.match('data:image/')) {
                    item['id'] = imageId;
                    item['data'] = validImage;
                    imageObj['image' + i] = item;
                } else {
                    item['id'] = '-1';
                    item['data'] = 'empty';
                    imageObj['image' + i] = item;
                }
            } else {
                item['id'] = '-1';
                item['data'] = 'empty';
                imageObj['image' + i] = item;
            }
        }
        console.log(imageObj);
        return imageObj;
    }


        //empty subcategories on category change.
    $('#category').on('change', function () {
        var $cid = $(this).val();
        $('#sub-category')
               .empty()
               .append('<option selected="selected" value="0">Choose sub-category</option>');
        if ($cid != 0) {
            animateProductFormTabsLoader('start', 'post');
            getProductSubCategoriesByCategoryId($cid, '');
        }
        
    });

        //empty states,cities on country change.
        $('#product-add-country').on('change', function () {
            var $cid = $(this).val();
            $('.address-common-fields')
                .empty()
                .append('<option selected="selected" value="0">Choose</option>');
            if ($cid != 0) {
                animateProductFormTabsLoader('start', 'post');
                getStatesByCountryId($cid, '');
            }
        });

        //change cities on state change.
        $('#product-add-state').on('change', function () {
            var $sid = $(this).val();
            $('#product-add-city')
                .empty()
                .append('<option selected="selected" value="0">Choose city</option>'); 
            if ($sid != 0) {
                animateProductFormTabsLoader('start', 'post');
                getCitiesByStateId($sid, '');
            }
        });

        //get product categories.
        function getProductCategories(selectedValue) {
            if (!selectedValue) {
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
            } else {
                $('#category').select2('val', selectedValue);
            }
        }

        //get product sub-categories.
        function getProductSubCategoriesByCategoryId(id, selectedValue) {
                var lclUrl = serviceGblURL + "category/subcategory/" + id;
                var clbck = {
                    scs: function (rsp) {
                        var response = JSON.parse(rsp);
                        if (response.length > 0) {
                            for (var i = 0; i < response.length; i++) {
                                $("#sub-category").append($("<option></option>").val(response[i].id).html(response[i].name));
                            }
                            if (selectedValue) {
                                $('#sub-category').select2('val', selectedValue);
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
                            if (selectedValue) {
                                $('#product-add-country').select2('val', selectedValue);
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
                        if (selectedValue) {
                            $('#product-add-state').select2('val', selectedValue);
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
                        if (selectedValue) {
                            $('#product-add-city').select2('val', selectedValue);
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
                for (var i = 0; i < curInputs.length; i++) {
                    var elem = curInputs[i],
                        elemId = $(elem).attr("id");
                    if ($("#" + elemId).parsley().isValid()) {
                        isValid++;
                    } else {
                        $("#" + elemId).parsley().validate();
                    }
                }

                if (isValid == curInputs.length) {
                    nextStepWizardLi.removeClass('disabled').addClass('active');
                    nextStepWizard.trigger('click');
                    if (curStepBtn == 'step-2') {
                        //get countries in dropdown.
                        if ($('#product-add-country').has('option').length == 1){
                            animateProductFormTabsLoader('start', 'post');
                            getCountries('');
                        }
                    }
                }
            });

            $('ul.setup-panel li.active a').trigger('click');
        }

        function initProductImageInput() {
            $(".product_image").fileinput('refresh',{
                overwriteInitial: true,
                maxFileSize: 5000,
                showClose: true,
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