
<?php
if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

function crunchify_add_gist_github_shortcode( $atts, $content = NULL ) {
   extract( shortcode_atts( array(
			'id' => '',
			'file' => '',
		), $atts ) );
   if (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) {
		return sprintf('<amp-gist data-gistid="%s" %s layout="fixed-height" height="250"></amp-gist>', 
			$id ? $id : trim(esc_attr($content)), 
			$file ? 'data-file="' . esc_attr( $file ) . '"' : ''
		);
	} else {
   		return sprintf('<script src="https://gist.github.com/%s.js%s"></script>', 
   			$id ? $id : trim(esc_attr($content)) , 
   			$file ? '?file=' . esc_attr( $file ) : '' 
   		);
   }
}
add_shortcode('gist', 'crunchify_add_gist_github_shortcode');
 
// Disable Gutenberg in Widgets 
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
	add_filter( 'use_widgets_block_editor', '__return_false' );

// Disable Jetpack Blocks in Gutenberg 
add_filter( 'jetpack_gutenberg', '__return_false' );

add_action('pre_get_posts', 'add_my_custom_post_type');

/**
 * @param WP_Query $query
 * @return WP_Query
 */
function add_my_custom_post_type($query) {
    if( ! is_page()
       and
        empty($query->query['post_type'])
        or $query->query['post_type'] === 'post'
        and !is_admin()
    ){
       $query->set('post_type', array('post', 'tutorial', 'review', 'collection'));
    }
}
add_filter( 'action_scheduler_pastdue_actions_check_pre', '__return_false' );
