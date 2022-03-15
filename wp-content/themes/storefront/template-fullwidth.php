<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Custom Product Page
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="storefront-sorting">Custom Filter : 
				<select name="filter" id="custom-filter">
				  <option value="default">Default</option>
				  <option value="popular">Show only Popular products</option>
				  <option value="featured">Show only featured products</option>
				  <option value="categories">Show only categories</option>
				</select>
			</div>
			<div class="content-custom">
				<h2>Featured Products</h2>
				<?php echo do_shortcode('[products limit="3" columns="3" visibility="featured" orderby="_regular_price" class="featured"]'); ?>
				<h2>Popular Products</h2>
				<?php echo do_shortcode('[products limit="6" columns="3" orderby="_regular_price" order="asc" class="popular"]'); ?>
				<h2>Product Categories</h2>
				<?php echo do_shortcode('[product_categories ids="31,32" columns="3" class="categories"]'); ?>
			</div>
			
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
