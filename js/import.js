$(document).ready(function(){
    var progress = new KitProgress("#D26A44",2);
    progress.endDuration = 0.3,
    errors = 0;

    if( $(".b-excel-input").length ){
        $(".b-excel-input").change(function(){
            if( $(this).val() != "" ){
                $(".b-import-image").addClass("active");
                $(".b-import-butt").removeClass("hidden");
            }else{
                $(".b-import-image").removeClass("active");
                $(".b-import-butt").addClass("hidden");
            }
        });
    }

    // Третий шаг --------------------------------------------------- Третий шаг
    if( $(".b-import-preview-table").length ){
        var log = $(".b-log"),
            count,
            ready = 0;
        // $(".b-import-butt").click(function(){
            startImport();
            // return false;
        // });
    }
    function startImport(){
        count = $(".b-import-preview-table tr").length,
        showImport();
        sendNext();
    }
    function endImport(){
        $(".progress").addClass("ready");
        setLog("Импорт завершен. Ошибок: "+errors);
    }
    function showImport(){
        $(".b-import").show();
        $(".b-preview").hide();
    }
    function sendNext(){
        if( $(".b-import-preview-table tr").eq(0).length ){
            var $tr = $(".b-import-preview-table tr").eq(0),
                data = $('<form>').append( $tr.clone() ).serialize();

            $tr.remove();

            $.ajax({
                type: "POST",
                url: $(".b-preview").attr("data-url"),
                data: data,
                success: function(msg){
                    var json = JSON.parse(msg);
                    setLog(json.message,json.result);
                },
                error: function(){
                    setLog("Ошибка в работе php-скрипта","error");
                },
                complete: function(){
                    ready++;
                    updateProgressBar();
                    sendNext();
                }
            });
        }
    }
    function setLog(string,result){
        if( result == "error" ) errors++;
        var li = $("<li>"+string+"</li>");
        if( typeof result == "string" )
            li.addClass(result);

        log.prepend(li);
    }
    function updateProgressBar(){
        var percent = Math.ceil(ready/count*100)+"%";
        $(".progress-bar").css("width",(percent > 100)?100:percent).html((percent > 100)?100:percent);
        if( count == ready ){
            setTimeout(endImport,200);
        }
    }
    // Третий шаг --------------------------------------------------- Третий шаг

});