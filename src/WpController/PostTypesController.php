<?php

/**
 * Class where we register our post types
 *
 */

namespace RubicoTechBlog\WpController;

class PostTypesController {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;


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
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }


  public static function register_post_types() {

    if ( post_type_exists( 'll_blog' ) )
      return;

  /**
   * Register the Jobs
   */

    $labels = array(
      'name'                => 'Blog Settings',
      'singular_name'       => 'Blog Setting',
      'menu_name'           => 'Blog Settings',
      'parent_item_colon'   => 'Parent Blog Setting',
      'all_items'           => 'All Blog Settings',
      'view_item'           => 'View Blog Setting',
      'add_new_item'        => 'Add New Blog Setting',
      'add_new'             => 'New Blog Setting',
      'edit_item'           => 'Edit Blog Setting',
      'update_item'         => 'Update Blog Setting',
      'search_items'        => 'Search Blog Setting',
      'not_found'           => 'No blog setting found',
      'not_found_in_trash'  => 'No blog setting found in Trash',
    );
    $args = array(
      'label'               => 'blog-setting',
      'description'         => 'Blog Setting',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes' ),
      // 'taxonomies'          => array( 'category', 'post_tag' ),
      'hierarchical'        => false,
      'public'              => false,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => false,
      'menu_position'       => 20,
      'menu_icon'           => 'data:image/svg+xml;base64,PCEtLSBHZW5lcmF0ZWQgYnkgSWNvTW9vbi5pbyAtLT4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjMyIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMzIgMzIiPgo8dGl0bGU+ZG9jczwvdGl0bGU+CjxwYXRoIGZpbGw9IiNhMGE1YWEiIGQ9Ik0yNy41NzcgOC42MDRsLTguNDE4LTguNDE4Yy0wLjExOS0wLjExOS0wLjI3OS0wLjE4NS0wLjQ0Ny0wLjE4NWgtMTEuODYyYy0xLjQ0MSAwLTIuNjEyIDEuMTcyLTIuNjEyIDIuNjEydjI2Ljc3NWMwIDEuNDQxIDEuMTcyIDIuNjEzIDIuNjEyIDIuNjEzaDE4LjNjMS40NDEgMCAyLjYxMy0xLjE3MiAyLjYxMy0yLjYxM3YtMjAuMzM3YzAtMC4xNjgtMC4wNjctMC4zMjktMC4xODUtMC40NDd6TTE5LjM0NCAyLjE1OWw2LjI1OSA2LjI1OWgtNC45MTFjLTAuNzQzIDAtMS4zNDgtMC42MDQtMS4zNDgtMS4zNDh2LTQuOTExek0yNi40OTggMjkuMzg3YzAgMC43NDMtMC42MDUgMS4zNDgtMS4zNDggMS4zNDhoLTE4LjNjLTAuNzQzIDAtMS4zNDgtMC42MDUtMS4zNDgtMS4zNDh2LTI2Ljc3NWMwLTAuNzQzIDAuNjA1LTEuMzQ4IDEuMzQ4LTEuMzQ4aDExLjIzdjUuODA2YzAgMS40NDEgMS4xNzIgMi42MTIgMi42MTIgMi42MTJoNS44MDZ2MTkuNzA0eiI+PC9wYXRoPgo8cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNMjEuMDU5IDE2LjA2M2gtMTAuMTE5Yy0wLjM0OSAwLTAuNjMyIDAuMjgzLTAuNjMyIDAuNjMyczAuMjgzIDAuNjMyIDAuNjMyIDAuNjMyaDEwLjExOWMwLjM0OSAwIDAuNjMyLTAuMjgzIDAuNjMyLTAuNjMycy0wLjI4My0wLjYzMi0wLjYzMi0wLjYzMnoiPjwvcGF0aD4KPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTIxLjA1OSAxOC44ODhoLTEwLjExOWMtMC4zNDkgMC0wLjYzMiAwLjI4My0wLjYzMiAwLjYzMnMwLjI4MyAwLjYzMiAwLjYzMiAwLjYzMmgxMC4xMTljMC4zNDkgMCAwLjYzMi0wLjI4MyAwLjYzMi0wLjYzMnMtMC4yODMtMC42MzItMC42MzItMC42MzJ6Ij48L3BhdGg+CjxwYXRoIGZpbGw9IiNhMGE1YWEiIGQ9Ik0yMS4wNTkgMjEuNzEzaC0xMC4xMTljLTAuMzQ5IDAtMC42MzIgMC4yODMtMC42MzIgMC42MzJzMC4yODMgMC42MzIgMC42MzIgMC42MzJoMTAuMTE5YzAuMzQ5IDAgMC42MzItMC4yODMgMC42MzItMC42MzJzLTAuMjgzLTAuNjMyLTAuNjMyLTAuNjMyeiI+PC9wYXRoPgo8cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNMTcuNzcxIDI0LjUzOGgtNi44M2MtMC4zNDkgMC0wLjYzMiAwLjI4My0wLjYzMiAwLjYzMnMwLjI4MyAwLjYzMiAwLjYzMiAwLjYzMmg2LjgzYzAuMzQ5IDAgMC42MzItMC4yODMgMC42MzItMC42MzJzLTAuMjgzLTAuNjMyLTAuNjMyLTAuNjMyeiI+PC9wYXRoPgo8L3N2Zz4K',
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'capability_type'     => 'post',
    );

    register_post_type( 'll_blog', $args );
  }
  public function set_default_configs() {
    if (empty(get_posts(['post_type' => 'll_blog']))) {
      $args = [
        'post_title' => 'Blog Config',
        'post_type' => 'll_blog',
        'post_status'  => 'publish',
        'meta_input' => [
          'show_sidebar' => '1',
          'show_featured_post' => '1',
          'featured_post' => 'false'
        ],
      ];
      wp_insert_post($args);
    }
  }
}
