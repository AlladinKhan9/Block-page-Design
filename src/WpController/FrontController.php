<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 */

namespace RubicoTechBlog\WpController;


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Rubico Tech
 */
class FrontController {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
    private $plugin_name;
    private $version;
    private $plugin_path;
    private $plugin_url;


  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version, $plugin_path, $plugin_url) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->plugin_path = $plugin_path;
    $this->plugin_url = $plugin_url;
  }


  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    global $wp_query;
    if ( $wp_query->is_posts_page || ($wp_query->is_single && $wp_query->post->post_type === 'post')) {
      if (($this->get_config())['use_tailwind'][0] === 'true') {
        wp_enqueue_style( $this->plugin_name, $this->plugin_url . '/assets/css/main.tw.min.css', array(), $this->version, 'all' );
      } else {
        wp_enqueue_style( $this->plugin_name, $this->plugin_url . '/assets/css/main.min.css', array(), $this->version, 'all' );
      }
    }

  }


  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    
    wp_enqueue_script( $this->plugin_name, $this->plugin_url . '/assets/js/frontscripts.min.js', array( 'jquery' ), $this->version, true );
    $config = $this->get_config();
    $sidebar = $config['show_sidebar'][0];
    $posts_per_page = $config['posts_per_page'][0];
    $default_grid_image = '';
    if (isset($config['default_grid_image']) && isset($config['default_grid_image'][0])) {
      $default_grid_image = wp_get_attachment_url($config['default_grid_image'][0]);
    }
    wp_localize_script($this->plugin_name, 'blog_info', [ 
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'sidebar' => ($sidebar === 'true'),
      'posts_per_page' => $posts_per_page,
      'default_grid_image' => $default_grid_image,
      ]);

  }

  public function display_archive($template) {

    global $wp_query;
     if ( $wp_query->is_posts_page ) {
       $template = $this->plugin_path . '/resources/views/archive.php';
      } elseif ($wp_query->is_single && $wp_query->post->post_type === 'post' ) {
        $template = $this->plugin_path . '/resources/views/single.php';
      }
     return $template;

  }
  
  public function custom_excerpt_more( $more ) {
      return '...';
  }
  public function custom_excerpt_length( $length ) {
    return 18;
  }
  public function get_config() {
    $config_post = get_posts(['post_type' => 'll_blog', ])[0];
    $config = get_post_meta($config_post->ID);
    return $config;
  }
  public function newsletter() {
    $email = $_POST['email'];
    $config_post = get_posts(['post_type' => 'll_blog', ])[0];
    $config = get_post_meta($config_post->ID);
    $args = [
      'form_id' => $config['newsletter_form'][0],
      strval($config['newsletter_field'][0]) => $email,
    ];
    \GFAPI::add_entry($args);
    echo ($email);
    wp_die();
  }

}
