var customHandlers = [];
$(document).ready(function(){   
    var myWidth,
        myHeight,
        title = window.location.href,
        titleVar = ( title.split("localhost").length > 1 )?4:3,
        progress = new KitProgress("#FFF",2),
        isMobile = $("body.is-mobile").length;

    progress.endDuration = 0.3;

    title = title.split(/[\/#?]+/);
    title = title[titleVar];

    $(".b-menu-tile[data-name='"+title+"'],.b-menu-tile[data-nameAlt='"+title+"']").addClass("active");

    setTimeout(function(){
        $("body").addClass("trans");
    },300);

    $.datepicker.regional['ru'] = {
        closeText: 'Готово', // set a close button text
        currentText: 'Сегодня', // set today text
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], // set month names
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'], // set short month names
        dayNames: ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'], // set days names
        dayNamesShort: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], // set short day names
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], // set more short days names
        dateFormat: 'dd/mm/yy' // set format date
    };        
    $.datepicker.setDefaults($.datepicker.regional["ru"]);

    function whenResize(){
        if( typeof( window.innerWidth ) == 'number' ) {
            myWidth = window.innerWidth;
            myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || 
        document.documentElement.clientHeight ) ) {
            myWidth = document.documentElement.clientWidth;
            myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
            myWidth = document.body.clientWidth;
            myHeight = document.body.clientHeight;
        }
        $("body,html").css("height",myHeight);
        // $(".main").css("height",myHeight-50);
    }
    $(window).resize(whenResize);
    whenResize();

    $().fancybox({
        selector : ".ajax-update, .ajax-create",
        type: "ajax",
        padding: 0,
        touch: false,
        margin: 30,
        afterLoad: function(){
            var $form = $(".fancybox-slide form").not(".binded");
            bindForm($form);
            bindImageUploader();
            bindTinymce();
            bindAutocomplete();
            bindTooltip();
            bindNewInputs();
            if( $form.attr("data-beforeShow") && customHandlers[$form.attr("data-beforeShow")] ){
                customHandlers[$form.attr("data-beforeShow")]($form);
            }
            $(".fancybox-slide form").addClass("binded");
        },
        afterClose:function(){
            unbindTinymce();
        },
        afterShow: function(){
            bindVariants();
            $(".fancybox-slide").find("input[type='text']:not(.current),textarea").filter(function() {
                return $(this).val() == "";
            }).eq(0).focus();
        },
        beforeClose: function(){        
            $(".select2-drop").hide();
        }
    });

    $("body").on("click",".ajax-delete", function(){
        var $this = $(this);
        $.fancybox.open({
            padding: 0,
            src: "#b-popup-delete",
            type: "inline",
            afterLoad: function(){
                bindDelete($this.attr("href"));
            }
        });
        return false;
    });

    function jsonHandler(msg){
        var json = JSON.parse(msg);
        if( json.result == "success" ){
            switch (json.action) {
                case "redirect":
                    window.location.href = json.href;
                break;
            }
        }
    }

    function isValidJSON(src) {
        var filtered = src;
        filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
        filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
        filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');

        return (/^[\],:{}\s]*$/.test(filtered));
    }

    function setResult(html){
        $(".b-main-center").html(html);

        setTimeout(function(){
            bindFilter();
            bindTooltip();
            bindAutocomplete();
            bindFancy();
        },100);
    }

    function bindDelete(url){
        $(".fancybox-slide .b-delete-yes").unbind("click");
        $(".fancybox-slide .b-delete-yes").bind("click", function(){

            progress.setColor("#FFF");
            progress.start(3);

            url = ( $(".main form").length ) ? (url+"&"+$(".main form").serialize()) : url;

            // $(".fancybox-wrap").remove();
            // $.fancybox.showLoading();

            $.ajax({
                url: url,
                success: function(msg){
                    // $.fancybox.hideLoading();
                    progress.end(function(){
                        setResult(msg);
                    });
                    $.fancybox.close();
                }
            });    
        });
    }

    $("body").on("click", ".ajax-action", function(){
        progress.setColor("#2f3640");
        progress.start(3);

        $.ajax({
            url: $(this).attr("href"),
            success: function(msg){
                progress.end(function(){
                    setResult(msg);
                    $(".qtip").remove();
                });
            }
        });  

        return false;
    });

    $(".fancy-img").fancybox({
        padding : 0
    });

    function bindFilter(){
        if( $(".b-filter").length && !$(".b-filter").hasClass("binded") ){
            $(".b-filter select, .b-filter input").bind("change",function(){
                if( !isMobile ){
                    filterSubmit($(this).parents("form"));
                }
            });

            $(".b-filter-submit").click(function(){
                filterSubmit($(".b-filter form"));

                $.fancybox.close();
            });

            $(".b-filter select, .b-filter input").bind("keyup", function(e){
                if( e.keyCode == 13 )
                    $(this).trigger("change");
            });

            $(".main form").submit(function(){
                return false;
            });

            $(".b-clear-filter").click(function(){
                $(".b-filter select,.b-filter input[type='text']").val("");
                $(".b-filter select,.b-filter input[type='text']:not(.select2-offscreen, .select2-input)").eq(0).trigger("change");

                if( $(this).parents(".b-popup").length ){
                    $(".b-filter-submit").click();
                }
                return false;
            });

            $(".b-filter .select2").select2({
                placeholder: "",
                allowClear: true
            });

            bindDate($(".b-filter").parents("form"));

            $(".b-filter").addClass("binded");
        }
    }

    $("body").on("dblclick", "tr", function(){
        if( $(this).find(".b-double-click").length ){
            window.location.href = $(this).find(".b-double-click").attr("href");
        }
    });

    function filterSubmit($form){
        progress.setColor("#2f3640");
        progress.start(3);

        // console.log($form.serialize());

        $.ajax({
            url: "?partial=true&"+$form.serialize(),
            success: function(msg){
                progress.end(function(){
                    setResult(msg);
                    history.pushState(null, null, "?"+$form.serialize());
                });
            }
        }); 
    }

    function bindForm($form){

        // var date = new Date(),
        // day = (date.getDate()).toString(),
        // month = (date.getMonth()+1).toString(),
        // hours = (date.getHours()).toString(),
        // minutes = (date.getMinutes()).toString();
        // day = (day.length==1) ? "0"+day : day;
        // month = (month.length==1) ? "0"+month : month,
        // hours = (hours.length==1) ? "0"+hours : hours;
        // minutes = (minutes.length==1) ? "0"+minutes : minutes;
        // date = day+"."+month+"."+date.getFullYear()+" "+hours+":"+minutes;
        // if(!$("#Event_start_time").val()) $("#Event_start_time").val(date);

        $form.find(".select2").select2({
            placeholder: "",
            allowClear: true
        });

        $form.validate({
            ignore: "",
            rules: {
                
            },
            highlight: function(element, errorClass) {
                $(element).addClass("error").parents(".b-hor-input").addClass("error");
            },
            unhighlight: function(element) {
                $(element).removeClass("error").parents(".b-hor-input").removeClass("error");
            }
        });

        autosize($form[0].querySelectorAll('textarea'));

        $form.find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").blur(function(){
           $(this).valid();
        });

        $form.find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").keyup(function(){
           $(this).valid();
        });

        $form.find("input[type='text'], input[type='tel'], input[type='email'], textarea, select").change(function(){
           $(this).valid();
        });

        if( $form.find(".date-time").length ){
            $form.find(".date-time").mask('99.99.9999 99:99',{placeholder:"_"});
        }

        if( $form.find(".phone").length ){
            $form.find(".phone").mask('+7 (999) 999-99-99',{placeholder:"_"});
        }

        bindDate($form);

        if( $(".type-checkbox").length ){
            $(".type-checkbox").change(function(){
                $(".b-type-item").hide();
                $(".b-type-"+$(this).val()).show();
            });

            $(".b-type-item").hide();
            $(".b-type-"+$(".type-checkbox:checked").val()).show();
        }

        if( $(".autofirst").length ){
            $(".autofirst").parent().each(function(){
                if( !$(this).find(".autofirst:checked").length ){
                    $(this).find(".autofirst").eq(0).prop("checked", true);
                }
            });
        }

        bindAutoSum();

        $(".numeric").numericInput({ allowFloat: false, allowNegative: true });
        $(".float").numericInput({ allowFloat: true, allowNegative: true });

        $(".float").keyup(function(){
            $(this).val( $(this).val().replace("руб",".").replace(",",".").replace(/[^0-9\.]+/g,"").replace( /^([^\.]*\.)|\./g, '$1' ) );
        });
        
        $form.submit(function(e,a){
            if( !checkFiles() ) return false;

            removeNewInputs();

            tinymce.triggerSave();
            if( $(this).valid() && !$(this).find("input[type=submit]").hasClass("blocked") ){
                var $form = $(this),
                    url = $form.attr("action"),
                    data;

                $(this).find("input[type=submit]").addClass("blocked");

                progress.setColor("#FFF");
                progress.start(3);

                url = ( $(".main form").length ) ? (url+( (url.split("?").length>1)?"&":"?" )+$(".main form").serialize()) : url;

                if( $form.attr("data-beforeAjax") && customHandlers[$form.attr("data-beforeAjax")] ){
                    customHandlers[$form.attr("data-beforeAjax")]($form);
                }

                data = $form.serialize();

                if( a == false ){
                    $form.find("input[type='text'],input[type='number'],textarea").val("");
                    $form.find("input").eq(0).focus();
                }

                if('#b-progress-bar-container'.length != 0){
                    $('#b-progress-bar-container').addClass('preloader');
                }

                $.ajax({
                    type: $form.attr("method"),
                    url: url,
                    data: data,
                    success: function(msg){
                        progress.end(function(){
                            $form.find("input[type=submit]").removeClass("blocked");
                            if( msg != "" && isValidJSON(msg) ){
                                jsonHandler(msg);
                            }else{
                                setResult(msg);
                            }
                        });
                        if( a != false ){
                            $.fancybox.close();
                        }
                    }
                });

                if('#b-progress-bar-container'.length != 0){
                    $('#b-progress-bar-container').removeClass('preloader');
                }

            }else{
                $(".fancybox-overlay").animate({
                    scrollTop : 0
                }, 200);

                $(this).find("input[type='text'].error,select.error,textarea.error").eq(0).focus();
            }
            return false;
        });

        $(".b-input-image").change(function(){
            var cont = $(this).parents(".b-image-cont").parent("div");
            if( $(this).val() != "" ){
                cont.find(".b-input-image-add").addClass("hidden");
                cont.find(".b-image-wrap").removeClass("hidden");
                cont.find(".b-input-image-img").css("background-image","url('/"+$(this).val()+"')");
            }else{
                cont.find(".b-input-image-add").removeClass("hidden");
                cont.find(".b-image-wrap").addClass("hidden");
            }
        });

        // Удаление изображения
        $(".b-image-delete").click(function(){
            var cont = $(this).parents(".b-image-cont").parent("div");
            cont.find(".b-image-cancel").attr("data-url",cont.find(".b-input-image").val())// Сохраняем предыдущее изображение для того, чтобы можно было восстановить
                                .show();// Показываем кнопку отмены удаления
            cont.find(".b-input-image").val("").trigger("change");// Удаляем ссылку на фотку из поля
        });

        // Отмена удаления
        $(".b-image-cancel").click(function(){
            var cont = $(this).parent("div");
            cont.find(".b-input-image").val(cont.find(".b-image-cancel").attr("data-url")).trigger("change")// Возвращаем сохраненную ссылку на изображение в поле
            cont.find(".b-image-cancel").hide(); // Прячем кнопку отмены удаления                                 
        });
    }

    function checkFiles(){
        if( $(".plupload_start:not(.plupload_disabled)").length ){
            $(".plupload_start:not(.plupload_disabled)").attr("data-save", "1").click();
            return false;
        }
        return true;
    }

    function bindAutoSum(){
        if( $(".b-auto-sum").length && $(".b-auto-price").length && $(".b-auto-cubage").length ){
            $(".b-auto-price, .b-auto-cubage").change(function(){
                $(".b-auto-sum").val( $(".b-auto-price").val()*$(".b-auto-cubage").val() );
            });
        }
    }

    function bindDate($form){
        if( $form.find(".date").length ){
            $form.find(".date").each(function(){
                var $this = $(this);
                $(this).mask('99.99.9999',{placeholder:"_"});
                $(this).wrap("<div class='b-to-datepicker'></div>");

                $(this).datepicker({
                    currentText: "Now",
                    dateFormat: "dd.mm.yy",
                    beforeShow:function(input, inst){
                        $this.parents(".b-to-datepicker").append($('#ui-datepicker-div'));  
                    }
                });

                if( $(this).hasClass("current") && $(this).val() == "" ){
                    $(this).datepicker("setDate", new Date());
                }
            });
        }
    }

    function bindNewInputs(){
        if( !$(".b-for-new-inputs").length ) return false;

        if( !$(".b-for-new-inputs div").length ){
            appendNewInputs(5, 0);
        }

        $(".add-new-inputs").click(function(){
            appendNewInputs(3, $(".b-for-new-inputs").children("*:last").attr("data-id")*1 + 1 );
        });
    }

    function appendNewInputs(count, from){
        for (var i = from; i < from*1 + count*1; i++) {
            var newInput = $("#input-template").html().replace(/#/g, i+"");
            $(".b-for-new-inputs").append( newInput );
        }
        $(".b-for-new-inputs .numeric").numericInput({ allowFloat: false, allowNegative: true }).removeClass("numeric");
        $(".b-for-new-inputs .float").numericInput({ allowFloat: true, allowNegative: true }).removeClass("float");
    }

    function removeNewInputs(){
        if( !$(".b-for-new-inputs").length ) return false;

        $(".b-for-new-inputs").children().filter(function() {
            return !$(this).find("input, select, textarea").filter(function(){
                return $(this).val() != "";
            }).length;
        }).remove();
    }

    function bindImageUploader(){
        $(".b-get-image").click(function(){
            $(".b-for-image-form").load($(this).parents(".b-image-cont").find(".b-get-image").attr("data-path"), {}, function(){
                $(".upload").addClass("upload-show");
                $(".b-upload-overlay").addClass("b-upload-overlay-show")
                $(".plupload_cancel,.b-upload-overlay,.plupload_save").click(function(){
                    $(".b-upload-overlay").removeClass("b-upload-overlay-show");
                    $(".upload").addClass("upload-hide");
                    setTimeout(function(){
                        $(".b-for-image-form").html("");
                    },400);
                    return false;
                });
            });
        });
    }

    /* TinyMCE ------------------------------------- TinyMCE */
    function bindTinymce(){
        if( $("#tinymce").length ){
            tinymce.init({
                selector : "#tinymce",
                width: '700px',
                height: '500px',
                language: 'ru',
                plugins: 'image table autolink emoticons textcolor charmap directionality colorpicker media contextmenu link textcolor responsivefilemanager',
                skin: 'kit-mini',
                toolbar: 'undo redo bold italic forecolor alignleft aligncenter alignright alignjustify bullist numlist outdent indent link image',
                onchange_callback: function(editor) {
                    tinymce.triggerSave();
                    $("#" + editor.id).valid();
                },
                image_advtab: true ,
                external_filemanager_path:"/filemanager/",
                filemanager_title:"Файловый менеджер" ,
                external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
            });
        }
    }

    function unbindTinymce(){
        tinymce.remove();
    }
    /* TinyMCE ------------------------------------- TinyMCE */

    /* Preloader ----------------------------------- Preloader */
    function setPreloader(el){
        var str = '<div class="circle-cont">';
        for( var i = 1 ; i <= 3 ; i++ ) str += '<div class="c-el c-el-'+i+'"></div>';
        el.append(str+'</div>').addClass("blocked");
    }

    function removePreloader(el){
        el.removeClass("blocked").find(".circle-cont").remove();
    }
    /* Preloader ----------------------------------- Preloader */

    /* Hot keys ------------------------------------ Hot keys */
    if( $(".ajax-create").length ){
        var cmddown = false,
            ctrldown = false;
        function down(e){
            if( e.keyCode == 13 && ( cmddown || ctrldown ) ){
                if( !$(".b-popup form").length ){
                    $(".ajax-create").click();
                }else{
                    $(".fancybox-slide form").trigger("submit",[true]);
                }
            }
            if( e.keyCode == 13 ){
                if( $(".fancybox-slide .b-delete-yes").length ){
                    $(".fancybox-slide .b-delete-yes").trigger("click");
                }
                enterVariantsHandler();
            }
            if( e.keyCode == 91 ) cmddown = true;
            if( e.keyCode == 17 ) ctrldown = true;
            if( e.keyCode == 27 && $(".fancybox-slide").length ) $.fancybox.close();
        }
        function up(e){
            if( e.keyCode == 91 ) cmddown = false;
            if( e.keyCode == 17 ) ctrldown = false;
        }
        $(document).keydown(down);
        $(document).keyup(up);
    }
    /* Hot keys ------------------------------------ Hot keys */

    /* Autocomplete -------------------------------- Autocomplete */
    function bindAutocomplete(){
        if( $(".autocomplete").length ){
            var i = 0;
            $(".autocomplete").each(function(){
                $(this).autocomplete({
                    source: JSON.parse($(this).attr("data-values"))
                });
            });
        }
    }
    /* Autocomplete -------------------------------- Autocomplete */

    /* Tooltip ------------------------------------- Tooltip */
    function bindTooltip(){
        bindTooltipSkin(".tooltip", "bottom center");
        bindTooltipSkin(".b-tool", "bottom right");
    }
    function bindTooltipSkin(selector,position){
        $(selector).qtip('destroy', true);
        $(selector).qtip({
            position: {
                my: position,
                at: 'top center'
            },
            style: {
                classes: 'qtip-dark qtip-shadow qtip-rounded',
                tip: {
                    width: 14
                }
            },
            show: {
                delay: 150
            }
        });
    }
    /* Tooltip ------------------------------------- Tooltip */

    /* Variants ------------------------------------ Variants */
    $("body").on("click","#add-variant",function(){
        $(".b-variant-cont .error").addClass("hidden");
        if( !$("#new-variant").hasClass("hidden") ){
            // Если вводили в инпут
            var val = $("#new-variant").val();
            if( !tryToAddVariant(val) ){
                $(".b-variant-cont .error-single").removeClass("hidden");
            }
        }else{
            // Если вводили в инпут textarea
            var val = $("#new-variant-list").val(),
                tmpArr = val.split("\n"),
                tmpError = new Array();
            for( var i in tmpArr ){
                if( !tryToAddVariant(tmpArr[i]) && tmpArr[i] != "" ){
                    tmpError.push(tmpArr[i]);
                }
            }
            if( tmpError.length ){
                $(".b-variant-cont .error-list").removeClass("hidden");
            }
            $("#new-variant-list").val(tmpError.join("\n"));
        }

        $((!$("#new-variant").hasClass("hidden"))?"#new-variant":"#new-variant-list").focus();
        updateVariantsSort();
        $.fancybox.update();
    });

    $("body").on("click","#b-variants li span",function(){
        if( confirm("Если удалить этот вариант, то во всех товарах, где был выбран именно этот вариант будет пустое значение атрибута. Подтвердить удаление?") ){
            $(this).parents("li").remove();
            updateVariantsSort();
            $.fancybox.update();
        }
    });

    $("body").on("click",".b-variant-cont .b-set-list",function(){
        $("#new-variant-list, .b-variant-cont .b-set-single").show();
        $("#new-variant, .b-variant-cont .b-set-list").hide().addClass("hidden");
        $("#new-variant-list").focus();
        $.fancybox.update();
    });

    $("body").on("click",".b-variant-cont .b-set-single",function(){
        $("#new-variant-list, .b-variant-cont .b-set-single").hide();
        $("#new-variant, .b-variant-cont .b-set-list").show().removeClass("hidden");
        $("#new-variant").focus();
        $.fancybox.update();
    });

    $("body").on("mouseenter", ".b-property-table td", function(){
        $("#company-"+$(this).parents("tr").attr("data-id")).addClass("hover");
    });

    $("body").on("mouseleave", ".b-property-table td", function(){
        $(".b-property-table td.hover").removeClass("hover");
    });

    function tryToAddVariant(val){
        val = regexVariant(val);
        if( val != "" ){
            if( !$("input[data-name='"+val.toLowerCase()+"']").length ){
                $("#b-variants ul").append("<li><p>"+val+"</p><span></span><input data-name=\""+val.toLowerCase()+"\" type=\"hidden\" name=\"VariantsNew["+val+"]\" value=\"\"></li>");
                $("#new-variant").val("");
                return true;
            }
        }
        return false;
    }

    function regexVariant(val){
        var regArr;
        switch( $("#new-variant").attr("data-type") ) {
            case "float":
                regArr = /^[^\d-]*(-{0,1}\d+\.{0,1}\d+)[\D]*$/.exec(val);

                break;
            case "int":
                regArr = /^[^\d-]*(-{0,1}\d+)[\D]*$/.exec(val);

                break;
            default:
                regArr = ["",val];
                break;
        }
        return ( regArr != null )?regArr[1]:"";
    }

    function updateVariantsSort(){
        var i = 0;
        $("#b-variants ul li").each(function(){
            i+=10;
            $(this).find("input").val(i);
        });
    }
    function enterVariantsHandler(){
        if( !$(".b-variant-cont input[type='text']").hasClass("hidden") ){
            $("#add-variant").click();
        }
    }
    function bindVariants(){
        if( $("#b-variants").length ){
            $("#b-variants .sortable").sortable({
                update: function( event, ui ) {
                    updateVariantsSort();
                }
            }).disableSelection();

            switch( $("#new-variant").attr("data-type") ) {
                case "float":
                    $("#new-variant").numericInput({ allowFloat: true, allowNegative: true });

                    break;
                case "int":
                    $("#new-variant").numericInput({ allowFloat: false, allowNegative: true });

                    break;
            }
        }
    }
    /* Variants ------------------------------------ Variants */

    /* Left menu ----------------------------------- Left menu */
    if( $(".b-menu-accordeon").length ){
        $("body").on("click", ".b-menu-accordeon", function(){
            if( $(this).hasClass("opened") ){
                $(this).removeClass("opened");
            }else{
                $(".b-menu-accordeon").removeClass("opened");
                $(this).addClass("opened");
            }
            return false;
        });
    }
    /* Left menu ----------------------------------- Left menu */

    /* Files --------------------------------------- Files */
    $("body").on("click", ".b-file-remove", function(){
        var $cont = $(this).parents(".b-doc-file");
        if( $cont.hasClass("removed") ){
            $(this).parents(".b-doc-file").removeClass("removed");
            $("#"+$(this).attr("data-id")).prop("checked", false);
        }else{
            $(this).parents(".b-doc-file").addClass("removed");
            $("#"+$(this).attr("data-id")).prop("checked", true);
        }
    });
    /* Files --------------------------------------- Files */

    /* Resizable inputs ---------------------------- Resizable inputs */

    $(".b-resize-input input").resizableInput();


    // resizeAllInput();

    // $(window).load(resizeAllInput);

    // $("body").on('input', '.b-resize-input input', function() {
    //     resizeInput( $(this) );
    // });

    // $(".b-resize-input input").keyup(function(e){
    //     console.log(e);
    //     if ( e.key == "Backspace" || e.keyCode == 8 ) {
    //         focusToPreviousInput( $(this) );
    //     }
    //     console.log($(this).val());
    // });

    // function resizeAllInput(){
    //     $(".b-resize-input input").each(function() {
    //         resizeInput( $(this) );
    //     });
    // }

    // function focusToNextInput($el){
    //     if( $el.parent(".b-resize-input").next(".b-resize-input").length ){
    //         $el.parent(".b-resize-input").next(".b-resize-input").find("input").focus();
    //     }
    // }

    // function focusToPreviousInput($el){
    //     if( $el.parent(".b-resize-input").prev(".b-resize-input").length ){
    //         $el.parent(".b-resize-input").prev(".b-resize-input").find("input").focus();
    //     }
    // }

    // function resizeInput($el){
    //     var $buffer = $el.next(".input-buffer"),
    //         value = $el.val();

    //     if( value !== value.trim() ){
    //         focusToNextInput($el);
    //     }

    //     if( value != "" ){
    //         $buffer.text( $el.val() );
    //     }else{
    //         $buffer.text( $el.attr("placeholder") );
    //     }
    //     $el.width( $buffer.width() );
    // }
    /* Resizable inputs ---------------------------- Resizable inputs */

    function transition(el,dur){
        el.css({
            "-webkit-transition":  "all "+dur+"s ease-in-out", "-moz-transition":  "all "+dur+"s ease-in-out", "-o-transition":  "all "+dur+"s ease-in-out", "transition":  "all "+dur+"s ease-in-out"
        });
    }

    bindFilter();
    bindAutocomplete();
    bindTooltip();
    bindImageUploader();
    bindFancy();

    if( $(".validatable").length ){
        $(".validatable").each(function(){
            bindForm( $(this) );
        });
    }

    $("body").on("click", ".b-burger", function(){
        toggleMenu();

        return false;
    });

    $("body").on("click touchend", ".b-menu-overlay", function(){
        if( $(".b-header").hasClass("opened") ){
            toggleMenu(false);
        }
    });

    function bindFancy(){
        $(".fancy").each(function(){
            var $popup = $($(this).attr("href")),
                $this = $(this);
            $this.fancybox({
                padding : 0,
                content : $popup,
                touch: false,
                helpers: {
                    overlay: {
                        locked: true 
                    }
                },
                beforeShow: function(){
                    $(".fancybox-wrap").addClass("beforeShow");
                    $popup.find(".custom-field").remove();
                    if( $this.attr("data-value") ){
                        var name = getNextField($popup.find("form"));
                        $popup.find("form").append("<input type='hidden' class='custom-field' name='"+name+"' value='"+$this.attr("data-value")+"'/><input type='hidden' class='custom-field' name='"+name+"-name' value='"+$this.attr("data-name")+"'/>");
                    }
                    if( $this.attr("data-beforeShow") && customHandlers[$this.attr("data-beforeShow")] ){
                        customHandlers[$this.attr("data-beforeShow")]($this);
                    }
                },
                afterShow: function(){
                    $(".fancybox-wrap").removeClass("beforeShow");
                    $(".fancybox-wrap").addClass("afterShow");
                    if( $this.attr("data-afterShow") && customHandlers[$this.attr("data-afterShow")] ){
                        customHandlers[$this.attr("data-afterShow")]($this);
                    }
                    $popup.find("input[type='text'],input[type='number'],textarea").eq(0).focus();
                },
                beforeClose: function(){
                    $(".fancybox-wrap").removeClass("afterShow");
                    $(".fancybox-wrap").addClass("beforeClose");
                    if( $this.attr("data-beforeClose") && customHandlers[$this.attr("data-beforeClose")] ){
                        customHandlers[$this.attr("data-beforeClose")]($this);
                    }
                    $(".select2-drop").hide();
                },
                afterClose: function(){
                    $(".fancybox-wrap").removeClass("beforeClose");
                    $(".fancybox-wrap").addClass("afterClose");
                    if( $this.attr("data-afterClose") && customHandlers[$this.attr("data-afterClose")] ){
                        customHandlers[$this.attr("data-afterClose")]($this);
                    }
                }
            });
        });
    }

    function toggleMenu(tog){
        if( tog === false ){
            $(".b-header").removeClass("opened");
        }else if( tog === true ){
            $(".b-header").addClass("opened");
        }else{
            $(".b-header").toggleClass("opened");
        }

        if( $(".b-header").hasClass("opened") ){
            $(".b-menu-overlay").addClass("show");
        }else{
            $(".b-menu-overlay").removeClass("show");
        }
    }

    $('#faculties-form').progressBtn({
        buttonId : 'b-progress-bar-container'
    });

    $('#b-progress-bar-container').on('click',function(){
        if ($("#b-progress-bar").attr('data-complete') == 'true') {
            $('#faculties-form').submit();
        }
    });

    $('#passenger_count').on('change',function(){
        $('#passenger_total_count').text($(this).val());
        $('#totalPassText').text(pluralForm($(this).val(), 'пассажир', 'пассажира', 'пассажиров'));
    });

    function calcTotalPrice(){
        var price = 0;
        $('.b-order-form-person').each(function(){
            price += $(this).find('.b-person-price').attr('data-price')*1;
        })
        $('#totalSum').text(price.toLocaleString());
        $('#totalSumText').text(pluralForm(price, 'рубль', 'рубля', 'рублей'));
    }

    calcTotalPrice();

    function pluralForm(number, one, two, five) {
        number = Math.abs(number);
        number %= 100;
        if (number >= 5 && number <= 20) {
            return five;
        }
        number %= 10;
        if (number == 1) {
            return one;
        }
        if (number >= 2 && number <= 4) {
            return two;
        }
        return five;
    } 

    function checkTotalSum(){
        var length = $('#totalSum').text().length;

        console.log(length);

        if (length >= 7) {
            $('#totalSum').addClass('hundreds');
            $('#passenger_total_count').addClass('hundreds');
        } else {
            $('#totalSum').removeClass('hundreds');
            $('#passenger_total_count').removeClass('hundreds');
        }
        
    }

    checkTotalSum();
    
});


// $(window).load(function(){
//     $('.b-sum-text-container').resizableFont();
//     $('#passenger_total_count').css('font-size', $('#totalSum').css('font-size'));
//     $('#totalPassText').css('font-size', $('#totalSumText').css('font-size'));
// })



// function fontSizeToParentBlockSize(firstWidth, firstTotalSumSize, firstTotalSumTextSize) {
//     var totalSum = $('#totalSum'),  
//         totalSumText = $('#totalSumText'),
//         totalSumfz = totalSum.css('font-size'),
//         totalSumTextfz = totalSumText.css('font-size'),
//         width = totalSum.width() + totalSumText.width();
//         console.log('--------');
//         console.log('width-'+width);
//         console.log('firstWidth-'+firstWidth);
//         console.log('totalSum.parent().width()-'+totalSum.parent().width());
//         console.log('--------');

//     if (width >= totalSum.parent().width()) {
//         totalSum.css('font-size', parseInt(totalSumfz) - 2);
//         totalSumText.css('font-size', parseInt(totalSumTextfz) - 1);
//     } else {
//         totalSum.css('font-size', parseInt(firstTotalSumSize));
//         totalSumText.css('font-size', parseInt(firstTotalSumTextSize));
//     }
// }

// var firstWidth = $('#totalSum').width() + $('#totalSumText').width(),
//     firstTotalSumSize = $('#totalSum').css('font-size'),
//     firstTotalSumTextSize = $('#totalSumText').css('font-size');