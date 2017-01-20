
    var systemObject = {        
        
        showHideRadio : function(thisIdentity) {
            $(document).on('click', thisIdentity, function(e) {
                var thisTarget = $(this).attr('name');
                var thisValue = $(this).val();
                if(thisValue == 1) {
                    $('.' + thisTarget).hide();
                } else {
                    $('.' + thisTarget).show();
                }
            });
        },
        
        topValidationTemp : function(thisMessage) {
            var thisTemp = '<div id="topMessage">';
            thisTemp += thisMessage;
            thisTemp += '</div>';
        },
        
        topValidation : function(thisMessage) {
            if(thisMessage !== '' && typeof thisMessage !== 'undefined') {
                if($('#topMessage').length > 0) {
                    $('#topMessage').remove();
                }
                $('body').prepend($(systemObject.topValidationTemp(thisMessage)).fadeIn(200));
                var thisTimeout = setTimeout(function() {
                    $('#topMessage').fadeOut(200, function() {
                        $(this).remove(); 
                    });
                }, 5000);
            }
        },
        
        isEmpty : function(thisValue) {
            "use strict";
            return (!(thisValue !== '' && typeof thisValue !== 'undefined'));
        },
        
        replaceValues : function(thisArray) {
            "use strict";            
            $.each(thisArray, function(thisKey, thisValue) {                
                $(thisKey).html(thisValue);
                
            });
            
        }
    };