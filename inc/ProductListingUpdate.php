<?php

namespace Derweili\DynamicProductListing;
use \WP_Term;
use \WC_Product_Query;

class ProductListingUpdate {

	public function register() {
		add_action( 'saved_term', [ $this, 'update_listing_on_term_update' ] );
		add_action( 'save_post_product', [ $this, 'update_listing_on_product_update' ] );
	}

	public function update_listing_on_term_update( $term_id ) {
		$listing = new ProductListing( $term_id );
		$listing->update_listing();
	}

	public function update_listing_on_product_update() {
		$listing_terms = get_terms([
			'taxonomy' => ProductListing::get_taxonomy(),
			'hide_empty' => false,
		]);

		foreach ($listing_terms as $term) {
			( new ProductListing( $term ) )->update_listing();
		}
	}
}