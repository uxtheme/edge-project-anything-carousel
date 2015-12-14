/**
 * Created by EDGE on 08/12/2015.
 */
jQuery(function ($) {
    "use strict";
    
    $('.visual-carousel .visual-carousel-items').each(function (index) {

        var _e = $(this);

        var _options = {};

        _options.items = visual_carousel_data_int(_e.data('items'), 3);
        _options.margin = visual_carousel_data_int(_e.data('margin'), 0);
        _options.loop = visual_carousel_data_boolean(_e.data('loop'), false);
        _options.center = visual_carousel_data_boolean(_e.data('center'), false);
        _options.stagePadding = visual_carousel_data_int(_e.data('stagepadding'), 0);
        _options.merge = visual_carousel_data_boolean(_e.data('merge'), false);
        _options.autoWidth = visual_carousel_data_boolean(_e.data('autowidth'), false);
        _options.startPosition = visual_carousel_data_int(_e.data('startposition'), 0);

        _options.autoplay = visual_carousel_data_boolean(_e.data('autoplay'), false);
        _options.autoplayTimeout = visual_carousel_data_int(_e.data('autoplay_timeout'), 5000);
        _options.autoplayHoverPause = visual_carousel_data_boolean(_e.data('autoplay_hover_pause'), false);

        _options.smartSpeed = visual_carousel_data_int(_e.data('smart_speed'), 250);
        _options.autoplaySpeed = visual_carousel_data_int(_e.data('autoplay_speed'), false);
        _options.navSpeed = visual_carousel_data_int(_e.data('nav_speed'), false);
        _options.dotsSpeed = visual_carousel_data_int(_e.data('dots_speed'), false);

        _options.nav = visual_carousel_data_boolean(_e.data('nav'), false);
        _options.navRewind = visual_carousel_data_boolean(_e.data('navrewind'), false);
        _options.dots = visual_carousel_data_boolean(_e.data('dots'), false);

        _options.animateIn = visual_carousel_data(_e.data('animate_in'), false);
        _options.animateOut = visual_carousel_data(_e.data('animate_out'), false);
        
        /* nav. */
        var _prev = _e.data('prev');
        var _next = _e.data('next');
        if(_prev != undefined && _next != undefined && _prev != '' && _next != ''){
        	_options.navText = [_prev, _next];
        }
        
        /* responsive. */
        var responsive = _e.data('responsive');
        
        if(responsive != undefined && responsive != ''){
        	
        	var _responsive = {};
        	
        	responsive = $.parseJSON(decodeURIComponent(responsive));
        	
        	$.each(responsive, function(_index, _value) {
        		var new_value = {};
        		var _size = parseInt(_value.width);
        		
        		if(_value.items != undefined)
        			new_value.items = parseInt(_value.items);
        		if(_value.margin != undefined)
        			new_value.margin = parseInt(_value.margin);
        		if(_value.stagePadding != undefined)
        			new_value.stagePadding = parseInt(_value.stagePadding);
        		
        		_responsive[_size] = new_value;
			});
        	
        	_options.responsiveClass = true;
        	_options.responsive = _responsive;
        }
        
        /* owl */
        var owl = _e.owlCarousel(_options);
        
        /* mousewheel. */
        if(_e.data('mousewheel') == '1'){
	        owl.on('mousewheel', '.owl-stage', function (e) {
	            if (e.deltaY>0) {
	                owl.trigger('next.owl');
	            } else {
	                owl.trigger('prev.owl');
	            }
	            e.preventDefault();
	        });
        }
    })

    function visual_carousel_data_boolean($attr, $default){

        return $attr != undefined ? Boolean($attr) : $default ;
    }

    function visual_carousel_data_int($attr, $default){

        return $attr != undefined && $attr != '' ? parseInt($attr) : $default ;
    }

    function visual_carousel_data($attr, $default){
        return $attr != undefined && $attr != '' ? $attr : $default ;
    }
})