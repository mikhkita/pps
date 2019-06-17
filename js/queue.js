$(document).ready(function(){	
    var progress = new KitProgress("#FFF",2),
        offset = 0;
    
    progress.endDuration = 0.3;

    $(window).load(function() {
        $.ajax({
            type: "GET",
            url: "/event/gettime",
            success: function(msg){
                offset = Math.round($.now()/1000)-msg;
            }
        });
    });

    function resize(){
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
        
    }
    $(window).resize(resize);
    resize();

    $("#refresh").click(getNewItems);

    function getNewItems(){
        $.ajax({
            type: "GET",
            url: "/admin/event/refresh?event="+$(".b-queue-list").attr("data-id")+"&last_id="+getLastId()+"&limit="+getLimit(),
            success: function(msg){
                var json = JSON.parse(msg),
                    str = "";

                if( typeof json.count != "undefined" ){
                    $(".b-left-butt span").html("В очереди: "+json.count);
                }

                for( var i in json.items ){
                    str += "<li data-id='"+json.items[i].id+"''><img src='"+json.items[i].image+"'><span data-time='"+(json.items[i].time)+"'>"+(json.items[i].time-curTimeFunc())+"</span></li>";
                }

                $(".b-queue-list").append(str);
                bindClick();
            }
        });
    }

    function getLimit(){
        return 121-(($(".b-queue-list li").length)?$(".b-queue-list li").length:0);
    }

    function getLastId(){
        var max = 0;
        $(".b-queue-list li").each(function(){
            var cur = $(this).attr("data-id")*1;
            if( cur > max ) max = cur;
        });
        return max;
    }

    bindClick();

    function bindClick(){
        $(".b-queue-list li").unbind("click").bind("click",function(){
            var $this = $(this);

            progress.setColor("#D26A44");
            progress.start(2);

            $.ajax({
                type: "GET",
                url: "/admin/event/deletephoto?id="+$this.attr("data-id"),
                success: function(msg){
                    var json = JSON.parse(msg);

                    if( json.result == "error" ){
                        alert(json.message);
                    }

                    $this.addClass("delete");
                    setTimeout(function(){
                        $this.remove();
                    },300);

                    for( var i in json.items ){
                        var curTime = (json.items[i].time-curTimeFunc());
                        $(".b-queue-list li[data-id='"+json.items[i].id+"'] span").attr("data-time",json.items[i].time).text(curTime);
                    }

                    progress.end();
                },
                error: function(){
                    alert("Ошибка удаления");
                    progress.end();
                }
            });
        });
    }

    

    function updateTimer(){
        $(".b-queue-list li span").each(function(){
            var time = $(this).attr("data-time")*1-curTimeFunc();
            $(this).text(time);
            if( time <= 0 ){
                var $this = $(this).parents("li");
                $this.addClass("remove");
                setTimeout(function(){
                    $this.remove();
                },500);
            }
        });
    }

    function curTimeFunc(){
        return Math.round($.now()/1000)-offset;
    }

    setInterval(updateTimer,1000);

    getNewItems();
    setInterval(getNewItems,10000);

});






