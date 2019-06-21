(function($) {
    $.fn.resizableFont = function(options) {
        var _ = this,

        options = $.extend({

        }, options);

        _.extend({
            o : options
        });

        _.extend({

            _init : function (){

                _.initHandlers();

            },

            initHandlers : function(){

                _.find('.resizable-font-item').each(function(){

                    var width = _.countWidth();

                    if (width > _.width()) {
                        while (width > _.width()) {

                            _.fontSizeToParentBlockSize('-');
                            width = _.countWidth();

                        }
                    } else {
                        while (width <= _.width()) {

                            _.fontSizeToParentBlockSize('+');
                            width = _.countWidth();

                        }
                    }

                });

            },

            fontSizeToParentBlockSize : function(math) {
                
                _.find('.resizable-font-item').each(function(){

                    var fz = $(this).css('font-size').split('px'),
                        lh = $(this).css('line-height').split('px'),
                        i = 1;

                    if ($(this).hasClass('big-resizable-font-item')) {
                        i = 2;
                    }

                    if (math == '+') {
                        fontsize = (fz[0]*1 + i) + 'px';
                        lineheight = (lh[0]*1 + i) + 'px';
                    } else {
                        fontsize = (fz[0]*1 - i) + 'px';
                        lineheight = (lh[0]*1 - i) + 'px';
                        if (fz[0]*1 < 18) {
                            return false;
                        }
                    }

                    $(this).css('font-size', fontsize);
                    $(this).css('line-height', lineheight);

                });

            },

            countWidth : function(){
                
                width = 0;

                _.find('.resizable-font-item').each(function(){
                    width += $(this).outerWidth();
                });

                return width;
            }

        });

        _._init();

    };
})(jQuery);