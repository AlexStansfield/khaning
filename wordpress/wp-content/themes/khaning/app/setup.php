<?php

namespace App;

use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Container;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action( 'wp_enqueue_scripts', function ()
{
  wp_enqueue_style( 'sage/main.css', asset_path( 'styles/main.css' ), false, null );
  wp_enqueue_script( 'sage/main.js', asset_path( 'scripts/main.js' ), ['jquery'], null, true );

  if ( is_single() && comments_open() && get_option( 'thread_comments' ) )
  {
    wp_enqueue_script( 'comment-reply' );
  }
}, 100 );

/**
 * Theme setup
 */
add_action( 'after_setup_theme', function ()
{
  /**
   * Enable features from Soil when plugin is activated
   * @link https://roots.io/plugins/soil/
   */
  add_theme_support( 'soil-clean-up' );
  add_theme_support( 'soil-jquery-cdn' );
  add_theme_support( 'soil-nav-walker' );
  add_theme_support( 'soil-nice-search' );
  add_theme_support( 'soil-relative-urls' );

  /**
   * Enable plugins to manage the document title
   * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
   */
  add_theme_support( 'title-tag' );

  /**
   * Register navigation menus
   * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
   */
  register_nav_menus( [
    'primary_navigation' => __( 'Primary Navigation', 'sage' ),
    'primary-menu'       => __( 'Primary Menu', 'sage' ),
  ] );

  /**
   * Enable post thumbnails
   * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
   */
  add_theme_support( 'post-thumbnails' );

  /**
   * Enable HTML5 markup support
   * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
   */
  add_theme_support( 'html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form'] );

  /**
   * Enable selective refresh for widgets in customizer
   * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
   */
  add_theme_support( 'customize-selective-refresh-widgets' );

  /**
   * Use main stylesheet for visual editor
   * @see resources/assets/styles/layouts/_tinymce.scss
   */
  add_editor_style( asset_path( 'styles/main.css' ) );
}, 20 );

/**
 * Register sidebars
 */
add_action( 'widgets_init', function ()
{
  $config = [
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ];
  register_sidebar( [
    'name' => __( 'Primary', 'sage' ),
    'id'   => 'sidebar-primary',
  ] + $config );
  register_sidebar( [
    'name' => __( 'Footer Column 1', 'sage' ),
    'id'   => 'sidebar-footer-1',
  ] + $config );
  register_sidebar( [
    'name' => __( 'Footer Column 2', 'sage' ),
    'id'   => 'sidebar-footer-2',
  ] + $config );
  register_sidebar( [
    'name' => __( 'Footer Column 3', 'sage' ),
    'id'   => 'sidebar-footer-3',
  ] + $config );
  register_sidebar( [
    'name' => __( 'Footer Column 4', 'sage' ),
    'id'   => 'sidebar-footer-4',
  ] + $config );
} );

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action( 'the_post', function ( $post )
{
  sage( 'blade' )->share( 'post', $post );
} );

/**
 * Setup Sage options
 */
add_action( 'after_setup_theme', function ()
{
  /**
   * Add JsonManifest to Sage container
   */
  sage()->singleton( 'sage.assets', function ()
  {
    return new JsonManifest( config( 'assets.manifest' ), config( 'assets.uri' ) );
  } );

  /**
   * Add Blade to Sage container
   */
  sage()->singleton( 'sage.blade', function ( Container $app )
  {
    $cachePath = config( 'view.compiled' );
    if ( !file_exists( $cachePath ) )
    {
      wp_mkdir_p( $cachePath );
    }
    ( new BladeProvider( $app ) )->register();

    return new Blade( $app['view'] );
  } );

  /**
   * Create @asset() Blade directive
   */
  sage( 'blade' )->compiler()->directive( 'asset', function ( $asset )
  {
    return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
  } );
} );

// Register Custom Post Type
function organic_post_type()
{
  $labels = array(
    'name'                  => _x( 'Organics', 'Post Type General Name', 'khaning' ),
    'singular_name'         => _x( 'Organic', 'Post Type Singular Name', 'khaning' ),
    'menu_name'             => __( 'Organics', 'khaning' ),
    'name_admin_bar'        => __( 'Organic', 'khaning' ),
    'archives'              => __( 'Item Archives', 'khaning' ),
    'attributes'            => __( 'Item Attributes', 'khaning' ),
    'parent_item_colon'     => __( 'Parent Item:', 'khaning' ),
    'all_items'             => __( 'All Items', 'khaning' ),
    'add_new_item'          => __( 'Add New Item', 'khaning' ),
    'add_new'               => __( 'Add New', 'khaning' ),
    'new_item'              => __( 'New Item', 'khaning' ),
    'edit_item'             => __( 'Edit Item', 'khaning' ),
    'update_item'           => __( 'Update Item', 'khaning' ),
    'view_item'             => __( 'View Item', 'khaning' ),
    'view_items'            => __( 'View Items', 'khaning' ),
    'search_items'          => __( 'Search Item', 'khaning' ),
    'not_found'             => __( 'Not found', 'khaning' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'khaning' ),
    'featured_image'        => __( 'Featured Image', 'khaning' ),
    'set_featured_image'    => __( 'Set featured image', 'khaning' ),
    'remove_featured_image' => __( 'Remove featured image', 'khaning' ),
    'use_featured_image'    => __( 'Use as featured image', 'khaning' ),
    'insert_into_item'      => __( 'Insert into item', 'khaning' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'khaning' ),
    'items_list'            => __( 'Items list', 'khaning' ),
    'items_list_navigation' => __( 'Items list navigation', 'khaning' ),
    'filter_items_list'     => __( 'Filter items list', 'khaning' ),
  );
  $args = array(
    'label'               => __( 'Organic', 'khaning' ),
    'description'         => __( 'Organic Product Description', 'khaning' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail' ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_rest'        => true,
    'menu_position'       => 5,
    'menu_icon'           => 'dashicons-buddicons-activity',
    'show_in_admin_bar'   => true,
    'show_in_nav_menus'   => true,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
  );
  register_post_type( 'organic', $args );

}
add_action( 'init', __NAMESPACE__ . '\\organic_post_type', 0 );


function my_acf_op_init()
{

  // Check function exists.
  if ( function_exists( 'acf_add_options_page' ) )
  {
    // Register options page.
    $option_page = acf_add_options_page( array(
      'page_title' => __( 'Theme Settings' ),
      'menu_title' => __( 'Theme Settings' ),
      'menu_slug'  => 'theme-settings',
      'capability' => 'edit_posts',
      'redirect'   => false,
    ) );
  }
}
add_action( 'acf/init', __NAMESPACE__ . '\\my_acf_op_init' );


function my_rest_api_init() {
  $sidebar = new Sidebar();
  $sidebar->register_routes();
  $widget = new Widget();
  $widget->register_routes();
}
add_action( 'rest_api_init', __NAMESPACE__ .'\\my_rest_api_init' );


function add_category_name_data()
{
  //Last date
  register_rest_field( 'post',
    'category_name',
    array(
      'get_callback'    => __NAMESPACE__ . '\\rest_get_category_name',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}
function rest_get_category_name( $object )
{
  //get the Post Id
  $formatted_categories = array();

  $categories = get_the_category( $object['categories'] );

  foreach ( $categories as $category )
  {
    $formatted_categories = $category->name;
  }

  return $formatted_categories;
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\add_category_name_data' );
