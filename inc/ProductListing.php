<?php

namespace Derweili\DynamicProductListing;
use \WP_Term;
use \WC_Product_Query;

class ProductListing {

	private $term = null;

	private static $taxonomy = 'dynamic-product-listing';

	public static function get_taxonomy() {
		return self::$taxonomy;
	}

	/**
	 * Init Product Listing
	 * 
	 * @param int|WP_Term $term 
	 */
	public function __construct( $term ) {
		$this->term = get_term( $term );
	}

	public function query_products() {
		$args = $this->build_query();

		return wc_get_products( $args );
	}

	public function build_query() {
		$query = [
			// 'post_type' => 'product',
			'limit' => -1,
			'post_status' => 'publish',
		];

		$query['meta_query'] = $this->build_meta_query();
		$query['tax_query'] = $this->build_tax_query();

		if( $this->get_attribute( "product_type" ) ) {
			$query['type'] = $this->get_attribute( "product_type" );
		}

		return $query;
	}

	public function build_meta_query() {

		$meta_query = [];

		/**
		 * Price Query
		 */
		if( $this->get_attribute( "min_price" ) && $this->get_attribute( "max_price" ) ) {
			$meta_query[] = array(
        'key' => '_price',
        'value' => \intval( array( $this->get_attribute( "min_price" ), $this->get_attribute( "max_price" ) ) ),
        'compare' => 'BETWEEN',
        'type' => 'NUMERIC'
			);
		} elseif( $this->get_attribute( "min_price" ) ) {
			$meta_query[] = array(
        'key' => '_price',
        'value' => \intval( $this->get_attribute( "min_price" ) ),
        'compare' => '>=',
        'type' => 'NUMERIC'
			);
		} elseif( $this->get_attribute( "max_price" ) ) {
			$meta_query[] = array(
        'key' => '_price',
        'value' => \intval( $this->get_attribute( "min_price" ) ),
        'compare' => '<=',
        'type' => 'NUMERIC'
			);
		}

		/**
		 * Rating Query
		 */
		if( $this->get_attribute( "min_rating" ) ) {
			$meta_query[] = array(
        'key' => '_wc_average_rating',
        'value' => \intval( $this->get_attribute( "min_rating" ) ),
        'compare' => '>=',
        'type' => 'NUMERIC'
			);
		}

		/**
		 * Stock status
		 */
		if( $this->get_attribute( "in_stock" ) ) {
			$meta_query[] = array(
        'key' => '_stock_status',
        'value' => 'instock',
        'compare' => '=',
			);
		}

		return $meta_query;
	}

	/**
	 * Todo: implement taxonomy operator select (at least a switch between "IN" and "AND" operator)
	 */
	public function build_tax_query() {
		$tax_query = [];

		/**
		 * Stock status
		 */
		if( $this->get_attribute( "product_cat" ) ) {

			$ids = array_map( function( $association ) {
				return intval( $association["id"] );
			}, $this->get_attribute( "product_cat" ) );

			$tax_query['product_cat'] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $ids,
			);
		}

		/**
		 * Product Attributes
		 */
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		foreach ( $attribute_taxonomies as $tax ) {
			$taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name ) ;

			if( $this->get_attribute( 'tax_' . $taxonomy_name ) ) {
				$ids = array_map( function( $association ) {
					return intval( $association["id"] );
				}, $this->get_attribute( 'tax_' . $taxonomy_name ) );
	
				$tax_query[$taxonomy_name] = array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'term_id',
					'terms'    => $ids,
				);
			}
		}
		
		return $tax_query;
	}

	public function get_attribute( $name ) {
		return carbon_get_term_meta( $this->term->term_id, $name );
	}

	public function update_listing() {
		$this->remove_old_listing_posts();

		$this->update_new_listing_posts();
	}

	public function remove_old_listing_posts() {
		$args = [
			'post_type' => 'product',
			'posts_per_page' => -1,
			'tax_query' => [
				[
					'taxonomy' => ProductListing::get_taxonomy(),
					'field'	=> 'term_id',
					'terms' => $this->term->term_id,
				]
			]
		];
		$posts = get_posts( $args );

		foreach ($posts as $post) {
			wp_remove_object_terms( $post->ID, $this->term->term_id, ProductListing::get_taxonomy());
		}
	}

	public function update_new_listing_posts() {
		$posts = $this->query_products();

		foreach ($posts as $post) {
			wp_set_object_terms( $post->id, $this->term->term_id, ProductListing::get_taxonomy(), true);
		}
	}
}