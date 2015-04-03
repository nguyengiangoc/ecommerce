$(document).ready(function() {
    
    
    function refreshSmallBasket() {
        $.ajax({
            url: '/ecommerce/mod/basket_small_refresh.php',
            //tao ra basket moi, ngay khi basket duoc tao ra function construct se tu tinh lai cac thong so cua basket
            //sau do gui ve cho ham nay
            dataType: 'json',
            success: function(data) {
                $.each(data, function(k, v) {
                    //du lieu duoc tra ve la mot mang, voi 4 thanh phan,
                    //key cua moi thanh phan la ten cua class, value la gia tri con so cua thanh phan ung voi tung key, tuc la tung class
                    //k la dai dien cho key, v la cho value
                    $("#basket_left ." + k + " span").text(v);
                    //basket left la id cua dl, bat dau cua list, sau do di vao class co noi dung class la key
                    //sau di vao phan span, va gan value vao cho phan span
                });
            },
            error: function(data) {
                alert("Error occured");
            }
        });
    }      
    
    function refreshBigBasket() {
        $.ajax({
            url: '/ecommerce/mod/basket_view.php',
            dataType: 'html',
            success: function(data) {
                $('#big_basket').html(data);
                initBinds();
            },
            error: function(data) {
                alert('Error occured')
            }
        });
    }
        
    if($(".add_to_basket").length > 0) {
        $(".add_to_basket").click(function() {
            var trigger = $(this);
            var param = trigger.attr("rel");
            var item = param.split("_");
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: ({id: item[0], job: item[1]}),
                url: '/ecommerce/mod/basket.php',
                //ajax se gui thong tin ve product id va cong viec can lam den basket php
                //neu hang chua co trong gio hang, job la 1, dua sang basket se kich hoat set Item
                //set item dua vao session thong bao rang product voi id duoc bam se co so luong bang 1
                //neu hang da co trong gio hang, job la 0, dua sang basket se kich hoat remove Item
                success: function(data) {
                    var new_id = item[0] + '_' + data.job;
                    if (data.job != item[1]) {
                        //sau khi da set hoac remove Item, basket php se gui lai job id moi, khac voi job id duoc gui di
                        //muc dich la de gan job id moi nay vao rel cua nut bam
                        if (data.job == 0) {
                            trigger.attr("rel", new_id);
                            trigger.text("Remove from basket");
                            trigger.addClass("red");
                            //neu job id duoc gui ve la 0, thi job id duoc gui di la 1, tuc la luc bam yeu cau set item va luc nay da set
                            //vay gui ve job id la 0 va chuyen noi dung chu thanh remove de lan bam tiep theo vao dong chu nay kich hoat remove
                        } else {
                            trigger.attr("rel", new_id);
                            trigger.text("Add to basket");
                            trigger.removeClass("red");
                            //neu job id duoc gui ve la 1, thi job id duoc gui di la 0, tuc la luc bam yeu cau remove item va luc nay da remove
                            //vay gui ve job id la 1 va chuyen noi dung chu thanh add de lan bam tiep theo vao dong chu nay kich hoat set
                        }
                        refreshSmallBasket();
                        //refresh small basket de lay thong tin tu tren session xuong basket left
                    }
                },
                error: function(data) {
                    alert("Error occured");
                }
            });
            return false; //return false de khi bam vao thi trang khong bi dich chuyen 
        });
    }
    
    initBinds(); //goi quy trinh init binds 
    
    function initBinds() {
        if($('.remove_basket').length > 0) {
            $('.remove_basket').bind('click', removeFromBasket)
        }
        
        if ($('.update_basket').length > 0) {
            $('.update_basket').bind('click', updateBasket)
            //gan cho nut update quy trinh xu ly update basket khi duoc bam vao
        }
        if ($('.fld_qty').length > 0) {
            $('.fld_qty').bind('keypress', function(e) {
                //gan cho cac o nhap so luong hang bo xu ly khi co mot nut duoc nhan
                var code = e.keyCode ? e.keyCode : e.which;
                if (code == 13) {
                    //neu nut nay duoc nhan thi cung goi ra quy trinh xu ly update basket
                    //tuc la co the cap nhat gio hang bang hai cach
                    updateBasket();
                }
            });
        }
    }
    
    function removeFromBasket() {
        var item = $(this).attr('rel');
        $.ajax({
            type: 'POST',
            url: '/ecommerce/mod/basket_remove.php',
            dataType: 'html',
            data: ({ id: item }),
            success: function() {
                refreshSmallBasket();
                refreshBigBasket();
            },
            error: function() {
                alert('Error occured');
            }
        });
    }
    
    function updateBasket() {
        $('#frm_basket :input').each(function() { 
            //ap dung function sau day cho tat ca cac truong duoc input
            //trong truong hop nay la tat ca cac o update quantity
            var sid = $(this).attr('id').split('-'); //lay id cua product
            var val = $(this).val(); //lay gia tri hien co trong o duoc nhap vao
            $.ajax ({
                type: 'POST',
                url: '/ecommerce/mod/basket_qty.php',
                data: ({ id: sid[1], qty: val }),
                //dua du lieu sang cho file xu ly basket qty, file nay se cap nhat du lieu len session
                success: function() {
                    refreshSmallBasket();
                    refreshBigBasket();
                    //lay gia tri tu session xuong cho vao hai cai basket
                },
                error: function() {
                    alert('Error occured');
                }
            });            
        });
    }
    
    if($('.paypal').length > 0) {
        $('.paypal').click(function() {
            var token = $(this).attr('id');
            var image = "<div style=\"text-align:center\">"; 
            image = image + "<img src=\"/ecommerce/images/loadinfo.net.gif\"";
            image = image + " alt=\"Proceeding to PayPal\" />";
            image = image + " <br />Please wait while we are redirecting you to PayPal...";
            image = image + "</div><div id=\"frm_pp\"></div>";
            
            $('#big_basket').fadeOut(200, function() {
                $(this).html(image).fadeIn(200, function() {
                    send2PP(token); 
                }); 
            }); 
        });
    }
    
    function send2PP(token) {
        $.ajax({
            type: 'POST',
            url: '/ecommerce/mod/paypal.php',
            data: ({ token: token }),
            dataType: 'html',
            success: function(data) {
                $('#frm_pp').html(data);
                $('#frm_paypal').submit(); //form frmpaypal o trong class Paypal method render
            },
            error: function() {
                alert('An error has occurred');
            } 
        });
    }
    
})