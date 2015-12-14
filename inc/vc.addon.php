<?php
/**
 * VC Addon.
 *
 * @author FOX
 */
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('Visual_Carousel_vc_addon')) :

    /**
     * Main Class
     *
     * @class Visual_Carousel_vc_addon
     *
     * @version 1.0.0
     */
    final class Visual_Carousel_vc_addon
    {

        function __construct()
        {
            add_action('wp_enqueue_scripts', array(
                $this,
                'add_scripts'
            ));

            add_action('vc_before_init', array(
                $this,
                'add_params'
            ));

            /* add shortcode "visual_carousel" */
            add_shortcode('visual_carousel', array(
                $this,
                'add_shortcode_visual_carousel'
            ));

            /* add shortcode "visual_carousel_item" */
            add_shortcode('visual_carousel_item', array(
                $this,
                'add_shortcode_visual_carousel_item'
            ));

            /* custom param for vc. */
            vc_add_shortcode_param('visual-carousel-number', array(
                $this,
                'number_param_settings_field'
            ));
        }

        function add_scripts()
        {
            wp_register_style('animate', visual_carousel()->acess_url . 'css/animate.min.css');
            wp_register_script('jquery.mousewheel', visual_carousel()->acess_url . 'js/jquery.mousewheel.min.js', array(
                'jquery'
            ), '3.1.13', true);

            wp_register_style('owl.carousel', visual_carousel()->acess_url . 'css/owl.carousel.css');
            wp_register_script('owl.carousel', visual_carousel()->acess_url . 'js/owl.carousel.min.js', array(
                'jquery'
            ), '2.0.0', true);

            wp_register_style('visual-carousel', visual_carousel()->acess_url . 'css/visual-carousel.css');
            wp_register_script('visual-carousel', visual_carousel()->acess_url . 'js/visual-carousel.js', array(
                'owl.carousel'
            ), '1.0.0', true);
        }

        function add_params()
        {
            if (!function_exists("vc_map"))
                return;

            vc_map(
                array(
                    "name" => esc_html__("Visual Carousel", "visual-carousel"),
                    "base" => "visual_carousel",
                    "class" => "visual_carousel",
                    "as_parent" => array(
                        'except' => 'visual_carousel'
                    ),
                    "content_element" => true,
                    "controls" => "full",
                    "show_settings_on_create" => true,
                    "category" => "Visual Carousel",
                    "description" => esc_html__("Carousel anything.", "visual-carousel"),
                    "params" => array(
                        array(
                            "type" => "loop",
                            "heading" => esc_html__("Select Source", "visual-carousel"),
                            "param_name" => "slider_source",
                            "settings" => array(),
                            "group" => esc_html__("Source", "visual-carousel")
                        ),
                        array(
                            "type" => "edge-images-select",
                            "heading" => esc_html__("Style", "visual-carousel"),
                            "param_name" => "style",
                            "options" => visual_carousel_get_template_thumb(),
                            "description" => esc_html__("Select a style.", "visual-carousel"),
                            "group" => esc_html__("Layout", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Items", "visual-carousel"),
                            "param_name" => "items",
                            "description" => esc_html__("The number of items you want to see on the screen.", "visual-carousel"),
                            "group" => esc_html__("Layout", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Margin", "visual-carousel"),
                            "param_name" => "margin",
                            "description" => esc_html__("margin-right(px) on item.", "visual-carousel"),
                            "group" => esc_html__("Layout", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Stage Padding", "visual-carousel"),
                            "param_name" => "stagepadding",
                            "description" => esc_html__("Padding left and right on stage (can see neighbours).", "visual-carousel"),
                            "group" => esc_html__("Layout", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Start Position", "visual-carousel"),
                            "param_name" => "startposition",
                            "description" => esc_html__("Start position.", "visual-carousel"),
                            "group" => esc_html__("Layout", "visual-carousel")
                        ),

                        /* mode */
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Loop", "visual-carousel"),
                            "param_name" => "loop",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Inifnity loop. Duplicate last and first items to get loop illusion.", "visual-carousel"),
                            "group" => esc_html__("Mode", "visual-carousel")
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Center", "visual-carousel"),
                            "param_name" => "center",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Center item. Works well with even an odd number of items.", "visual-carousel"),
                            "group" => esc_html__("Mode", "visual-carousel")
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Merge", "visual-carousel"),
                            "param_name" => "merge",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Merge items. Looking for data-merge='{number}' inside item...", "visual-carousel"),
                            "group" => esc_html__("Mode", "visual-carousel")
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Auto Width", "visual-carousel"),
                            "param_name" => "autowidth",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Set non grid content. Try using width style on divs.", "visual-carousel"),
                            "group" => esc_html__("Mode", "visual-carousel")
                        ),

                        /* play */
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("AutoPlay", "visual-carousel"),
                            "param_name" => "autoplay",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Autoplay.", "visual-carousel"),
                            "group" => esc_html__("Play", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Autoplay Timeout", "visual-carousel"),
                            "param_name" => "autoplay_timeout",
                            "dependency" => array(
                                'element' => 'autoplay',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("Autoplay interval timeout ( Default: 5000 ) .", "visual-carousel"),
                            "group" => esc_html__("Play", "visual-carousel")
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("AutoPlay Hover Pause", "visual-carousel"),
                            "param_name" => "autoplay_hover_pause",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "dependency" => array(
                                'element' => 'autoplay',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("Pause on mouse hover.", "visual-carousel"),
                            "group" => esc_html__("Play", "visual-carousel")
                        ),

                        /* nav */
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Nav", "visual-carousel"),
                            "param_name" => "nav",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Show next/prev buttons.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Nav Type", "visual-carousel"),
                            "param_name" => "nav_type",
                            "value" => array(
                                'Icons' => '',
                                'Text' => 'text'
                            ),
                            "dependency" => array(
                                'element' => 'nav',
                                'value' => array('1')
                            ),
                            "description" => esc_html__("Icons or Text.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Next", "visual-carousel"),
                            "param_name" => "next_text",
                            "dependency" => array(
                                'element' => 'nav_type',
                                'value' => array('text')
                            ),
                            "description" => esc_html__("Custom text for next button.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "iconpicker",
                            "heading" => esc_html__("Next", "visual-carousel"),
                            "param_name" => "next_icon",
                            "dependency" => array(
                                'element' => 'nav_type',
                                'value' => array('')
                            ),
                            'settings' => array(
                                'emptyIcon' => false,
                                'type' => 'fontawesome',
                            ),
                            "description" => esc_html__("Select icon for next button.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Prev", "visual-carousel"),
                            "param_name" => "prev_text",
                            "dependency" => array(
                                'element' => 'nav_type',
                                'value' => array('text')
                            ),
                            "description" => esc_html__("Custom text for prev button.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "iconpicker",
                            "heading" => esc_html__("Prev", "visual-carousel"),
                            "param_name" => "prev_icon",
                            "dependency" => array(
                                'element' => 'nav_type',
                                'value' => array('')
                            ),
                            "description" => esc_html__("Select icon for prev button.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Nav Position", "visual-carousel"),
                            "param_name" => "nav_position",
                            "value" => array(
                                esc_html__('Default', 'visual-carousel') => '',
                                esc_html__('Top', 'visual-carousel') => 'top',
                                esc_html__('Bottom', 'visual-carousel') => 'bottom',
                                esc_html__('Left Right', 'visual-carousel') => 'left-right',
                            ),
                            "dependency" => array(
                                'element' => 'nav',
                                'value' => array('1')
                            ),
                            "description" => esc_html__("Nav Position.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Nav Align", "visual-carousel"),
                            "param_name" => "nav_align",
                            "value" => array(
                                esc_html__('Default', 'visual-carousel') => '',
                                esc_html__('Left', 'visual-carousel') => 'left',
                                esc_html__('Center', 'visual-carousel') => 'center',
                                esc_html__('Right', 'visual-carousel') => 'right',
                            ),
                            "dependency" => array(
                                'element' => 'nav_position',
                                'value' => array('', 'top', 'bottom'),
                            ),
                            "description" => esc_html__("Dots Align.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Dots", "visual-carousel"),
                            "param_name" => "dots",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("Show dots navigation.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Dots Position", "visual-carousel"),
                            "param_name" => "dots_position",
                            "value" => array(
                                esc_html__('Default', 'visual-carousel') => '',
                                esc_html__('Top', 'visual-carousel') => 'top',
                                esc_html__('Bottom', 'visual-carousel') => 'bottom',
                            ),
                            "dependency" => array(
                                'element' => 'dots',
                                'value' => array('1')
                            ),
                            "description" => esc_html__("dots Position.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Dots Align", "visual-carousel"),
                            "param_name" => "dots_align",
                            "value" => array(
                                esc_html__('Default', 'visual-carousel') => '',
                                esc_html__('Left', 'visual-carousel') => 'left',
                                esc_html__('Center', 'visual-carousel') => 'center',
                                esc_html__('Right', 'visual-carousel') => 'right',
                            ),
                            "dependency" => array(
                                'element' => 'dots',
                                'value' => array('1'),
                            ),
                            "description" => esc_html__("Dots Align.", "visual-carousel"),
                            "group" => esc_html__("Control", "visual-carousel")
                        ),
                        /* speed */
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Speed", "visual-carousel"),
                            "param_name" => "smart_speed",
                            "description" => esc_html__("Speed Calculate. ( Default: 250 )", "visual-carousel"),
                            "group" => esc_html__("Speed", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Auto Play Speed", "visual-carousel"),
                            "param_name" => "autoplay_speed",
                            "dependency" => array(
                                'element' => 'autoplay',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("autoplay speed.", "visual-carousel"),
                            "group" => esc_html__("Speed", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Nav Speed", "visual-carousel"),
                            "param_name" => "nav_speed",
                            "dependency" => array(
                                'element' => 'nav',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("Navigation speed.", "visual-carousel"),
                            "group" => esc_html__("Speed", "visual-carousel")
                        ),
                        array(
                            "type" => "visual-carousel-number",
                            "heading" => esc_html__("Dots Speed", "visual-carousel"),
                            "param_name" => "dots_speed",
                            "dependency" => array(
                                'element' => 'dots',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("Pagination speed.", "visual-carousel"),
                            "group" => esc_html__("Speed", "visual-carousel")
                        ),

                        /* animations. */
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Animate In", "visual-carousel"),
                            "param_name" => "animate_in",
                            "value" => array(
                                esc_html__("Default", "visual-carousel") => '',
                                esc_html__("bounceIn", "visual-carousel") => 'bounceIn',
                                esc_html__("bounceInDown", "visual-carousel") => 'bounceInDown',
                                esc_html__("bounceInLeft", "visual-carousel") => 'bounceInLeft',
                                esc_html__("bounceInRight", "visual-carousel") => 'bounceInRight',
                                esc_html__("bounceInUp", "visual-carousel") => 'bounceInUp',
                                esc_html__("fadeIn", "visual-carousel") => 'fadeIn',
                                esc_html__("fadeInDown", "visual-carousel") => 'fadeInDown',
                                esc_html__("fadeInDownBig", "visual-carousel") => 'fadeInDownBig',
                                esc_html__("fadeInLeft", "visual-carousel") => 'fadeInLeft',
                                esc_html__("fadeInLeftBig", "visual-carousel") => 'fadeInLeftBig',
                                esc_html__("fadeInRight", "visual-carousel") => 'fadeInRight',
                                esc_html__("fadeInRightBig", "visual-carousel") => 'fadeInRightBig',
                                esc_html__("fadeInUp", "visual-carousel") => 'fadeInUp',
                                esc_html__("fadeInUpBig", "visual-carousel") => 'fadeInUpBig',
                                esc_html__("flipInX", "visual-carousel") => 'flipInX',
                                esc_html__("flipInY", "visual-carousel") => 'flipInY',
                                esc_html__("lightSpeedIn", "visual-carousel") => 'lightSpeedIn',
                                esc_html__("rotateIn", "visual-carousel") => 'rotateIn',
                                esc_html__("rotateInDownLeft", "visual-carousel") => 'rotateInDownLeft',
                                esc_html__("rotateInDownRight", "visual-carousel") => 'rotateInDownRight',
                                esc_html__("rotateInUpLeft", "visual-carousel") => 'rotateInUpLeft',
                                esc_html__("rotateInUpRight", "visual-carousel") => 'rotateInUpRight',
                                esc_html__("slideInUp", "visual-carousel") => 'slideInUp',
                                esc_html__("slideInDown", "visual-carousel") => 'slideInDown',
                                esc_html__("slideInLeft", "visual-carousel") => 'slideInLeft',
                                esc_html__("slideInRight", "visual-carousel") => 'slideInRight',
                                esc_html__("zoomIn", "visual-carousel") => 'zoomIn',
                                esc_html__("zoomInDown", "visual-carousel") => 'zoomInDown',
                                esc_html__("zoomInLeft", "visual-carousel") => 'zoomInLeft',
                                esc_html__("zoomInRight", "visual-carousel") => 'zoomInRight',
                                esc_html__("zoomInUp", "visual-carousel") => 'zoomInUp',
                                esc_html__("rollIn", "visual-carousel") => 'rollIn'
                            ),
                            "dependency" => array(
                                'element' => 'items',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("CSS3 animation in.", "visual-carousel"),
                            "group" => esc_html__("Animations", "visual-carousel")
                        ),
                        array(
                            "type" => "dropdown",
                            "heading" => esc_html__("Animate Out", "visual-carousel"),
                            "param_name" => "animate_out",
                            "value" => array(
                                esc_html__("Default", "visual-carousel") => '',
                                esc_html__("bounceOut", "visual-carousel") => 'bounceOut',
                                esc_html__("bounceOutDown", "visual-carousel") => 'bounceOutDown',
                                esc_html__("bounceOutLeft", "visual-carousel") => 'bounceOutLeft',
                                esc_html__("bounceOutRight", "visual-carousel") => 'bounceOutRight',
                                esc_html__("bounceOutUp", "visual-carousel") => 'bounceOutUp',
                                esc_html__("fadeOut", "visual-carousel") => 'fadeOut',
                                esc_html__("fadeOutDown", "visual-carousel") => 'fadeOutDown',
                                esc_html__("fadeOutDownBig", "visual-carousel") => 'fadeOutDownBig',
                                esc_html__("fadeOutLeft", "visual-carousel") => 'fadeOutLeft',
                                esc_html__("fadeOutLeftBig", "visual-carousel") => 'fadeOutLeftBig',
                                esc_html__("fadeOutRight", "visual-carousel") => 'fadeOutRight',
                                esc_html__("fadeOutRightBig", "visual-carousel") => 'fadeOutRightBig',
                                esc_html__("fadeOutUp", "visual-carousel") => 'fadeOutUp',
                                esc_html__("fadeOutUpBig", "visual-carousel") => 'fadeOutUpBig',
                                esc_html__("flipOutX", "visual-carousel") => 'flipOutX',
                                esc_html__("flipOutY", "visual-carousel") => 'flipOutY',
                                esc_html__("lightSpeedOut", "visual-carousel") => 'lightSpeedOut',
                                esc_html__("rotateOut", "visual-carousel") => 'rotateOut',
                                esc_html__("rotateOutDownLeft", "visual-carousel") => 'rotateOutDownLeft',
                                esc_html__("rotateOutDownRight", "visual-carousel") => 'rotateOutDownRight',
                                esc_html__("rotateOutUpLeft", "visual-carousel") => 'rotateOutUpLeft',
                                esc_html__("rotateOutUpRight", "visual-carousel") => 'rotateOutUpRight',
                                esc_html__("slideOutUp", "visual-carousel") => 'slideOutUp',
                                esc_html__("slideOutDown", "visual-carousel") => 'slideOutDown',
                                esc_html__("slideOutLeft", "visual-carousel") => 'slideOutLeft',
                                esc_html__("slideOutRight", "visual-carousel") => 'slideOutRight',
                                esc_html__("zoomOut", "visual-carousel") => 'zoomOut',
                                esc_html__("zoomOutDown", "visual-carousel") => 'zoomOutDown',
                                esc_html__("zoomOutLeft", "visual-carousel") => 'zoomOutLeft',
                                esc_html__("zoomOutRight", "visual-carousel") => 'zoomOutRight',
                                esc_html__("zoomOutUp", "visual-carousel") => 'zoomOutUp',
                                esc_html__("rollOut", "visual-carousel") => 'rollOut'
                            ),
                            "dependency" => array(
                                'element' => 'items',
                                'value' => array(
                                    '1'
                                )
                            ),
                            "description" => esc_html__("CSS3 animation out.", "visual-carousel"),
                            "group" => esc_html__("Animations", "visual-carousel")
                        ),

                        /* responsive */
                        array(
                            "type" => "param_group",
                            "heading" => esc_html__("Responsive Devices", "visual-carousel"),
                            "param_name" => "responsive",
                            'value' => rawurlencode(json_encode(array(
                                array(
                                    'width' => 0,
                                    'items' => 1,
                                    'margin' => 0,
                                ),
                                array(
                                    'width' => 480,
                                    'items' => 2,
                                    'margin' => 3,
                                ),
                                array(
                                    'width' => 768,
                                    'items' => 3,
                                    'margin' => 5,
                                ),
                                array(
                                    'width' => 992,
                                    'items' => 3,
                                    'margin' => 8,
                                ),
                                array(
                                    'width' => 1200,
                                    'items' => 4,
                                    'margin' => 10,
                                ),
                            ))),
                            'params' => array(
                                array(
                                    "type" => "visual-carousel-number",
                                    "heading" => esc_html__("Screen Width", "visual-carousel"),
                                    "param_name" => "width",
                                    "description" => esc_html__("Devices screen with px.", "visual-carousel"),
                                    'admin_label' => true
                                ),
                                array(
                                    "type" => "visual-carousel-number",
                                    "heading" => esc_html__("Show Items", "visual-carousel"),
                                    "param_name" => "items",
                                    "description" => esc_html__("Number items show on screen.", "visual-carousel"),
                                    'admin_label' => true
                                ),
                                array(
                                    "type" => "visual-carousel-number",
                                    "heading" => esc_html__("Margin", "visual-carousel"),
                                    "param_name" => "margin",
                                    "description" => esc_html__("margin-right(px) on item.", "visual-carousel"),
                                    'admin_label' => true
                                ),
                                array(
                                    "type" => "visual-carousel-number",
                                    "heading" => esc_html__("Stage Padding", "visual-carousel"),
                                    "param_name" => "stagePadding",
                                    "description" => esc_html__("Padding left and right on stage.", "visual-carousel"),
                                    'admin_label' => true
                                )
                            ),
                            "description" => esc_html__("Responsive Devices Desktops, Tablets and Phones.", "visual-carousel"),
                            "group" => esc_html__("Responsive", "visual-carousel")
                        ),

                        /* advance */
                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Mousewheel", "visual-carousel"),
                            "param_name" => "mousewheel",
                            "value" => array(
                                'Yes' => 1
                            ),
                            "description" => esc_html__("A jQuery plugin created by Brandon Aaron that adds cross-browser mouse wheel support with delta normalization.", "visual-carousel"),
                            "group" => esc_html__("Advance", "visual-carousel")
                        )
                    ),
                    "js_view" => 'VcColumnView'
                ));

            vc_map(array(
                "name" => esc_html__("Custom Item", "visual-carousel"),
                "base" => "visual_carousel_item",
                "class" => "visual_carousel_item",
                "as_parent" => array(
                    'except' => 'visual_carousel_item'
                ),
                "content_element" => true,
                "controls" => "full",
                "show_settings_on_create" => true,
                "category" => "Visual Carousel",
                "description" => esc_html__("Custom Merge Index/ Width... for item.", "visual-carousel"),
                "params" => array(
                    array(
                        "type" => "visual-carousel-number",
                        "heading" => esc_html__("Merge index", "visual-carousel"),
                        "param_name" => "itemmerge",
                        "description" => esc_html__("Merge item index. ( 1, 2, 3, 4, 5, 6, 7, ...)  ", "visual-carousel")
                    ),
                    array(
                        "type" => "visual-carousel-number",
                        "heading" => esc_html__("Width", "visual-carousel"),
                        "param_name" => "itemwidth",
                        "description" => esc_html__("Set width for item (px).", "visual-carousel")
                    )
                ),
                "js_view" => 'VcColumnView'
            ));
        }

        /**
         * number field.
         *
         * @param
         *            $settings
         * @param
         *            $value
         * @return string
         */
        function number_param_settings_field($settings, $value)
        {
            ob_start();

            echo '<div class="visual_carousel_number_param_block">';
            echo '<input min="0" name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value wpb-textinput ' . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field" type="number" value="' . esc_attr($value) . '" style="width:100px;" />';
            echo '</div>';

            return ob_get_clean();
        }

        function add_shortcode_visual_carousel($atts, $content = '')
        {
            global $shortcode_template;

            $slider_source = $style = $items = $margin = $loop = $center = $stagepadding = $merge = $autowidth = $startposition = $smart_speed = $nav = $nav_type = $next_text = $prev_text = $next_icon = $prev_icon = $nav_position = $nav_align = $dots = $dots_position = $dots_align = $autoplay = $autoplay_timeout = $autoplay_hover_pause = $autoplay_speed = $nav_speed = $dots_speed = $animate_in = $animate_out = $fallback_easing = $lazyload = $mousewheel = $responsive = '';

            extract(shortcode_atts(array(
                'slider_source' => '',
                'style' => 'default',
                'items' => '',
                'margin' => '',
                'loop' => '',
                'center' => '',
                'stagepadding' => '',
                'merge' => '',
                'autowidth' => '',
                'startposition' => '',
                'nav' => '',
                'nav_type' => '',
                'next_text' => '',
                'next_icon' => '',
                'prev_text' => '',
                'prev_icon' => '',
                'nav_position' => 'bottom',
                'dots_position' => 'bottom',
                'nav_align' => 'left',
                'dots_align' => 'right',
                'dots' => '',
                'autoplay' => '',
                'autoplay' => '',
                'autoplay_timeout' => '',
                'autoplay_hover_pause' => '',
                'smart_speed' => '',
                'autoplay_speed' => '',
                'nav_speed' => '',
                'dots_speed' => '',
                'responsive' => '',
                'animate_out' => '',
                'animate_in' => '',
                'mousewheel' => ''
            ), $atts));

            $data = array(
                'data-items' => esc_attr($items),
                'data-margin' => esc_attr($margin),
                'data-loop' => esc_attr($loop),
                'data-center' => esc_attr($center),
                'data-stagepadding' => esc_attr($stagepadding),
                'data-merge' => esc_attr($merge),
                'data-autowidth' => esc_attr($autowidth),
                'data-startposition' => esc_attr($startposition),
                'data-smart_speed' => esc_attr($smart_speed),
                'data-nav' => esc_attr($nav),
                'data-dots' => esc_attr($dots),
                'data-autoplay_speed' => esc_attr($autoplay_speed),
                'data-autoplay' => esc_attr($autoplay),
                'data-autoplay_timeout' => esc_attr($autoplay_timeout),
                'data-autoplay_hover_pause' => esc_attr($autoplay_hover_pause),
                'data-nav_speed' => esc_attr($nav_speed),
                'data-dots_speed' => esc_attr($dots_speed),
                'data-animate_in' => esc_attr($animate_in),
                'data-animate_out' => esc_attr($animate_out),
                'data-mousewheel' => esc_attr($mousewheel),
                'data-responsive' => esc_attr($responsive),
                'data-mousewheel' => esc_attr($mousewheel),
            );

            $shortcode_template = $style;

            /* nav custom. */
            if ($nav_type) {
                $data['data-next'] = esc_attr($next_text);
                $data['data-prev'] = esc_attr($prev_text);
            } else {
                $data['data-next'] = "<i class='" . esc_attr($next_icon) . "'></i>";
                $data['data-prev'] = "<i class='" . esc_attr($prev_icon) . "'></i>";
            }

            /* remove attributes null. */
            $data = array_filter($data);

            /* class */
            $_class = array('visual-carousel', 'template-' . $style);

            if ($nav) {
                $_class[] = 'nav-' . $nav_position;
                $_class[] = 'nav-' . $nav_align;
            }

            if ($dots) {
                $_class[] = 'dots-' . $dots_position;
                $_class[] = 'dots-' . $dots_align;

                /* load icon fonts. */
                vc_icon_element_fonts_enqueue('fontawesome');
            }

            $_class = apply_filters('visualcarousel/shortcodes/carousel/class', $_class);

            /* if source from query. */
            if ($slider_source) {
                $carousel = new WP_Query($this->parse_data($slider_source));
                set_query_var('carousel', $carousel);
            }

            /* load style. */
            visual_carousel_get_template_style();

            set_query_var('_class', $_class);
            set_query_var('data', $data);
            set_query_var('content', $content);

            ob_start();

            /* load template. */
            visual_carousel_get_template_part('archive', 'slider');

            wp_reset_postdata();

            return ob_get_clean();
        }

        function add_shortcode_visual_carousel_item($atts, $content = '')
        {
            $itemmerge = $itemwidth = '';

            extract(shortcode_atts(array(
                'itemmerge' => '',
                'itemwidth' => ''
            ), $atts));

            $style = $itemwidth ? ' style="width:' . esc_attr($itemwidth) . 'px"' : '';
            $data = $itemmerge ? ' data-merge="' . $itemmerge . '"' : '';

            ob_start();

            ?>
            <div class="visual-carousel-merge" <?php echo $data . $style; ?>>
                <?php echo apply_filters('the_content', $content); ?>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * @param $value
         *
         * @since 1.0.0
         * @return array
         */
        function parse_data($value)
        {
            $data = array();
            $values_pairs = preg_split('/\|/', $value);
            foreach ($values_pairs as $pair) {
                if (!empty($pair)) {
                    list($key, $value) = preg_split('/\:/', $pair);
                    $data[$key] = $value;
                }
            }
            return $data;
        }
    }

    new Visual_Carousel_vc_addon();

    if (class_exists('WPBakeryShortCodesContainer')) {

        class WPBakeryShortCode_visual_carousel extends WPBakeryShortCodesContainer
        {
        }

        class WPBakeryShortCode_visual_carousel_item extends WPBakeryShortCodesContainer
        {
        }
    }
endif;