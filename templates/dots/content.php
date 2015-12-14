<?php
/**
 * The Template for displaying post items.
 *
 * Override this template by copying it to yourtheme/visual-carousel/content.php
 *
 * @package 	Visual Carousel
 * @version     1.0.0
 */

if (! defined('ABSPATH')) {
    exit();
}

/* before item. */
do_action('visualcarousel/shortcodes/carousel/item/before');

?>
<div class="item">
	<h4><?php the_title(); ?></h4>
</div>

<?php
/* after item. */
do_action('visualcarousel/shortcodes/carousel/item/after');