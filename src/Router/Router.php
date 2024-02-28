<?php

namespace RubicoTechBlog\Router;

use \WP_Error;
use \WP_REST_Server;
use \RubicoTechBlog\Http\Middleware\Middleware;

class Router
{
    private $routes;
    private $route_variable;
    private $is_rest_request;
    private $is_ajax_request;
    private $is_server_request;

    public function __construct($route_variable = 'route_name', array $routes = array())
    {
        $this->routes = array();
        $this->route_variable = $route_variable;
        $this->is_rest_request = ( defined( 'REST_REQUEST' ) && REST_REQUEST );
        $this->is_ajax_request = wp_doing_ajax();
        $this->is_server_request = ( !$this->is_rest_request && !$this->is_ajax_request );

        foreach ($routes as $name => $route) {
            $this->add_route($name, $route);
        }
    }


    public function add_route($name, Route $route)
    {
        $this->routes[$name] = $route;
    }


    public function compile()
    {
        add_rewrite_tag('%'.$this->route_variable.'%', '(.+)');

        foreach ($this->routes as $name => $route) {
            if ( !$route->is_rest() )
                $this->add_rule($name, $route);
        }
    }

    public function compile_rest()
    {
        $router = $this;

        foreach ($this->routes as $name => $route) {

            if ( !$route->is_rest() )
                continue;

            register_rest_route( \RubicoTechBlog\LCC()->api_base, $route->get_path(), array(
                'methods'  => $route->get_method(),
                'callback' => $route->get_template(),
                'permission_callback' => function( \WP_REST_Request $request ) use ( $router, $name, $route ) {
                    if ( !wp_verify_nonce( $request->get_header('X-WP-Nonce'), 'wp_rest' ) ) {
                        return new WP_Error(
                            'rest_not_found',
                            __( 'Resource not found' ),
                            array( 'status' => 404 )
                        );
                    }

                    $request->is_ajax_request = $router->is_ajax_request;
                    $request->is_rest_request = $router->is_rest_request;
                    $request->is_server_request = $router->is_server_request;

                    return call_user_func_array( '\RubicoTechBlog\Http\Middleware\Middleware::'.$route->get_middleware(), array($request) );
                }
            ));
        }
    }

    /**
     * Flushes all WordPress routes.
     *
     * @uses flush_rewrite_rules()
     */
    public function flush()
    {
        flush_rewrite_rules();
    }

    /**
     * Tries to find a matching route using the given query variables. Returns the matching route
     * or a WP_Error.
     *
     * @param array $query_variables
     *
     * @return Route|WP_Error
     */
    public function match(array $query_variables)
    {
        if (empty($query_variables[$this->route_variable])) {
            return new WP_Error('missing_route_variable');
        }

        $route_name = $query_variables[$this->route_variable];

        if (!isset($this->routes[$route_name])) {
            return new WP_Error('route_not_found');
        }

        return $this->routes[$route_name];
    }

    /**
     * Adds a new WordPress rewrite rule for the given Route.
     *
     * @param string $name
     * @param Route  $route
     * @param string $position
     */
    private function add_rule($name, Route $route, $position = 'top')
    {
        add_rewrite_rule($this->generate_route_regex($route), 'index.php?'.$this->route_variable.'='.$name, $position);
    }

    /**
     * Generates the regex for the WordPress rewrite API for the given route.
     *
     * @param Route $route
     *
     * @return string
     */
    private function generate_route_regex(Route $route)
    {
        return '^'.ltrim(trim($route->get_path()), '/').'$';
    }

    public function is_rest_request()
    {
        return ( defined( 'REST_REQUEST' ) && REST_REQUEST );
    }

    public function is_ajax_request()
    {
        return wp_doing_ajax();
    }

    public function is_server_request()
    {
        return !$this->is_rest_request() && !$this->is_ajax_request();
    }
}