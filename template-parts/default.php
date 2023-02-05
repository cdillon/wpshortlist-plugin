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

$filter_set = wpshortlist_get_filter_set();
if ( ! $filter_set ) {
	return;
}

?>
<form id="wpshortlist-form" class="wpshortlist-form">
	<?php wpshortlist_print_filter_reset_all(); ?>
	<div class="wpshortlist-filterset">
		<?php foreach ( $filter_set['filters'] as $filter ) : ?>

		<div class="wpshortlist-filter">
			<fieldset>
				<legend>
					<h3 class="wpshortlist-filter-heading">
						<?php echo esc_html( $filter['name'] ); ?>
					</h3>
				</legend>
				<?php wpshortlist_print_filter_reset( $filter ); ?>
				<?php wpshortlist_print_filter_list( $filter ); ?>
			</fieldset>
		</div><!-- .wpshortlist-filter -->

		<?php endforeach; ?>
	</div><!-- .wpshortlist-filterset -->
</form>
