(function($) {
    $.fn.progressBtn = function(options) {
        var _ = this,
        total = _.find('input[required], select[required], textarea[required]').length;

        options = $.extend({
            colors : [
                {
                    rgb : 'rgb(178, 16, 16)',
                    point : 0
                },
                {
                    rgb : 'rgb(236, 143, 30)',
                    point : 33
                },
                {
                    rgb : 'rgb(244, 202, 4)',
                    point : 67
                },
                {
                    rgb : 'rgb(146, 210, 25)',
                    point : 90
                },
                {
                    rgb : 'rgb(28, 160, 4)',
                    point : 100
                }
            ],
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

                $('#' + _.o.buttonId).prepend('<div id="b-progress-bar" data-complete="false" class="b-progress-bar"></div>');
                $('#' + _.o.buttonId).after(_.writeInfoBlock(_.total));
            },

            findNeedToFeel : function(){
                var total = 0,
                    filled = 0;

                _.find('input[required], select[required], textarea[required]').each(function(){
                    if( !$(this).parents(".hide").length ){
                        total ++;
                    }
                    if($(this).val() != ''){
                        filled ++;
                    }
                });

                var needToFill = total - filled;

                _.checkFilledInputs(needToFill);
                _.checkProgress(total, filled);
            },

            rgbColorPercent : function(percent) {

                var firstPoint,
                    secondPoint;
                   
                for (var color in _.o.colors){
                    if (percent == 0) {
                        firstPoint = _.o.colors[color];
                    }
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

            checkProgress : function(total, filled) {

                var percent = (parseInt(filled)/parseInt(total))*100,
                    color = _.rgbColorPercent(percent);

                $('#b-progress-bar').css('background-color', color);
                $('#b-progress-bar').css('width', percent+'%');

                if (filled == total) {
                    return true;
                }
            },

            checkFilledInputs : function(needToFill){

                if (needToFill == 0) {
                    $('#'+_.o.buttonId).find('#b-progress-bar').attr('data-complete', 'true');
                } else {
                    $('#'+_.o.buttonId).find('#b-progress-bar').attr('data-complete', 'false');
                }

                $('#' + _.o.buttonId).siblings('.b-right-tile-block-bottom-text').remove();
                $('#' + _.o.buttonId).after(_.writeInfoBlock(needToFill));
            },

            writeInfoBlock : function(needToFill){

                if (needToFill != 0) {
                    var pluralText = _.pluralForm(needToFill, 'обязательное поле', 'обязательных поля', 'обязательных полей');
                    return infoText = '<div class="b-right-tile-block-bottom-text">'+
                                            'Осталось заполнить '+
                                            '<a href="#" id="b-need-to-fill-inputs-text">'+
                                                '<span id="b-need-to-fill-inputs-count">'+needToFill+'</span>'+
                                            ' '+pluralText+
                                            '</a>'+
                                        '</div>';
                } else {
                    return infoText = '<div class="b-right-tile-block-bottom-text">'+
                                            'Все обязательные поля заполнены'+
                                        '</div>';
                }
            },

            pluralForm : function(number, one, two, five) {
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
        });

        _._init();

        _.on('change', 'input[required], select[required], textarea[required]', _.findNeedToFeel);

        _.on('updateState', _.findNeedToFeel);

        $('#'+_.o.buttonId).parent().on('click', '#b-need-to-fill-inputs-text', function(){
            _.submit();
        })


    };
})(jQuery);