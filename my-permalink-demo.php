<?php
/*
Plugin Name: My Permalink Demo
Plugin URI: http://soderlind.no/archives/2012/11/01/wordpress-plugins-and-permalinks-how-to-use-pretty-links-in-your-plugin/
Description: Demo plugin to show how to implement your custom permalink for your plugin. To test, add the [mypermalink] or [mypermalink val="ipsum"] shortcode to a page or post.
Version: 1.1.3
Author: Per Soderlind
Author URI: http://soderlind.no/
*/

if ( defined( 'ABSPATH' ) ) {
	My_Permalink::instance();
}

class My_Permalink {

	private static $instance;
	private $pages_not_in_menu = array();

	public static function instance() {
		if ( self::$instance ) {
			return self::$instance;
		}
		self::$instance = new self();
		return self::$instance;
	}

	private function __construct() {
		// demo shortcode
		add_shortcode( 'mypermalink', array( &$this, 'my_permalink_demo_shortcode' ) );

		// permalink hooks:
		add_filter( 'generate_rewrite_rules', array( &$this, 'my_permalink_rewrite_rule' ) );
		add_filter( 'query_vars', array( &$this, 'my_permalink_query_vars' ) );
		add_filter( 'admin_init', array( &$this, 'my_permalink_flush_rewrite_rules' ) );
		add_action( 'parse_request', array( &$this, 'my_permalink_parse_request' ) );
	}

	/**************************************************************************
     * Demo shortcode
     * A simple shortcode used to demonstrate the plugin.
     *
     * @see http://codex.wordpress.org/Shortcode_API
     * @param array $atts shortcode parameters
     * @return string URL to demonstrate custom permalink
     **************************************************************************/
	function my_permalink_demo_shortcode( $atts ) {
		$atts = shortcode_atts(array(
			// default values
			'val'       => 'lorem',
		), $atts );
		return sprintf( '<a href="%s">My permalink</a>',$this->my_permalink_url( $atts['val'] ) );
	}

	/**************************************************************************
     * Create your URL
     * If the blog has a permalink structure, a permalink is returned. Otherwise
     * a standard URL with param=val.
     *
     * @param sting $val Parameter to custom url
     * @return string URL
     **************************************************************************/
	function my_permalink_url( $val ) {
		if ( get_option( 'permalink_structure' ) ) { // check if the blog has a permalink structure
			return sprintf( '%s/my-permalink/%s',home_url(),$val );
		} else {
			return sprintf( '%s/index.php?my_permalink_variable_01=%s',home_url(),$val );
		}
	}

	/**************************************************************************
     * Add your rewrite rule.
     * The rewrite rules array is an associative array with permalink URLs as regular
     * expressions (regex) keys, and the corresponding non-permalink-style URLs as values
     * For the rule to take effect, For the rule to take effect, flush the rewrite cache,
     * either by re-saving permalinks in Settings->Permalinks, or running the
     * my_permalink_flush_rewrite_rules() method below.
     *
     * @see http://codex.wordpress.org/Custom_Queries#Permalinks_for_Custom_Archives
     * @param object $wp_rewrite
     * @return array New permalink structure
     **************************************************************************/
	function my_permalink_rewrite_rule( $wp_rewrite ) {
		$new_rules = array(
			 'my-permalink/(.*)$' => sprintf( 'index.php?my_permalink_variable_01=%s',$wp_rewrite->preg_index( 1 ) ),
			 /*
             // a more complex permalink:
             'my-permalink/([^/]+)/([^.]+).html$' => sprintf("index.php?my_permalink_variable_01=%s&my_permalink_variable_02=%s",$wp_rewrite->preg_index(1),$wp_rewrite->preg_index(2))
             */
		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
		return $wp_rewrite->rules;
	}

	/**************************************************************************
     * Add your custom query variables.
     * To make sure that our parameter value(s) gets saved,when WordPress parse the URL,
     * we have to add our variable(s) to the list of query variables WordPress
     * understands (query_vars filter)
     *
     * @see http://codex.wordpress.org/Custom_Queries
     * @param array $query_vars
     * @return array $query_vars with custom query variables
     **************************************************************************/
	function my_permalink_query_vars( $query_vars ) {
		$query_vars[] = 'my_permalink_variable_01';
		/*
        // need more variables?:
        $query_vars[] = 'my_permalink_variable_02';
        $query_vars[] = 'my_permalink_variable_03';
        */
		return $query_vars;
	}

	/**************************************************************************
     * Parses a URL into a query specification
     * This is where you should add your code.
     *
     * @see http://codex.wordpress.org/Query_Overview
     * @param array $atts shortcode parameters
     * @return string URL to demonstrate custom permalink
     **************************************************************************/
	function my_permalink_parse_request( $wp_query ) {
		if ( isset( $wp_query->query_vars['my_permalink_variable_01'] ) ) { // same as the first custom variable in my_permalink_query_vars( $query_vars )
			// add your code here, code below is for this demo
			printf( '<pre>%s</pre>',print_r( $wp_query->query_vars,true ) );
			exit( 0 );
		}
	}

	/**************************************************************************
     * Flushes the permalink structure.
     * flush_rules is an extremely costly function in terms of performance, and
     * should only be run when changing the rule.
     *
     * @see http://codex.wordpress.org/Rewrite_API/flush_rules
     **************************************************************************/
	function my_permalink_flush_rewrite_rules() {
		$rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
		if ( ! isset( $rules['my-permalink/(.*)$'] ) ) { // must be the same rule as in my_permalink_rewrite_rule($wp_rewrite)
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
} //End Class
