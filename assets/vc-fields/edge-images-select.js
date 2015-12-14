/**
 * Created by thien on 12/12/2015.
 */
jQuery(function ($) {
    "use strict";

    $('.vc_images_select_param_block').on('click', 'ul li img', function(){
        $(this).parents('ul').find('img').removeClass('active');
        $(this).addClass('active');
        var _value = $(this).parent().data('value');
        $(this).parents('.vc_images_select_param_block').find('input').val(_value);
    })
})
