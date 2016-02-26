<?php
/**
 * Plugin Name: Visual Carousel
 * Plugin URI: #
 * Description: A Wordpress Plugin Add-Ons For Visual Composer Plugin.
 * Version: 1.0.0
 * Author: FOX
 * Author URI: #
 * License: GPLv2 or later
 * Text Domain: visual-carousel
 */
if (! defined('ABSPATH')) {
    exit();
}

if (! class_exists('Visual_Carousel')) :

    /**
     * Main Class
     *
     * @class Visual_Carousel
     *
     * @version 1.0.0
     */
    final class Visual_Carousel
    {

        /* single instance of the class */
        public $file = null;

        /* base plugin_dir. */
        public $plugin_dir = null;

        public $plugin_url = null;

        /* base acess folder. */
        public $acess_dir = null;

        public $acess_url = null;
        
        public $template_dir = null;
        public $template_url = null;
        
        public $theme_dir = null;
        public $theme_url = null;

        /**
         * Main Visual_Carousel Instance
         *
         * Ensures only one instance of Essential Instagram is loaded or can be loaded.
         *
         * @since 1.0.0
         * @static
         *
         * @see Visual_Carousel()
         * @return Visual_Carousel - Main instance
         */
        public static function instance()
        {
            static $_instance = null;
            
            if (is_null($_instance)) {
                
                $_instance = new Visual_Carousel();
                
                // globals.
                $_instance->setup_globals();
                
                // includes.
                $_instance->includes();
                
                // actions.
                $_instance->setup_actions();
            }
            
            return $_instance;
        }

        /**
         * globals value.
         *
         * @package Visual_Carousel
         * @global path + uri.
         */
        private function setup_globals()
        {
            $this->file = __FILE__;

            /* base plugin. */
            $this->plugin_dir = plugin_dir_path($this->file);
            $this->plugin_url = plugin_dir_url($this->file);
            
            /* base assets. */
            $this->acess_dir = trailingslashit($this->plugin_dir . 'assets');
            $this->acess_url = trailingslashit($this->plugin_url . 'assets');
            
            /* base template. */
            $this->template_dir = trailingslashit($this->plugin_dir . 'templates');
            $this->template_url = trailingslashit($this->plugin_url . 'templates');
            
            /* custom template. */
            $this->theme_dir = trailingslashit(get_template_directory () . '/visual-carousel');
            $this->theme_url = trailingslashit(get_template_directory_uri() . '/visual-carousel');
        }

        /**
         * Setup all actions + filter.
         *
         * @package Visual_Carousel
         * @version 1.0.0
         */
        private function setup_actions()
        {
            // front-end scripts.
            add_action('wp_enqueue_scripts', array(
                $this,
                'add_scrips'
            ));
            
            // admin scripts.
            add_action('admin_enqueue_scripts', array(
                $this,
                'add_admin_script'
            ));
        }

        /**
         * include files.
         *
         * @package Visual_Carousel
         * @version 1.0.0
         */
        private function includes()
        {
            require_once $this->plugin_dir . 'inc/templates.php';
            require_once $this->plugin_dir . 'inc/vc.fields.php';
            require_once $this->plugin_dir . 'inc/vc.addon.php';
        }

        /**
         * add front-end scripts.
         *
         * @package Visual_Carousel
         * @version 1.0.0
         */
        function add_scrips()
        {
            global $post;

            if(!$post) return false;

            if( stripos( $post->post_content, '[visual_carousel') ) {
                wp_enqueue_style('animate');
                wp_enqueue_style('owl.carousel');
                wp_enqueue_style('visual-carousel');

                wp_enqueue_script('jquery.mousewheel');
                wp_enqueue_script('owl.carousel');
                wp_enqueue_script('visual-carousel');
            }
        }

        /**
         * add back-end scripts.
         *
         * @package Visual_Carousel
         * @version 1.0.0
         */
        function add_admin_script()
        {
            wp_enqueue_style('post-visual-carousel', $this->acess_url . 'css/post-visual-carousel.css');
        }

        public static function process_attributes($attributes){

            $attr_s = '';

            foreach ($attributes as $key => $attr){
                $attr_s .= ' ' . $key . '="' . $attr . '"';
            }

            return $attr_s;
        }
    }

endif;

/**
 * Returns the main instance of visual_carousel() to prevent the need to use globals.
 *
 * @since 1.0
 * @return Visual_Carousel
 */
if (! function_exists('Visual_Carousel')) {

    function visual_carousel()
    {
        return Visual_Carousel::instance();
    }
}

if (defined('VC_CAROUSEL_LATE_LOAD')) {
    
    add_action('plugins_loaded', 'visual_carousel', (int) VC_CAROUSEL_LATE_LOAD);
} else {
    
    visual_carousel();
}