    var adminObject = {
        isEmpty : function(thisValue) {
            "use strict";
            return (thisValue !== '' && typeof thisValue !== 'undefined') ? false : true;
        },
        clickReplace : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisURL = thisObj.data('url');
                $.getJSON(thisURL, function(data) { //lay du lieu GET
                    if (data && !data.error) {
                        if(!adminObject.isEmpty(data.replace)) {
                            thisObj.replaceWith(data.replace);
                        }
                    }
                }); 
            });
        },
        clickCallReload : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisURL = $(this).data('url');
                $.getJSON(thisURL, function(data) {
                    if(data && !data.error) {
                        window.location.reload();
                    }
                });
            });
        },
        clickYesNoSingle : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisValue = thisObj.data('value');
                if(parseInt(thisValue, 10) === 0) {
                    var thisGroup = thisObj.data('group');
                    var thisGroupItems = $('[data-group="' + thisGroup + '"]');
                    var thisURL = thisObj.data('url');
                    $.getJSON(thisURL, function(data) {
                        if(data && !data.error) {
                            $.each(thisGroupItems, function() {
                                $(this).text('No');
                                $(this).attr('data-value', 0);
                            });
                            thisObj.text('Yes');
                            thisObj.attr('data-value', 1);
                        } 
                    });
                } 
            });
        },
        clickRemoveRowTemplate : function(id, span, url) {
            "use strict";
            var thisTemp = '<tr id="clickRemove-' + id + '">';
            if(span > 1) {
                thisTemp += '<td colspan="' + span + '">';
            }
            thisTemp += '<div class="fl_r">';
            thisTemp += '<a href="#" data-url="' + url;
            thisTemp += '" class="clickRemoveRow mrr5">Yes</a>';
            thisTemp += '<a href="#" class="clickRemoveRowConfirm">No</a>';
            thisTemp += '</div>';
            thisTemp += '<span class="warn">Are you sure you wish to remove this record?<br />';
            thisTemp += 'This action cannot be reversed!</span>';
            thisTemp += '</td>';
            thisTemp += '</tr>';
            return thisTemp;
        },
        clickAddRowConfirm : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisParent = thisObj.closest('tr');
                var thisId = thisParent.attr('id').split('-')[1];
                var thisSpan = thisObj.data('span');
                var thisURL = thisObj.data('url');
                if ($('#clickRemove-' + thisId).length === 0) {
                    thisParent.before(adminObject.clickRemoveRowTemplate(thisId, thisSpan, thisURL));
                } 
            });
        },
        clickRemoveRowConfirm : function (thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                $(this).closest('tr').remove(); 
            });
        },
        clickRemoveRow : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisObj = $(this);
                var thisId = thisObj.closest('tr').attr('id').split('-')[1];
                var thisURL = thisObj.data('url');
                $.getJSON(thisURL, function(data) {
                    if (data && !data.error) {
                        if (!adminObject.isEmpty(data.replace)) {
                            $.each(data.replace, function(k, v) {
                                $(k).html(v); 
                            });
                        } else {
                            $('#row-' + thisId).remove();
                            thisObj.closest('tr').remove(); //remove confirmation message
                        }
                    } 
                }); 
            });
        },
        clickHideShow : function(thisIdentity) {
            "use strict";
            $(document).on('click', thisIdentity, function(e) {
                e.preventDefault();
                var thisTarget = $(this).data('show');
                $(this).hide();
                $(thisTarget).show().focus();
            });
        },
        blurUpdateHideShow : function(thisIdentity) {
            "use strict";
            $(document).on('keypress', thisIdentity, function(e) {
                if(e.which == 13) {
                    var thisObj = $(this);
                    var thisForm = thisObj.closest('form');
                    thisForm.find('.warn').remove();
                    var thisId = thisObj.data('id');
                    var thisURL = thisForm.data('url') + '/' + thisId;                
                    var thisShow = thisObj.attr('id');
                    var thisValue = thisObj.val();
                    if (!adminObject.isEmpty(thisValue)) {
                        $.post(thisURL, { id : thisId, value : thisValue }, function(data) {
                            if (data && !data.error) {
                                thisObj.hide();
                                $('[data-show="#' + thisShow + '"]').text(thisValue).show();
                            }  
                        }, 'json');
                    } else {
                        thisObj.before('<p class="warn">Please provide a value</p>');
                    }
                }
            });            
        },
        /*sortRows : function(obj) {
            "use strict";
            obj.find('tr').livequery(function() {
                var thisObj = $(this).parent('tbody');
                $.each(thisObj, function() {
                    var thisTbody = $(this);
                    var thisURL = thisTbody.data('url');
                    if (!adminObject.isEmpty(thisURL)) {
                        $(".mar").tableDnD({
                            onDrop : function(thisTable, thisRow) {
                                var thisArray = $.tableDnD.serialize();
                                $.ajax({
                                    type: 'POST',
                                    url: thisURL,
                                    data: thisArray
                                });
                            } 
                        });
                    } 
                }); 
            });
        },*/
        sortRows : function () {
            //$(".sortRows").tableDnD();
            var thisURL = $(".sortRows").data('url');
            if (!adminObject.isEmpty(thisURL)) {
                $(".sortRows").tableDnD({
                    onDrop : function(thisTable, thisRow) {
                        var thisArray = $.tableDnD.serialize();
                        $.ajax({
                            type: 'POST',
                            url: thisURL,
                            data: thisArray
                        });
                    } 
                });
            }
        },
        submitAjax : function() {
            "use strict";
            $(document).on('submit', 'form.ajax', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var thisForm = $(this);
                thisForm.find('.warn').remove();
                var thisArray = thisForm.serializeArray();
                var thisURL = thisForm.data('action');
                if (!adminObject.isEmpty(thisURL)) {
                    $.post(thisURL, thisArray, function(data) {
                        if(data) {
                            if(!data.error) {
                                if(!adminObject.isEmpty(data.replace)) {
                                    $.each(data.replace, function(k, v) {
                                        $(k).html(v);
                                    });
                                    thisForm[0].reset();
                                } else {
                                    window.location.reload();
                                }
                            } else if (!adminObject.isEmpty(data.validation)) {
                                $.each(data.validation, function(k, v) {
                                    $('.' + k).append(v); 
                                });
                            }
                        }
                    }, 'json');
                } 
            });
        },
        selectRedirect : function(thisIdentity) {
            "use strict";
            $('form').on('change', thisIdentity, function(e) {
                var thisSelected = $('option:selected', this);
                var thisURL = thisSelected.data('url');
                if (!adminObject.isEmpty(thisURL)) {
                    window.location.href = thisURL;
                } 
            });
        }
    };
    $(function() {
        "use strict";
        adminObject.clickReplace('.clickReplace');
        adminObject.clickCallReload('.clickCallReload');
        adminObject.clickYesNoSingle('.clickYesNoSingle');
        adminObject.clickRemoveRowConfirm('.clickRemoveRowConfirm');
        adminObject.clickAddRowConfirm('.clickAddRowConfirm');
        adminObject.clickRemoveRow('.clickRemoveRow');
        adminObject.clickHideShow('.clickHideShow');
        adminObject.blurUpdateHideShow('.blurUpdateHideShow');
        //adminObject.sortRows($('.sortRows'));
        adminObject.sortRows();
        adminObject.submitAjax();
        adminObject.selectRedirect('.selectRedirect');
    })