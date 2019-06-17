$(document).ready(function(){	

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
        myWidth = ( myWidth < 1630 )?1630:myWidth;
        myHeight = myWidth/16*9;
        $(".b-video").css({
            "width" : myWidth,
            "height" : myHeight
        });
    }
    $(window).resize(resize);
    resize();

    var curSpan = 0;
    $(".b-1 .car h2 span").eq(0).show();

    setInterval(function(){
        $(".b-1 .car h2 span").fadeOut(300);
        curSpan = (curSpan+1 < $(".b-1 .car h2 span").length)?(curSpan+1):0;
        setTimeout(function(){
            $(".b-1 .car h2 span").eq(curSpan).fadeIn(300);
        },330);
    },4000);

    $('.b-video').fadeIn(500);

    $("select[name='1']").change(function(){
        var mark = $("select[name='1'] option:selected").val();
        $("select[name='2'],select[name='3']").empty();
        if(mark=="другое") {
            $("select[name='2']").append('<option value="" disabled>Марка</option><option value="другое" selected>Другое</option>');
            $("select[name='3']").append('<option value="" disabled>Двигатель</option><option value="другое" selected>Другое</option>');
            $("#car-img").attr("src",'/upload/images/default.png');
            if(!$("#logo-img").parent().hasClass("hidden")) {
                $("#logo-img").attr("src",'').parent().addClass("hidden").parent().find("h2").addClass("no-logo");
            }
            $("#title-name").text("автомобиль");
            $("#logo-name").text('вашего автомобиля');
        } else {
            $("select[name='3']").append('<option value="" disabled selected>Двигатель</option>');
            $("select[name='"+mark+"'] option").clone().appendTo("select[name='2']");
            if($("select[name='"+mark+"']").attr("data-car")!='') {
                var carImg = $("select[name='"+mark+"']").attr("data-car");
                $("#car-img").attr("src",carImg);
                $("#title-name").text(mark);
                $("#logo-name").html(mark+'<br>');
                if($("select[name='"+mark+"']").attr("data-logo")!='') {
                    var logoImg = $("select[name='"+mark+"']").attr("data-logo");
                    $("#logo-img").attr("src",logoImg).parent().removeClass("hidden").parent().find("h2").removeClass("no-logo");
                } else if(!$("#logo-img").parent().hasClass("hidden")) {
                    $("#logo-img").attr("src",'').parent().addClass("hidden").parent().find("h2").addClass("no-logo");
                }            
            } else {
                $("#car-img").attr("src",'/upload/images/default.png');
                if(!$("#logo-img").parent().hasClass("hidden")) {
                    $("#logo-img").attr("src",'').parent().addClass("hidden").parent().find("h2").addClass("no-logo");
                }
                $("#title-name").text("автомобиль");
                $("#logo-name").text('вашего автомобиля');
            }
        }

    });

    $("select[name='2']").change(function(){
        var model = $("select[name='2'] option:selected").val();
        $("select[name='3']").empty();
        if(model=="другое") {
            $("select[name='3']").append('<option value="" disabled>Двигатель</option><option value="другое" selected>Другое</option>');
        } else {
            $("select[name='"+model+"'] option").clone().appendTo("select[name='3']");
        }

    });
    var select = $("select[name='1']").attr("data-brand");
        if(select!='') {
        for (var i = 0; i < $("select[name='1'] option").length; i++) {
            if(select.toLowerCase() == $("select[name='1'] option").eq(i).text().toLowerCase()) {
                $("select[name='1'] option").eq(i).prop("selected",true);    
                $("select[name='1']").change();
            } 
        }
    }
             
    
});






