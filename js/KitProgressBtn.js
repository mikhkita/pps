(function($) {
    $.fn.progressBtn = function(options) {
        var _ = this,
        total = _.find('input[required], select[required]').length;

        options = $.extend({
            colors : {
                0 : {
                    rgb : 'rgb(178, 16, 16)',
                    point : 0
                },
                1 : {
                    rgb : 'rgb(236, 143, 30)',
                    point : 50
                },
                2 : {
                    rgb : 'rgb(28, 160, 4)',
                    point : 100
                },
            },
            buttonId : 'b-progress-bar-container',
        }, options);

        _.extend({
            total : total,
            o : options 
        });

        _.extend({
            _init : function (){

                _.initHandlers();

            },

            initHandlers : function(){

                infoText = '<div class="b-right-tile-block-bottom-text">'+
                                'Осталось заполнить '+
                                '<a href="#" id="b-need-to-fill-inputs-text">'+
                                    '<span id="b-need-to-fill-inputs-count">'+_.total+'</span>'+
                                ' обязательных поля'+
                                '</a>'+
                            '</div>';

                $('#' + _.o.buttonId).prepend('<div id="b-progress-bar" data-complete="false" class="b-progress-bar"></div>');
                $('#' + _.o.buttonId).after(infoText);
            },

            rgbColorPercent : function(percent) {

                var firstPoint,
                    secondPoint;

                for (var color in _.o.colors){
                    if (percent > _.o.colors[color].point) {
                        firstPoint = _.o.colors[color];
                    } else {
                        secondPoint = _.o.colors[color];
                        break;
                    }
                }

                firstColors = firstPoint.rgb.replace(/[^+\d\,]/g, '').split(',');
                secondColors = secondPoint.rgb.replace(/[^+\d\,]/g, '').split(',');

                for (var rgb in firstColors){
                    firstColors[rgb] = firstColors[rgb]*1;
                }
                for (var rgb in secondColors){
                    secondColors[rgb] = secondColors[rgb]*1;
                }

                var kf = (percent - secondPoint.point)/(firstPoint.point - secondPoint.point);

                R = Math.round(secondColors[0] - (secondColors[0] - firstColors[0]) * kf);
                G = Math.round(secondColors[1] - (secondColors[1] - firstColors[1]) * kf);
                B = Math.round(secondColors[2] - (secondColors[2] - firstColors[2]) * kf);

                return 'rgb('+R+', '+G+', '+B+')';
            },

            checkProgress : function(filled) {

                var percent = (parseInt(filled)/parseInt(_.total))*100,
                    color = _.rgbColorPercent(percent);
                $('#b-progress-bar').css('background-color', color);
                $('#b-progress-bar').css('width', percent+'%');

                if (filled == _.total) {
                    return true;
                }
            },

            checkFilledInputs : function(needToFill){
                var infoText = $('#'+_.o.buttonId).siblings('.b-right-tile-block-bottom-text');
                if (needToFill == 0) {
                    if (!infoText.hasClass('hide')) {
                        infoText.addClass('hide');
                    }
                    $('#'+_.o.buttonId).parents('#b-progress-bar').attr('data-complete', 'true');
                } else {
                    if (infoText.hasClass('hide')) {
                        infoText.removeClass('hide');
                    }
                    infoText.find('#b-need-to-fill-inputs-count').text(''+needToFill);
                    $('#'+_.o.buttonId).find('#b-progress-bar').attr('data-complete', 'false');
                }
            },
        });

        _.find('input[required], select[required]').on('change', function(){
            var total = 0,
                filled = 0;

            _.find('input, select').each(function(){
                if ($(this).attr('required')) {
                    total ++;
                    if($(this).val() != ''){
                        filled ++;
                    }
                }
            });

            var needToFill = total - filled;
            _.checkFilledInputs(needToFill);
            _.checkProgress(filled);

        });

        _._init();
    };
})(jQuery);