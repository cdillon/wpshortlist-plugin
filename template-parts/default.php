<?php
/**
 * Default filter template.
 *
 * @package wpshortlist
 */

namespace Shortlist\Core;

/*
HTML Structure:

<form id="wpshortlist-form" class="wpshortlist-form">
	<div class="wpshortlist-filterset">
		<div class="wpshortlist-filter">
			<fieldset>
				<legend>
					<h3 class="wpshortlist-filter-heading">...</h3>
				</legend>
				<ul class="wpshortlist-filter-list">
					<li class="wpshortlist-filter-list-item">
						<input type="radio" ...>
						<label>...</label>
					</li>
				</ul>
			</fieldset>
		</div><!-- .wpshortlist-filter -->
	</div><!-- .wpshortlist-filterset -->
</form>
*/

// Omitting `action` attribute so form will pst to the current page.
?>
<form id="wpshortlist-form" class="wpshortlist-form" method="get">

	<div class="form-actions">
		<div class="form-action action-reset">
			<?php $this->print_filter_reset_all(); ?>
		</div>
	</div>


	<div class="wpshortlist-search-wrap">
		<input type="search" id="wpshortlist-search" class="" name="fs" value="<?php echo esc_attr( get_query_var( 'fs' ) ); ?>" placeholder="">
		<button type="submit" class="">Search</button>
	</div>


	<?php foreach ( $this->current as $filter_set ) : ?>
	<div class="wpshortlist-filterset">
		<?php $this->print_filter_set_content( $filter_set ); ?>
		<?php if ( isset( $filter_set['filters'] ) ) : ?>
			<?php foreach ( $filter_set['filters'] as $filter ) : ?>
				<div class="wpshortlist-filter"
						data-filter_name="<?php echo esc_attr( $filter['query_var'] ); ?>"
						data-filter_type="<?php echo esc_attr( $filter['input_type'] ); ?>">
					<fieldset>
						<legend>
							<h3 class="wpshortlist-filter-heading">
								<?php echo esc_html( $filter['name'] ); ?>
							</h3>
							<?php $this->print_explainer( $filter ); ?>
						</legend>
						<div class="filter-actions">
							<?php $this->print_filter_actions( $filter ); ?>
						</div>
						<?php $this->print_filter_list( $filter ); ?>
					</fieldset>
				</div><!-- .wpshortlist-filter -->
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- .wpshortlist-filterset -->
	<?php endforeach; ?>

	<div>
		<button type="submit" class="">Apply</button>
	</div>

</form>
