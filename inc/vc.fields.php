<?php
/**
 * Created by EDGE STUDIO.
 * User: EDGE
 * Date: 12/12/2015
 * Time: 4:35 PM
 */
if (!defined('ABSPATH')) {
    exit();
}

if (!class_exists('EDGE_VC_Fields')) :

    class EDGE_VC_Fields
    {
        function __construct()
        {
            add_action('init', array(
                $this,
                'vc_add_shortcode_params'
            ));
        }

        function vc_add_shortcode_params(){
            vc_add_shortcode_param('edge-images-select', array(
                $this,
                'vc_images_select'
            ), visual_carousel()->acess_url . 'vc-fields/edge-images-select.js');

            vc_add_shortcode_param('visual-carousel-number', array(
                $this,
                'number_param_settings_field'
            ));
        }

        function vc_images_select($settings, $value)
        {
            ob_start();

            echo '<div class="vc_images_select_param_block">';
            echo '<ul>';
                foreach($settings["options"] as $key => $url){

                    $class = $key == $value ? 'active' : '';

                    echo '<li data-value="'.esc_attr($key).'"><img class="'.$class.'" src="'.esc_url($url).'" alt="'.esc_attr($key).'"></li>';
                }
            echo '</ul>';
            echo '<input name="' . esc_attr($settings['param_name']) . '" class="wpb_vc_param_value ' . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '_field" type="hidden" value="' . esc_attr($value) . '"/>';
            echo '</div>';

            return ob_get_clean();
        }

        /**
         * number field.
         *
         * @param $settings
         * @param $value
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
    }

    new EDGE_VC_Fields;

endif;