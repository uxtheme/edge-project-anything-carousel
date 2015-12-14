<?php
/**
 * all templates functions.
 *
 * @package VC
 * @version 1.0.0
 */
if (!defined('ABSPATH')) {
    exit();
}

/**
 * get template style.
 *
 * @package VC
 * @version 1.0.0
 */
if(!function_exists('visual_carousel_get_template_style')):

    function visual_carousel_get_template_style(){

        global $shortcode_template;

        if (file_exists(visual_carousel()->theme_dir . "{$shortcode_template}/visual-carousel-{$shortcode_template}.css")) {
            wp_enqueue_style('visual-carousel-' . $shortcode_template, visual_carousel()->theme_url . "{$shortcode_template}/visual-carousel-{$shortcode_template}.css");
        }
        elseif (file_exists(visual_carousel()->template_dir . "{$shortcode_template}/visual-carousel-{$shortcode_template}.css")) {
            wp_enqueue_style('visual-carousel-' . $shortcode_template, visual_carousel()->template_url . "{$shortcode_template}/visual-carousel-{$shortcode_template}.css");
        }
    }

endif;

/**
 * get template file.
 *
 *
 * @package VC
 * @version 1.0.0
 */
if (!function_exists('visual_carousel_get_template_part')) :

    function visual_carousel_get_template_part($slug, $name = '', $require = false)
    {
        global $shortcode_template;

        $template = '';

        // Look in yourtheme/pluginname/slug-name.php
        if ($slug && $name && file_exists(visual_carousel()->theme_dir . "{$shortcode_template}/{$slug}-{$name}.php")) {
            $template = visual_carousel()->theme_dir . "{$shortcode_template}/{$slug}-{$name}.php";
        } // If template slug-name.php doesn't exist, look in yourtheme/pluginname/slug.php
        elseif (!$template && file_exists(visual_carousel()->theme_dir . "{$shortcode_template}/{$slug}.php")) {
            $template = visual_carousel()->theme_dir . "{$shortcode_template}/{$slug}.php";
        } // If template file doesn't exist, look in pluginname/templates/slug-name.php
        elseif (!$template && $name && file_exists(visual_carousel()->template_dir . "{$shortcode_template}/{$slug}-{$name}.php")) {
            $template = visual_carousel()->template_dir . "{$shortcode_template}/{$slug}-{$name}.php";
        } // If plugin slug-name.php file doesn't exist, look in slug.php
        elseif (!$template && file_exists(visual_carousel()->template_dir . "{$shortcode_template}/{$slug}.php")) {
            $template = visual_carousel()->template_dir . "{$shortcode_template}/{$slug}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters('visual_carousel_get_template_part', $template, $slug, $name);

        if (!$template)
            return false;

        if ($require)
            return $template;

        // load template file.
        load_template($template, true);
    }

endif;

/**
 * Get screenshot image from layout name.
 *
 * @return array('template', 'thumb')
 * @package VC
 * @version 1.0.0
 */
if (!function_exists('visual_carousel_get_template_thumb')) :

    function visual_carousel_get_template_thumb()
    {
        $_templates = array();

        $templates = visual_carousel_get_template_list();

        foreach ($templates as $template) {

            if (file_exists(visual_carousel()->theme_dir . $template . '/screenshot.png')) {

                $_templates[$template] = visual_carousel()->theme_url . $template . '/screenshot.png';
            } elseif (file_exists(visual_carousel()->template_dir . $template . '/screenshot.png')) {

                $_templates[$template] = visual_carousel()->template_url . $template . '/screenshot.png';
            }
        }

        return $_templates;

    }

endif;

/**
 * Get list layouts.
 *
 * check templates from plugin and theme.
 *
 * @return array layouts.
 * @package VC
 * @version 1.0.0
 */
if (!function_exists('visual_carousel_get_template_list')) :

    function visual_carousel_get_template_list()
    {
        $in_plugin = false;
        $in_theme = false;

        /* scan in plugin. */
        if (is_dir(visual_carousel()->theme_dir))
            $in_theme = scandir(visual_carousel()->theme_dir);

        /* scan in theme. */
        if (is_dir(visual_carousel()->template_dir))
            $in_plugin = scandir(visual_carousel()->template_dir);

        /* if layouts exists in theme and plugin. */
        if ($in_plugin && $in_theme) {

            unset($in_plugin[0]);
            unset($in_plugin[1]);
            unset($in_theme[0]);
            unset($in_theme[1]);

            return array_unique(array_merge($in_plugin, $in_theme));
        }

        /* if layouts only exists in plugin. */
        if ($in_plugin) {

            unset($in_plugin[0]);
            unset($in_plugin[1]);

            return $in_plugin;
        }
    }

endif;