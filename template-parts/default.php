<?php
/**
 * Default filter template.
 *
 * @package wpshortlist
 */

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
?>
<form id="wpshortlist-form" class="wpshortlist-form">

	<div class="form-actions">
		<div class="form-action action-reset">
			<?php wpshortlist_print_filter_reset_all(); ?>
		</div>
	</div>

	<?php foreach ( $filter_sets as $filter_set ) : ?>
	<div class="wpshortlist-filterset">
		<?php wpshortlist_print_filter_set_content( $filter_set ); ?>
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
							<?php wpshortlist_print_explainer( $filter ); ?>
						</legend>
						<div class="filter-actions">
							<?php wpshortlist_print_filter_actions( $filter ); ?>
						</div>
						<?php wpshortlist_print_filter_list( $filter ); ?>
					</fieldset>
				</div><!-- .wpshortlist-filter -->
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- .wpshortlist-filterset -->
	<?php endforeach; ?>

</form>
