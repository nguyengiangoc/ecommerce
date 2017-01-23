    
    var basketObject = {
        updateBasketEnter : function(thisIdentity) {
            "use strict";
            $(document).on('keypress', thisIdentity, function(e) {
                var code = e.keyCode ? e.keyCode : e.which;
                if (code == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    basketObject.updateBasket();
                }
            });
        },


        removeFromBasket : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var item = $(this).attr('rel');
                $.post('/mod/call/basket-remove', { id : item }, function(data) {
                    if(!systemObject.isEmpty(!data.replace_values)) {
                        systemObject.replaceValues(data.replace_values);
                    }
                }, 'json');
            });
        },


        add2Basket : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var trigger = $(this);
                var param = trigger.attr("rel");
                var item = param.split("_");
                $.post(root + '/mod/call/basket', { id : item[0], job : item[1] }, function(data) {
                    var new_id = item[0] + '_' + data.job;
                    if (data.job != item[1]) {
                        if (data.job == 0) {
                            trigger.attr("rel", new_id);
                            trigger.text("Remove from basket");
                            trigger.addClass("red");
                        } else {
                            trigger.attr("rel", new_id);
                            trigger.text("Add to basket");
                            trigger.removeClass("red");
                        }
                        if(!systemObject.isEmpty(data.replace_values)) {
                            systemObject.replaceValues(data.replace_values);
                        }
                    }
                }, 'json');
            });
        },
        
        updateBasketClick : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                basketObject.updateBasket();
            });
        },
        
        updateBasket : function() {
            "use strict";
            var thisArray = $('#frm_basket').serializeArray();
            
            
            $.post(root + '/mod/call/basket-qty', thisArray, function(data) {
                if(!systemObject.isEmpty(data.replace_values)) {
                    systemObject.replaceValues(data.replace_values);
                }
            }, 'json');
            
        },
        
        loadingPayPal : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                e.stopPropagation();
                var thisShippingOption = $('input[name="shipping"]:checked');
                if(thisShippingOption.length > 0) {
                    var token = $(this).attr('id');
                    var image = "<div style=\"text-align:center\">";
                    image = image + "<p><img src=\"/ecommerce/images/loadinfo.net.gif\"";
                    image = image + " alt=\"Proceeding to PayPal\" />";
                    image = image + "<br />Please wait while we are redirecting you to PayPal...</p>";
                    image = image + "</div><div id=\"frm_pp\"></div>";
                    $('#big_basket').fadeOut(200, function() {
                        $(this).html(image).fadeIn(200, function() {
                            basketObject.send2PayPal(token);
                        });
                    });
                } else {
                    systemObject.topValidation('Please select the shipping option');
                }
                
            });
        },
        
        send2PayPal : function(token) {
            $.post(root + '/mod/call/paypal', { token : token }, function(data) {
                if(data && !data.error) {
                    $('#frm_pp').html(data.form);
                    // submit form automatically
                    $('#frm_paypal').submit();
                } else {
                    systemObject.topValidation(data.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 5000);
                }
            }, 'json');
        },
        shipping : function(thisIdentity) {
            $(document).on('change', thisIdentity, function(e) {
                var thisOption = $(this).val();
                $.getJSON(root + '/mod/call/summary-update/shipping/' + thisOption, function(data) {
                    if(data && !data.error) {
                        $('#basketSubTotal').html(data.totals.basketSubTotal);
                        $('#basketVAT').html(data.totals.basketVAT);
                        $('#basketTotal').html(data.totals.basketTotal);
                    } 
                });
            });
        }
    };  