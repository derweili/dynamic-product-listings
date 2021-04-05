<?php

namespace Derweili\DynamicProductListing;

use Derweili\DynamicProductListing\BlockTypes\ProductListing as ProductListingBlockType;

use Carbon_Fields\Block as CarbonBlock;
use Carbon_Fields\Field;

class ProductListingBlock {
	public function register() {
		add_action( 'carbon_fields_register_fields', [ $this, 'register_block' ] );
	}

	public function register_block() {
		CarbonBlock::make( __( 'Product Listing' ) )
			->add_fields( array(
				Field::make( 'association', 'listings', __( 'Listing' ) )
					->set_types( array(
						array(
								'type'      => 'term',
								'taxonomy' => ProductListing::get_taxonomy(),
						)
					) ),
				Field::make( 'text', 'columns', __( 'Columns' ) )
					->set_attribute('type', 'number')	
					->set_attribute('min', '1')
					->set_attribute('max', '6'),
				Field::make( 'text', 'rows', __( 'Columns' ) )
					->set_attribute('type', 'number')	
					->set_attribute('min', '1')
					->set_attribute('max', '6'),
				Field::make( 'set', 'content_visibility', __( 'Display Options' ) )
					->add_options( array(
						'title' => 'Product title',
						'price' => 'Product price',
						'rating' => 'Product rating',
						'button' => 'Add to cart button'
					) )
					->set_default_value( ['title', 'price', 'rating', 'button'] ),
			) )
			->set_category( 'woocommerce')
			->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {

				/**
				 * Set Listings
				 */
				$ids = array_map( function( $association ) {
					return intval( $association["id"] );
				}, $fields['listings'] );

				$attributes['listing_ids'] = $ids;

				/**
				 * Set Columns & Rows
				 */
				if( $fields['columns'] ) {
					$attributes['columns'] = intval( $fields['columns'] );
				}
				if( $fields['rows'] ) {
					$attributes['rows'] = intval( $fields['rows'] );
				}

				$contentVisibility = [];
				foreach ($fields['content_visibility'] as $visibilityOption) {
					$contentVisibility[ $visibilityOption ] = true;
				}

				$attributes["contentVisibility"] = $contentVisibility;

				echo ( new ProductListingBlockType() )->render( $attributes );
			} );
	}
}