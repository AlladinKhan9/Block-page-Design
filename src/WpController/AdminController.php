<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 */

namespace RubicoTechBlog\WpController;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Rubico Tech
 */
class AdminController {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;
  private $tailwind_scan;
  private $plugin_path;
  private $plugin_url;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version, $plugin_path, $plugin_url ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->plugin_path = $plugin_path;
    $this->plugin_url = $plugin_url;
    $this->tailwind_scan = null;

  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    $my_current_screen = get_current_screen();
      if ($my_current_screen->base === 'toplevel_page_rt-blog-options') {
      wp_enqueue_style( $this->plugin_name, $this->plugin_url . '/assets/css/main.tw.min.css', array(), $this->version, 'all' );
    }
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    $my_current_screen = get_current_screen();
    if ($my_current_screen->base === 'toplevel_page_rt-blog-options') {
      wp_enqueue_script( $this->plugin_name, $this->plugin_url . '/assets/js/scripts.min.js', array( 'jquery' ), $this->version, false );
    }
  }

  public function register_admin_menu_item() {
    add_menu_page(
        __( 'Blog Settings', 'textdomain' ),
        'Blog Setting',
        'manage_options',
        'rt-blog-options',
        [$this, 'display_admin'],
        'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAuMyA4OS4xMSI+PHRpdGxlPkFzc2V0IDE8L3RpdGxlPjxnIGlkPSJMYXllcl8yIiBkYXRhLW5hbWU9IkxheWVyIDIiPjxnIGlkPSJMYXllcl8xLTIiIGRhdGEtbmFtZT0iTGF5ZXIgMSI+PHBhdGggZD0iTTg2LjE4LDBINDcuNDlMMCw4OS4xMUg4Ny42NFY2MUg0OS41NFoiIGZpbGw9ImJsYWNrIi8+PHBhdGggZD0iTTExNC4xMiwwaDM4LjY5TDIwMC4zLDg5LjExSDExMi42NlY2MWgzOC4xWiIgZmlsbD0iYmxhY2siLz48L2c+PC9nPjwvc3ZnPg==',
        99
    );
  }
	public function display_admin() {
		include($this->plugin_path . '/resources/views/admin-blog.php');
  }
  
  public function get_blog_config() {
    $config_post = get_posts(['post_type' => 'll_blog', ])[0];
    $config = (object)[];
    $config->settings = get_post_meta($config_post->ID);

    $config->posts = get_posts([
      'numberposts' => 30,
      'post_type' => 'post',
    ]);

    foreach($config->posts as $post) {
      $post->image_url = get_the_post_thumbnail_url($post->ID, 'full');
    }

    $media_args = [
      'post_type' => 'attachment',
      'post_mime_type' => 'image/jpeg,image/jpg,image/png',
      'post_status' => 'inherit',
      'posts_per_page' => 30,
      'orderby' => 'id',
      'order' => 'DESC'
    ];
    $config->images = get_posts($media_args);

    $config->forms = \GFAPI::get_forms();

    echo json_encode($config);
    wp_die();
  }
  public function update_blog_config() {
    $key = $_POST['key'];
    $value = $_POST['value'];
    $config_post = get_posts(['post_type' => 'll_blog', ])[0];
    $update = update_post_meta($config_post->ID, $key, $value);
    echo $update;
    wp_die();
  }
  public function scanner($dir) {
    $result = [];
    foreach(scandir($dir) as $filename) {
      if ($filename[0] === '.') continue;
      if ($filename === 'node_modules') continue;
      $filePath = $dir . '/' . $filename;
      if (is_dir($filePath)) {
        foreach ($this->scanner($filePath) as $childFilename) {
          $result[] = $filename . '/' . $childFilename;
        }
      } else {
        $result[] = $filename;
      }
    }
    return $result;
  }
  public function scan_for_tailwind() {
    $root = get_template_directory();
    $scan = $this->scanner($root);
    $tailwind_check = array_filter($scan, function($var) {
      return strpos($var, 'tailwind.config.js') !== false;
    });
    $config_post = get_posts(['post_type' => 'll_blog', ])[0];
    $key = 'use_tailwind';
    if (!empty($tailwind_check)) {
      $value = false;
    } else {
      $value = true;
    }
    $update = update_post_meta($config_post->ID, $key, $value);
    echo json_encode($tailwind_check);
    wp_die();
  }
  public function add_subtitle_box() {
    add_meta_box(
      'rr_blog_subtitle',
      'Post Subtitle',
      [self::class, 'subtitle_box'],
      'post'
    );
  }
  public static function subtitle_box($post) {
    $value = get_post_meta($post->ID, '_ll_subtitle', true);
    ?>
    <label for="ll_subtitle">(appears above post title)</label>
    <input name="ll_subtitle" type="text" id="ll_subtitle" value="<?php echo $value ?>">
    <?php
  }
  public function save_subtitle($post_id) {
    if (array_key_exists('ll_subtitle', $_POST)) {
      update_post_meta(
          $post_id,
          '_ll_subtitle',
          $_POST['ll_subtitle']
      );
    }
  }
  public function meta_above_editor() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
  }
}
