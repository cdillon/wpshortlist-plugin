<?php
/**
 * Query
 *
 * @since 1.0.0
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Query
 *
 * @since 1.0.0
 */
class Query {

	/**
	 * Filter sets
	 *
	 * @var $filter_sets;
	 */
	private $filter_sets;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->filter_sets = new Filter_Sets();
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'init', array( $this, 'remove_empty_query_args' ), 1 );

		add_filter( 'query_vars', array( $this, 'register_query_vars' ) );

		// @see class-wp.php
		add_filter( 'request', array( $this, 'prioritize_primary_taxonomy' ) );

		// @see class-wp-query.php
		add_filter( 'posts_clauses', array( $this, 'add_search_clause' ) );

		add_action( 'pre_get_posts', array( $this, 'change_sort_order' ) );
		add_action( 'pre_get_posts', array( $this, 'alter_query' ) );
		add_action( 'pre_get_posts', array( $this, 'log_query' ), 999 );
	}

	/**
	 * Remove empty query args and redirect.
	 *
	 * Why? Because our filter form contains a search field that may not be used
	 * but the GET string still includes the parameter name.
	 *
	 * For example:
	 *
	 * From /features/display-term-list/?fs=&tool-type=plugin
	 *                                   ^ empty
	 * To   /features/display-term-list/?tool-type=plugin
	 */
	public function remove_empty_query_args() {
		$server = map_deep( wp_unslash( (array) $_SERVER ), 'sanitize_text_field' );
		if ( false !== strpos( $server['REQUEST_URI'], 'favicon' ) ) {
			return;
		}

		$parsed_url = wp_parse_url( $server['REQUEST_URI'] );
		if ( ! isset( $parsed_url['query'] ) ) {
			return;
		}

		$redirect       = false;
		$new_query_args = array();
		$query_args     = explode( '&', $parsed_url['query'] );

		foreach ( $query_args as $query_arg ) {
			$query_pair = explode( '=', $query_arg );

			if ( empty( $query_pair[1] ) ) {
				// Missing value so don't save it, and enable redirect.
				$redirect = true;
			} else {
				// Save it.
				$new_query_args[] = $query_arg;
			}
		}

		if ( ! $redirect ) {
			return;
		}

		$new_request_uri = $parsed_url['path'];

		$new_query_string = implode( '&', $new_query_args );
		if ( $new_query_string ) {
			$new_request_uri = $new_request_uri . '?' . $new_query_string;
		}

		if ( wp_safe_redirect( home_url( $new_request_uri ) ) ) {
			exit;
		}
	}

	/**
	 * Register our query vars.
	 *
	 * @param array $vars  Query vars.
	 *
	 * @return array
	 */
	public function register_query_vars( $vars ) {
		$new_vars = array();

		// Iterate filter sets that have filters.
		$has_filters = $this->filter_sets->get_filter_sets_with( 'filters' );
		foreach ( $has_filters as $filter_set ) {
			foreach ( $filter_set['filters'] as $filter ) {
				if ( isset( $filter['query_var'] ) && $filter['query_var'] ) {
					$new_vars[] = $filter['query_var'];
				}
			}
		}

		sort( $new_vars );
		$vars = array_unique( array_merge( $vars, $new_vars ) );

		// Our unique search var.
		$vars[] = 'fs';

		return $vars;
	}

	/**
	 * Modify the parsed query vars before creating the query.
	 *
	 * If switching from a post type archive to a *primary* taxonomy archive,
	 * then remove post type parameter. This is an edge case specific to our
	 * implementation.
	 *
	 * @param array $qv The parsed query vars.
	 */
	public function prioritize_primary_taxonomy( $qv ) {
		/*
		 * @todo Compare against a list of our taxonomies that have rewrites.
		 *
		 * Currently only 2 places this is needed:
		 * When selecting the Feature Category on the Feature Directory,
		 * and when selecting the Tool Type on the Tool Directory.
		 */
		if ( ! ( isset( $qv['feature-category'] ) || isset( $qv['tool-type'] ) ) ) {
			return $qv;
		}

		// Taxonomy overrides post_type.
		if ( isset( $qv['post_type'] ) ) {
			unset( $qv['post_type'] );
			$url = add_query_arg( $qv, home_url() );
			if ( wp_safe_redirect( $url ) ) {
				exit;
			}
		}

		return $qv;
	}

	/**
	 * Modify query clauses.
	 *
	 * @param array $clauses The query clauses.
	 */
	public function add_search_clause( $clauses ) {
		global $wpdb;

		// We currently only have 2 starting points: Feature Directory and Tool Directory.
		// How to not hardcode this?
		if ( false === strpos( $clauses['where'], "post_type = 'feature_proxy'" ) && false === strpos( $clauses['where'], "post_type = 'tool'" ) ) {
			return $clauses;
		}

		$search = get_query_var( 'fs' );
		if ( $search ) {
			$search            = '%' . $search . '%';
			$where             = $wpdb->prepare(
				' AND ( (wp_posts.post_title LIKE %s) OR (wp_posts.post_excerpt LIKE %s) OR (wp_posts.post_content LIKE %s) )',
				array( $search, $search, $search )
			);
			$clauses['where'] .= $where;
		}

		return $clauses;
	}

	/**
	 * Sort our post types and taxonomies alphabetically everywhere.
	 *
	 * @param object $query  WP_Query.
	 */
	public function change_sort_order( $query ) {
		// Both admin and front end, regardless if main query.
		// @todo Find a way to NOT hard code these.
		$post_types = array( 'tool', 'category_proxy', 'feature_proxy' );
		$taxonomies = array( 'feature', 'feature_category', 'tool_type' );

		if ( $query->is_post_type_archive( $post_types ) || $query->is_tax( $taxonomies ) ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * If it's OK to modify or debug stuff like the main query.
	 *
	 * @param WP_Query $query  A WP_Query object.
	 *
	 * @return boolean
	 */
	public function ok_modify( $query ) {
		if ( ! is_a( $query, 'WP_Query' ) ) {
			return false;
		}

		if ( is_admin() ) {
			return false;
		}

		if ( isset( $query->query['favicon'] ) ) {
			return false;
		}

		if ( isset( $query->query['post_type'] ) ) {
			return 'tool' === $query->query['post_type'];
		}

		if ( ! isset( $query->queried_object ) ) {
			return false;
		}

		// All that just to narrow it down to our Tool/Feature query. Why?

		// Assemble a list of our public custom taxonomies.
		//
		// @todo Move to separate function
		// OR save in options table and update upon changes
		// OR collect when registering post types.
		$tax_query_vars = array();
		$taxonomies     = get_object_taxonomies( 'tool', 'objects' );
		foreach ( $taxonomies as $tax_object ) {
			if ( $tax_object->public ) {
				if ( is_bool( $tax_object->query_var ) ) {
					$tax_query_vars[] = $tax_object->name;
				} else {
					$tax_query_vars[] = $tax_object->query_var;
				}
			}
		}

		// Is queried taxonomy one of our custom taxonomies?
		// This is necessary because the post type is not present in $wp_query
		// on a taxonomy archive.
		if ( array_intersect( $tax_query_vars, array_keys( $query->query ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Add filters to meta query.
	 *
	 * @param object $query  WP_Query.
	 */
	public function alter_query( $query ) {
		if ( ! $this->ok_modify( $query ) ) {
			return;
		}

		$meta_query = array();

		// Iterate filter sets that have filters.
		$has_filters = $this->filter_sets->get_filter_sets_with( 'filters' );

		foreach ( $has_filters as $filter_set ) {
			foreach ( $filter_set['filters'] as $filter ) {
				// Only for filters that are configured for meta queries.
				if ( 'post_meta' !== $filter['type'] ) {
					continue;
				}
				if ( ! isset( $query->query[ $filter['query_var'] ] ) ) {
					continue;
				}

				// Disassemble multiple values.
				$q_values = explode( '|', $query->query[ $filter['query_var'] ] );

				if ( 'AND' === $filter['relation'] ) {
					// Add a meta query for each option value.
					foreach ( $q_values as $q_value ) {
						$meta_query[] = array(
							'key'     => $filter['query_var'],
							'value'   => $q_value,
							'compare' => '=',
						);
					}
				} else {
					// Add a single query with an array of option values.
					$meta_query[] = array(
						'key'     => $filter['query_var'],
						'value'   => $q_values,
						'compare' => 'IN',
					);
				}
			}
		}

		// phpcs:ignore
		// q2( $meta_query, 'NEW META QUERY', '', 'meta-query.log' );
		$query->set( 'meta_query', $meta_query );
	}

	/**
	 * Debug function to log just the current query vars.
	 *
	 * @param WP_Query $q The query.
	 */
	public function log_query( $q ) {
		if ( ! $q->is_main_query() ) {
			return;
		}
		if ( isset( $q->query['favicon'] ) ) {
			return;
		}
		if ( isset( $q->query['post_type'] ) && in_array( $q->query['post_type'], array( 'nav_menu_item', 'wp_template', 'wp_template_part' ), true ) ) {
			return;
		}

		q2( $q->query, 'QUERY', '-', 'query.log' );
	}

}
