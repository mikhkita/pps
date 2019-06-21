// 
//	Kit Resizable Input v1.0
//
// 	Developed by Mike Kitaev
//
//	Website: http://redder.pro/
//	E-mail: mike@kitaev.pro
//

(function($) {
	$.fn.resizableInput = function(options) {
		var _ = this;

		// default options
		options = $.extend({
			previousValue : null,
		}, options);

		// service options
       	_.extend({ 
       		items : "",
       		o : options 
       	});

       	// methods
		_.extend({
			_init : function (){
				_.resizeAllInput();

				_.initHandlers();

				_.afterInit();
			},

			initHandlers : function(){
				$(window).load(_.resizeAllInput);

				_.focus(function(){
					if( $(this).parent().prev().find("input").length && !$(this).parent().prev().find("input").val() ){
						$(this).parent().prev().find("input").focus();
					}
				});

				_.on('input', function() {
					var value = $(this).val();

					if( value !== value.trim() ){
			            if( _.focusToNextInput($(this)) ){
			            	$(this).val( value.trim() );
			            }
			        }

			        _.resizeInput( $(this) );
			    });

			    _.keydown(function(e){
			        _.previousValue = $(this).val();

			        if ( (e.key == "Backspace" || e.keyCode == 8) && _.getCursorPosition( $(this)[0] ) === 0 ) {
			            _.focusToPreviousInput( $(this) );
			            e.preventDefault();
			        }
			    });
			},


			resizeInput : function ($el){
		        var $buffer = $el.prev(".input-buffer"),
		            value = $el.val();

		        if( value != "" ){
		            $buffer.text( $el.val().replace(" ", "&nbsp;") );
		        }else{
		            $buffer.text( $el.attr("placeholder") );
		        }

		        $el.width( $buffer.width() );
		    },

		    getCursorPosition : function( ctrl ) {
		        var CaretPos = 0;

		        if( ctrl.selectionStart != ctrl.selectionEnd ){
		        	return false;
		        }else{
		        	return ctrl.selectionStart
		        }
		    },

		    resizeAllInput : function(){
		        $(".b-resize-input input").each(function() {
		            _.resizeInput( $(this) );
		        });
		    },

		    focusToNextInput : function($el){
		        if( $el.parent().next().find("input").length ){
		            $el.parent().next().find("input").focus();
		        }

		        return $el.parent().next().find("input").length;
		    },

		    focusToPreviousInput : function($el){
		        if( $el.parent().prev().find("input").length ){
		            $el.parent().prev().find("input").focus();
		        }
		    },

		    afterInit : function(){
				
			}
		});

		_._init();
	};
})(jQuery);