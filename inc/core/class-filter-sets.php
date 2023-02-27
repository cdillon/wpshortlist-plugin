<?php
/**
 * Filter Sets
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
 * Class Filter_Sets
 *
 * @since 1.0.0
 */
class Filter_Sets {

	/**
	 * Our filter sets
	 *
	 * @var array $filter_sets
	 */
	private $filter_sets;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_filter_sets();
	}

	/**
	 * Return the filter set for the current page.
	 */
	public function get_current_filter_set() {
		return $this->get_filter_sets( get_current_query_type() );
	}

	/**
	 * Return the starting page for the active filter sets.
	 * Also serves as indicator that the current page has filter sets.
	 *
	 * @todo How to make intelligent guess instead of hard-coding in config?
	 *
	 * @return string
	 */
	public function get_start() {
		$filter_sets = $this->get_current_filter_set();
		if ( ! $filter_sets ) {
			return false;
		}

		// Progressive assignment: The last 'start' wins.
		$start = '';
		foreach ( $filter_sets as $filter_set ) {
			if ( isset( $filter_set['start'] ) && $filter_set['start'] ) {
				$start = $filter_set['start'];
			}
		}

		return $start;
	}

	/**
	 * Set filter sets
	 */
	public function set_filter_sets() {
		$this->filter_sets = get_option( 'wpshortlist_filter_sets', array() );
	}

	/**
	 * Return the filter set for a specific taxonomy and term.
	 * Return false if not found.
	 *
	 * @param array $params  Parameters.
	 *
	 * @return array|false
	 */
	public function get_filter_sets( $params ) {
		if ( ! $params ) {
			return false;
		}

		$active_filters = array();

		$conditions = $this->get_conditions( $params );

		// Match filter set rules against the current page conditions.
		$has_rules = $this->get_filter_sets_with( 'rules' );
		foreach ( $has_rules as $filter_set ) {
			if ( array_intersect( $conditions, (array) $filter_set['rules'] ) ) {
				$active_filters[ $filter_set['order'] ] = $filter_set;
			}
		}
		ksort( $active_filters );

		// phpcs:ignore
		// q2( $active_filters, 'ACTIVE FILTERS' );
		return $active_filters;
	}

	/**
	 * Return filter sets that have a specific element.
	 *
	 * @param string $criterion  The element to check for.
	 *                           For example, 'rules' or 'filters'.
	 *
	 * @return array
	 */
	public function get_filter_sets_with( $criterion ) {
		if ( ! $criterion ) {
			return array();
		}

		return array_filter(
			$this->filter_sets,
			function( $f ) use ( $criterion ) {
				return ( isset( $f[ $criterion ] ) && $f[ $criterion ] );
			}
		);
	}

	/**
	 * Build an array of current page conditions like:
	 * Array
	 * (
	 *     [0] => tax_archive
	 *     [1] => tax_archive:feature
	 *     [2] => tax_archive:feature:display-term-list
	 * )
	 *
	 * @param array $params The current query type.
	 */
	public function get_conditions( $params ) {
		$conditions     = array();
		$last_condition = '';
		foreach ( $params as $param ) {
			$condition      = ( $last_condition ? $last_condition . ':' : '' ) . $param;
			$conditions[]   = $condition;
			$last_condition = $condition;
		}

		return $conditions;
	}

	/**
	 * Return a list of filter properties.
	 *
	 * @param string $prop  The property.
	 *
	 * @return array|bool
	 */
	public function get_filter_sets_property( $prop ) {
		if ( ! $prop ) {
			return false;
		}

		$props = array();
		foreach ( $this->filter_sets as $filter_set ) {
			if ( isset( $filter_set[ $prop ] ) && $filter_set[ $prop ] ) {
				$props[] = $filter_set[ $prop ];
			}
		}

		return array_unique( $props );
	}

}
