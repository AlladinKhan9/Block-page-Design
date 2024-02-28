<?php

namespace RubicoTechBlog\Http\Controllers;

use \WP_REST_Response;
use \WP_REST_Request;
use \WP_Error;
use \WP_REST_Server;
use \RubicoTechBlog\Router\Router;

class AuthController {

    /*
     * TODO: Extract these into class methods. These are necessary to
     * correctly process the nonce, and manage wordpress built in auth cookies
     */
    public function __construct()
    {
      add_filter( 'rest_post_dispatch', function( WP_REST_Response $response, WP_REST_Server $rest, WP_REST_Request $request) {
          $response->header('X-WP-Nonce', wp_create_nonce( 'wp_rest' ));
          return $response;
      }, PHP_INT_MAX, 3);

      add_action('set_logged_in_cookie', function($cookie_value){
          $_COOKIE[ LOGGED_IN_COOKIE ] = $cookie_value;
      }, PHP_INT_MAX);

      add_action('clear_auth_cookie', function(){
          $_COOKIE[ LOGGED_IN_COOKIE ] = ' ';
      });

      add_action('wp_login', function($login, $user){
        wp_set_current_user( $user->ID );
      }, PHP_INT_MAX, 2);

      add_action('wp_logout', function(){
        wp_set_current_user( 0 );
      }, PHP_INT_MAX);
    }


    public function login( WP_REST_Request $request )
    {
      $request_data = $request->get_params();
      $user_signon = wp_signon( $request_data, true );


      if ( is_wp_error( $user_signon ) ) {
        $validation_errors = $this->filter_errors( $user_signon );
        return new WP_REST_Response( $validation_errors, 200 );
      }

      wp_set_current_user( $user_signon->ID );
      return new WP_REST_Response( [ 'user' => $user_signon ], 200 );
    }

    /*
     * Overwrite built in login messages
     */
    public function filter_errors( $errors )
    {
      $codes = [
        'empty_password' => array(
          'code' => 'password',
          'message' => 'Password is required',
        ),
        'empty_email' => array(
          'code' => 'email',
          'message' => 'Email is required',
        ),
        'invalid_email' => array(
          'code' => 'email',
          'message' => 'Email was not found or is incorrect',
        ),
        'invalidcombo' => array(
          'code' => 'email',
          'message' => 'There is no email address',
        ),
        'empty_username' => array(
          'code' => 'email',
          'message' => 'Email is required',
        ),
        'invalid_username' => array(
          'code' => 'email',
          'message' => 'Email was not found or is incorrect',
        ),
        'incorrect_password' => array(
          'code' => 'password',
          'message' => 'Password is incorrect',
        ),
        'retrieve_password_email_failure' => array(
          'code' => 'reset',
          'message' => 'The email could not be sent. Your site may not be correctly configured to send emails',
        )
      ];

      $error_message = new WP_Error;
      foreach( $errors->get_error_codes() as $code ) {
        $error_message->add( $codes[$code]['code'], $codes[$code]['message'] );
      }

      return $error_message;
    }
}
