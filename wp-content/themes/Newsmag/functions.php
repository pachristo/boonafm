<?php

define('TAGDIV_ROOT', get_template_directory_uri());
define('TAGDIV_ROOT_DIR', get_template_directory());


// load the deploy mode
require_once( TAGDIV_ROOT_DIR . '/tagdiv-deploy-mode.php' );


/**
 * Theme configuration.
 */
require_once TAGDIV_ROOT_DIR . '/includes/tagdiv-config.php';


/**
 * Theme wp booster.
 */
require_once( TAGDIV_ROOT_DIR . '/includes/wp-booster/tagdiv-wp-booster-functions.php');


/**
 * Theme page generator support.
 */
if ( ! class_exists('tagdiv_page_generator' ) ) {
	include_once ( TAGDIV_ROOT_DIR . '/includes/tagdiv-page-generator.php');
}


/**
 * Theme sidebar.
 */
add_action( 'widgets_init', function (){
	register_sidebar(
		array(
			'name'=> 'Newsmag default',
			'id' => 'td-default',
			'before_widget' => '<aside class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<div class="block-title"><span>',
			'after_title' => '</span></div>'
		)
	);
});

/**
 * Theme setup.
 */
add_action( 'after_setup_theme', function (){

	/**
	 * Loads the theme's translated strings.
	 */
	load_theme_textdomain( strtolower(TD_THEME_NAME ), get_template_directory() . '/translation' );

	/**
	 * Theme menu location.
	 */
	register_nav_menus(
		array(
			'header-menu' => 'Header Menu (main)',
			'footer-menu' => 'Footer Menu',
		)
	);
});


/* ----------------------------------------------------------------------------
 * Add theme support for features
 */
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
add_theme_support('woocommerce');
add_theme_support('bbpress');

global $content_width;
if ( !isset($content_width) ) {
    $content_width = 640;
}


/* ----------------------------------------------------------------------------
 * WooCommerce
 */

// breadcrumb
add_filter('woocommerce_breadcrumb_defaults', 'tagdiv_woocommerce_breadcrumbs');
function tagdiv_woocommerce_breadcrumbs() {
    return array(
        'delimiter' => ' <i class="td-icon-right td-bread-sep"></i> ',
        'wrap_before' => '<div class="entry-crumbs" itemprop="breadcrumb">',
        'wrap_after' => '</div>',
        'before' => '',
        'after' => '',
        'home' => _x('Home', 'breadcrumb', 'newsmag'),
    );
}


// Override theme default specification for product 3 per row
add_filter( 'loop_shop_columns', 'tagdiv_wc_loop_shop_columns', 1, 10 );
function tagdiv_wc_loop_shop_columns( $number_columns ) {
	return 3;
}


// Number of product per page 6
add_filter( 'loop_shop_per_page', 'tagdiv_wc_loop_shop_per_page' );
function tagdiv_wc_loop_shop_per_page( $cols ) {
	return 6;
}


// use own pagination
if (!function_exists('woocommerce_pagination')) {
    // pagination
    function woocommerce_pagination() {
        echo tagdiv_page_generator::get_pagination();
    }
}

if ( ! function_exists('woocommerce_output_related_products' ) ) {
    // Number of related products
    function woocommerce_output_related_products() {
        woocommerce_related_products(array(
            'posts_per_page' => 3,
            'columns' => 3,
            'orderby' => 'rand',
        )); // Display 3 products in rows of 1
    }
}

update_option( 'siteurl', 'https://www.boonafm.ug' );
update_option( 'home', 'https://www.boonafm.ug' );