<?php

/**
 * Plugin Name:       Rubico Tech Blog
 * Description:       Blog plugin in India
 * Version:           0.1
**/

namespace RubicoTechBlog;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

// Define RubicoTechBlog_FILE.
if ( ! defined( 'RubicoTechBlog_FILE' ) ) {
  define( 'RubicoTechBlog_FILE', __FILE__ );
}

spl_autoload_register( function( $class ) {

  $namespace = 'RubicoTechBlog\\';
  $path      = 'src';

  // Bail if the class is not in our namespace.
  if ( 0 !== strpos( $class, $namespace ) ) {
    return;
  }

  // Remove the namespace.
  $class = str_replace( $namespace, '', $class );

  // Build the filename.
  $file = realpath( __DIR__ . "/{$path}" );
  $file = $file . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

  // If the file exists for the class name, load it.
  if ( file_exists( $file ) ) {
    include( $file );
  }
} );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_rt_blog_plugin() {
  PluginProcess\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_rt_blog_plugin() {
  PluginProcess\Deactivator::deactivate();
}

register_activation_hook( __FILE__, '\RubicoTechBlog\activate_rt_blog_plugin' );
register_deactivation_hook( __FILE__, '\RubicoTechBlog\deactivate_rt_blog_plugin' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_rt_blog_plugin() {
  $plugin = new Main();
  $plugin->run();
}

/*
 * This is meant to mimic woocommerce's WOO() function
 * which is a function to call the singular instance of the plugin.
 * LCC in this case stood for "LL Cabinet Configurator".
 */
function LCC() {
  return Main::instance();
}

run_rt_blog_plugin();
