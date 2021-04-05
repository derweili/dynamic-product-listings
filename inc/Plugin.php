<?php

namespace Derweili\DynamicProductListing;

use Derweili\DynamicProductListing\PostTypes\ProductListingPostType;
use Derweili\DynamicProductListing\Taxonomies\ProductListingTaxonomy;

class Plugin {

	public static $is_running = false;

	public function run() {
		if( Plugin::$is_running ) return;
		Plugin::$is_running = true;

		$this->init();

		add_action( ProductListing::get_taxonomy() . '_edit_form_fields', function( $term ) {
			$listing = new ProductListing( $term );
			
			$products = $listing->query_products();

			echo '<ul>';
				array_map( function( $product ) {
					echo '<li>';
					echo $product->get_title();
					echo '</li>';
				}, $products);
			echo '</ul>';
		} );

	}

	public function init() {
		(new ProductListingTaxonomy() )->register();
		(new ProductListingUpdate() )->register();
		(new ProductListingBlock() )->register();

		add_action( 'after_setup_theme', [ $this, 'init_carbon_fields' ] );
	}
	
	public function init_carbon_fields() {
		\Carbon_Fields\Carbon_Fields::boot();
	}
}