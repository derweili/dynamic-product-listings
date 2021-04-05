<?php

namespace Derweili\DynamicProductListing\Taxonomies;

use Derweili\DynamicProductListing\ProductListing;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

class ProductListingTaxonomy {

	public function register() {
		add_action( 'init', [$this, 'register_taxonomy'] );
		add_action( 'carbon_fields_register_fields', [ $this, 'register_meta_fields' ] );
	}
	
	public function register_taxonomy() {
		$return = register_taxonomy( ProductListing::get_taxonomy(), [ 'product' ], [
			"label" => __( "Product Listing", 'dynamic-product-listing'),
			"public" => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'supports' => ['title'],
			'hierarchical' => true,
			'show_admin_column' => true,
			'capabilities' => [
				'manage_terms' => 'manage_categories',
				'edit_terms' => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'assign_product_listings',
			]
		] );
	}

	public function register_meta_fields() {
		Container::make( 'term_meta', __( 'Product Listing Properties' ) )
			->where( 'term_taxonomy', '=', ProductListing::get_taxonomy() )
			->add_fields( array(
				Field::make( 'text', 'min_price', __( 'Min Price' ) )
					->set_attribute('type', 'number')
					->set_attribute('min', '0')
					->set_visible_in_rest_api(true)
					->set_default_value(0),
				Field::make( 'text', 'max_price', __( 'Max Price' ) )
					->set_attribute('type', 'number')	
					->set_attribute('min', '1'),
				Field::make( 'text', 'min_rating', __( 'Min Rating' ) )
					->set_attribute('type', 'number')	
					->set_attribute('min', '1'),
				Field::make( 'checkbox', 'in_stock', __( 'Only products in stock' ) ),

				Field::make( 'association', 'product_cat', __( 'Product Category' ) )
					->set_types( array(
							array(
									'type'      => 'term',
									'taxonomy' => 'product_cat',
							),
					) ),
					// Field::make( 'association', 'pa_color', __( 'Color' ) )
					// 	->set_types( array(
						// 			array(
							// 					'type'      => 'term',
							// 					'taxonomy' => 'pa_color',
							// 			),
							// 	) ),
							// Field::make( 'separator', 'attributes_separator', __( 'Attributes' ) ),
							...$this->get_attributes_filter(),

				Field::make( 'set', 'product_type', __( 'Product Type' ) )
					->set_options( wc_get_product_types() ),
			) );
	}

	public function get_attributes_filter() {
		$attribute_filters = [];
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		foreach ( $attribute_taxonomies as $tax ) {
			$taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name ) ;
			// echo $taxonomy_name;

			$attribute_filters[] = Field::make( 'association', 'tax_' . $taxonomy_name, $tax->attribute_label )
			->set_types( array(
					array(
							'type'      => 'term',
							'taxonomy' => $taxonomy_name,
					),
			) );
		}
		// echo '<pre>';
		// var_dump($taxonomies); die();

		return $attribute_filters;
	}
}