<?php
/**
 * The Template for displaying slide items.
 *
 * Override this template by copying it to yourtheme/visual-carousel/archive-slider.php
 *
 * @param array $_class
 * @param array $data
 * @param string $content
 * @param object $carousel from post query.
 * @package 	Visual Carousel
 * @version     1.0.0
 */

if (! defined('ABSPATH')) {
    exit();
}

/* before shortcode. */
do_action('visualcarousel/shortcodes/carousel/before');

?>
<div class="<?php echo implode(' ', $_class); ?>">
	<div class="visual-carousel-items"<?php echo visual_carousel()->process_attributes($data); ?>>

        <?php
        /* get content template. */
        $template_content = visual_carousel_get_template_part('content', '', true);
        
        /* from query. */
        if(isset($carousel) && $carousel->have_posts()){
            while ($carousel->have_posts()): $carousel->the_post();
                load_template($template_content, true);
            endwhile;
        }
        /* vc elements. */
        echo apply_filters('the_content', $content);
        ?>
	</div>
</div>

<?php
/* after shortcode. */
do_action('visualcarousel/shortcodes/carousel/after');
