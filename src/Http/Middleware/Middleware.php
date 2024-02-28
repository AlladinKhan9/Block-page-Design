<?php

namespace RubicoTechBlog\Http\Middleware;

use \WP_REST_Response;
use \WP_REST_Request;
use \WP_Error;
use \RubicoTechBlog\Router\Router;

class Middleware {

  private static $instance;

  public static function getInstance()
  {
    if ( is_null( self::$instance ) )
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function open()
  {
    return true;
  }

  static function auth( $request )
  {
    if ( !is_user_logged_in() ) {
      if ( $request->is_server_request() ) {
        wp_safe_redirect( home_url() . '/login' );
        exit;
      }

      return new WP_Error(
        'rest_forbidden_context',
        __( 'Sorry, you do not have correct permissions.' ),
        array( 'status' => rest_authorization_required_code() )
      );
    }

    return true;
  }

  static function loggedout( $request )
  {
    if ( is_user_logged_in() ) {
      if ( $request->is_server_request() ) {
        wp_safe_redirect( home_url() );
        exit;
      }

      return new WP_Error(
        'rest_forbidden_context',
        __( 'Sorry, you do not have correct permissions.' ),
        array( 'status' => rest_authorization_required_code() )
      );
    }

    return true;
  }
}
