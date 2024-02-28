<?php

namespace RubicoTechBlog;

use RubicoTechBlog\PluginProcess\Internationalization;
use RubicoTechBlog\PluginProcess\Loader;
use RubicoTechBlog\Router\RouteProcessor;
use RubicoTechBlog\WpController\FrontController;
use RubicoTechBlog\WpController\AdminController;
use RubicoTechBlog\WpController\PostTypesController;
use RubicoTechBlog\Router\Route;
use RubicoTechBlog\Router\Router;
use RubicoTechBlog\Http\Controllers\AuthController;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @author     Rubico Tech
 */
class Main {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The router that's responsible for maintaining and registering all http routes that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Router    $router
   */
  protected $router;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;


  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  protected $routes;

  protected static $_instance = null;

  public $plugin_base = '/';
  public $api_base = 'rt-blog-plugin/api/v1';

  /**
   * Main RubicoTechBlog Instance.
   *
   * Ensures only one instance of RubicoTechBlog is loaded or can be loaded.
   *
   * @since 2.1
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    $this->version = '1.0.0';
    $this->plugin_name = 'rt-blog-plugin';
    $this->loader = new Loader();
    $this->router = new Router($this->plugin_base);

    $this->set_locale();
    $this->register_post_types();
    $this->set_default_configs();
    $this->define_routes();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }


  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Internationalization class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new Internationalization();
    $plugin_i18n->set_domain( $this->get_plugin_name() );

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  private function register_post_types() {
    $plugin_post_types = new PostTypesController( $this->get_plugin_name(), $this->get_version() );
    $this->loader->add_action( 'init', $plugin_post_types, 'register_post_types', 10 );
  }
  private function set_default_configs() {
    $plugin_post_types = new PostTypesController( $this->get_plugin_name(), $this->get_version() );
    $this->loader->add_action( 'wp', $plugin_post_types, 'set_default_configs', 11 );
    
  }
  private function define_routes() {
    /*
     * Example Routes only
     * first would overwrite the url set for plugin_base, then would load the
     * view at the path defined, with the auth guard in place. in this example,
     * that means only logged in users could access that route.
     * The second is an example of using a WP REST api endpoint. This one is a test login
     * method, so you'll notice there isn't a middleware guard on it.

    $this->routes = array(
      'index'       => Route::default( $this->plugin_base, '', $this->plugin_path().'/resources/views/app.php', 'auth' ),
      'api_auth'    => Route::post( '/auth', '', array( new AuthController, 'login' ) )
    );

    RouteProcessor::init( $this->router, $this->routes );
    */
  }


  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new AdminController( $this->get_plugin_name(), $this->get_version(), $this->plugin_path(), $this->plugin_url() );

    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'register_admin_menu_item' );
    $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_subtitle_box' );
    $this->loader->add_action( 'save_post', $plugin_admin, 'save_subtitle' );
    // Move all "advanced" metaboxes above the default editor
    $this->loader->add_action('edit_form_after_title', $plugin_admin, 'meta_above_editor' );
    // AJAX hooks
    $this->loader->add_action( 'wp_ajax_get_blog_config', $plugin_admin, 'get_blog_config' );
    $this->loader->add_action( 'wp_ajax_update_blog_config', $plugin_admin, 'update_blog_config' );
    $this->loader->add_action( 'wp_ajax_scan_for_tailwind', $plugin_admin, 'scan_for_tailwind' );



  }


  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new FrontController( $this->get_plugin_name(), $this->get_version(), $this->plugin_path(), $this->plugin_url() );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
    $this->loader->add_filter( 'template_include', $plugin_public, 'display_archive' );
    
    // remove the default 'Read More' Link and set excerpt length
    $this->loader->add_filter( 'excerpt_more', $plugin_public, 'custom_excerpt_more', 11 );
    $this->loader->add_filter( 'excerpt_length', $plugin_public, 'custom_excerpt_length', 11 );
    if ( is_admin() ) {
      // Ajax hooks
      $this->loader->add_action( 'wp_ajax_newsletter', $plugin_public, 'newsletter' );
      $this->loader->add_action( 'wp_ajax_nopriv_newsletter', $plugin_public, 'newsletter' );
    }


  }


  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }


  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }


  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }


  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }


  /**
   * Get the plugin path.
   *
   * @return string
   */
  public function plugin_path() {
    return untrailingslashit( plugin_dir_path( RubicoTechBlog_FILE ) );
  }


  /**
   * Get the plugin url.
   *
   * @return string
   */
  public function plugin_url() {
    return untrailingslashit( plugins_url( '/', RubicoTechBlog_FILE ) );
  }


  public function plugin_base() {
    return $this->plugin_base;
  }


  public function api_base() {
    return $this->api_base;
  }
}
