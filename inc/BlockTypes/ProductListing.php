<?php

namespace Derweili\DynamicProductListing\BlockTypes;

use Automattic\WooCommerce\Blocks\BlockTypes\AbstractProductGrid;
use Derweili\DynamicProductListing\ProductListing as Listing;

class ProductListing extends AbstractProductGrid {
	protected $block_name = 'dyamic-product-listing';

	protected function set_block_query_args( &$query_args ) {
		$query_args["tax_query"] = [
			[
				'taxonomy' => Listing::get_taxonomy(),
				'field'    => 'term_id',
				'terms'    => $this->attributes["listing_ids"],
				]
		];

		// echo '<pre>';
		// var_dump($query_args);
		// echo '</pre>';
	}
}