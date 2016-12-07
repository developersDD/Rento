// rentoApp
//extend
function extend(destination, source) {
    var toString = Object.prototype.toString,
        objTest = toString.call({});
    for (var property in source) {
        if (source[property] && objTest == toString.call(source[property])) {
            destination[property] = destination[property] || {};
            extend(destination[property], source[property]);
        } else {
            destination[property] = source[property];
        }
    }
    return destination;
};

console.group("objExtend namespacing tests");

var RentoApp = RentoApp || {};

extend(RentoApp, {
    Utilities: {

    },
    Methods: {

    },
    RentoNotify: {
        settings:{
            type: {
                type1: 'info',
                type2: 'success',
                type3: 'warning',
                type4: 'danger'
            },
            placement: {
                position1: 'top',
                position2: 'right',
                position3: 'left',
                position4: 'bottom',
                position5:'center'
            },
            animation: {
                attentionSeeker:{
                    bounce: 'animated bounce',
                    flash: 'animated flash',
                    pulse: 'animated pulse',
                    rubber: 'animated rubberBand',
                    shake: 'animated shake',
                    swing: 'animated swing',
                    tada: 'animated tada',
                    wobble: 'animated wobble',
                    jello: 'animated jello'
                },
                bouncingEntrance: {
                    bounceIn: 'animated bounceIn',
                    bounceInDown: 'animated bounceInDown',
                    bounceInLeft: 'animated bounceInLeft',
                    bounceInRight: 'animated bounceInRight',
                    bounceInUp: 'animated bounceInUp'
                },
                bouncingExits: {
                    bounceOut: 'animated bounceOut',
                    bounceOutDown: 'animated bounceOutDown',
                    bounceOutLeft: 'animated bounceOutLeft',
                    bounceOutRight: 'animated bounceOutRight',
                    bounceOutUp: 'animated bounceOutUp'
                },
                fadingEntrance: {
                    fadeIn: 'animated fadeIn',
                    fadeInDown: 'animated fadeInDown',
                    fadeInDownBig: 'animated fadeInDownBig',
                    fadeInLeft: 'animated fadeInLeft',
                    fadeInLeftBig: 'animated fadeInLeftBig',
                    fadeInRight: 'animated fadeInRight',
                    fadeInRightBig: 'animated fadeInRightBig',
                    fadeInUp: 'animated fadeInUp',
                    fadeInUpBig: 'animated fadeInUpBig'
                },
                fadingExits: {
                    fadeIn: 'animated fadeOut',
                    fadeOutDown: 'animated fadeOutDown',
                    fadeOutDownBig: 'animated fadeOutDownBig',
                    fadeOutLeft: 'animated fadeOutLeft',
                    fadeOutLeftBig: 'animated fadeOutLeftBig',
                    fadeOutRight: 'animated fadeOutRight',
                    fadeOutRightBig: 'animated fadeOutRightBig',
                    fadeOutUp: 'animated fadeOutUp',
                    fadeOutUpBig: 'animated fadeOutUpBig'
                },
                flippers: {
                    flip: 'animated flip',
                    flipInX: 'animated flipInX',
                    flipInY: 'animated flipInY',
                    flipOutX: 'animated flipOutX',
                    flipOutY: 'animated flipOutY'
                }
            }
        }
    },
    Config: {
        baseUrl: "",
        localBaseUrl: "http://192.168.0.18"
        //localBaseUrl: "http://192.168.200.43"
        },
            Constants: {
                    EQUALS_OPERATOR: "=",
                    QUERY_DELIMETER: "?",
                    FWD_SLASH: "/",
                    RENTOMOJO: "retomojo",
                    SERVICES: "services",
                    UTILITY: "utility",
                    USER: "user"
            },

            RentoSearch: {
                //search parallax.
                    SearchParallax: {
                        Autocomplete: function (acelement, felement, attr, url) {
                            jQuery("." + acelement).autocomplete({
                                source: function (request, response) {
                                    jQuery.getJSON(url + "/" + request.term, {
                                    }, function (data) {
                                        //check if not "No Match Found"
                                        if (!data.hasOwnProperty("msg")) {
                                            if (Object.keys(data[0])[0] == 'cityname') {
                                                jQuery.each(data, function (_, item) {
                                                    item.label = item.cityname;
                                                    item.value = item.statename;
                                                });
                                            } else if (Object.keys(data[0])[0] == 'name') {
                                                jQuery.each(data, function (_, item) {
                                                    item.label = item.name;
                                                });
                                            }
                                            if (data.length) {
                                                response(data);
                                            }
                                        }
                                    });  
                                },
                                focus: function (event, ui) {
                                    event.preventDefault();
                                    jQuery('#' + felement).val(ui.item.label);
                                },
                                select: function (event, ui) {
                                    event.preventDefault();
                                    if (attr == "city") {
                                        jQuery('#' + felement).val(ui.item.cityname + ", " + ui.item.statename);
                                    } else if (attr == "category") {
                                        jQuery('#' + felement).val(ui.item.name);
                                    }
                                }
                            });
                        }
                    }
            }
});


extend(RentoApp, {
    // ajax call.
    RentoAjax: {
        AjaxHttp: function (u, mtd, dt, clbck) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4) {
                    if (xmlhttp.status == 200) {
                        clbck.scs(xmlhttp.responseText);
                    }
                    else {
                        clbck.flr(xmlhttp.responseText);
                    }
                }
            }
            xmlhttp.open(mtd, u, true);
            xmlhttp.setRequestHeader('Content-Type', 'application/json');
            xmlhttp.send(dt);
        }
    }
});


extend(RentoApp.Methods, {
    // alert or notification.
    rentoNotify:function(options){
        $.notify({
            message: options.msg
        },
        {
            z_index: 10000,
            delay: options.delay,
            offset: {
                x: options.offset.x,
                y: options.offset.y
            },
            type: options.type,
            animate: {
                enter: options.animate.enter,
                exit: options.animate.exit
            },
            placement: {
                from: options.placement.from,
                align: options.placement.align
            }
        });
    }
});

console.log('test 1', RentoApp);
console.groupEnd();